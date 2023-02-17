<template>
  <div id="payment-form">
    <div id="payment-entry" class="text-center">
      <h4 class="vcc-payment-button panel-title">
        <a v-on:click.prevent="togglePaymentUI()" href="#payment-ui">
          <div class="btn btn-default btn-primary btn-xs width-85 text-uppercase" :class="{ btnDisabled: !response.setup.data.payment_access }">Add / Edit Payment<br>Method</div>
        </a>
      </h4>
    </div><!-- END #payment-entry -->
    <PaymentUI v-if="!manageCard && displayPaymentUI"
               :csrf-token="csrfToken"
               :setup-data="response.setup.data"
               :is-admin="isAdmin"
               :customer-discount="customerDiscount"
               @submitCC="submitCC($event) " />
    <PaymentUI v-if="manageCard && displayPaymentUI"
               :csrf-token="csrfToken"
               :setup-data="response.setup.data"
               :is-admin="isAdmin"
               :customer-discount="customerDiscount"
               @submitCC="submitCC($event) " />
    <div id="payment-loading-indicator" class="padding-top-20 vcc-row-spinner">
      <PulseLoader :loading="addPaymentStatus" color="#6e298d"></PulseLoader>
      <div class="response__info fade in" id="card-info" role="alert"></div>
    </div><!-- END #payment-loading-indicator -->
    <div id="manage-card-modal" v-if="displayManageCardModal">
      <ManageCardModal
        :modal-copy=showManageCardModalAction />
    </div><!-- END #manage-card-modal -->
  </div><!-- END #payment-form -->
</template>

<script>
import Api from '../../../services/BaseService'
import BankACH from './BankACH'
import VerifyBankACH from './VerifyBankACH'
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import ManageCardModal from './modal/ManageCardModal'
import PaymentUI from './PaymentUI'
import { EventBus } from '../../../app.js'

// Custom styling can be passed to options when creating an Element.
var style = {
  base: {
    color: '#32325d',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '13px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

export default {
  name: 'Payment',
  components: {
    BankACH,
    VerifyBankACH,
    PulseLoader,
    ManageCardModal,
    PaymentUI
  },
  data () {
    return {
      stripeLoaded: false,
      response: {
        setup: {
          data: {
            business_status: '',
            payment_type: null,
            asa_type: '',
            accounts: [],
            cards: [],
            plans: {
              success: '',
              data: {
                "HR Director": [],
                "HR Director One_time Fee": []
              }
            },
            coupon: {
              success: '',
              data: [],
            },
            payment_access: false,
          }
        },
        transaction: {}
      },
      csrfToken: null,
      stripe: '',
      elements: '',
      card:'',
      displayCardActive: 'block',
      ownership_type: '',
      price: '',
      productID: '',
      product: '',
      instrument: 'credit_card',
      existing_card: '',
      paymentAction: '',
      cardID: '',
      isAdmin: false,
      displayPaymentUI: false,
      addPaymentStatus: false,
      showCCEditMode: false,
      showUpdate: false,
      showPurchase: false,
      showPlans: false,
      showExistingCards: false,
      showCardInfo: false,
      showAdd: false,
      customerDiscount: {}
    }
  },
  computed: {
    model () {
      return {
        fullName: this.fullName,
        line1: this.line1,
        line2: this.line2,
        city: this.city,
        state: this.state,
        postal_code: this.postal_code,
        account: this.account,
        routing: this.routing,
        ownership_type: this.ownership_type
      }
    },
    chargeLater() {
      return this.$store.state.chargeLater;
    },
    chargeLaterOption() {
      return this.$store.state.chargeLaterOption;
    },
    chargeNow() {
      return this.$store.state.chargeNow;
    },
    chargeNowOption() {
      return this.$store.state.chargeNowOption;
    },
    showManageCardModalAction () {
      return this.$store.state.showManageCardModalAction;
    },
    displayManageCardModal () {
      return this.$store.state.displayManageCardModal;
    },
    paymentType () {
      return this.$store.state.paymentType;
    },
    savePaymentMode () {
      return this.$store.state.savePaymentMode;
    },
    makeDefaultPayment () {
      return this.$store.state.makeDefaultPayment;
    },
    manageCard () {
      return this.$store.state.manageCard;
    },
    replaceMode () {
      return this.$store.state.replaceMode;
    },
    existingCardInfo() {
      let cardSelection = this.$store.state.existingCardInfo;
      this.exp_month = cardSelection.exp_month;
      this.exp_year = cardSelection.exp_year;
      this.fullName = cardSelection.fullName;
      // this.showUpdate = expiry.showUpdate;
    },
    newCardInfo() {
      let creditCard = this.$store.state.newCardInfo;
      this.fullName = creditCard.fullName;
      this.line1 = creditCard.line1;
      this.line2 = creditCard.line2;
      this.city = creditCard.city;
      this.state = creditCard.state;
      this.postal_code = creditCard.postal_code;
    },
    planOptionSelection() {
      let selection = this.$store.state.planOptionSelection;
      this.price = selection.price;
      this.productID = selection.id;
      this.product = selection.description;
    },
    setupIntenturl () {
      return `/payment/setup`
    },
    createTransaction () {
      return `/payment/create-transaction`
    },
    createInstrumentTransaction () {
      return `/payment/add-instrument`
    },
    // This function is used to create a payment without purchase
    updateTransaction () {
      return `/payment/update-instrument`
    },
    deleteInstrumentTransaction () {
      return `/payment/delete-instrument`
    },
    invoiceSubscriptionTransaction () {
      return `/payment/invoice-subscription`
    },
    cancelSubscriptionTransaction () {
      return `/payment/cancel-subscription`
    },
    lockSubmitInstrumentACH  () {
      let lockStatus =
        this.fullName === '' ||
        this.account === '' ||
        this.routing === '' ||
        this.ownership_type === '' ||
        this.disableSubmitInstrumentACH;
      return lockStatus;
    },
    lockSubmitACH () {
      return this.$store.state.paymentType === '' || this.productID === '';
    },
    lockSubmitVerifyACH () {
      return this.deposit1 === '' || this.deposit2 === '' || this.disableSubmitVerifyACH;
    },
    paymentDisplayInfo() {
      return this.response.setup.data['payment_type'] === null ? "No Payment Setup" : this.response.setup.data['payment_type'].replace("_", " ");
    }
  },
  watch: {
    // Billing Vue component
    fullName () {
      this.$emit('input', this.model);
    },
    line1 () {
      this.$emit('input', this.model);
    },
    line2 () {
      this.$emit('input', this.model);
    },
    city () {
      this.$emit('input', this.model);
    },
    state () {
      this.$emit('input', this.model);
    },
    postal_code () {
      this.$emit('input', this.model);
    },
    account () {
      this.$emit('input', this.model);
    },
    routing () {
      this.$emit('input', this.model);
    },
    ownership_type () {
      this.$emit('input', this.model);
    },
    // Plans Vue component
    price () {
      this.$emit('input', this.selection);
    },
    productID () {
      this.$emit('input', this.selection);
    },
    product () {
      this.$emit('input', this.selection);
    },
    instrument () {
      this.$emit('change', this.selection);
    },
    deposit1 () {
      this.$emit('input', this.selection);
    },
    deposit2 () {
      this.$emit('input', this.selection);
    }
  },
  methods: {
    /*
    Includes Stripe.js dynamically, only needed when component is being rendered.
    */
    loadStripe() {
      this.includeStripe('js.stripe.com/v3/', function () {
        this.configureStripe();
      }.bind(this));
    },
    includeStripe(URL, callback) {
      let stripeV3 = document.getElementById('stripe_v3');
      if (stripeV3 !== null) {
        stripeV3.remove();
      }
      let documentTag = document, tag = 'script',
        object = documentTag.createElement(tag),
        scriptTag = documentTag.getElementsByTagName(tag)[0];
      object.src = '//' + URL;
      object.id = 'stripe_v3';
      if (callback) {
        object.addEventListener('load', function (e) {
          callback(null, e);
        }, false);
      }
      scriptTag.parentNode.insertBefore(object, scriptTag);
    },
    /*
        Configures Stripe by setting up the elements and
        creating the card element.
    */
    configureStripe() {
      this.stripe = Stripe(window.env.STRIPE_KEY);
      this.elements = this.stripe.elements();
      this.card = this.elements.create('card', {
        style,
        hidePostalCode: true
      });
      if (document.querySelector('#card') !== null) {
        this.card.mount('#card');
      }
    },
    /*
    Loads the payment intent key for the user to pay.
    */
    loadIntent() {
      this.getData(this.setupIntenturl, this.applyPaymentEntryState);
    },
    togglePaymentUI() {
      if(!this.response.setup.data.payment_access) {
        return;
      }
      const resultElement = document.getElementById('card-info');
      resultElement.textContent = "";
      this.displayPaymentUI = !this.displayPaymentUI;
      if (this.displayPaymentUI) {
        this.loadStripe();
        this.$nextTick(() => {
          this.prepareCustomerDiscount();
        })
      }
    },
    selectCard(cardSelection) {
      this.cardID = cardSelection.id;
      this.displayCardActive = cardSelection.id === '' ? 'block' : 'none';
      this.exp_month = cardSelection.exp_month;
      this.exp_year = cardSelection.exp_year;
      this.fullName = cardSelection.fullName;
    },
    async getData(url, callback) {
      Object.assign(this.response.setup, await Api.get(url));
      callback();
    },
    async postTransaction(url, dataToken, el) {
      const resultElement = document.getElementById(el);
      resultElement.textContent = "";
      // This is for confirmation on purchase success.  Or any other message we need to show the user here
      Object.assign(this.response.transaction, await Api.post(url, {...dataToken}));
      this.addPaymentStatus = false;
      resultElement.textContent = this.response.transaction.data;

      if (
        this.response.transaction.data === "Stripe subscription collection method set to 'send_invoice'." ||
        this.response.transaction.data === "Payment instrument successfully deleted." && !this.replaceMode ||
        this.response.transaction.data === "Payment instrument successfully updated." && this.$store.state.chargeLaterOption === "save_only" ||
        this.response.transaction.data === "Subscription purchase successful." &&
        (this.$store.state.chargeNowOption === "autopay" || this.$store.state.chargeNowOption === "onetime_save" || this.$store.state.chargeNowOption === "onetime_ignore")) {
        setTimeout(() => {
          // Giving webhook a few seconds and then reloading.  Could possibly benefit from a better solution at some point.
          window.location.reload();
        },3500);
      }
      this.displayPaymentUI = false;
      // Attempt to reload latest data to continue
      this.loadIntent();
      // Forcing the DOM to update so the Stripe Element can update.
      this.$forceUpdate();

      // Set UI state
      await this.$store.dispatch('replaceMode', false);
      await this.$store.dispatch('editCardAction', false);

    },
    applyPaymentEntryState() {
      if (this.response.setup.data.cards.length) {
        this.$store.dispatch('manageCard', true);
        this.cardID = this.response.setup.data.cards[0].id;
      }
      if (this.response.setup.data.user_role === "admin") {
        this.isAdmin = true;
      }
    },
    /*
     * Method to determine instrument_mode for saving or existing payment types.
     */
    getInstrumentMode() {
      // New Payment method.  No card ID.
      if (this.cardID === '' || this.replaceMode) {
        if (this.$store.state.savePaymentMode === 'save') {
          return 'save';
        } else {
          return 'ignore';
        }
      } else {
        // Using existing payment method already of file.
        return 'existing';
      }
    },
    chargeNowOptionDeselected() {
      this.$store.dispatch('paymentType', '');
      this.$store.dispatch('savePaymentMode','');
    },
    chargeLaterOptionDeselected() {
      this.$store.state.chargeLaterOption = "";
    },
    submitCC(type) {
      switch (type) {
        case 'addCC':
          this.addCC();
          break;
        case 'updateCC':
          this.updateCC();
          break;
        case 'purchaseCC':
          this.purchaseCC();
          break;
        case 'updatePurchaseCC':
          this.updatePurchaseCC();
          break;
      }
    },
    /*
    When CC information needs to be added only as payment method
    */
    async addCC() {

      if(this.replaceMode) {
        this.deleteCC();
      }

      // this.disableSubmitInstrumentACH = true;
      const billingData = {
        name: this.$store.state.newCardInfo.fullName,
        address_line1: this.$store.state.newCardInfo.line1,
        address_line2: this.$store.state.newCardInfo.line2,
        address_city: this.$store.state.newCardInfo.city,
        address_state: this.$store.state.newCardInfo.state,
        address_zip: this.$store.state.newCardInfo.postal_code
      }
      this.addPaymentStatus = true;
      // Reset any previous error messages

      let errorElement = null;
      this.$nextTick(() => {
        // Reset any previous error messages
        errorElement = document.getElementById('card-info');
        errorElement.textContent = null;
      });

      const result = await this.stripe.createToken(this.card, billingData);
      if (result.error) {
        // Inform the user if there was an error.
        this.addPaymentStatus = false;
        let errorElement = document.getElementById('card-info');
        errorElement.textContent = result.error.message;
        this.$forceUpdate(); // Forcing the DOM to update so the Stripe Element can update.
      } else {
        let card = true;
        this.setupPaymentTransaction(result, 'card-info', card);
      }
    },
    /*
    When CC information needs to be updated after already saved.
     */
    async updateCC() {

      let currentCard = this.response.setup.data.cards[0];
      if (currentCard.name === this.$store.state.existingCardInfo.fullName &&
        currentCard.exp_month === this.$store.state.existingCardInfo.exp_month
        && currentCard.exp_year === this.$store.state.existingCardInfo.exp_year) {
        // nothing to update here, move along
        return true;
      }

      const instrument_data = {
        name: this.$store.state.existingCardInfo.fullName,
        exp_month: this.$store.state.existingCardInfo.exp_month,
        exp_year: this.$store.state.existingCardInfo.exp_year,
      };

      const dataToken = {
        instrument: this.cardID,
        default_instrument: this.$store.state.makeDefaultPayment,
        instrument_data: instrument_data,
      };

      // Add logic here to route depending on new payment or existing CC.
      this.addPaymentStatus = true;

      // for existing payments, we send back custom ID as token
      if (this.cardID !== '') {
        return this.postTransaction(this.updateTransaction, dataToken, 'card-info');
      } else {
        errorElement.textContent = "Unable to update credit card information.  Please try again later";
        this.addPaymentStatus = false;
      }
    },
    /*
     Generate Token for credit-card and handle any errors
     NOTE:  If one-time payment, can send back crd_id instead of token.
     */
    async purchaseCC() {

      if(this.replaceMode && this.$store.state.chargeNowOption !== "onetime_ignore") {
        this.deleteCC();
      }

      let result = {};
      let token;
      const billingData = {
        name: this.$store.state.newCardInfo.fullName,
        address_line1: this.$store.state.newCardInfo.line1,
        address_line2: this.$store.state.newCardInfo.line2,
        address_city: this.$store.state.newCardInfo.city,
        address_state: this.$store.state.newCardInfo.state,
        address_zip: this.$store.state.newCardInfo.postal_code
      }

      // Add logic here to route depending on new payment or existing CC.
      this.addPaymentStatus = true;

      let errorElement = null;
      this.$nextTick(() => {
        // Reset any previous error messages
        errorElement = document.getElementById('card-info');
        errorElement.textContent = null;
      });

      // for existing payments, we send back custom ID as token
      if (this.$store.state.manageCard) {
        token = this.cardID;
      } else {
        result = await this.stripe.createToken(this.card, billingData);
        token = result.token.id;
      }

      if (result.hasOwnProperty('error')) {
        if (result.error) {
          // Inform the user if there was an error.
          this.addPaymentStatus = false;
          errorElement.textContent = result.error.message;
          this.$forceUpdate(); // Forcing the DOM to update so the Stripe Element can update.
        }
      } else {
        this.processCCTransaction(token, 'card-info');
      }
    },
    updatePurchaseCC () {

      this.addPaymentStatus = true;

      const promise = new Promise((resolve ,reject) => {
        if(this.updateCC()) {
          resolve("instrument_updated");
          reject("instrument_update_failed")
        }
      });
      promise.then(
        response => {
          if(response === "instrument_updated") {
            setTimeout(() => {
              this.purchaseCC();
            }, 1500);
          }
        },
        error => {
          // console.log(error);
        }
      )
    },
    deleteCC() {
      this.addPaymentStatus = true;
      const dataToken = {
        instrument: this.cardID
      }
      this.postTransaction(this.deleteInstrumentTransaction, dataToken, 'card-info');
    },
    cancelSubscription() {
      this.postTransaction(this.cancelSubscriptionTransaction, {}, 'card-info');
    },
    invoiceSubscription() {
      this.postTransaction(this.invoiceSubscriptionTransaction, {}, 'card-info');
    },
    /*
    Credit Card - POST the token ID and other information to backend.
    NOTE:  If one-time payment, can send back card_id instead of generated token.
     */
    processCCTransaction (transactionToken, el) {
      const dataToken = {
        instrument_type: this.instrument,
        plan: this.$store.state.planOptionSelection.id,
        token: transactionToken,
        payment_type: this.$store.state.paymentType,
        default_instrument: this.$store.state.makeDefaultPayment,
        instrument_mode: this.getInstrumentMode(),
      }
      this.postTransaction(this.createTransaction, dataToken, el);
    },
    /*
    NEW  - POST the token ID and other information to backend.
   */
    purchaseACH (el) {
      const dataToken = {
        instrument_type: this.instrument,
        plan: this.productID,
        token: this.response.setup.data.accounts[0]['id'],
        default_instrument: this.$store.state.makeDefaultPayment,
        payment_type: this.$store.state.paymentType
      }
      // Reset any previous error messages
      let errorElement = document.getElementById(el);
      errorElement.textContent = null;
      this.addPaymentStatus = true;
      this.postTransaction(this.createTransaction, dataToken, el);
    },
    /*
      Setup of payment token - POST the token ID and other information to backend.
    */
    setupPaymentTransaction (transactionToken, el, card = false) {
      const dataToken = {
        token: transactionToken.token.id,
        default_instrument: this.$store.state.makeDefaultPayment,
        card: card
      };
      this.postTransaction(this.createInstrumentTransaction, dataToken, el);
    },
    prepareCustomerDiscount() {
      if(JSON.parse(this.response.setup.data.coupon.data) === null) {
        return;
      }
      let couponData = JSON.parse(this.response.setup.data.coupon.data).coupon;
      if(!Object.keys(couponData).length) {
        return;
      }
      if(couponData.amount_off === null) {
        this.customerDiscount = { discountType: "percentage", discountAmount: couponData.percent_off };
      } else {
        this.customerDiscount = { discountType: "fixed", discountAmount: couponData.amount_off };
      }
    }
  },
  mounted() {
    this.loadIntent();
    this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    EventBus.$on('chargeNowOptionDeselected', () => { this.chargeNowOptionDeselected(); });
    EventBus.$on('chargeLaterOptionDeselected', () => { this.chargeLaterOptionDeselected(); });
    EventBus.$on('creditCardComponentLoaded', () => { this.loadStripe(); });
    EventBus.$on('existingCardComponentLoaded', () => { this.loadStripe(); });
    EventBus.$on('invoiceSubscription', () => { this.invoiceSubscription(); });
    EventBus.$on('cancelSubscription', () => { this.cancelSubscription(); });
    EventBus.$on('deleteCC', () => { this.deleteCC(); });
  }
};

</script>

<style>

.vcc-container {
  display: block;
  max-width: 320px;
  margin: 0 auto;
}

.vcc-row {
  display: flex;
  flex-direction: row;
  width: 100%;
  margin: 0 0 6px 0;
}
.vcc-row-narrow-centered {
  display: flex;
  flex-direction: row;
  width: 80%;
  margin: 0 auto;
}

.vcc-row-end {
  display: flex;
  flex-direction: row;
  width: 100%;
  margin-bottom: 20px;
}

.vcc-row-center {
  display: flex;
  flex-direction: column;
  width: 100%;
  justify-content: center;
  margin: 0 0 6px 0;
}
.vcc-row-spinner {
  display: flex;
  flex-direction: row;
  width: 100%;
  justify-content: center;
  align-items: center;
}

.vcc-col {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  align-items: flex-start;
}

.vcc-payment-setup {
  display: flex;
  flex-direction: row;
  justify-content: flex-end;
  text-align: left
}

.vcc-payment-group {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  text-align: left
}

.vcc-payment-options {
  display: flex;
  flex-direction: row;
  align-items: center;
  margin: 0;
}

.vcc-col__payment-message {
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex-grow: 1;
  align-items: flex-start;
}

.vcc-col__options {
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex-grow: 1;
  flex-basis: 0;
  align-items: flex-start;
}

.vcc-row__new-credit-container {
  margin: 20px 0 30px 0
}

.vcc-payment-new {
  margin: 0 0 20px;
}

.vcc-control {
  display: block;
  width: 100%;
  margin: 0;
  border: 1px solid #cdcdcd;
  border-radius: 4px;
  padding: 6px;
  outline: none;
  box-sizing: border-box;
  background: #fff;
}
.vcc-checkbox-label, .vcc-checkbox-control[type=checkbox], .vcc-radio-label, .vcc-radio-control[type=radio] {
  margin: 5px;
}
.charge-options input[type=radio] {
  margin-top: 7px;
}
.vcc-payment-button {
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 8px;
}
/*payment field accordian styling*/
.panel-payment {
  background-color: #eef2f3;
  margin-bottom: 15px;
}
.panel-group > .panel {
  margin-bottom: 0;
  background-color: transparent;
  border: 0 solid transparent;
}
.panel > .panel-payment_heading {
  color: #333;
  background-color: #f5f5f5;
  border-color: #ddd;
}
.panel > .panel-payment_heading h4 a {
  text-decoration: none;
}
/* Make entire div clickable */
.accordion-toggle {
  display: block;
}
.panel-heading .accordion-toggle:after {
  /* symbol for "opening" panels */
  font-family: 'Glyphicons Halflings';  /* essential for enabling glyphicon */
  content: "\e114";    /* adjust as needed, taken from bootstrap.css */
  float: right;        /* adjust as needed */
  color: grey;
}
.panel-heading .accordion-toggle.collapsed:after {
  /* symbol for "collapsed" panels */
  content: "\e080";    /* adjust as needed, taken from bootstrap.css */
}
.accordion-arrow__position {
  display: inline-block;
}
.response__info {
  padding: 18px;
}
#business_status, #payment_type-display {
  text-transform: capitalize;
}
.width-85 {
  width: 85%;
}
.existing_cc_intent {
  width: 100%;
  padding: 1px 0;
}
#payment-loading-indicator {
  text-align: center;
}
#payment-loading-indicator v-spinner {
  width: 60%;
  margin: 0 auto;
}
.btnDisabled, .btnDisabled:hover, .btnDisabled:active {
  background-color: grey;
  border: 1px solid grey;
  box-shadow: none;
}

</style>

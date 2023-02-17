<template>
  <div id="asa-service-plan">
    <div class="vcc-row-bottom-margin padding-top-20">
      <div class="vcc-col">
        <label>Support Fee:
          <select class="vcc-control" v-model="selection" @change="updateSelection()" disabled>
            <option v-for="option in responseValue" v-bind:value="option">
              {{ option.description }}
            </option>
          </select>
        </label>
      </div>
      <div class="vcc-col payment-amount">
        <label class="vcc-label text-center" for="paymentAmount">Payment Amount
          <div class="vcc-control" id="paymentAmount">
            <div><span v-if="appliedDiscount() !== 'None'">Original Price:</span> {{ totalCharge(selection.price) | dollar }}</div>
            <div v-if="appliedDiscount() !== 'None'">
              <span>Discount: {{ appliedDiscount() }}</span><br />
              <span><strong>Net Price: {{ netPrice(totalCharge(selection.price)) }}</strong></span>
            </div>
          </div>
        </label>
      </div>
    </div>
    <div v-if="oneTimeCharge() > 0" class="padding-bottom-20">
      <p>Payment amount includes a one-time fee of {{ oneTimeValue | dollar }}</p>
    </div>
  </div><!-- END #asa-service-plan -->
</template>

<script>
export default {
  name: 'Plans',
  asaType: null,
  props: {
    responseValue: Array,
    responseOneTimeValue: Array,
    responseOneTimeStatus: Boolean,
    responseAsaType: String,
    customerDiscount: Object
  },
  data () {
    return {
      selection: {
        price_id: '',
        id: '',
        description: '',
        price: undefined
      }
    }
  },
  computed: {
    oneTimeValue () {
      return this.oneTimeCharge();
    }
  },
  methods: {
    updateSelection () {
      this.$store.dispatch('planOptionSelected', this.selection);
    },
    setSelection () {
      this.responseValue.forEach(plan => {
        if (plan.description.replace(" ", "-").replace("+", "").toLowerCase() === this.responseAsaType) {
          this.selection = plan;
          this.updateSelection();
        }
      }, this);
    },
    // If business status is "null", then new customer.  Include one-time fee in charge amount
    oneTimeCharge () {
      if (this.responseOneTimeStatus) {
        return 0 // BYPASSING ONE-TIME FEE, UFN: this.responseOneTimeValue[0]['price'];
      }
      return 0;
    },
    // Generate new value for one-time charge display
    totalCharge (price) {
      return Number(parseFloat(price) + parseFloat(this.oneTimeCharge())).toFixed(2);
    },
    appliedDiscount () {
      if (this.$store.state.chargeLater || (this.$store.state.chargeNow && this.$store.state.chargeNowOption !== "autopay") || !Object.keys(this.customerDiscount).length) {
        return "None";
      }
      if (this.customerDiscount.discountType === "fixed") {
        return `$${(this.customerDiscount.discountAmount / 100).toFixed(2)}`;
      }
      return `${this.customerDiscount.discountAmount}%`;
    },
    netPrice (price) {
      if (this.$store.state.chargeLater || (this.$store.state.chargeNow && this.$store.state.chargeNowOption !== "autopay") || !Object.keys(this.customerDiscount).length) {
        return `$${price}`;
      }
      if (this.customerDiscount.discountType === "fixed") {
        return `$${(price - (this.customerDiscount.discountAmount / 100)).toFixed(2)}`;
      }
      return `$${(price - (price * (this.customerDiscount.discountAmount / 100))).toFixed(2)}`;
    }
  },
  mounted () {
    this.setSelection();
  }
}
</script>

<style scoped>

.vcc-row-bottom-margin {
  display: flex;
  flex-direction: row;
  width: 100%;
  margin: 0 0 20px 0;
}
.vcc-col {
  align-items: initial;
}
.vcc-col.payment-amount {
  margin-top: 20px;
}
#paymentAmount {
  border: 0;
  background: none;
}
#paymentAmount span {
  text-indent: 10px;
}
.padding-bottom-20 {
  padding-bottom: 20px;
}
.vcc-row-bottom-margin {
  display: block;
}
.vcc-col.support-fee select {
  margin-top: 10px;
}

</style>

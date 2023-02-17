<template>
  <div :id="paymentUI">
    <div id="manage-card-action" v-if="manageCard">
      <div class="padding-top-20">
        <label>Saved Payment Method:</label>
        <div class="vcc-row-space">
          <div class="vcc-payment-options">
            <div class="vcc-label vcc-radio-label"></div>
          </div>
        </div>
        <div id="existing_cc_intent" class="vcc-row">

          <div class="col-md-4" v-if="isAdmin">
            <button
              type="button"
              class="btn btn-default btn-primary btn-xs existing_cc_intent"
              @click="editCardAction(true)">EDIT
            </button>
          </div>
          <div class="col-md-4" v-if="isAdmin">
            <button
              type="button"
              class="btn btn-default btn-primary btn-xs existing_cc_intent"
              @click="showManageCardModalAction('delete')">DELETE
            </button>
          </div>
          <div class="col-md-4" v-if="isAdmin">
            <button
              type="button"
              class="btn btn-default btn-primary btn-xs existing_cc_intent"
              @click="showManageCardModalAction('add')">ADD NEW
            </button>
          </div>

          <div class="col-md-6" v-if="!isAdmin">
            <button
              type="button"
              class="btn btn-default btn-primary btn-xs existing_cc_intent"
              @click="editCardAction(true)">EDIT
            </button>
          </div>
          <div class="col-md-6" v-if="!isAdmin">
            <button
              type="button"
              class="btn btn-default btn-primary btn-xs existing_cc_intent"
              @click="showManageCardModalAction('add')">ADD NEW
            </button>
          </div>

        </div><!-- END #existing_cc_intent -->
      </div>
    </div><!-- END #manage-card-action -->
    <form v-if="editCard || !manageCard">
      <input type="hidden" :value="csrfToken" name="_token"/>
      <Plans
        v-bind:response-value="setupData.plans.data['HR Director']"
        v-bind:response-one-time-status="setupData['apply_fee']"
        v-bind:response-asa-type="setupData['asa_type']"
        v-bind:response-one-time-value="setupData.plans.data['HR Director One-Time Fee']"
        v-bind:customer-discount="customerDiscount"
      />
      <CreditCard
        v-if="!manageCard"
        :setup-data="setupData" />
      <ExistingCard
        v-if="manageCard"
        :setup-data="setupData.cards[0] "/>
      <ChargeOptions />
      <SubmitButton>
        <input v-if="!manageCard"
               slot="submit-button"
               :disabled="newCardInfo"
               class="btn btn-primary shadow-sm" type="submit"
               :value="submitButtonLabel"
               v-on:click.prevent="submitCC()"/>
        <input v-if="manageCard"
               slot="submit-button"
               :disabled="existingCardInfo"
               class="btn btn-primary shadow-sm" type="submit"
               :value="submitButtonLabel"
               v-on:click.prevent="submitCC()"/>
      </SubmitButton>
    </form>
  </div>
</template>

<script>
import Plans from './Plans'
import CreditCard from './CreditCard'
import ExistingCard from './ExistingCard'
import ChargeOptions from './ChargeOptions'
import SubmitButton from './SubmitButton'
import { mapState } from 'vuex'

export default {
  name: "PaymentUI",
  components: {
    SubmitButton,
    CreditCard,
    ExistingCard,
    Plans,
    ChargeOptions
  },
  props: {
    setupData: Object,
    isAdmin: Boolean,
    csrfToken: String,
    customerDiscount: Object
  },
  computed: {
    paymentUI () {
      return this.manageCard === true ? "manage-card" : "setup-card";
    },
    ...mapState({
      'manageCard': state => state.payments.manageCard,
      'submitButtonLabel': state => state.payments.submitButtonLabel,
      'editCard': state => state.payments.editCard,
      'existingCardInfo': state => {
        const info = state.payments.existingCardInfo;
        for (const key in info) {
          if (info[key].toString().length < 1) {
            return true;
          }
        }
        if (!state.payments.chargeNowOption.length && !state.payments.chargeLaterOption.length) {
          return true
        }
        return false;
      },
      'newCardInfo': state => {
        const info = state.payments.newCardInfo;
        for (const key in info) {
          if (key === 'line2') {
            continue;
          }
          if (info[key].toString().length < 1) {
            return true;
          }
        }
        if (!state.payments.chargeNowOption.length && !state.payments.chargeLaterOption.length) {
          return true
        }
        return false;
      }
    })
  },
  methods: {
    editCardAction (value) {
      this.$store.dispatch('payments/editCardAction', value)
    },
    submitCC () {
      this.$emit('submitCC', this.$store.state.payments.submitButtonAction);
    },
    showManageCardModalAction (action) {
      this.$store.dispatch('payments/showManageCardModalAction', action);
    }
  }
}
</script>

<style scoped>
#manage-card form, #setup-card form {
  width: 92%;
  margin: 0 auto;
}
#manage-card-action label {
  text-align: center;
  display: block;
}
@media (max-width: 991px) {
  #existing_cc_intent {
    flex-direction: column;
    width: 50%;
    margin: 0 auto 6px auto;
  }
  #existing_cc_intent > div {
    margin-bottom: 6px;
  }
}
</style>

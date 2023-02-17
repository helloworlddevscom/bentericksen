<template>
  <div id="new_card_charge_options" class="padding-top-20">
    <div class="vcc-row">
      <label>Charge Now?</label>
    </div>
    <div class="vcc-payment-options">
      <input type="radio" id="charge_now" name="new_cc_intent" value="charge_now" class="vcc-radio-control"
             @change="optionSelected($event)" />
      <label class="vcc-label vcc-radio-label" for="charge_now">Yes</label>
    </div>
    <div v-if="newCCIntent==='charge_now'" class="vcc-payment-options" v-observe-visibility="chargeNowVisibilityChanged">
      <ChargeNowOptions />
    </div>
    <div class="vcc-payment-options">
      <input type="radio" id="charge_later" name="new_cc_intent" value="charge_later" class="vcc-radio-control"
             @change="optionSelected($event)" />
      <label class="vcc-label vcc-radio-label" for="charge_later">No</label>
    </div>
    <div v-if="newCCIntent==='charge_later'" class="vcc-payment-options" v-observe-visibility="chargeLaterVisibilityChanged">
      <ChargeLaterOptions />
    </div>
  </div>
</template>

<script>
import ChargeNowOptions from './ChargeNowOptions'
import ChargeLaterOptions from './ChargeLaterOptions'
import { EventBus } from '../../../app.js'

export default {
  name: "ChargeOptions",
  components: {
    ChargeLaterOptions,
    ChargeNowOptions
  },
  data () {
    return {
      newCCIntent: ''
    }
  },
  methods: {
    optionSelected (event) {
      this.newCCIntent = event.target.value;
      this.$store.dispatch('chargeOptionUpdated', event.target.value);
    },
    chargeNowVisibilityChanged (isVisible) {
      if (isVisible) {
        EventBus.$emit('chargeLaterOptionDeselected');
        this.$store.dispatch('submitButtonUpdate', 'submit');
      }
    },
    chargeLaterVisibilityChanged (isVisible) {
      if (isVisible) {
        EventBus.$emit('chargeNowOptionDeselected');
        this.$store.dispatch('chargeLaterOptionSelectUI', true);
        this.$store.dispatch('submitButtonUpdate', 'save_only');
      }
    }
  }
}
</script>

<style scoped>

</style>

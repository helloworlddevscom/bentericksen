<template>
  <div class="charge-options padding-top padding-bottom">
    <div class="vcc-row-narrow-centered">
      <input type="radio" id="charge_now_autopay" name="charge_now_mode" value="autopay" class="vcc-checkbox-control" @change="optionSelected($event)">
      <label class="vcc-label vcc-checkbox-label" for="charge_now_autopay">Save Card Info for Auto-Pay Renewal</label>
    </div>
    <div class="vcc-row-narrow-centered">
      <input type="radio" id="charge_now_onetime_save" name="charge_now_mode" value="onetime_save" class="vcc-checkbox-control" @change="optionSelected($event)">
      <label class="vcc-label vcc-checkbox-label" for="charge_now_onetime_save">Save Card Info: One-Time Payment Only</label>
    </div>
    <div class="vcc-row-narrow-centered" v-if="!ignoreState">
      <input type="radio" id="charge_now_onetime_ignore" name="charge_now_mode" value="onetime_ignore" class="vcc-checkbox-control" @change="optionSelected($event)">
      <label class="vcc-label vcc-checkbox-label" for="charge_now_onetime_ignore">Don't Save Card Info</label>
    </div>
  </div>
</template>

<script>
export default {
  name: "ChargeNowOptions",
  data () {
    return {
      ignore: false
    }
  },
  computed: {
    ignoreState () {
      return this.$store.getters.ignoreState;
    }
  },
  methods: {
    optionSelected (event) {
      this.$store.dispatch('chargeNowOptionSelected', event.target.value);
      this.$store.dispatch('submitButtonUpdate', event.target.value);
    }
  }
}

</script>

<style scoped>

</style>

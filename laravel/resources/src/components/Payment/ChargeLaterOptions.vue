<template>
  <div class="charge-options padding-top padding-bottom">
    <div class="vcc-row-narrow-centered">
      <input type="radio" id="charge_later_save_only" name="charge_later_mode" value="save_only" class="vcc-checkbox-control" checked>
      <label class="vcc-label vcc-checkbox-label" for="charge_later_save_only">Save Card Info Only</label>
    </div>
  </div>
</template>

<script>

export default {
  name: "ChargeLaterOptions"
}

</script>

<style scoped>

</style>

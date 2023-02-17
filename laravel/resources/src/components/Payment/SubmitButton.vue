<template>
  <div class="submit-button vcc-payment-group padding-top-18">
    <div class="vcc-row">
      <div class="vcc-col__payment-message">
        <slot></slot>
      </div>
    </div>
    <div class="vcc-payment-button">
      <slot name="submit-button"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "SubmitButton"
}
</script>

<style scoped>

</style>

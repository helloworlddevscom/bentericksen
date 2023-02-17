<template>
  <div>
    <div class="vcc-row-end">
      <div>Enter deposit amounts (in cents) to verify your account</div>
    </div>
    <div class="vcc-col">
      <div class="vcc-row">
        <div>
          <label class="vcc-label" for="deposit1">Deposit #1 (cents)</label>
          <input type="number" class="vcc-control" id="deposit1" v-model.number="verifyACH.deposit1" @input="verifyBank()" placeholder="Deposit 1"/>
        </div>
        <div>
          <label class="vcc-label" for="deposit2">Deposit #2 (cents)</label>
          <input type="number" class="vcc-control" id="deposit2" v-model.number="verifyACH.deposit2" @input="verifyBank()" placeholder="Deposit 2"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VerifyBankACH',
  data () {
    return {
      verifyACH: {
        deposit1: '',
        deposit2: ''
      }
    }
  },
  methods: {
    verifyBank () {
      this.$emit('verifyBank', this.verifyACH);
    }
  }
}
</script>

<style>

</style>

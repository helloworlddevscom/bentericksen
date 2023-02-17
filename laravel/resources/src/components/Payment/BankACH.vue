<template>
  <div>
    <div class="vcc-message">
      <div class="vcc-row">
        <div class="vcc-col">
          Please enter your account information
        </div>
      </div>
    </div>
    <br/>
    <div class="vcc-row-ownership-type">
      <div class="vcc-payment-options">
        <input type="radio" v-model="bankACH.ownership_type" value="individual" @change="updateBank()" id="individual_type" class="vcc-radio-control">
        <label class="vcc-label vcc-radio-label" for="individual_type">Individual</label>
      </div>
      <div class="vcc-payment-options">
        <input type="radio" v-model="bankACH.ownership_type" value="company" @change="updateBank()" id="company_type" class="vcc-radio-control">
        <label class="vcc-label vcc-radio-label" for="company_type">Company</label>
      </div>
    </div>
    <div class="vcc-row">
      <div class="vcc-col">
        <label class="vcc-label" for="fullName">Full Name</label>
        <input type="text" class="vcc-control" id="fullName" v-model="bankACH.fullName" @change="updateBank()"  placeholder="Name on Bank Account">
      </div>
    </div>
    <div class="vcc-row">
      <div class="vcc-col">
        <label class="vcc-label" for="routing">Bank Information</label>
        <input type="text" class="vcc-control" id="routing" v-model="bankACH.routing" @change="updateBank()" placeholder="Bank Routing Number"/>
        <label class="vcc-label" for="account"></label>
        <input type="text" class="vcc-control" id="account" v-model="bankACH.account" @change="updateBank()" placeholder="Account Number"/>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BankACH',
  data () {
    return {
      bankACH: {
        fullName: '',
        account: '',
        routing: '',
        ownership_type: ''
      }
    }
  },
  methods: {
    updateBank () {
      this.$emit('updateBank', this.bankACH);
    }
  }
}
</script>

<style>

.vcc-message {
  margin-top: 20px;
}

.vcc-row-ownership-type {
  display: flex;
  flex-direction: row;
  width: 100%;
  justify-content: space-around;
  margin: 0 0 10px 0;
}

</style>

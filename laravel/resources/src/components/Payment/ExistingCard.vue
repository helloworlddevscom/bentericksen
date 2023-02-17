<template>
  <div class="vcc-row">
    <div class="vcc-col">
      <div class="vcc-expiry__container">
        <div class="vcc-row">
          <div class="vcc-col">
            <label class="vcc-label" for="fullName">Full Name</label>
            <input type="text" class="vcc-control" id="fullName" v-model="cardSelection.fullName" @change="updateCard($event)"  placeholder="Name on Credit Card"/>
          </div>
        </div>
        <div class="vcc-row">
          <div class="vcc-col">
            <label class="vcc-label" for="exp_month">Expiration Month</label>
            <input type="number" class="vcc-expiry__input" id="exp_month" v-model="cardSelection.exp_month" @change="updateCard($event)" placeholder="month <MM>"/>
          </div>
          <div class="vcc-col">
            <label class="vcc-label" for="exp_year">Expiration Year</label>
            <input type="number" class="vcc-expiry__input" id="exp_year" v-model="cardSelection.exp_year" @change="updateCard($event)"  placeholder="year <YYYY>"/>
          </div>
        </div>
        <div class="vcc-row">
          <div v-if="msg.expMonth">{{msg.expMonth}}</div>
        </div>
        <div class="vcc-row">
          <div v-if="msg.expYear">{{msg.expYear}}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ExistingCard',
  props: {
    setupData: Object,
    editMode: Boolean
  },
  data () {
    return {
      cardSelection: {
        id: '',
        exp_month: '',
        exp_year: '',
        fullName: ''
      },
      msg: []
    }
  },
  watch: {
    'cardSelection.exp_month' (value) {
      this.cardSelection.exp_month = value;
      this.validateExpMonth(value);
    },
    'cardSelection.exp_year' (value) {
      this.cardSelection.exp_year = value;
      this.validateExpYear(value);
    }
  },
  methods: {
    validateExpMonth (value) {
      if (value.length >= 3) {
        this.msg['expMonth'] = 'Must be in 2 number format <MM>';
      } else if (value / 12 > 1) {
        this.msg['expMonth'] = 'Must be valid number month - entered ' + value;
      } else {
        this.msg['expMonth'] = '';
      }
    },
    validateExpYear (value) {
      if (value.length >= 5) {
        this.msg['expYear'] = 'Must be in 4 number format <YYYY>';
      } else {
        this.msg['expYear'] = '';
      }
    },
    updateCard () {
      this.$store.dispatch('payments/updateExistingCard', this.cardSelection);
    }
  },
  mounted () {
    this.cardSelection.id = this.setupData.id;
    this.cardSelection.fullName = this.setupData.name;
    this.cardSelection.exp_month = this.setupData.exp_month;
    this.cardSelection.exp_year = this.setupData.exp_year;
    this.$nextTick(() => {
      this.$store.dispatch('payments/updateExistingCard', this.cardSelection);
    });
  }

}
</script>

<style>

input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none !important;
  margin: 0 !important;
}
.vcc-expiry__container {
  margin: 12px 0 3px 0;
}
.vcc-expiry__input {
  display: block;
  width: 75%;
  margin: 0;
  border: 1px solid #cdcdcd;
  border-radius: 4px;
  padding: 6px;
  outline: none;
  box-sizing: border-box;
  background: #fff;
}

</style>

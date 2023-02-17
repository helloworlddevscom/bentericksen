<template>
  <div id="cc-info">
    <div class="vcc-row-spinner">
      <PulseLoader :loading="!setupData.plans.success" color="#6e298d"></PulseLoader>
    </div>
    <div class="vcc-row">
      <div class="vcc-col">
        <label class="vcc-label" for="fullname">Full Name</label>
        <input type="text" class="vcc-control" id="fullName" v-model="creditCard.fullName" @change="updateCreditCard()"
               placeholder="Name on Credit Card"/>
      </div>
    </div>
    <div class="vcc-row">
      <div class="vcc-col">
        <label class="vcc-label" for="line1">Credit Card Address</label>
        <input type="text" class="vcc-control" id="line1" v-model="creditCard.line1" @change="updateCreditCard()"
               placeholder="street, PO Box, or company name"/>
        <label class="vcc-label" for="line2"></label>
        <input type="text" class="vcc-control" id="line2" v-model="creditCard.line2" @change="updateCreditCard()"
               placeholder="apartment, suite, unit, or building"/>
      </div>
    </div>
    <div class="vcc-row">
      <div class="vcc-col">
        <label class="vcc-label" for="city">City</label>
        <input type="text" class="vcc-control" id="city" v-model="creditCard.city" @change="updateCreditCard()"
               placeholder="City"/>
      </div>
      <div class="vcc-col">
        <label>State</label>
        <select class="vcc-control" v-model="creditCard.state" @change="updateCreditCard()">
          <option v-for="option in states" v-bind:value="option.abbr">
            {{ option.name }}
          </option>
        </select>
      </div>
      <div class="vcc-col">
        <label class="vcc-label" for="postal_code">Zip</label>
        <input type="text" class="vcc-control" pattern="[0-9]{5}" id="postal_code" v-model="creditCard.postal_code"
               @change="updateCreditCard()" placeholder="Zip"/>
      </div>
    </div>
    <div class="vcc-col">
      <label class="vcc-label" for="card">Credit or debit card</label>
      <div class="vcc-control" id="card"></div>
    </div>
  </div><!-- END #cc-info -->
</template>

<script>
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import { EventBus } from '../../../app.js'

export default {
  name: 'CreditCard',
  components: {
    PulseLoader
  },
  props: {
    setupData: Object
  },
  data () {
    return {
      creditCard: {
        fullName: '',
        line1: '',
        line2: '',
        city: '',
        state: '',
        postal_code: ''
      },
      states: [
        {
          "name": "Alabama",
          "abbr": "AL"
        },
        {
          "name": "Alaska",
          "abbr": "AK"
        },
        {
          "name": "Arizona ",
          "abbr": "AZ"
        },
        {
          "name": "Arkansas",
          "abbr": "AR"
        },
        {
          "name": "California ",
          "abbr": "CA"
        },
        {
          "name": "Colorado ",
          "abbr": "CO"
        },
        {
          "name": "Connecticut",
          "abbr": "CT"
        },
        {
          "name": "Delaware",
          "abbr": "DE"
        },
        {
          "name": "District of Columbia",
          "abbr": "DC"
        },
        {
          "name": "Florida",
          "abbr": "FL"
        },
        {
          "name": "Georgia",
          "abbr": "GA"
        },
        {
          "name": "Hawaii",
          "abbr": "HI"
        },
        {
          "name": "Idaho",
          "abbr": "ID"
        },
        {
          "name": "Illinois",
          "abbr": "IL"
        },
        {
          "name": "Indiana",
          "abbr": "IN"
        },
        {
          "name": "Iowa",
          "abbr": "IA"
        },
        {
          "name": "Kansas",
          "abbr": "KS"
        },
        {
          "name": "Kentucky",
          "abbr": "KY"
        },
        {
          "name": "Louisiana",
          "abbr": "LA"
        },
        {
          "name": "Maine",
          "abbr": "ME"
        },
        {
          "name": "Maryland",
          "abbr": "MD"
        },
        {
          "name": "Massachusetts",
          "abbr": "MA"
        },
        {
          "name": "Michigan",
          "abbr": "MI"
        },
        {
          "name": "Minnesota",
          "abbr": "MN"
        },
        {
          "name": "Mississippi",
          "abbr": "MS"
        },
        {
          "name": "Missouri",
          "abbr": "MO"
        },
        {
          "name": "Montana",
          "abbr": "MT"
        },
        {
          "name": "Nebraska",
          "abbr": "NE"
        },
        {
          "name": "Nevada",
          "abbr": "NV"
        },
        {
          "name": "New Hampshire",
          "abbr": "NH"
        },
        {
          "name": "New Jersey",
          "abbr": "NJ"
        },
        {
          "name": "New Mexico",
          "abbr": "NM"
        },
        {
          "name": "New York",
          "abbr": "NY"
        },
        {
          "name": "North Carolina",
          "abbr": "NC"
        },
        {
          "name": "North Dakota",
          "abbr": "ND"
        },
        {
          "name": "Ohio",
          "abbr": "OH"
        },
        {
          "name": "Oklahoma",
          "abbr": "OK"
        },
        {
          "name": "Oregon",
          "abbr": "OR"
        },
        {
          "name": "Pennsylvania",
          "abbr": "PA"
        },
        {
          "name": "Rhode Island",
          "abbr": "RI"
        },
        {
          "name": "South Carolina",
          "abbr": "SC"
        },
        {
          "name": "South Dakota",
          "abbr": "SD"
        },
        {
          "name": "Tennessee",
          "abbr": "TN"
        },
        {
          "name": "Texas",
          "abbr": "TX"
        },
        {
          "name": "Utah",
          "abbr": "UT"
        },
        {
          "name": "Vermont",
          "abbr": "VT"
        },
        {
          "name": "Virginia ",
          "abbr": "VA"
        },
        {
          "name": "Washington",
          "abbr": "WA"
        },
        {
          "name": "West Virginia",
          "abbr": "WV"
        },
        {
          "name": "Wisconsin",
          "abbr": "WI"
        },
        {
          "name": "Wyoming",
          "abbr": "WY"
        }
      ]
    }
  },
  computed: {
    selectState () {
      return this.states.find(i => i.name === this.creditCard.state);
    }
  },
  methods: {
    updateCreditCard () {
      this.$store.dispatch('updateNewCard', this.creditCard);
    }
  },
  mounted () {
    EventBus.$emit('creditCardComponentLoaded');
  }
}
</script>

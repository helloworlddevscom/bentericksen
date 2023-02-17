<template>
  <table>
    <thead>
    <tr class="row--table-border">
      <th class="column column--table-header">Hygiene Team Plan:</th>
      <th v-for="month in activeMonths">
      </th>
      <th class="column"></th>
    </tr>
    <tr>
      <th class="column column--title"></th>
      <th v-for="month in activeMonths">
        {{ getMonth(month.month) }}/{{ month.year }}
      </th>
      <th class="column column--averages">Average</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td class="column column--title">Net Hygiene Production</td>
      <td v-for="(month, key) in activeMonths">
        <input type="text" class="dollar"
               :name="'months.' + key + '.hygiene_production_amount'"
               :value="activeMonths[key].hygiene_production_amount | bp-dollar"
               :tabindex="(key * 3) + 3"
               :disabled="!hasHygiene || !editable"
               @blur="update">
      </td>
      <td class="column column--averages">
        <input type="text" :value='hygieneProductionAverage | bp-dollar' disabled>
      </td>
    </tr>
    <tr>
      <td class="column column--title">Rolling Average Hygiene Production</td>
      <td v-for="(month, key) in activeMonths">
        <input v-if="key > 0" type="text"
               :value='activeMonths[key].productionAverage | bp-dollar' disabled>
      </td>
    </tr>
    <tr  class="row--spacer"></tr>
    <tr >
      <td class="column column--title">Hygiene Staff Salaries</td>
      <td v-for="(month, key) in activeMonths">
        <input type="text" :value="getTotalSalary(key) | bp-dollar" disabled>
      </td>
      <td class="column column--averages">
        <input id="staff_salaries_average" name="staff_salaries_average"
          type="text" :value='hygieneStaffSalaries | bp-dollar' disabled>
      </td>
    </tr>
    <tr class="row--spacer"></tr>
    <tr >
      <td class="column column--title">Hygiene Salary Percentage</td>
      <td v-for="(month, key) in activeMonths">
        <input type="text"
               :value="getAverageSalary(key, month) | percentage" disabled>
      </td>
    </tr>
    <tr class="row--spacer"></tr>
    <tr >
      <td class="column column--title">Hygiene Bonus Plan:  6 Month Historical Averages</td>
    </tr>
    <tr >
      <td class="column column--subtitle">Hygiene Production Average</td>
      <td class="column column--averages"><input type="text" :value='hygieneProductionAverage | bp-dollar' disabled>
      </td>
    </tr>
    <tr >
      <td class="column column--subtitle">Hygiene Staff Salaries Average</td>
      <td class="column column--averages"><input type="text" :value='hygieneStaffSalaries | bp-dollar' disabled>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<script>
  import { mapState, mapActions, mapGetters } from 'vuex'
  import moment from 'moment'

  export default {
    name: "PlanDataHygieneTable",
    props: {
      editable: Number,
      hygieneStaffSalaries: Number
    },
    data: function () {
      return {}
    },
    methods: {
      ...mapActions({
        runCalculations: "planData/runCalculations",
        updateField: "planData/updateField",
        setErrors: "global/setErrors",
        clearErrors: "global/clearErrors",
        goToNextStep: "ui/nextStep"
      }),
      update: function (el) {
        let value = el.target.value;
        if (el.target.className === 'dollar' && typeof el.target.value === 'string') {
          value = parseFloat(value.replace(/[$,]/g, ''));
        }
        this.updateField({ prop: el.target.name, value: value });
        this.$emit('update');
      },
      getMonth: (month) => {
        if (month) {
          return moment().month(month - 1).format("MMM");
        }
      },
      getTotalSalary: function (key) {
        let totalSalary = 0;
        if (this.hasHygiene) {
          this.employeesAndFunds.employees
            .filter(ee => ee.bp_employee_type === 'hygienist')
            .forEach(ee => {
              totalSalary = totalSalary + parseInt(ee.monthlyData[key].gross_pay, 10);
            });
        }
        return totalSalary;
      },
      getAverageSalary: function (key, month) {
        return (this.getTotalSalary(key) / month.productionAverage) * 100;
      }
    },
    computed: {
      ...mapState({
        hasHygiene: store => store.initialSetup.hygiene_plan,
        currentStep: store => store.ui.currentStep,
        currentStepObj: store => store.ui.steps[store.ui.currentStep],
        planData: store => store.planData,
        employeesAndFunds: store => store.employeesAndFunds
      }),
      ...mapGetters({
        activeMonths: 'planData/activeMonths',
        netProductionAverage: 'planData/netProductionAverage',
        netCollectionAverage: 'planData/netCollectionAverage',
        productionCollectionAverage: 'planData/productionCollectionAverage',
        collectionRatioAverage: 'planData/collectionRatioAverage',
        hygieneProductionAverage: 'planData/hygieneProductionAverage',
        productionAverage: 'planData/productionAverage'
      }),
      hideSummaryData () {
        return this.currentStep !== 'planData';
      }
    },
    mounted () {
    }
  }

</script>

<style scoped lang="scss">
  table {
    width: 100%;
    table-layout: fixed;
    margin: 50px 0;

    td, tr, th {
      text-align: center;
    }

    td, th {
      width: 8%;
      padding: 1px;
    }

    th {
      padding: 10px 0;
      font-weight: bold;
    }
  }

  input[ type=text ] {
    width: 100%;
    text-align: center;
    padding: 5px 0;

    &:disabled {
      padding: 6px 0;
      border: 1px solid #DDDDDD;
      background-color: #DDDDDD;
    }
  }

  .column {
    font-weight: bold;
    padding-right: 10px;

    &--title {
      text-align: right;
      width: 12%;
    }
    &--subtitle {
      font-weight: normal;
      text-align: right;
      width: 12%;
    }
    &--table-header {
      padding-bottom: 0px;
      text-align: left;
      width: 12%;
    }
  }

  .row {
    &--spacer {
      height: 3rem;
    }
    &--table-border {
      border-top: 2px solid;
    }
  }

</style>

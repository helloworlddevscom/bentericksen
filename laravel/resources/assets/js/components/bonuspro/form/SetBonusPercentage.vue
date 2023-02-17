<template>
  <div class="set-bonus-percentage" v-if="active">
    <div class="row">
      <div v-if="bonusPercentage.errors" class="alert alert-danger">
        <strong>There are some errors with your submission. Please correct the following:</strong>
      </div>
      <div class="text-center">
        <h4>Set Employee Bonus Percentage</h4>
      </div>
      <div class="form-group">
        <div class="col-md-8 col-md-offset-4">* Required Field</div>
      </div>
    </div>

    <!-- General staff (non-Hygiene) -->
    <div class="row text-center">
      <plan-data-table :editable="0" :staff-salaries="this.staffSalaries"></plan-data-table>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div
          :class="{'form-group': true, 'has-error': bonusPercentage.errors && bonusPercentage.errors.staff_bonus_percentage}">
          <label for="staff_bonus_percentage" class="col-xs-2 control-label">Desired {{ displayHygieneText }}Staff-Bonus Percentage:</label>
          <div class="col-xs-1">
            <div class="row">
              <div class="no-padding-right">
                <input id="staff_bonus_percentage" type="text" name="staff_bonus_percentage"
                       class="form-control percentage" :value="bonusPercentage.staff_bonus_percentage | percentage"
                       @blur="update">
              </div>
            </div>
            <small class="text-danger" v-if="bonusPercentage.errors && bonusPercentage.errors.staff_bonus_percentage">{{
                bonusPercentage.errors.staff_bonus_percentage[0] }}
            </small>
          </div>
        </div>
        <div class="col-md-10 col-md-offset-1">
          <div class="row">
            <div class="col-md-8">At current staff salaries, this percentage would mean production and collection would have
              to average:
            </div>
            <div class="col-md-4">
              <input id="production_collection_salaries" name="production_collection_salaries"
                     type="text" :value="productionCollectionGoal | bp-dollar" disabled>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">- OR -</div>
          </div>
          <div class="row">
            <div class="col-md-8">At current production/collection levels, this percentage would mean that staff salaries
              would have to average:
            </div>
            <div class="col-md-4">
              <input id="current_staff_salaries" name="current_staff_salaries"
                     type="text" :value="staffSalaryGoal | bp-dollar" disabled>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end general staff -->

    <!-- Hygiene -->
    <div class="row-padding--top row text-center" v-if="hasHygiene">
      <plan-data-hygiene-table :editable="0" :hygiene-staff-salaries="this.hygieneSalaries" ></plan-data-hygiene-table>
    </div>
    <div class="row"  v-if="hasHygiene">
      <div class="col-xs-12">
        <div
          :class="{'form-group': true, 'has-error': bonusPercentage.errors && bonusPercentage.errors.hygiene_bonus_percentage}">
          <label for="staff_bonus_percentage" class="col-xs-2 control-label">Desired Hygiene Staff-Bonus Percentage:</label>
          <div class="col-xs-1">
            <div class="row">
              <div class="no-padding-right">
                <input id="hygiene_bonus_percentage" type="text" name="hygiene_bonus_percentage"
                       class="form-control percentage" :value="bonusPercentage.hygiene_bonus_percentage | percentage"
                       @blur="update">
              </div>
            </div>
            <small class="text-danger" v-if="bonusPercentage.errors && bonusPercentage.errors.hygiene_bonus_percentage">{{
                bonusPercentage.errors.hygiene_bonus_percentage[0] }}
            </small>
          </div>
        </div>
        <div class="col-md-10 col-md-offset-1">
          <div class="row">
            <div class="col-md-8">At current hygiene salaries, this percentage would mean hygiene production would have to
              average:
            </div>
            <div class="col-md-4">
              <input id="hygiene_production_collection_salaries" name="hygiene_production_collection_salaries"
                     type="text" :value="hygieneProductionGoal | bp-dollar" disabled>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">- OR -</div>
          </div>
          <div class="row">
            <div class="col-md-8">At current hygiene production levels, this percentage would mean that hygiene salaries
              would have to average:
            </div>
            <div class="col-md-4">
              <input id="hygiene_current_staff_salaries" name="hygiene_current_staff_salaries"
                     type="text" :value="hygieneSalaryGoal | bp-dollar" disabled>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end hygiene -->

    <form-buttons hide-draft="true" next="false" @save="save" @exit="checkIsDirty"></form-buttons>
  </div>
  <div v-else></div>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import moment from 'moment'
import PlanDataTable from "./PlanDataTable";
import PlanDataHygieneTable from "./PlanDataHygieneTable";

import { isInLastSixMonths } from "../../../utils";
import createFormMixin from '../../../mixins/createForm'

export default {
  name: "setBonusPercentage",
  components: { PlanDataTable, PlanDataHygieneTable },
  props: [],
  mixins: [createFormMixin],
  data: function () {
    return {
      stepName: 'setBonusPercentage',
      rows: [],
      isDirty: false
    }
  },
  methods: {
    ...mapActions({
      saveBonusPercentage: "bonusPercentage/save",
      saveBonusPercentageDraft: "bonusPercentage/saveDraft",
      updateProperty: "bonusPercentage/updateProperty",
      runCalculations: "planData/runCalculations",
      goToNextStep: "ui/nextStep",
      clearErrors: "bonusPercentage/clearErrors"
    }),
    update: function (el) {
      this.isDirty = true;
      let value = el.target.value;
      if (el.target.className === 'dollar' && typeof el.target.value === 'string') {
        value = parseFloat(value.replace(/[$,]/g, ''));
      }
      if (el.target.className.indexOf('percentage' >= 0) && typeof el.target.value === 'string') {
        value = parseFloat(value.replace(/%/g, ''));
      }
      this.updateProperty({ prop: el.target.name, value: value });
    },
    getMonth: (month) => {
      if (month) {
        return moment().month(month - 1).format("MMM");
      }
    },
    save: function () {
      this.clearErrors();
      this.saveBonusPercentage()
        .then(() => {
          this.goToNextStep();
        })
        .catch((errors) => {
          //
        });
    },
    saveDraft: function (exit) {
      this.clearErrors();
      this.saveBonusPercentageDraft()
        .then(() => {
          this.isDirty = false;
          if (exit) {
            this.exit();
          }
        })
        .catch(() => {
          this.toggleExitConfirmationModal();
        });
    },
    checkIsDirty: function () {
      if (!this.isDirty) {
        this.exit();
        return;
      }
      this.toggleExitConfirmationModal();
    },
    toggleExitConfirmationModal: function () {
      this.$emit('toggleExitConfirmationModal');
    },
    exit: function () {
      this.$emit('exit');
    }
  },
  computed: {
    ...mapState({
      initialSetup: store => store.initialSetup,
      hasHygiene: store => store.initialSetup.hygiene_plan,
      planData: store => store.planData,
      employeesAndFunds: store => store.employeesAndFunds,
      bonusPercentage: store => store.bonusPercentage
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
    staffSalaries: function () {   // numStaffSalaries in old system
      // the average of all staff salaries over the given time period
      let salaries = 0;

      let employees = this.hasHygiene
        ? employees = this.employeesAndFunds.employees
          .filter(ee => ee.bp_employee_type !== 'hygienist')
        : this.employeesAndFunds.employees;

      employees
        .forEach(ee => {
          if (typeof ee.monthlyData !== 'undefined') {
            ee.monthlyData.filter(mon => isInLastSixMonths(this.initialSetup, mon)).forEach(mon => {
              salaries += parseFloat(mon.gross_pay);
            });
          }
        });
      return salaries / this.activeMonths.length;
    },
    staffPercentageActual: function () {  // numStaffPercent
      // the 'actual' staff percentage
      // i.e., the total real staff salaries divided by the p+c average
      const salaries = this.staffSalaries;
      const pcAverage = this.productionCollectionAverage;
      if (pcAverage > 0) {
        return salaries / pcAverage * 100;
      }
      return 0;
    },
    hygieneSalaries: function () {   // numHygSalaries in old system
      // the total of all hygienist salaries over the given time period
      let salaries = 0;
      this.employeesAndFunds.employees
        .filter(ee => ee.bp_employee_type === 'hygienist')
        .forEach(ee => {
          if (typeof ee.monthlyData !== 'undefined') {
            ee.monthlyData.filter(mon => isInLastSixMonths(this.initialSetup, mon)).forEach(mon => {
              salaries += parseFloat(mon.gross_pay)
            });
          }
        });
      return salaries / this.activeMonths.length;
    },
    hygienePercentageActual: function () {  // numHygienePercent
      // the 'actual' hygienist percentage
      // i.e., the total real hygienist salaries divided by the p+c average
      const salaries = this.hygieneSalaries;
      const prodAverage = this.hygieneProductionAverage;
      if (prodAverage > 0) {
        return salaries / prodAverage * 100;
      }
      return 0;
    },

    // the following are the "goal" fields, i.e., what number X would have to be
    // to meet the desired bonus percentage
    productionCollectionGoal: function () {
      // what p+c would have to average to meet the staff/bonus percent goal
      const desiredStaffPercent = this.bonusPercentage.staff_bonus_percentage / 100;
      if (desiredStaffPercent > 0) {
        return this.staffSalaries / desiredStaffPercent;
      }
      return 0;
    },
    staffSalaryGoal: function () {
      // what staff salaries would have to average to meet the p+c goal
      const desiredStaffPercent = this.bonusPercentage.staff_bonus_percentage / 100;
      if (desiredStaffPercent > 0) {
        // Per BEM-753/BEM-769, update goal calculation to use last month of rolling P&C average
        return this.$store.state.planData.months[5].productionCollectionAverage * desiredStaffPercent;
      }
    },
    hygieneProductionGoal: function () {
      // what hygiene production would have to average to meet the staff/bonus percent goal
      const desiredHygieneStaffPercent = this.bonusPercentage.hygiene_bonus_percentage / 100;
      if (desiredHygieneStaffPercent > 0) {
        return this.hygieneSalaries / desiredHygieneStaffPercent;
      }
      return 0;
    },
    hygieneSalaryGoal: function () {
      // what hygiene salaries would have to average to meet the hygiene production goal
      const desiredHygieneStaffPercent = this.bonusPercentage.hygiene_bonus_percentage / 100;
      if (desiredHygieneStaffPercent > 0) {
        // Per BEM-753/BEM-769, update goal calculation to use last month of rolling P&C average
        return this.$store.state.planData.months[5].productionAverage * desiredHygieneStaffPercent;
        // return this.hygieneProductionAverage * desiredStaffPercent;
      }
    },
    displayHygieneText () {
      return this.hasHygiene ? "Non-Hygiene " : "";
    }
  }
}

</script>

<style scoped lang="scss">
table {
  width: 100%;
  table-layout: fixed;
  margin: 0;

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
    width: 15%;
  }
}

.set-bonus-percentage .row-padding--top {
  padding-top: 8rem;
}

.form-group {
  margin: 3rem 0;
}

</style>

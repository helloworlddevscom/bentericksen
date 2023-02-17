<template>
    <div v-if="activeMonth">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4>{{ getHeading }}</h4>
                </div>
                <div class="modal-body">
                    <div id="contentPage" :class="{fade: true, in: true, hidden: showConfirmation}">
                        <ul class="nav nav-tabs" role="tablist" id="plan_tab">
                            <li :class="{active: activeMainTab === 'data'}">
                                <a href="#data" role="tab" data-toggle="tab" @click="toggleMainTab('data')">Data</a>
                            </li>
                            <li :class="{active: activeMainTab === 'preview'}">
                                <a href="#preview" role="tab" data-toggle="tab" @click="toggleMainTab('preview')">Preview</a>
                            </li>
                        </ul>
                        
                        <div class="tab-content">
                            <div :class="{'tab-pane': true, fade: true, in:true, active: activeMainTab === 'data'}" id="data">
                                <div :class="{'form-group': true, 'has-error': errors && errors.production_amount}">
                                    <label for="production_amount" class="col-md-4 control-label"><span class="text-danger">*</span> Net Production:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-10 no-padding-right">
                                                <input id="production_amount" type="text" name="production_amount"
                                                        :class="{'form-control': true, 'dollar': true, 'is-invalid': errors && errors.production_amount}"
                                                        :value="activeMonth.production_amount | bp-dollar"
                                                        @blur="updateMonth"
                                                        :disabled="notActive === true">
                                            </div>
                                            <small class="text-danger" v-if="errors && errors.production_amount">{{ errors.production_amount[0] }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div :class="{'form-group': true, 'has-error': errors && errors.collection_amount}">
                                    <label for="collection_amount" class="col-md-4 control-label"><span class="text-danger">*</span> Net Collection:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-10 no-padding-right">
                                                <input id="collection_amount" type="text" name="collection_amount"
                                                        :class="{'form-control': true, 'is-invalid': errors && errors.collection_amount}"
                                                        :value="activeMonth.collection_amount | bp-dollar"
                                                        @blur="updateMonth"
                                                       :disabled="notActive === true">
                                            </div>
                                            <small class="text-danger" v-if="errors && errors.collection_amount">{{ errors.collection_amount[0] }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="initialSetup.hygiene_plan" :class="{'form-group': true, 'has-error': errors && errors.hygiene_production_amount}">
                                    <label for="hygiene_production_amount" class="col-md-4 control-label"><span class="text-danger">*</span> Hygiene Production:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-10 no-padding-right">
                                                <input id="hygiene_production_amount" type="text" name="hygiene_production_amount"
                                                        :class="{'form-control': true, 'is-invalid': errors && errors.hygiene_production_amount}"
                                                        :value="activeMonth.hygiene_production_amount | bp-dollar"
                                                        @blur="updateMonth"
                                                       :disabled="notActive === true">
                                            </div>
                                            <small class="text-danger" v-if="errors && errors.hygiene_production_amount">{{ errors.hygiene_production_amount[0] }}</small>
                                        </div>
                                    </div>
                                </div>

                                <ul class="nav nav-tabs" role="tablist" id="employee_tab">
                                    <li :class="{active: activeSubTab === 'staff'}">
                                        <a href="#staff" role="tab" data-toggle="tab" @click="toggleSubTab('staff')">Staff</a>
                                    </li>
                                    <li v-if="initialSetup.hygiene_plan" :class="{active: activeSubTab === 'hygiene'}">
                                        <a href="#hygiene" role="tab" data-toggle="tab" @click="toggleSubTab('hygiene')">Hygiene</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div :class="{'tab-pane': true, fade: true, 'in active': activeSubTab === 'staff'}" id="staff">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>Hours</th>
                                                <th>Salary</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="employee in normalPlanEmployees" :key="employee.id">
                                                <td>{{ employee.first_name + ' ' + employee.last_name }}</td>
                                                <td>
                                                    <input type="text" name="hours_worked"
                                                            :value="employee.monthlyData[getMonthIndex(employee)].hours_worked"
                                                            :data-empid="employee.id"
                                                            @blur="updateField"
                                                           :disabled="notActive === true">
                                                </td>
                                                <td>
                                                    <input type="text" name="gross_pay"
                                                            :value="employee.monthlyData[getMonthIndex(employee)].gross_pay| bp-dollar"
                                                            :data-empid="employee.id"
                                                            @blur="updateField"
                                                           :disabled="notActive === true">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                      <div class="totals">
                                        <div class="row">
                                          <div class="total-heading col-md-1 col-md-offset-3">
                                            <label>Totals: </label>
                                          </div>
                                          <div class="form-group col-md-2 col-md-offset-3">
                                            <label for="hours-total" >Hours: </label>
                                            <input type="text" class="form-control"  name="hours-total" :value="activeMonthHoursTotal()" disabled>
                                          </div>
                                          <div class="form-group col-md-2 col-md-offset-3">
                                            <label for="salary-total">Salaries: </label>
                                            <input type="text" class="form-control" name="salary-total" :value="activeMonthSalariesTotal() | bp-dollar" disabled>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div :class="{'tab-pane': true, fade: true, 'in active': activeSubTab === 'hygiene'}" id="hygiene">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>Hours</th>
                                                <th>Salary</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="employee in hygienePlanEmployees" :key="employee.id">
                                                <td>{{ employee.first_name + ' ' + employee.last_name }}</td>
                                                <td>
                                                    <input type="text" name="hours_worked"
                                                            :value="employee.monthlyData[getMonthIndex(employee)].hours_worked"
                                                            :data-empid="employee.id"
                                                            @blur="updateField"
                                                           :disabled="notActive === true">
                                                </td>
                                                <td>
                                                    <input type="text" name="gross_pay"
                                                            :value="employee.monthlyData[getMonthIndex(employee)].gross_pay | bp-dollar"
                                                            :data-empid="employee.id"
                                                            @blur="updateField"
                                                           :disabled="notActive === true">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="totals">
                                          <div class="row">
                                            <div class="total-heading col-md-1 col-md-offset-3">
                                              <label>Totals: </label>
                                            </div>
                                            <div class="form-group col-md-2 col-md-offset-3">
                                              <label for="hours-total" >Hours: </label>
                                              <input type="text" class="form-control"  name="hours-total" :value="activeMonthHoursTotal('hygienistsHoursTotal')" disabled>
                                            </div>
                                            <div class="form-group col-md-2 col-md-offset-3">
                                              <label for="salary-total">Salaries: </label>
                                              <input type="text" class="form-control" name="salary-total" :value="activeMonthSalariesTotal('hygienistsSalariesTotal') | bp-dollar" disabled>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div :class="{'tab-pane': true, fade: true, in:true, active: activeMainTab === 'dataWarning' }">
                                <h4 class="text-center">
                                    Are you sure? You have one or more fields with a 0.
                                </h4>
                            </div>

                            <div :class="{'tab-pane': true, fade: true, in:true, active: activeMainTab === 'preview'}" id="preview">
                                <ul class="nav nav-tabs" role="tablist" id="preview_tab">
                                    <li class="active">
                                        <a href="#staffPreview" role="tab" data-toggle="tab">Staff</a>
                                    </li>
                                    <li v-if="initialSetup.hygiene_plan">
                                        <a href="#hygienePreview" role="tab" data-toggle="tab">Hygiene</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="staffPreview">
                                        <div class="content" v-if="activeMonth">
                                            <div class="content">
                                                <h4 class="text-center">Staff bonus calculation for the month of {{ getMonth(activeMonth.month, activeMonth.year) + '/' + activeMonth.year}}</h4>
                                            </div>
                                            <div class="content">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="production_collection_average" class="col-md-6 control-label">Production/Collection Average:</label>
                                                        <div class="col-md-6">
                                                            <div class="no-padding-right">
                                                                <input id="production_collection_average" type="text" name="production_collection_average" class="form-control"
                                                                        :value="activeMonth.productionCollectionAverage | bp-dollar" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="staff_salaries" class="col-md-6 control-label">Total Staff Salaries:</label>
                                                        <div class="col-md-6">
                                                            <div class="no-padding-right">
                                                                <input id="staff_salaries" type="text" name="staff_salaries" class="form-control"
                                                                        :value="activeMonthSalariesTotal() | bp-dollar" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  <div class="form-group">
                                                    <label for="staff_salaries" class="col-md-6 control-label">Total Staff Hours:</label>
                                                    <div class="col-md-6">
                                                      <div class="no-padding-right">
                                                        <input id="staff_hours" type="text" name="staff_hours" class="form-control"
                                                               :value="activeMonthHoursTotal()" disabled>
                                                      </div>
                                                    </div>
                                                  </div>
                                                    <div class="form-group">
                                                        <label for="salaryPercentageOfProductionCollection" class="col-md-6 control-label">Salaries % of P + C:</label>
                                                        <div class="col-md-6">
                                                            <div class="no-padding-right">
                                                                <input id="salaryPercentageOfProductionCollection" type="text" name="salaryPercentageOfProductionCollection" class="form-control"
                                                                        :value="activeMonth.staffSalaryPercentageOfPandC | percentage(2)" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="salaryBonusPercentage" class="col-md-6 control-label">Salaries Bonus Percentage:</label>
                                                        <div class="col-md-6">
                                                            <div class="no-padding-right">
                                                                <input id="salaryBonusPercentage" type="text" name="salaryBonusPercentage" class="form-control"
                                                                        :value="initialSetup.staff_bonus_percentage | percentage(2)" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div :class="{'bonus-icons': true, bonus: staffBonus, 'no-bonus': !staffBonus}">
                                                        <i :class="{fa: true, 'fa-smile-o': staffBonus, 'fa-frown-o': !staffBonus}"></i>
                                                        <span class="bonus-label text-center">{{ staffBonus ? 'BONUS' : 'NO BONUS' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="content">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Employee/Fund Name</th>
                                                        <th>Type</th>
                                                        <th>Salary</th>
                                                        <th>Hours</th>
                                                        <th>Emp. %</th>
                                                        <th>Bonus Pay</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="employee in normalPlanEmployees" :key="employee.id">
                                                        <td>
                                                          {{ employee.first_name + ' ' + employee.last_name }}
                                                          <span v-if="!employeeEligible(employee)">
                                                            (<i style="color: red;">Ineligible</i>)
                                                          </span>
                                                        </td>
                                                        <td>{{ employee.bp_employee_type | titlecase }}</td>
                                                        <td>{{ employee.monthlyData[getMonthIndex(employee)].gross_pay | bp-dollar}}</td>
                                                        <td>{{ employee.monthlyData[getMonthIndex(employee)].hours_worked }}</td>
                                                        <td>{{ employee.monthlyData[getMonthIndex(employee)].percentage | percentage(2) }}</td>
                                                        <td>{{ employee.monthlyData[getMonthIndex(employee)].amount_received | bp-dollar}}</td>
                                                    </tr>
                                                    <tr v-for="fund in funds" v-if="initialSetup.separate_fund">
                                                        <td>{{ fund.fund_id + '-' + fund.fund_name }}</td>
                                                        <td v-if="fund.fund_type === 'percentage'">
                                                            <strong>Fund (Percentage: {{ fund.fund_amount | percentage(2) }})</strong>
                                                        </td>
                                                        <td v-else>
                                                            <strong>Fund (Amount: {{ fund.fund_amount | bp-dollar }})</strong>
                                                        </td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>{{ getFundAmount(fund) | bp-dollar }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="content">
                                                <div class="form-group">
                                                    <label for="totalBonusesToBePaid" class="col-md-4 col-md-offset-6 control-label">Total Staff Bonuses To Be Paid:</label>
                                                    <div class="col-md-2">
                                                        <div class="no-padding-right">
                                                            <input id="totalBonusesToBePaid" type="text" name="totalBonusesToBePaid" class="form-control" :value="activeMonth.staffBonusToBePaid | bp-dollar" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade in" id="hygienePreview" v-if="initialSetup.hygiene_plan">
                                        <div class="content" v-if="activeMonth">
                                            <div class="content">
                                                <h4 class="text-center">Hygiene bonus calculation for the month of {{ getMonth(activeMonth.month, activeMonth.year) + '/' + activeMonth.year}}</h4>
                                            </div>
                                            <div class="content">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="hygieneProductionAverage" class="col-md-6 control-label">Hygiene Production Average:</label>
                                                        <div class="col-md-6">
                                                            <div class="no-padding-right">
                                                                <input id="hygieneProductionAverage" type="text" name="hygieneProductionAverage" class="form-control"
                                                                        :value="activeMonth.productionAverage | bp-dollar" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="hygienistsSalariesTotal" class="col-md-6 control-label">Total Staff Salaries:</label>
                                                        <div class="col-md-6">
                                                            <div class="no-padding-right">
                                                                <input id="hygienistsSalariesTotal" type="text" name="hygienistsSalariesTotal" class="form-control"
                                                                        :value="activeMonth.hygienistsSalariesTotal | bp-dollar" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="hygienistsSalaryPercentageOfProd" class="col-md-6 control-label">Salary % of Production Avg:</label>
                                                        <div class="col-md-6">
                                                            <div class="no-padding-right">
                                                                <input id="hygienistsSalaryPercentageOfProd" type="text" name="hygienistsSalaryPercentageOfProd" class="form-control"
                                                                        :value="activeMonth.hygienistsSalaryPercentageOfProd | percentage(2)" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="hygieneBonusPercentage" class="col-md-6 control-label">Salaries Bonus Percentage:</label>
                                                        <div class="col-md-6">
                                                            <div class="no-padding-right">
                                                                <input id="hygieneBonusPercentage" type="text" name="salaryBonusPercentage" class="form-control"
                                                                        :value="initialSetup.hygiene_bonus_percentage | percentage(2)" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div :class="{'bonus-icons': true, bonus: hygienistsBonus, 'no-bonus': !hygienistsBonus}">
                                                        <i :class="{fa: true, 'fa-smile-o': hygienistsBonus, 'fa-frown-o': !hygienistsBonus}"></i>
                                                        <span class="bonus-label text-center">{{ hygienistsBonus ? 'BONUS' : 'NO BONUS' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="content">
                                                <table v-if="initialSetup.hygiene_plan" class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Employee Name</th>
                                                        <th>Type</th>
                                                        <th>Salary</th>
                                                        <th>Hours</th>
                                                        <th>Emp. %</th>
                                                        <th>Bonus Pay</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="employee in hygienePlanEmployees" :key="employee.id">
                                                        <td>
                                                            {{ employee.first_name + ' ' + employee.last_name }}
                                                            <span v-if="!employeeEligible(employee)">
                                                              (<i style="color: red;">Ineligible</i>)
                                                            </span>
                                                        </td>
                                                        <td>{{ employee.bp_employee_type | titlecase }}</td>
                                                        <td>{{ employee.monthlyData[getMonthIndex(employee)].gross_pay | bp-dollar }}</td>
                                                        <td>{{ employee.monthlyData[getMonthIndex(employee)].hours_worked }}</td>
                                                        <td>{{ employee.monthlyData[getMonthIndex(employee)].percentage | percentage(2) }}</td>
                                                        <td>{{ employee.monthlyData[getMonthIndex(employee)].amount_received | bp-dollar }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <div class="form-group">
                                                <label for="totalHygienistsBonusesToBePaid" class="col-md-4 col-md-offset-6 control-label">Total Hygienists Bonuses To Be Paid:</label>
                                                <div class="col-md-2">
                                                    <div class="no-padding-right">
                                                        <input id="totalHygienistsBonusesToBePaid" type="text" name="totalHygienistsBonusesToBePaid" class="form-control" :value="activeMonth.hygieneBonusToBePaid | bp-dollar" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="ConfirmationPage" :class="{fade: true, in: true, hidden: !showConfirmation}">
                        <div class="text-center content">
                            <h4>You are about to make changes to the database and close the month. Do you wish to continue?</h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div v-if="!showConfirmation">
                        <button type="button" class="btn text-uppercase btn-default" @click="activeMainTab = 'data'" v-if="activeMainTab == 'dataWarning'">
                            cancel
                        </button>
                        <button v-else type="button" class="btn text-uppercase btn-default" @click="close">
                            cancel
                        </button>

                        <button type="button" class="btn text-uppercase btn-primary" @click="activeMainTab = showWarning ? 'dataWarning' : 'preview'" v-if="activeMainTab == 'data'"> next
                        </button>

                        <button type="button" class="btn text-uppercase btn-primary" @click="activeMainTab ='preview'" v-if="activeMainTab == 'dataWarning'"> Yes, proceed
                        </button>

                        <button type="button" class="btn text-uppercase btn-primary" @click="toggleConfirmation" v-if="activeMainTab == 'preview'" :disabled="notActive">
                            accept
                        </button>
                    </div>
                    <div v-else>
                        <button type="button" class="btn btn-default" @click="toggleConfirmation">NO</button>
                        <button type="button" class="btn btn-primary" @click="save">YES</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import { mapState, mapGetters, mapActions } from 'vuex'
  import moment from 'moment'
  import { employeeEligibleForBonus } from '@/store/modules/bonusPro/utils'
  const FOCUS_DELAY = 500

  export default {
    name: "MonthModal",
    props: ['notActive'],
    data: function () {
      return {
        activeMainTab: 'data',
        activeSubTab: 'staff',
        dataWarningAccepted: false,
        errors: null,
        message: null,
        staffSalaries: 0,
        showConfirmation: false
      }
    },
    watch: {
        activeMonth: {
            immediate: true,
            handler(month) {
                if (!month) {
                    return
                }

                setTimeout(() => {
                    if (!parseFloat(month.production_amount)) {
                        this.$el.querySelector('#production_amount').value = ''    
                    }
                    
                    this.$el.querySelector('#production_amount').focus()
                }, FOCUS_DELAY)
            }
        }
    },
    methods: {
      ...mapActions({
        clearActiveMonth: 'bonusPro/planData/clearActiveMonth',
        updateMonthField: "bonusPro/planData/updateActiveMonthField",
        updateEmployeeMonthlyData: "bonusPro/employeesAndFunds/updateEmployeeMonthlyData",
        saveMonthData: "bonusPro/employeesAndFunds/saveMonthData"
      }),
      getFundAmount: function (fund) {
        if (!fund.monthlyData) {
          return parseFloat(fund.amount_received);
        }

        const fundData = fund.monthlyData.find((el) => {
          return el.month_id === this.activeMonth.id
        });

        return parseFloat(fundData.amount_received);
      },
      getMonthIndex: function (employee) {
        return employee.monthlyData.findIndex((el) => {
          return el.month_id === this.activeMonth.id
        });
      },
      save: function () {
        this.saveMonthData(this.activeMonth.id)
          .then((response) => {
            this.message = response.message
            this.close();
          })
          .catch((errors) => {
            console.log(errors);
          });
      },
      reset: function () {
        this.clearActiveMonth();
        this.showConfirmation = false;
        this.errors = null;
        this.message = null;
        this.activeMainTab = 'data';
        this.activeSubTab = 'staff';
      },
      close: function () {
        this.$emit('close', this.message);
        this.reset();
      },
      getMonth: (month, year) => {
        return moment().year(year).month(month - 1).format('MMMM');
      },
      toggleMainTab: function (tab) {
        this.activeMainTab = tab;
      },
      toggleConfirmation: function () {
        this.showConfirmation = !this.showConfirmation;
      },
      toggleSubTab: function (tab) {
        this.activeSubTab = tab;
      },
      updateMonth: function (e) {
        const value = parseFloat(e.target.value.replace(/[,$]/g, ''));
        this.updateMonthField({ prop: e.target.name, value: value });
        this.$store.dispatch('bonusPro/planData/updateProductionCollectionAverages')
      },
      updateField: function (e) {
        const value = parseFloat(e.target.value.replace(/[,$]/g, ''));
        this.updateEmployeeMonthlyData({
          prop: e.target.name,
          value: value,
          employeeId: parseInt(e.target.dataset.empid),
          monthId: parseInt(this.activeMonth.id)
        });
      }
    },
    computed: {
      ...mapState({
        initialSetup: state => state.bonusPro.initialSetup,
        employees: state => state.bonusPro.employeesAndFunds.employees,
        funds: state => state.bonusPro.employeesAndFunds.funds
      }),
      ...mapGetters('bonusPro/planData', [
        'activeMonth',
        'activeMonthHoursTotal',
        'activeMonthSalariesTotal'
      ]),
      hygienistsBonus () {
        return this.activeMonth.hygienistsSalaryPercentageOfProd < parseFloat(this.initialSetup.hygiene_bonus_percentage);
      },
      staffBonus () {
        return this.activeMonth.staffSalaryPercentageOfPandC < parseFloat(this.initialSetup.staff_bonus_percentage);
      },
      getHeading () {
        return this.activeMonth
          ? "Bonus Calculation for " + this.getMonth(this.activeMonth.month, this.activeMonth.year) + '/' + this.activeMonth.year : null;
      },
      showWarning () {
        return !parseFloat(this.activeMonth.production_amount) ||
            !parseFloat(this.activeMonth.collection_amount) ||
            this.employees.reduce((leftEmpty, employee) => {
                return leftEmpty ?
                    leftEmpty : 
                    (
                        !parseFloat(employee.monthlyData[this.getMonthIndex(employee)].hours_worked) ||
                        !parseFloat(employee.monthlyData[this.getMonthIndex(employee)].gross_pay)
                    )
            }, false)
      },
      normalPlanEmployees () {
        if (this.initialSetup.hygiene_plan) {
          return this.employees.filter((e) => e.bp_employee_type === 'admin/assistant')
        }
        return this.employees
      },
      hygienePlanEmployees () {
        return this.employees.filter((e) => e.bp_employee_type === 'hygienist')
      },
      employeeEligible() {
        const { year, month } = this.activeMonth
        return (employee) => employeeEligibleForBonus(employee, { year, month })
      }
    }
  }
</script>

<style scoped lang="scss">
    .modal-dialog {
        width: 80%;
    }

    .content {
        margin-bottom: 0px;
        overflow: hidden;
    }

    .bonus-icons {
        width: 120px;
        padding: 13px 0;
        border-radius: 8px;

        i {
            display: block;
            font-size: 8em;
            text-align: center;
            margin-bottom: 10px;
        }

        .bonus-label {
            clear: both;
            display: block;
            font-weight: bold;
            font-size: 20px;
        }

        &.bonus {
            i,
            .bonus-label {
                color: #28a745;
            }
        }
        &.no-bonus {
            i,
            .bonus-label {
                color: #17a2b8;
            }
        }
    }

    .tab-pane {
        padding: 50px 20px;
    }
    .total-heading {
      label {
        padding-top: 25px;
        margin-left: 15px;
      }
    }
</style>

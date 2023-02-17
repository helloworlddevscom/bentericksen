<template>
    <div>
        <div class="col-md-4 no-padding-left">
            <div class="section-header">
                <h4 class="text-center">Report Settings</h4>
            </div>
            <div class="form-group">
                <label for="report_name" class="col-md-6 no-padding-left control-label"><span class="text-danger">*</span> Report Name:</label>
                <div class="col-md-6 no-padding-left">
                    <select value="report_name" name="report_name" id="report_name" class="form-control"
                            v-model="reportSettings.reportName"
                            @change="updateOptions">
                        <option value="">- Select One -</option>
                        <option value="individual_emp_fund">Individual Employee or Fund Summary</option>
                        <option value="employee_fund_summary">Employee and Fund Summary</option>
                        <option value="plan_recap">Plan Recap</option>
                        <option value="plan_summary">Plan Summary</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="report_type" class="col-md-6 no-padding-left control-label"><span class="text-danger" v-if="!disableTypeSelector">*</span> Report Type:</label>
                <div class="col-md-6 no-padding-left">
                    <select value="report_type" name="report_type" id="report_type" class="form-control"
                            v-model="reportSettings.reportType"
                            :disabled="disableTypeSelector">
                        <option value="">- Select One -</option>
                        <option value="detailed">Detailed Report</option>
                        <option value="summary">Summary Report</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="employees_funds" class="col-md-6 no-padding-left control-label"><span class="text-danger" v-if="!disableEmpFundSelector">*</span> Employees/Funds:</label>
                <div class="col-md-6 no-padding-left">
                    <select value="employees_funds" name="employees_funds" id="employees_funds" class="form-control" v-model="reportSettings.employeesFunds"
                            @change="checkEmployeeFund"
                            :disabled="disableEmpFundSelector">
                        <option value="">- Select One -</option>
                        <option value="all">All Employees/Funds</option>
                        <option value="funds">Only Funds</option>
                        <option value="staff_employees">Only Staff Employees</option>
                        <option value="hygiene_employees">Only Hygiene Employees</option>
                        <option value="one">Only One Employee/Fund</option>
                    </select>
                </div>
            </div>
            <div class="form-group" v-if="reportSettings.singleEmployeeFund">
                <label for="employees_fund_one" class="col-md-6 no-padding-left control-label"><span class="text-danger">*</span> Select One:</label>
                <div class="col-md-6 no-padding-left">
                    <select name="employees_fund_one" id="employees_fund_one" class="form-control" v-model="reportSettings.singleEmployeeFundId">
                        <option value="">- Select One -</option>
                        <option v-for="employee in employees" :value="'e-' + employee.id">{{ employee.first_name + ' ' + employee.last_name }}</option>
                        <option v-if="funds.length" v-for="fund in funds" :value="'f-' + fund.id">{{ fund.fund_id + ' ' + fund.fund_name }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="start_date_month" class="col-md-6 no-padding-left control-label"><span class="text-danger" v-if="!disableDates">*</span> Start Date:</label>
                <div class="col-md-2 no-padding-both">
                    <div class="input-group">
                        <select name="start_date_month" id="start_date_month" class="form-control" v-model="reportSettings.startDateMonth" :disabled="disableDates">
                            <option value="">- Select One -</option>
                            <option v-for="(month, index) in months" :value="index">{{ month }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 no-padding-both">
                    <div class="input-group">
                        <select name="start_date_year" id="start_date_year" class="form-control" v-model="reportSettings.startDateYear" :disabled="disableDates">
                            <option value="">- Select One -</option>
                            <option v-for="year in years" :value="year">{{ year }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="end_date_month" class="col-md-6 no-padding-left control-label"><span class="text-danger" v-if="!disableDates">*</span> End Date:</label>
                <div class="col-md-2 no-padding-both">
                    <div class="input-group">
                        <select name="end_date_month" id="end_date_month" class="form-control" v-model="reportSettings.endDateMonth" :disabled="disableDates">
                            <option value="">- Select One -</option>
                            <option v-for="(month, index) in months" :value="index">{{ month }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 no-padding-both">
                    <div class="input-group">
                        <select name="end_date_year" id="end_date_year" class="form-control" v-model="reportSettings.endDateYear" :disabled="disableDates">
                            <option value="">- Select One -</option>
                            <option v-for="year in years" :value="year">{{ year }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div class="row">
                    <button class="btn btn btn-default btn-xs btn-sm btn-danger" v-if="showResetButton" @click="reset">CLEAR DATA</button>
                    <button class="btn btn btn-default btn-xs btn-sm btn-primary" @click="makeApiCall" :disabled="isDisabled">GENERATE REPORT</button>
                </div>
                <div class="row" v-if="showLoading">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
                <div class="row" v-if="showMessage">
                    <span class="text-danger">Your report has been generated.</span>
                </div>
            </div>
        </div>
        <div class="col-md-8 no-padding-both">
            <iframe id="preview" type="application/pdf"></iframe>
        </div>
    </div>
</template>

<script>
  import BonusProService from "../../../../services/BonusProService";
  import { mapState } from 'vuex';

  export default {
    name: "Reports",
    props: ['years', 'months'],
    data: function () {
      return {
        showLoading: false,
        showResetButton: false,
        showMessage: false,
        isDisabled: true,
        report: null,
        disableTypeSelector: false,
        disableEmpFundSelector: false,
        disableDates: false,
        reportSettings: {
          reportName: '',
          reportType: '',
          employeesFunds: '',
          startDateMonth: '',
          startDateYear: '',
          endDateMonth: '',
          endDateYear: '',
          planId: '',
          singleEmployeeFund: false,
          singleEmployeeFundId: ''
        }
      }
    },
    computed: {
      ...mapState({
        planId: store => store.bonusPro.initialSetup.id,
        employees: store => store.bonusPro.employeesAndFunds.employees,
        funds: store => store.bonusPro.employeesAndFunds.funds
      })
    },
    methods: {
      updateOptions (e) {
        this.disableTypeSelector = false;
        this.disableEmpFundSelector = false;
        this.disableDates = false;

        if (e.target.value === 'plan_summary') {
          this.disableTypeSelector = true;
          this.disableEmpFundSelector = true;
          this.reportSettings.reportType = '';
          this.reportSettings.employeesFunds = '';
        }

        if (e.target.value === 'plan_recap') {
          this.disableTypeSelector = true;
          this.disableEmpFundSelector = true;
          this.disableDates = true;
          this.reportSettings.reportType = '';
          this.reportSettings.employeesFunds = '';
          this.reportSettings.startDateMonth = '';
          this.reportSettings.startDateYear = '';
          this.reportSettings.endDateMonth = '';
          this.reportSettings.endDateYear = '';
        }
      },
      checkEmployeeFund (e) {
        if (e.target.value === 'one') {
          this.reportSettings.singleEmployeeFund = true;
        } else {
          this.reportSettings.singleEmployeeFund = false;
        }
      },
      generateReport: function () {
        $('#preview').attr('src', 'data:application/pdf;base64,' + this.report);
      },
      makeApiCall: function () {
        const self = this;
        this.showLoading = true;
        this.reportSettings.planId = this.planId;
        BonusProService.generateReport(this.reportSettings)
          .then((data) => {
            self.showLoading = false;
            self.showResetButton = true;
            self.report = data.report;
            self.showMessage = true;
            self.generateReport();

            setTimeout(function () {
              self.showMessage = false;
            }, 5000);
          })
          .catch((err) => {
            console.log('error', err);
          })
      },
      updateStartDate: function (date) {
        this.reportSettings.startDate = date;
      },
      updateEndDate: function (date) {
        this.reportSettings.endDate = date;
      },
      reset: function () {
        this.reportSettings = {
          reportName: '',
          reportType: '',
          employeesFunds: '',
          startDateMonth: '',
          startDateYear: '',
          endDateMonth: '',
          endDateYear: '',
          planId: '',
          disableTypeSelector: false,
          disableEmpFundSelector: false,
          singleEmployeeFund: false,
          singleEmployeeFundId: ''
        };
        this.showLoading = false;
        this.showResetButton = false;
        this.isDisabled = true;
        this.showMessage = false;
        $('#preview').attr('src', null);
      }
    },
    watch: {
      reportSettings: {
        handler (newData) {
          let decision = false;
          for (const prop in newData) {
            if (prop === 'planId') {
              continue;
            }

            if (prop === 'reportType' && this.disableTypeSelector) {
              continue;
            }

            if (prop === 'employeesFunds' && this.disableEmpFundSelector) {
              continue;
            }

            if ((prop === 'startDateMonth' && this.disableDates) ||
              (prop === 'startDateYear' && this.disableDates) ||
              (prop === 'endDateMonth' && this.disableDates) ||
              (prop === 'endDateYear' && this.disableDates)) {
              continue;
            }

            if (prop === 'singleEmployeeFundId') {
              if (!newData.singleEmployeeFund) {
                continue;
              }
            }

            if (this.reportSettings[prop] === '') {
              decision = true;
              break
            }
          }
          this.isDisabled = decision;
        },
        deep: true
      }
    }
  }
</script>

<style scoped lang="scss">
    .fa-spinner {
        font-size: 18px
    }

    #preview {
        width: 100%;
        height: 500px;
        border: 1px solid #C7C7C7;
        background-color: #F5F5F5;
        border-radius: 4px;
    }

    .section-header {
        margin-bottom: 10px;
        display: block;
        overflow: hidden;
    }
</style>

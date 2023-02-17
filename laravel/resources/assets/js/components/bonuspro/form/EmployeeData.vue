<template>
    <div id="employee-data" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content" v-if="employeeData">
                <div class="modal-header text-center">
                    <h4 class="modal-title" id="modalLabel">{{ employeeData.first_name }} {{ employeeData.last_name }}</h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" role="tablist" id="employee_tab">
                        <li @click.prevent="setActive('general')" :class="{ active: isActive('general') }">
                            <a href="#general" role="tab">General</a>
                        </li>
                      <li @click.prevent="setActive('salary')" :class="{ active: isActive('salary') }">
                            <a href="#salary" role="tab">Salary</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Attendance -->
                        <div class="tab-pane fade in" id="general" :class="{ 'active show': isActive('general') }">
                            <div v-if="errors" class="alert alert-danger">
                                <strong>There are some errors with your submission. Please correct the following:</strong>
                            </div>
                            <div class="row">
                                <div :class="{'form-group': true, 'has-error': errors && errors.first_name}">
                                    <label for="first_name" class="col-md-4 control-label"><span class="text-danger">*</span> First Name:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-10 no-padding-right">
                                                <input id="first_name" type="text" name="first_name"
                                                        :class="{ 'form-control': true, 'is-invalid': errors && errors.first_name}"
                                                        :value="employeeData.first_name"
                                                        @blur="update">
                                            </div>
                                            <small class="text-danger" v-if="errors && errors.first_name">{{ errors.first_name[0] }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div :class="{'form-group': true, 'has-error': errors && errors.last_name}">
                                    <label for="last_name" class="col-md-4 control-label"><span class="text-danger">*</span> Last Name:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-10 no-padding-right">
                                                <input id="last_name" type="text" name="last_name"
                                                        :class="{ 'form-control': true, 'is-invalid': errors && errors.last_name}"
                                                        :value="employeeData.last_name"
                                                        @blur="update">
                                            </div>
                                            <small class="text-danger" v-if="errors && errors.last_name">{{ errors.last_name[0] }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div :class="{'form-group': true, 'has-error': errors && errors.email}">
                                    <label for="email" class="col-md-4 control-label"><span class="text-danger">*</span> Email:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-10 no-padding-right">
                                                <input id="email" type="text" name="email"
                                                        :class="{ 'form-control': true, 'is-invalid': errors && errors.email}"
                                                        :value="employeeData.email"
                                                        @blur="update">
                                            </div>
                                            <small class="text-danger" v-if="errors && errors.email">{{ errors.email[0] }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="!employeeId" :class="{'form-group': true}">
                                    <label for="address1" class="col-md-4 control-label">Address 1:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-10 no-padding-right">
                                                <input id="address1" type="text" name="address1"
                                                        :class="{ 'form-control': true}"
                                                        :value="employeeData.address1"
                                                        @blur="update">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="!employeeId" :class="{'form-group': true}">
                                    <label for="address2" class="col-md-4 control-label">Address 2:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-10 no-padding-right">
                                                <input id="address2" type="text" name="address2"
                                                        :class="{ 'form-control': true}"
                                                        :value="employeeData.address2"
                                                        @blur="update">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="!employeeId" class="form-group">
                                    <div>
                                        <label for="city" class="col-md-4 control-label">City/State/ZIP:</label>
                                    </div>
                                    <div :class="{'col-sm-2': true, 'no-padding-right': true, 'has-error': errors && (errors.city)}">
                                        <input id="city" type="text" name="city"
                                                :class="{ 'form-control': true}"
                                                :value="employeeData.city"
                                                @blur="update">
                                    </div>
                                    <div :class="{'col-sm-1': true }">
                                        <select name="state" id="state"
                                                :class="{ 'form-control': true}"
                                                :value="employeeData.state"
                                                @blur="update">
                                            <option value="">- Select -</option>
                                            <option v-for="(state, abbr) in states" :value="abbr">{{ state }}</option>
                                        </select>
                                    </div>
                                    <div :class="{'col-sm-2': true}">
                                        <input id="postal_code" type="text" name="postal_code"
                                                :class="{ 'form-control': true}"
                                                :value="employeeData.postal_code"
                                                @blur="update">
                                    </div>
                                </div>
                                <div v-if="!employeeId" class="form-group">
                                    <div>
                                        <label for="phone1_type" class="col-md-4 control-label">Phone:</label>
                                    </div>
                                    <div class="col-sm-1 no-padding-right">
                                        <select name="phone1_type" id="phone1_type"
                                                :class="{ 'form-control': true}"
                                                :value="employeeData.phone1_type"
                                                @blur="update">
                                            <option value="home">Home</option>
                                            <option value="cell">Cell</option>
                                        </select>
                                    </div>
                                    <div :class="{'col-sm-4': true, 'has-error': (employeeData.phone1 && employeeData.phone1.length > 10)}">
                                        <input id="phone1" type="text" name="phone1"
                                                :class="{ 'form-control': true, 'is-invalid': errors && errors.phone1}"
                                                :value="employeeData.phone1 | phone"
                                                @keyup="update">
                                        <small :class="{'text-danger': employeeData.phone1 && employeeData.phone1.length > 10}">Please enter only the numbers (10 digits).</small>
                                        <small class="text-danger" v-if="errors && errors.phone1">{{ errors.phone1[0] }}</small>
                                    </div>
                                </div>
                                <div v-if="!employeeId" :class="{'form-group': true}">
                                    <label for="hired" class="col-md-4 control-label">Hired Date:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 no-padding-right">
                                                <div class="input-group">
                                                    <input id="hired" type="text"
                                                            name="hired"
                                                            placeholder="mm/dd/yyyy"
                                                            class="date-picker hired form-control"
                                                            :class="{ 'form-control': true}"
                                                            :value="employeeData.hired">
                                                    <span class="input-group-addon">
                                                        <label for="hired"><i class="fa fa-calendar"></i></label>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="!employeeId" :class="{'form-group': true}">
                                    <label for="dob" class="col-md-4 control-label">Date of Birth:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 no-padding-right">
                                                <div class="input-group">
                                                    <input id="dob" type="text"
                                                            name="dob"
                                                            placeholder="mm/dd/yyyy"
                                                            class="date-picker dob form-control"
                                                            :class="{ 'form-control': true}"
                                                            :value="employeeData.dob">
                                                    <span class="input-group-addon">
                                                        <label for="dob"><i class="fa fa-calendar"></i></label>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">
                                        Eligible For The Bonus:
                                    </label>
                                    <div class="col-md-6">
                                        <input type="checkbox" :checked="employeeData.bp_eligible" @change="updateEligible">
                                    </div>
                                </div>

                              <div v-if="employeeData.bp_eligible" :class="{'form-group': true, 'has-error': errors && errors.bp_eligibility_date}">
                                <label for="bp_eligibility_date" class="col-md-4 control-label"><span class="text-danger">*</span> Eligibility Date:</label>
                                <div class="col-md-6">
                                  <div class="row">
                                    <div class="col-md-6 no-padding-right">
                                      <div class="input-group">
                                        <input id="bp_eligibility_date" type="text"
                                               name="bp_eligibility_date"
                                               placeholder="mm/yyyy"
                                               class="date-picker bp_eligibility_date form-control"
                                               :class="{ 'form-control': true}"
                                               :value="employeeData.bp_eligibility_date">
                                        <span class="input-group-addon">
                                            <label for="bp_eligibility_date"><i class="fa fa-calendar"></i></label>
                                        </span>
                                      </div>
                                    </div>
                                    <small class="text-danger" v-if="errors && errors.bp_eligibility_date">{{ errors.bp_eligibility_date[0] }}</small>
                                  </div>
                                </div>
                              </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label"><span class="text-danger">*</span>Employee Type:</label>
                                    <div class="col-md-6">
                                        <label class="radio-inline" for="bp_employee_type_admin_assistant">
                                            <input type="radio" id="bp_employee_type_admin_assistant" name="bp_employee_type" value="admin/assistant"
                                                    :checked="employeeData.bp_employee_type === 'admin/assistant'"
                                                    @blur="update"> Admin/Assistant
                                        </label>
                                        <label class="radio-inline" for="bp_employee_type_hygienist">
                                            <input type="radio" id="bp_employee_type_hygienist" name="bp_employee_type" value="hygienist"
                                                    :checked="employeeData.bp_employee_type === 'hygienist'"
                                                    @blur="update"> Hygienist
                                        </label>
                                    </div>
                                </div>
                                <div :class="{'form-group': true, 'has-error': errors && errors.bp_bonus_percentage}" v-if="bonusDistributionType === 'fixed_percentage'">
                                    <label for="employee_percentage" class="col-md-4 control-label"><span class="text-danger">*</span> Employee Percentage:</label>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-4 no-padding-right">
                                                <input id="employee_percentage" type="text" name="bp_bonus_percentage"
                                                        :class="{ 'form-control': true, 'is-invalid': errors && errors.bp_bonus_percentage}"
                                                        :value="employeeData.bp_bonus_percentage"
                                                        @blur="update">
                                            </div>
                                            <small class="text-danger" v-if="errors && errors.bp_bonus_percentage">{{ errors.bp_bonus_percentage[0] }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="salary" :class="{ 'active show': isActive('salary') }">
                            <h4 class="text-center">Enter employee's gross pay and hours worked below:</h4>
                            <table>
                                <thead>
                                <tr>
                                    <th class="column column--title"></th>
                                    <th v-for="month in activeMonthlyData">{{ getMonth(month.month) }}/{{ month.year }}</th>
                                    <th class="column column--averages">Average</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="column column--title">Gross Pay</td>
                                    <td v-for="(month, key) in activeMonthlyData">
                                        <input type="text" class="dollar"
                                                :name="'monthlyData.' + getMonthIndex(employeeData, month.month_id) + '.gross_pay'"
                                                :value="employeeData.monthlyData[getMonthIndex(employeeData, month.month_id)].gross_pay | bp-dollar"
                                                :tabindex="(key * 2) + 1"
                                                @blur="update">
                                    </td>
                                    <td class="column column--averages">
                                        <input type="text" :value='grossPayAverage | bp-dollar' disabled></td>
                                </tr>
                                <tr>
                                    <td class="column column--title">Hours Worked</td>
                                    <td v-for="(month, key) in activeMonthlyData">
                                        <input type="text"
                                                :name="'monthlyData.' + getMonthIndex(employeeData, month.month_id) + '.hours_worked'"
                                                :value="employeeData.monthlyData[getMonthIndex(employeeData, month.month_id)].hours_worked"
                                                :tabindex="(key * 2) + 2"
                                                @blur="update">
                                    </td>
                                    <td class="column column--averages">
                                        <input type="text" :value='hoursWorkedAverage' disabled></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <a href="#" class="btn btn-danger btn-xs" @click="close">CANCEL</a>
                    <a v-if="activeItem === 'general'" href="#" class="btn btn-primary btn-xs" @click="nextTab">NEXT</a>
                    <a href="#" v-else class="btn btn-primary btn-xs" @click="save">SAVE</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState, mapGetters } from 'vuex'
    import moment from 'moment'

    export default {
      name: "EmployeeData",
      props: ['employeeId', 'states'],
      data: function () {
        return {
          activeItem: 'general',
          saveButtonDisabled: true,
          eligibilityMonth: '',
          eligibilityYear: ''
        }
      },
      watch: {
        employeeId: function () {
          this.setEmployeeData();
        },
        eligibilityDate(date) {
          this.updateEligibilityDate(date)
        },
      },
      methods: {
        ...mapActions({
          addExistingEmployeeToPlan: 'employeesAndFunds/addExistingEmployeeToPlan',
          createEmployee: 'employeesAndFunds/createEmployee',
          updateEmployeeField: 'employeesAndFunds/updateEmployeeField',
          setActiveEmployee: 'employeesAndFunds/setActiveEmployee',
          clearErrors: 'employeesAndFunds/clearErrors'
        }),
        isActive (tab) {
          return this.activeItem === tab;
        },
        setActive (tab) {
          this.activeItem = tab;
        },
        getMonthIndex: function (employee, month_id) {
          return employee.monthlyData.findIndex((el) => {
            return el.month_id === month_id
          });
        },
        getMonth: (month) => {
          if (month) {
            return moment().month(month - 1).format("MMM");
          }
        },
        close: function () {
          this.setActive('general');
          this.clearErrors('employees');
          this.$emit('close');
        },
        update: function (e) {
          let value = e.target.value;
          if (e.target.className === 'dollar' && typeof e.target.value === 'string') {
            value = parseFloat(value.replace(/[$,]/g, ''));
          }
          this.updateEmployeeField({ prop: e.target.name, value: value });
        },
        updateEligible(e) {
            this.updateEmployeeField({ prop: 'bp_eligible', value: e.target.checked })
        },
        updateEligibilityDate: function (obj) {
          let date = `${obj.selectedMonth+1}/${obj.selectedYear}`;
          this.updateEmployeeField({ prop: 'bp_eligibility_date', value: date });
        },
        updateHiredDate: function (date) {
          this.updateEmployeeField({ prop: 'hired', value: date });
        },
        updateDobDate: function (date) {
          this.updateEmployeeField({ prop: 'dob', value: date });
        },
        save: function () {
          const action = this.employeeId ? 'addExistingEmployeeToPlan' : 'createEmployee';

          this[action]()
                    .then(() => {
                      this.setActive('general');
                      this.close();
                    })
                    .catch(() => {
                      this.setActive('general');
                    });
        },
        nextTab: function () {
          return this.setActive('salary');
        },
        setEmployeeData: function () {
          this.setActiveEmployee(this.employeeId);
          const self = this;

          setTimeout(function () {


            $('.date-picker.hired').datepicker({
              changeMonth: true,
              changeYear: true,
              showButtonPanel: true,
              yearRange: "-100:+10",
              onSelect: function (date) {
                self.updateHiredDate(date);
              }
            });

            $('.date-picker.dob').datepicker({
              changeMonth: true,
              changeYear: true,
              showButtonPanel: true,
              yearRange: "-100:+10",
              onSelect: function (date) {
                self.updateDobDate(date);
              }
            });

            $('.date-picker.bp_eligibility_date').datepicker({
              changeMonth: true,
              changeYear: true,
              showButtonPanel: true,
              yearRange: "-100:+10",
              dateFormat: 'mm/yy',
              beforeShow: function() {
                $('#ui-datepicker-div').addClass('doe');
              },
              onClose: function (date, obj) {
                self.updateEligibilityDate(obj);
                setTimeout(function() {
                  $('#ui-datepicker-div').removeClass('doe');
                },250);
              }
            });

          }, 100);
        }
      },
      computed: {
        ...mapState({
          employeeData: state => state.employeesAndFunds.activeEmployee,
          errors: state => state.employeesAndFunds.errors.employees,
          bonusDistributionType: state => state.initialSetup.distribution_type
        }),
        ...mapGetters({
          activeMonthlyData: 'employeesAndFunds/activeMonthlyData'
        }),
        textInputFieldClasses (name) {
          return {
            "form-control": true,
            "error": this.errors && this.errors[name]
          }
        },
        grossPayAverage () {
          let average = 0;

          if (!this.employeeData || !this.activeMonthlyData) {
            return '-';
          }

          this.activeMonthlyData.forEach(el => {
            average += parseFloat(el.gross_pay);
          });

          return average > 0 ? (average / this.activeMonthlyData.length).toFixed(2) : '-';
        },
        hoursWorkedAverage () {
          let average = 0;

          if (!this.employeeData || !this.activeMonthlyData) {
            return '-';
          }

          this.activeMonthlyData.forEach(el => {
            average += parseFloat(el.hours_worked);
          });

          return average > 0 ? (average / this.activeMonthlyData.length).toFixed(2) : '-';
        },
        eligibilityDateInvalid() {
            if (!this.eligibilityDate) {
                return false
            }

            return isNaN(new Date(this.eligibilityDate))
        }
      }
    }
</script>

<style scoped lang="scss">
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';
    @import '~bootstrap/scss/utilities';
    .font-size-11::placeholder {
      font-size: 11px !important;
    }

    .tab-pane {
        padding: 30px 0 20px 0;
    }

    #email {
        margin-bottom: 0;
    }

    .modal-dialog {
        width: 80%;
        overflow-y: initial;
    }
    .modal-body {
      max-height: calc(100vh - 200px);
      overflow-y: auto;
    }

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

        &:disabled {
            padding: 2px;
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

    small {
        clear: both;
        display: block;
        padding-left: 15px;

        .col-sm-1 &,
        .col-sm-2 &,
        .col-sm-4 & {
            padding-left: 0;
        }
    }

    input[type=radio] {
        margin-top: 2px;
    }
</style>

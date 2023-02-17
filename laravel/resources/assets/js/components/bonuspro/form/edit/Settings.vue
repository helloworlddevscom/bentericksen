<template>
    <div>
        <div class="form-group">
            <div v-if="errors" class="alert alert-danger fade in">
                <strong>There are some errors with your submission. Please correct the following:</strong>
                <ul v-for="(errorMsgs, key) in errors">
                    <li>Field <strong>{{ key }}</strong>:
                        <div v-for="msg in errorMsgs">{{ msg }}</div>
                    </li>
                </ul>
            </div>
            <div v-if="feedback" class="alert alert-success fade in">
                <span>{{ feedback }}</span>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4"><span class="text-danger">* Required Fields</span></div>
            </div>
            <div class="form-group">
                <label for="plan_id" class="col-md-4 control-label"><span class="text-danger">*</span> Plan ID/Plan Name:</label>
                <input :value="initialSetup.id" type="hidden" name="id" class="form-control">
                <input :value="businessId" type="hidden" name="business_id" class="form-control">
                <input :value="createdBy" type="hidden" name="created_by" class="form-control">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2 no-padding-right">
                            <input id="plan_id" :value="initialSetup.plan_id" @blur="update" type="text" name="plan_id" class="form-control">
                        </div>
                        <div class="col-md-8 no-padding-right">
                            <input id="plan_name" :value="initialSetup.plan_name" @blur="update" type="text" name="plan_name" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="start_month" class="col-md-4 control-label"><span class="text-danger">*</span> Plan Start Date (MM/YYYY):</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2 no-padding-right">
                            <input id="start_month" :value="initialSetup.start_month" type="text" name="start_month" class="form-control" disabled>
                        </div>
                        <div class="separator">
                            <span>/</span>
                        </div>
                        <div class="col-md-3 no-padding-both no-margin-both">
                            <input id="start_year" :value="initialSetup.start_year" type="text" name="start_year" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input :value="initialSetup.password_set" type="hidden" name="password_set" class="form-control">
                <label for="plan_password" class="col-md-4 control-label"><span class="text-danger">*</span> Password:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-8 no-padding-right">
                            <input id="plan_password" :value="initialSetup.password" type="password" name="password" class="form-control" @blur="update">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="col-md-4 control-label"><span class="text-danger">*</span> Confirm Password:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-8 no-padding-right">
                            <input id="password_confirmation" :value="initialSetup.password_confirmation" type="password" name="password_confirmation" class="form-control" @blur="update">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="distribution_type" class="col-md-4 control-label"><span class="text-danger">*</span> Type of Bonus Distribution:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <select :value="initialSetup.distribution_type" name="distribution_type" id="distribution_type" class="form-control" @blur="update">
                                <option value="">- Select One -</option>
                                <option value="hours">Hours</option>
                                <option value="salary">Salary</option>
<!--                                <option value="fixed_percentage">Fixed Percentage</option>-->
                                <option value="equal_share">Equal Share</option>
                            </select>
                        </div>
                        <span class="bp_tooltip" type="button" data-toggle="tooltip" data-placement="right" title="Distribution by hours will look at the hours worked by an employee to determine their amount of bonus payout. Salary will determine the bonus an employee receives based on their salary. Fixed percentage distribution will distribute based on a percentage of total bonus money. Equal share will distribute evenly across all employees.">?</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="rolling_average" class="col-md-4 control-label"><span class="text-danger">*</span> Rolling Average Period:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <select :value="initialSetup.rolling_average" name="rolling_average" id="rolling_average" class="form-control" @blur="updateRollingAverage">
                                <option value="">- Select One -</option>
                                <option value="2">2 Months</option>
                                <option value="3">3 Months</option>
                                <option value="4">4 Months</option>
                                <option value="6">6 Months</option>
                            </select>
                        </div>
                        <span class="bp_tooltip" type="button" data-toggle="tooltip" data-placement="right" title="ROLLING AVERAGE PERIOD - The rolling average corresponds to the number of months BonusPro will average production and collection.">?</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="staff_bonus_percentage" class="col-md-4 control-label"><span class="text-danger">*</span> Staff Bonus Percentage:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2 no-padding-right">
                            <input id="staff_bonus_percentage" :value="initialSetup.staff_bonus_percentage" type="text" name="staff_bonus_percentage" class="form-control" @blur="update">
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label">Enable Hygiene Plan:</label>
                <div class="col-md-6">
                    <label class="radio-inline" for="create_hygiene_plan_yes">
                        <input type="radio" id="create_hygiene_plan_yes" name="hygiene_plan" value="1"
                               :checked="initialSetup.hygiene_plan"
                               @click="updateChecked"> Yes
                    </label>
                    <label class="radio-inline" for="create_hygiene_plan_no">
                        <input type="radio" id="create_hygiene_plan_no" name="hygiene_plan" value="0"
                               :checked="!initialSetup.hygiene_plan"
                               @click="updateChecked"> No
                    </label>
                </div>
            </div>

            <div v-if="hasHygiene">
                <div class="form-group">
                    <label class="col-md-4 control-label">
                        Hygiene Production:
                    </label>

                    <div class="col-md-6 d-flex align-items-end">
                        <div v-for="(month, key) in activeMonths" class="d-flex flex-column align-items-center mr-2">
                            <div>
                                <strong>{{ month.month | month }}/{{ month.year }}</strong>
                            </div>
                            <input type="text" class="form-control dollar"
                               :name="`months.${key}.hygiene_production_amount`"
                               :value="activeMonths[key].hygiene_production_amount | bp-dollar"
                               @blur="(e) => updateHygieneProductionAmountByMonthId(e, month.id)">
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div>
                                <strong>Average</strong>
                            </div>
                            <input class="form-control dollar" type="text" :value='hygieneProductionAverage | bp-dollar' style="width: 80px" disabled>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <label for="hygiene_bonus_percentage" class="col-md-4 control-label">
                        Hygiene Bonus Percentage:
                    </label>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-2 no-padding-right">
                                <input id="hygiene_bonus_percentage" :value="initialSetup.hygiene_bonus_percentage" type="text" name="hygiene_bonus_percentage" class="form-control" @blur="update">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Enable Funds:</label>
                <div class="col-md-6">
                    <label class="radio-inline" for="separate_fund_yes">
                        <input type="radio" id="separate_fund_yes" name="separate_fund" value="1"
                                :checked="initialSetup.separate_fund === 1"
                                @change="update"> Yes
                    </label>
                    <label class="radio-inline" for="separate_fund_no">
                        <input type="radio" id="separate_fund_no" name="separate_fund" value="0"
                                :checked="initialSetup.separate_fund === 0"
                                @change="update"> No
                    </label>
                    <span class="bp_tooltip" type="button" data-toggle="tooltip" data-placement="right" title='Funds represent an ability to set aside either a percent or a set amount of the bonus dollars available to "fund" special events such as team retreats, continuing education, shopping sprees, etc. Fund information is entered on the same screen as employee information in the Emp/Fund tab. You must first let the program know that you want to use funding by clicking on the Enable Funds option on the Setup screen.'>?</span>
                </div>
            </div>

            <div class="text-center">
                <button class="btn btn btn-default btn-xs btn-sm btn-primary" @click="save">SAVE</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters, mapState } from 'vuex'

    export default {
      name: "Settings",
      props: ['createdBy', 'businessId'],
      data: function () {
        return {
          feedback: null
        }
      },
      methods: {
        ...mapActions({
          saveInitialSetup: "initialSetup/save",
          updateProperty: "initialSetup/updateProperty",
          setErrors: "global/setErrors",
          clearErrors: "global/clearErrors",
          updateProductionCollectionAverages: "planData/updateProductionCollectionAverages",
          updateField: 'planData/updateField',
          savePlanData: 'planData/save'
        }),
        setFeedback: function (msg) {
          const self = this;
          this.feedback = msg;
          setTimeout(() => {
            self.feedback = null;
          }, 5000);
        },
        save: function () {
          this.clearErrors();
          this.saveInitialSetup()
                    .then(() => {
                      this.setFeedback('Plan settings updated successfully');
                    })
                    .catch((errors) => {
                      this.setErrors(errors)
                    });

          if (this.hasHygiene) {
            this.savePlanData()
          }
        },
        update: function (e) {
          const prop = e.target.name;
          const value = e.target.value;

          this.updateProperty({
            prop: prop,
            value: value
          });
        },
        updateChecked(e) {
          const prop = e.target.name;
          const value = parseInt(e.target.value, 0);

          this.updateProperty({
            prop: prop,
            value: value
          });
        },
        updateHygieneProductionAmountByMonthId(e, id) {
            let index = this.months.reduce((key, month, index) => month.id == id ? index : key, 0)

            this.updateField({
                prop: `months.${index}.hygiene_production_amount`,
                value: parseFloat(e.target.value.replace(/[$,]/g, ''))
            })
        },
        updateRollingAverage: function (e) {
          this.updateProperty({ prop: e.target.name, value: e.target.value });
          this.updateProductionCollectionAverages();
        }
      },
      computed: {
        ...mapState({
          initialSetup: state => state.initialSetup,
          errors: state => state.global.errors,
          hasHygiene: store => store.initialSetup.hygiene_plan,
        }),
        ...mapGetters({
          activeMonths: 'planData/activeMonths',
          months: 'planData/months',
          hygieneProductionAverage: 'planData/hygieneProductionAverage'
        })
      }

    }
</script>

<style scoped lang="scss">
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';
    @import '~bootstrap/scss/utilities';

    .no-margin-both {
        margin: 0;
    }

    .separator {
        float: left;
        padding: 5px 5px 0 5px;
    }

    input[type=radio] {
        margin-top: 2px;
    }
</style>

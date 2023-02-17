<template>
    <div v-if="active">
        <div class="form-group">
            <div v-if="errors" class="alert alert-danger">
                <strong>There are some errors with your submission. Please correct the following:</strong>
                <ul v-for="(errorMsgs, key) in errors">
                    <li>Field <strong>{{ key }}</strong>:
                        <div v-for="msg in errorMsgs">{{ msg }}</div>
                    </li>
                </ul>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4"><span class="text-danger">* Required Field</span></div>
            </div>
            <div class="form-group">
                <label for="plan_id" class="col-md-4 control-label"><span class="text-danger">*</span> Plan ID/Plan Name:</label>
                <input :value="initialSetup.id" type="hidden" name="id" class="form-control">
                <input :value="businessId" type="hidden" name="business_id" class="form-control">
                <input :value="createdBy" type="hidden" name="created_by" class="form-control">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2 no-padding-right">
                            <input id="plan_id" :value="initialSetup.plan_id" @change="update" type="text" name="plan_id" class="form-control">
                        </div>
                        <div class="col-md-8 no-padding-right">
                            <input id="plan_name" :value="initialSetup.plan_name" @change="update" type="text" name="plan_name" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="start_month" class="col-md-4 control-label"><span class="text-danger">*</span> Plan Start Date (MM/YYYY):</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2 no-padding-right">
                            <input id="start_month" :value="initialSetup.start_month" @change="update" type="text" name="start_month" class="form-control" maxlength="2">
                        </div>
                        <div class="separator">
                            <span>/</span>
                        </div>
                        <div class="col-md-3 no-padding-both no-margin-both">
                            <input id="start_year" :value="initialSetup.start_year" @change="update" type="text" name="start_year" class="form-control" maxlength="4">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input :value="initialSetup.password_set || 0" type="hidden" name="password_set" class="form-control">
                <label for="plan_password" class="col-md-4 control-label"><span class="text-danger">*</span> Password:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-8 no-padding-right">
                            <input id="plan_password" :value="initialSetup.password" type="password" name="password" class="form-control" @change="update">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="col-md-4 control-label"><span class="text-danger">*</span> Confirm Password:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-8 no-padding-right">
                            <input id="password_confirmation" :value="initialSetup.password_confirmation" type="password" name="password_confirmation" class="form-control" @change="update">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="distribution_type" class="col-md-4 control-label"><span class="text-danger">*</span> Type of Bonus Distribution:</label>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <select :value="initialSetup.distribution_type" name="distribution_type" id="distribution_type" class="form-control" @change="update">
                                <option value="">- Select One -</option>
                                <option value="hours">Hours</option>
                                <option value="salary">Salary</option>
                                <!-- <option value="fixed_percentage">Fixed Percentage</option> -->
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
                            <select :value="initialSetup.rolling_average" name="rolling_average" id="rolling_average" class="form-control" @change="updateRollingAverage">
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
                <label for="type_of_practice" class="col-md-4 control-label">
                    <span class="text-danger">*</span> Type of Practice:
                </label>
                
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4 no-padding-right">
                            <select :value="initialSetup.type_of_practice" name="type_of_practice" id="type_of_practice" class="form-control" @change="update">
                                <option value="">- Select One</option>
                                <option value="cosmetic">Cosmetic</option>
                                <option value="endodontic">Endodontic</option>
                                <option value="general">General</option>
                                <option value="oral_maxillofacial">Oral Maxillofacial</option>
                                <option value="oral_surgery">Oral Surgery</option>
                                <option value="orthodontic">Orthodontic</option>
                                <option value="pediatric">Pediatric</option>
                                <option value="pedodontic">Pedodontic</option>
                                <option value="periodontic">Periodontic</option>
                                <option value="prosthodontics">Prosthodontics</option>
                                <option value="veterinary">Veterinary</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" v-if="initialSetup.type_of_practice !== 'veterinary'">
                <label class="col-md-4 control-label">Enable Hygiene Plan:</label>
                <div class="col-md-6">
                    <label class="radio-inline" for="create_hygiene_plan_yes">
                        <input type="radio" id="create_hygiene_plan_yes" name="hygiene_plan" value="1"
                                :checked="initialSetup.hygiene_plan"
                                @change="update"> Yes
                    </label>
                    <label class="radio-inline" for="create_hygiene_plan_no">
                        <input type="radio" id="create_hygiene_plan_no" name="hygiene_plan" value="0"
                                :checked="!initialSetup.hygiene_plan"
                                @change="update"> No
                    </label>
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
            <div class="form-group">
                <div class="col-md-offset-4 col-md-6">
                    <div class="checkbox">
                        <label>
                            <input id="use_business_address" type="checkbox" name="use_business_address"
                                    :checked="initialSetup.use_business_address"
                                    @change="update"> Use business address info on file
                        </label>
                        <span class="bp_tooltip" type="button" data-toggle="tooltip" data-placement="right" title="Some plans may be tied to a different branch of your practice, you can outline the address details here">?</span>
                    </div>
                </div>
            </div>
            <fieldset id="address_data" :class="addressDataContainerClasses">
                <div class="col-md-offset-4 col-md-8">
                    <legend>Address Info</legend>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="address1" class="col-md-4 control-label">Address 1:</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-8 no-padding-right">
                                        <input id="address1" :value="initialSetup.address.address1" type="text" name="address.address1" class="form-control" @change="update">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address2" class="col-md-4 control-label">Address 2:</label>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-8 no-padding-right">
                                        <input id="address2" :value="initialSetup.address.address2" type="text" name="address.address2" class="form-control" @change="update">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="col-md-4 control-label">City:</label>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8 no-padding-right">
                                        <input id="city" :value="initialSetup.address.city" type="text" name="address.city" class="form-control" @change="update">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="state" class="col-md-4 control-label">State:</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 no-padding-right">
                                        <select :value="initialSetup.address.state" name="address.state" id="state" class="form-control" @change="update">
                                            <option value="">- Select One</option>
                                            <option v-for="(state, abbr) in states" :value="abbr">{{ state }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="zip" class="col-md-4 control-label">Zip Code:</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 no-padding-right">
                                        <input id="zip" :value="initialSetup.address.zip" type="text" name="address.zip" class="form-control" @change="update">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-md-4 control-label">Phone:</label>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8 no-padding-right">
                                        <input id="phone" :value="initialSetup.address.phone" type="text" name="address.phone" class="form-control" @change="update">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <form-buttons @save="save" @saveDraft="saveDraft" @exit="checkIsDirty"></form-buttons>
        </div>
    </div>
    <div v-else></div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import createFormMixin from '@/mixins/createForm'
    import FormButtons from '@/components/BonusPro/Buttons'

    export default {
      name: "InitialSetup",
      props: ['states'],
      mixins: [createFormMixin],
      data: function () {
        return {
          stepName: 'initialSetup',
          isDirty: false
        }
      },
      components: {
        FormButtons
      },
      computed: {
        ...mapState({
          initialSetup: store => store.bonusPro.initialSetup,
          currentStep: store => store.bonusPro.ui.currentStep || "initialSetup",
          businessId: store => store.bonusPro.initialSetup.business_id || store.bonusPro.global.businessId,
          createdBy: store => store.bonusPro.initialSetup.created_by || store.bonusPro.global.createdBy,
          errors: store => store.bonusPro.global.errors
        }),
        addressDataContainerClasses () {
          return {
            hidden: this.initialSetup.use_business_address
          }
        }
      },
      methods: {
        ...mapActions({
          saveInitialSetup: "bonusPro/initialSetup/save",
          saveInitialSetupDraft: "bonusPro/initialSetup/saveDraft",
          updateProperty: "bonusPro/initialSetup/updateProperty",
          setErrors: "bonusPro/global/setErrors",
          clearErrors: "bonusPro/global/clearErrors",
          goToNextStep: "bonusPro/ui/nextStep",
          updateProductionCollectionAverages: "bonusPro/planData/updateProductionCollectionAverages"
        }),
        update: function (e) {
          const prop = e.target.name;
          let value = e.target.value;

          if (prop === 'separate_fund' || prop === 'hygiene_plan') {
            value = parseInt(value);
          }

          if (prop === 'use_business_address') {
            value = !this.initialSetup.use_business_address;
          }

          this.isDirty = true;

          this.updateProperty({
            prop: prop,
            value: value
          });
        },
        updateRollingAverage: function (e) {
          this.updateProperty({ prop: e.target.name, value: e.target.value });
          this.updateProductionCollectionAverages();
        },
        saveDraft: function (exit) {
          this.saveInitialSetupDraft()
                    .then(() => {
                      this.isDirty = false;
                      if (exit) {
                        this.exit();
                      }
                    })
                    .catch((errors) => {
                      this.setErrors(errors);
                    });
        },
        save: function () {
          this.clearErrors();
          this.saveInitialSetup()
                    .then(() => {
                      this.goToNextStep();
                    })
                    .catch((errors) => {
                      this.setErrors(errors);
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
      mounted () {
      }
    }
</script>

<style scoped>
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

<template>
    <div v-model="fundData.add" id="add-fund" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="modalLabel">Add Funds</h4>
                </div>
                <div class="modal-body">
                    <div v-if="errors" class="alert alert-danger">
                        <strong>There are some errors with your submission. Please correct the following:</strong>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">* Required Field</div>
                    </div>
                    <div :class="{'form-group': true, 'has-error': errors && (errors.fund_id || errors.fund_name)}">
                        <input v-model="fundData.id" type="hidden" name="id" class="form-control">
                        <label for="fund_id" class="col-md-4 control-label">* Fund ID/Fund Name:</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-2 no-padding-right">
                                    <input id="fund_id" type="text" name="fund_id"
                                            v-model="fundData.fund_id"
                                            :class="{ 'form-control': true, 'is-invalid': errors && errors.fund_id}">
                                </div>
                                <div class="col-md-8 no-padding-right">
                                    <input id="fund_name" type="text" name="fund_name"
                                            v-model="fundData.fund_name"
                                            :class="{ 'form-control': true, 'is-invalid': errors && errors.fund_name}">
                                </div>
                                <small class="text-danger" v-if="errors && errors.fund_id">{{ errors.fund_id[0] }}</small>
                                <small class="text-danger" v-if="errors && errors.fund_name">{{ errors.fund_name[0] }}</small>
                            </div>
                        </div>
                    </div>
                    <div :class="{'form-group': true, 'has-error': errors && (errors.fund_start_month || errors.fund_start_year)}">
                        <label for="fund_start_month" class="col-md-4 control-label">* Fund Start Date (MM/YYYY):</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-2 no-padding-right">
                                    <input id="fund_start_month" type="text" name="fund_start_month"
                                            v-model="fundData.fund_start_month"
                                            :class="{ 'form-control': true, 'is-invalid': errors && errors.fund_start_month}">
                                </div>
                                <div class="separator">
                                    <span>/</span>
                                </div>
                                <div class="col-md-3 no-padding-both no-margin-both">
                                    <input id="fund_start_year" type="text" name="fund_start_year"
                                            v-model="fundData.fund_start_year"
                                            :class="{ 'form-control': true, 'is-invalid': errors && errors.fund_start_year}">
                                </div>
                                <small class="text-danger" v-if="errors && errors.fund_start_month">{{ errors.fund_start_month[0] }}</small>
                                <small class="text-danger" v-if="errors && errors.fund_start_year">{{ errors.fund_start_year[0] }}</small>
                            </div>
                        </div>
                    </div>
                    <div :class="{'form-group': true, 'has-error': errors && errors.fund_type}">
                        <label for="fund_type" class="col-md-4 control-label">* Type of Fund:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 no-padding-right">
                                    <select name="fund_type" id="fund_type"
                                            v-model="fundData.fund_type"
                                            :class="{ 'form-control': true, 'is-invalid': errors && errors.fund_type}">
                                        <option value="">- Select One -</option>
                                        <option value="percentage">Percentage</option>
                                        <option value="amount">Dollar Amount</option>
                                    </select>
                                </div>
                                <small class="text-danger" v-if="errors && errors.fund_type">{{ errors.fund_type[0] }}</small>
                            </div>
                        </div>
                    </div>
                    <div :class="{'form-group': true, 'has-error': errors && errors.fund_amount}">
                        <label for="fund_amount" class="col-md-4 control-label">* Fund Amount:</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8 no-padding-right">
                                    <input id="fund_amount" type="text" name="fund_amount"
                                            v-model="fundData.fund_amount"
                                            :class="{ 'form-control': true, 'is-invalid': errors && errors.fund_amount}">
                                </div>
                                <small class="text-danger" v-if="errors && errors.fund_amount">{{ errors.fund_amount[0] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-xs btn-danger" @click="close">CANCEL</button>
                    <button class="btn btn-xs btn-primary" @click="save">SAVE</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapState, mapActions } from 'vuex'

    export default {
      name: "Funds",
      props: ['show', 'fundId'],
      data: function () {
        return {
          fundData: {}
        }
      },
      computed: {
        ...mapState({
          planId: store => store.initialSetup.id,
          funds: store => store.employeesAndFunds.funds,
          errors: state => state.employeesAndFunds.errors.funds
        })
      },
      watch: {
        fundId: function () {
          this.setFundData();
        }
      },
      methods: {
        ...mapActions({
          addFund: 'employeesAndFunds/addFund',
          updateFund: 'employeesAndFunds/updateFund',
          clearErrors: 'employeesAndFunds/clearErrors'
        }),
        setFundData: function () {
          let obj = {};

          if (this.fundId) {
            obj = this.funds.find((el) => {
              return el.id === this.fundId;
            })
          }

          this.fundData = {
            id: obj.id || null,
            fund_id: obj.fund_id || null,
            fund_name: obj.fund_name || null,
            fund_start_month: obj.fund_start_month || null,
            fund_start_year: obj.fund_start_year || null,
            fund_type: obj.fund_type || '',
            fund_amount: obj.fund_amount || null
          }
        },
        close: function () {
          this.setFundData();
          this.clearErrors('funds');
          this.$emit('close');
        },
        save: function () {
          const action = this.fundId ? 'updateFund' : 'addFund';

          this[action](this.fundData)
                    .then(() => {
                      this.close();
                    })
                    .catch(() => {
                    });
        }
      },
      mounted () {
        this.setFundData();
      }
    }
</script>

<style scoped lang="scss">
    .separator {
        float: left;
        padding: 5px 5px 0 5px;
    }
    .alert {
        ul {
            list-style: none;
        }
    }
    small {
        clear: both;
        display: block;
        padding-left: 15px;
    }
</style>

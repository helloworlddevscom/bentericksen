<template>
    <div class="row">
        <div class="col-md-4 col-md-offset-8 text-left padding-top-18">
            <p><a href="#" @click="toggleEmployeeModal"><i class="fa fa-plus-circle bp_add" aria-hidden="true"></i> ADD EMPLOYEE</a></p>
            <p v-if="isSeparateFundsEnabled"><a href="#" @click="toggleFundModal"><i class="fa fa-plus-circle bp_add" aria-hidden="true"></i> ADD FUND</a></p>
        </div>
        <div class="col-md-8 col-md-offset-2 content">
            <div class="table-responsive">
                <table class="table table-striped" id="active_table">
                    <thead>
                    <tr>
                        <th width="75%">Employee/Fund Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="employeesAndFunds.employees.length > 0" v-for="employee in employeesAndFunds.employees">
                        <td>{{ employee.first_name }} {{ employee.last_name }}</td>
                        <td>
                            <button href="#" class="btn btn-primary btn-xs" @click="editEmployeeData(employee.id)">VIEW</button>&nbsp;
                            <button href="#" class="btn btn-danger btn-xs" @click="removeEmployeeModal(employee.id)">REMOVE</button>
                        </td>
                    </tr>
                    <tr v-if="employeesAndFunds.funds.length > 0" v-for="fund in employeesAndFunds.funds">
                        <td>Fund: {{ fund.fund_id }} - {{ fund.fund_name }}</td>
                        <td>
                            <button href="#" class="btn btn-primary btn-xs" @click="editFund(fund.id)">VIEW</button>&nbsp;
                            <button href="#" class="btn btn-danger btn-xs" @click="removeEmployee(fund.id)">REMOVE</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <employee-warning
              :show="showEmployeeWarning"
              @confirm="removeEmployee(employeeId); showEmployeeWarning = false"
              @cancel="showEmployeeWarning = false">
            </employee-warning>
            <form-add-funds @close="toggleFundModal" :fundId="getFundId"></form-add-funds>
            <form-add-employees @add="toggleSetEmployeeData" @close="toggleEmployeeModal" :states="states"></form-add-employees>
            <form-employee-data :states="states" @close="toggleEmployeeDataModal" :employeeId="getEmployeeId"></form-employee-data>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import EmployeeWarning from './EmployeeWarning'

    export default {
      name: "Employees",
      props: ['businessUsers', 'states'],
      components: {
        EmployeeWarning,
      },
      data: function () {
        return {
          stepName: 'employees',
          showFundModal: false,
          showEmployeeModal: false,
          showEmployeeDataModal: false,
          showEmployeeWarning: false,
          fundId: null,
          employeeId: null
        }
      },
      methods: {
        ...mapActions({
          removeFund: 'employeesAndFunds/removeFund',
          removeEmployee: 'employeesAndFunds/removeEmployee',
          goToNextStep: "ui/nextStep"
        }),
        removeFundFromPlan: function (id) {
          this.removeFund(id);
        },
        removeEmployeeModal: function (id) {
          this.employeeId = id;
          this.showEmployeeWarning = true;
        },
        editFund: function (id) {
          this.fundId = id;
          this.toggleFundModal();
        },
        editEmployeeData: function (id) {
          this.employeeId = id;
          this.toggleEmployeeDataModal();
        },
        toggleFundModal: function () {
          this.showFundModal = !this.showFundModal;
          if (!this.showFundModal) {
            this.fundId = null;
          }
          $('#add-fund').modal(this.showFundModal ? 'show' : 'hide');
        },
        toggleEmployeeModal: function () {
          this.showEmployeeModal = !this.showEmployeeModal;
          $('#add-employee').modal(this.showEmployeeModal ? 'show' : 'hide');
        },
        toggleSetEmployeeData: function (id) {
          this.employeeId = id;
          this.toggleEmployeeModal();
          this.toggleEmployeeDataModal();
        },
        toggleEmployeeDataModal: function () {
          this.showEmployeeDataModal = !this.showEmployeeDataModal;
          if (!this.showEmployeeDataModal) {
            this.employeeId = null;
          }
          $('#employee-data').modal(this.showEmployeeDataModal ? 'show' : 'hide');
        }
      },
      computed: {
        ...mapState({
          employeesAndFunds: store => store.employeesAndFunds,
          isSeparateFundsEnabled: store => store.initialSetup.separate_fund
        }),
        getFundId: function () {
          return this.fundId;
        },
        getEmployeeId: function () {
          return this.employeeId;
        }
      }
    }
</script>

<style scoped lang="scss">
    a {
        text-decoration: none;
    }
</style>
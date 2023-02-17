<template>
    <div id="add-employee" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="modalLabel">Add Employees</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-4 col-md-offset-8 text-left">
                        <p><button type="button" class="btn btn-primary btn-sm" @click="add()">CREATE NEW EMPLOYEE</button></p>
                    </div>
                    <table class="table table-striped" id="active_table">
                        <thead>
                        <tr>
                            <th width="75%">Employee/Fund Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="employee in employees">
                            <td>{{ employee.first_name }} {{ employee.last_name }} - {{ employee.id }}</td>
                            <td><p><a href="#" @click="add(employee.id)"><i class="fa fa-plus-circle bp_add" aria-hidden="true"></i> ADD TO PLAN</a></p></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer text-center">
                    <a href="#" class="btn btn-primary btn-xs" @click="close">CLOSE</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'

    export default {
      name: "AddEmployees",
      data: function () {
        return {}
      },
      methods: {
        ...mapActions({
        }),
        close: function () {
          this.$emit('close');
        },
        add: function (id) {
          this.$emit('add', id);
        }
      },
      computed: {
        ...mapState({
          existingBusinessEmployees: store => store.global.businessUsers,
          employeesAndFunds: store => store.employeesAndFunds
        }),
        employees() {
          const employees = this.employeesAndFunds.employees.map((employee) => employee.id)
          return this
            .existingBusinessEmployees
            .filter((employee) => !employees.includes(employee.id))
            .filter((employee) => employee.status === 'enabled')
            .filter((employee) => employee.can_access_system)
        }
      }
    }
</script>

<style scoped lang="scss">
</style>
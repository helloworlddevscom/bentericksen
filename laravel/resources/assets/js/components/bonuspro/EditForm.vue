<template>
    <div id="bp-edit-form">
        <div class="row text-center">
            <h3>BonusPro - {{ getHeading }}</h3>
        </div>
        <ul class="nav nav-tabs" role="tablist" id="plan_tab">
            <li class="active" @click="savePlan"><a href="#snapshot" role="tab" data-toggle="tab">Snapshot</a></li>
            <li @click="savePlan"><a href="#employees" role="tab" data-toggle="tab">Employees/Funds</a></li>
            <li @click="savePlan"><a href="#monthlyData" role="tab" data-toggle="tab">Monthly Data</a></li>
            <li @click="savePlan"><a href="#reports" role="tab" data-toggle="tab">Reports</a></li>
            <li @click="savePlan"><a href="#settings" role="tab" data-toggle="tab"><i class="fa navigation_img fa-gears"></i>Settings</a></li>
        </ul>
        <div class="tab-content">
            <bp-edit-form-snapshot class="tab-pane fade in active content" id="snapshot"></bp-edit-form-snapshot>
            <form-employee-list class="tab-pane fade content" id="employees" :states="states"></form-employee-list>
            <bp-edit-form-monthly-data class="tab-pane fade content" id="monthlyData"></bp-edit-form-monthly-data>
            <bp-edit-form-reports class="tab-pane fade content" id="reports" :years="years" :months="months"></bp-edit-form-reports>
            <bp-edit-form-settings class="tab-pane fade content" id="settings"></bp-edit-form-settings>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex';

    export default {
      name: "editForm",
      props: ['createdBy', 'businessId', 'planData', 'businessUsers', 'years', 'months', 'states'],
      data: function () {
        return {}
      },
      computed: {
        ...mapState({
          initialSetup: store => store.initialSetup
        }),
        getHeading: function () {
          let msg = 'Plan: ';
          if (this.initialSetup.plan_id && this.initialSetup.plan_name) {
            msg = this.initialSetup.plan_id + ' / ' + this.initialSetup.plan_name;
          }
          return msg;
        }
      },
      methods: {
        ...mapActions({
          setBusinessId: "global/setBusinessId",
          setCreatedBy: "global/setCreatedBy",
          setBusinessUsers: "global/setBusinessUsers",
          setInitialSetupData: "initialSetup/setInitialSetupData",
          savePlan: 'planData/save'
        })
      },
      mounted () {
        if (this.createdBy) {
          this.setCreatedBy(this.createdBy);
        }

        if (this.businessId) {
          this.setBusinessId(this.businessId);
        }

        // There is logic inside planData for undefined case, so this is not wrapped in existence check
        this.setInitialSetupData(this.planData);

        if (this.businessUsers) {
          this.setBusinessUsers(this.businessUsers);
        }
      }

    }
</script>

<style scoped lang="scss">

</style>

<template>
    <div id="bp-edit-form">
        <div class="row text-center">
            <h3>BonusPro - {{ getHeading }}</h3>
        </div>
        <b-tabs
          lazy
          active-nav-item-class="tab-item-active"
          content-class="mt-3">
          <b-tab title="Snapshot" title-link-class="tab-item-base" @click="savePlan">
            <bp-edit-form-snapshot></bp-edit-form-snapshot>
          </b-tab>
          <b-tab title="Employees/Funds" title-link-class="tab-item-base" @click="savePlan">
            <form-employee-list :states="states"></form-employee-list>
          </b-tab>
          <b-tab title="Monthly Data" title-link-class="tab-item-base" @click="savePlan">
            <bp-edit-form-monthly-data ></bp-edit-form-monthly-data>
          </b-tab>
          <b-tab title="Reports" title-link-class="tab-item-base" @click="savePlan">
            <bp-edit-form-reports :years="years" :months="months"></bp-edit-form-reports>
          </b-tab>
          <b-tab title="Settings" title-link-class="tab-item-base" @click="savePlan">
            <bp-edit-form-settings></bp-edit-form-settings>
          </b-tab>
        </b-tabs>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import {BTabs, BTab} from 'bootstrap-vue'
    import BpEditFormSnapshot from '@/components/BonusPro/form/edit/Snapshot'
    import FormEmployeeList from '@/components/BonusPro/form/EmployeeList'
    import BpEditFormMonthlyData from '@/components/BonusPro/form/edit/MonthlyData'
    import BpEditFormReports from '@/components/BonusPro/form/edit/Reports'
    import BpEditFormSettings from '@/components/BonusPro/form/edit/Settings'


    export default {
      name: "editForm",
      props: ['createdBy', 'businessId', 'planData', 'businessUsers', 'years', 'months', 'states'],
      components: {
        BTab,
        BTabs,
        BpEditFormSnapshot,
        FormEmployeeList,
        BpEditFormMonthlyData,
        BpEditFormReports,
        BpEditFormSettings
      },
      data: function () {
        return {}
      },
      computed: {
        ...mapState({
          initialSetup: store => store.bonusPro.initialSetup
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
          setBusinessId: "bonusPro/global/setBusinessId",
          setCreatedBy: "bonusPro/global/setCreatedBy",
          setBusinessUsers: "bonusPro/global/setBusinessUsers",
          setInitialSetupData: "bonusPro/initialSetup/setInitialSetupData",
          savePlan: 'bonusPro/planData/save'
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

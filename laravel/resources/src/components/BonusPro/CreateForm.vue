<template>
    <div id="bp-create-form">
        <div class="row text-center">
            <h3>BonusPro - {{ getHeading }}</h3>
        </div>
        <progress-bar></progress-bar>
        <form-initial-setup @toggleExitConfirmationModal="toggleExitConfirmationModal('initialSetup')" @exit="exit" ref="initialSetup" :states="states"></form-initial-setup>
        <form-plan-data @toggleExitConfirmationModal="toggleExitConfirmationModal('planData')" @exit="exit" ref="planData"></form-plan-data>
        <form-employees :states="states" @toggleExitConfirmationModal="toggleExitConfirmationModal('employees')" @exit="exit" ref="employees"></form-employees>
        <form-set-bonus-percentage @exit="exit" @toggleExitConfirmationModal="toggleExitConfirmationModal('setBonusPercentage')"  ref="setBonusPercentage"></form-set-bonus-percentage>
        <form-modal-confirmation id="exit-confirmation-modal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div slot="footer">
                <button type="button" class="btn btn-xs btn-primary" @click="saveDraft(true)">SAVE & EXIT</button>
                <button type="button" class="btn btn-xs btn-danger" @click="toggleExitConfirmationModal">CANCEL</button>
            </div>
        </form-modal-confirmation>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex'
    import ProgressBar from './ProgressBar.vue'
    import FormInitialSetup from './form/InitialSetup.vue'
    import FormPlanData from './form/PlanData.vue'
    import FormEmployees from './form/Employees.vue'
    import FormSetBonusPercentage from './form/SetBonusPercentage.vue'
    import FormModalConfirmation from './Confirmation.vue'

    export default {
      name: "CreateForm",
      props: ['createdBy', 'businessId', 'states', 'planData', 'bonusPercentage', 'businessUsers'],
      data: function () {
        return {
          showConfirmation: false,
          activeRef: null
        }
      },
      components: {
        ProgressBar,
        FormInitialSetup,
        FormPlanData,
        FormEmployees,
        FormSetBonusPercentage,
        FormModalConfirmation,
      },
      computed: {
        ...mapState({
          initialSetup: store => store.bonusPro.initialSetup
        }),
        getHeading: function () {
          let msg = 'Create New Plan';
          if (this.initialSetup.plan_id && this.initialSetup.plan_name) {
            msg = 'Plan: ' + this.initialSetup.plan_id + ' / ' + this.initialSetup.plan_name;
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
          setBonusPercentageData: "bonusPro/bonusPercentage/setBonusPercentageData"
        }),
        toggleExitConfirmationModal: function (ref) {
          this.showConfirmation = !this.showConfirmation;
          this.activeRef = ref;
          $('#exit-confirmation-modal').modal(this.showConfirmation ? 'show' : 'hide');
        },
        saveDraft: function (exit) {
          this.$refs[this.activeRef].saveDraft(exit);
        },
        exit: function () {
          window.location = '/bonuspro';
        }
      },
      mounted () {
        if (this.createdBy) {
          this.setCreatedBy(this.createdBy);
        }

        if (this.businessId) {
          this.setBusinessId(this.businessId);
        }
        
        this.setInitialSetupData(this.planData);
        
        this.setBonusPercentageData(this.bonusPercentage);

        if (this.businessUsers) {
          this.setBusinessUsers(this.businessUsers);
        }
      }
    }
</script>

<style scoped lang="scss">

</style>

<template>
  <div v-if="active">
    <div class="row">
      <div class="text-center">
        <h4>Please enter the following plan historical data:</h4>
      </div>
    </div>
    <div class="row text-center">
      <plan-data-table :editable="1" @update="update"></plan-data-table>
    </div>
    <form-buttons @save="save" @saveDraft="saveDraft" @exit="checkIsDirty"></form-buttons>
  </div>
  <div v-else></div>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import PlanDataTable from "./PlanDataTable";
  import createFormMixin from '@/mixins/createForm'
  import FormButtons from '@/components/BonusPro/Buttons'

  export default {
    name: "PlanData",
    components: { PlanDataTable, FormButtons },
    props: [],
    mixins: [createFormMixin],
    data: function () {
      return {
        stepName: 'planData',
        isDirty: false
      }
    },
    watch: {
      active(active) {
        if (!active) {
          return
        }

        this.$nextTick(() => {
          let input = this.$el.querySelector('input')
          
          input.focus()

          input.setSelectionRange(0, input.value.length)
        })
      }
    },
    methods: {
      ...mapActions({
        runCalculations: "bonusPro/planData/runCalculations",
        updateField: "bonusPro/planData/updateField",
        setErrors: "bonusPro/global/setErrors",
        clearErrors: "bonusPro/global/clearErrors",
        goToNextStep: "bonusPro/ui/nextStep",
        savePlanData: "bonusPro/planData/save"
      }),
      save: function () {
        this.clearErrors();
        this.savePlanData()
          .then(() => {
            this.goToNextStep();
          })
          .catch((errors) => {
            this.setErrors(errors);
          });
      },
      saveDraft: function (exit) {
        this.savePlanData()
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
      update: function () {
        this.isDirty = true;
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
    computed: {
      ...mapState({
        hasHygiene: store => store.initialSetup.hygiene_plan,
        planData: store => store.planData
      }),
    }
  }
</script>

<style scoped lang="scss">
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
    text-align: center;
    padding: 5px 0;

    &:disabled {
      padding: 6px 0;
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
</style>

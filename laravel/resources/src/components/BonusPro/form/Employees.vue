<template>
    <div v-if="active">
        <div class="row">
            <div class="text-center">
                <h4>Employees and Funds</h4>
            </div>
        </div>
        <form-employee-list :states="states"></form-employee-list>
        <div class="row">
            <div class="text-center">
                <form-buttons hide-draft="true" next="true" @save="save" @exit="checkIsDirty"></form-buttons>
            </div>
        </div>
    </div>
    <div v-else></div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import createFormMixin from '@/mixins/createForm'
    import FormEmployeeList from '@/components/BonusPro/form/EmployeeList'
    import FormButtons from '@/components/BonusPro/Buttons'

    export default {
      name: "Employees",
      props: ['businessUsers', 'states'],
      components: {
        FormEmployeeList,
        FormButtons
      },
      mixins: [createFormMixin],
      data: function () {
        return {
          stepName: 'employees',
          isDirty: false
        }
      },
      methods: {
        ...mapActions({
          goToNextStep: "bonusPro/ui/nextStep",
          setErrors: "bonusPro/global/setErrors",
          clearErrors: "bonusPro/global/clearErrors"
        }),
        save: function () {
          this.goToNextStep();
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
      }
    }
</script>

<style scoped lang="scss">
    a {
        text-decoration: none;
    }
</style>

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
    import createFormMixin from '../../../mixins/createForm'

    export default {
      name: "Employees",
      props: ['businessUsers', 'states'],
      mixins: [createFormMixin],
      data: function () {
        return {
          stepName: 'employees',
          isDirty: false
        }
      },
      methods: {
        ...mapActions({
          goToNextStep: "ui/nextStep",
          setErrors: "global/setErrors",
          clearErrors: "global/clearErrors"
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

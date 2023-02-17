<template>
    <div class="col-md-12 content text-center">
        <button type="button" id="prevStep" @click="goToPreviousStep" class="btn btn-default btn-xs btn-primary" :disabled="isPreviousDisabled">Previous</button>
        <button type="button" id="exit" @click="exit" class="btn btn-default btn-xs btn-primary">Save & Exit</button>
        <button type="button" id="nextStep" @click="goToNextStep" class="btn btn-default btn-xs btn-primary" :disabled="isNextDisabled">{{ getNextButtonLabel() }}</button>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex';

    export default {
      name: "Buttons",
      props: ['hideDraft', 'next'],
      data: function () {
        return {}
      },
      computed: {
        ...mapState({
          currentStep: store => store.bonusPro.ui.steps[store.bonusPro.ui.currentStep || 'initialSetup']
        }),
        isPreviousDisabled () {
          return this.currentStep.previousStep === null;
        },
        isNextDisabled () {
          return this.currentStep.nextStep === null;
        }
      },
      methods: {
        ...mapActions({
          previousStep: 'bonusPro/ui/previousStep'
        }),
        goToPreviousStep: function () {
          this.previousStep();
        },
        goToNextStep: function () {
          this.$emit('save');
        },
        exit: function () {
          this.$emit('saveDraft');
          this.$emit('exit');
        },
        getNextButtonLabel: function () {
          let label = 'Next';

          if (this.next && this.next === 'false') {
            label = 'Finish';
          }

          return label;
        },
        showSaveDraft: function () {
          let showDraft = true;

          if (this.hideDraft && this.hideDraft === 'true') {
            showDraft = false;
          }

          return showDraft;
        }
      },
      mounted () {}
    }
</script>

<style scoped>
    a.btn,
    button {
        text-transform: uppercase;
    }
</style>

import { mapState } from 'vuex'

export default {
  computed: {
    ...mapState({
      currentStep: store => store.bonusPro.ui.currentStep
    }),
    active() {
      return this.currentStep === this.stepName
    }
  }
}
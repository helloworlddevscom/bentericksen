import { mapState } from 'vuex'

export default {
  computed: {
    ...mapState({
      currentStep: store => store.ui.currentStep
    }),
    active() {
      return this.currentStep === this.stepName
    }
  }
}
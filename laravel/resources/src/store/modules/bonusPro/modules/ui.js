const state = {
  currentStep: null,
  previousStep: null,
  steps: {
    initialSetup: {
      label: 'Initial Setup',
      classes: ['active'],
      key: 'initialSetup',
      nextStep: 'planData',
      previousStep: null
    },
    planData: {
      label: 'Plan Data',
      classes: [],
      key: 'planData',
      nextStep: 'employees',
      previousStep: 'initialSetup'
    },
    employees: {
      label: 'Employees',
      classes: [],
      key: 'employees',
      nextStep: 'setBonusPercentage',
      previousStep: 'planData'
    },
    setBonusPercentage: {
      label: 'Set Bonus Percentage',
      classes: [],
      key: 'setBonusPercentage',
      nextStep: 'initialSetup',  // temporary - change this when the next page is created
      previousStep: 'employees'
    }
  }
};

const mutations = {
  resetClasses: (state) => {
    // resetting active links
    for (const step in state.steps) {
      state.steps[step].classes = [];
    }
  },
  previousStep: (state) => {
    const current = state.steps[state.currentStep || 'initialSetup'];

    state.currentStep = current.previousStep;

    state.steps[current.previousStep].classes.push('active');

    return state.currentStep;
  },
  nextStep: (state) => {
    const current = state.steps[state.currentStep || 'initialSetup'];

    state.previousStep = current.previousStep;
    state.currentStep = current.nextStep;

    // setting active link
    state.steps[current.nextStep].classes.push('active');

    return state.currentStep;
  },
  toggleSteps: (state, newStep) => {
    state.previousStep = state.currentStep;
    state.currentStep = newStep;

    // setting active link
    state.steps[newStep].classes.push('active');
  }
};

const actions = {
  updateStep ({ commit, dispatch, rootState }, newStep) {
    if (!rootState.bonusPro.initialSetup.completed) {
      commit('resetClasses');
      commit('toggleSteps', newStep);
      dispatch('saveStep');
    }
  },
  async nextStep ({ commit, dispatch }) {
    commit('resetClasses');
    await dispatch('saveStep');
    commit('nextStep');
    dispatch('saveStep');
  },
  async previousStep ({ commit, dispatch }) {
    commit('resetClasses');
    await dispatch('saveStep');
    commit('previousStep');
    await dispatch('saveStep');
  },
  saveStep ({ commit, dispatch, state }) {
    dispatch('bonusPro/initialSetup/updateProperty', {
      prop: 'current_step',
      value: state.currentStep
    }, { root: true });

    return dispatch('bonusPro/initialSetup/save', {}, {
      root: true
    });
  }

};

const getters = {
  steps: (state) => state.steps
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
};

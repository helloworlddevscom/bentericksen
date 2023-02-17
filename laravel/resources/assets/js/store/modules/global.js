const state = {
  errors: null,
  businessId: null,
  createdBy: null,
  businessUsers: []
};

const mutations = {
  setCurrentStep: (state, currentStep) => {
    state.currentStep = currentStep;
  },
  setErrors: (state, errors) => {
    state.errors = errors;
  },
  clearErrors: (state) => {
    state.errors = null;
  },
  setBusinessId: (state, id) => {
    state.businessId = id;
  },
  setCreatedBy: (state, id) => {
    state.createdBy = id;
  },
  setBusinessUsers: (state, users) => {
    state.businessUsers = users;
  },
  addUserToBusiness: (state, user) => {
    state.businessUsers.push(user);
  }
};

const actions = {
  addUserToBusiness: ({ commit }, user) => {
    commit('addUserToBusiness', user)
  },
  setCurrentStep: ({ commit }, step) => {
    commit('setCurrentStep', step);
  },
  setErrors: ({ commit }, errors) => {
    commit('setErrors', errors);

    setTimeout(function () {
      commit('clearErrors');
    }, 8000);
  },
  clearErrors: ({ commit }) => {
    commit('clearErrors');
  },
  setBusinessId: ({ commit }, id) => {
    commit('setBusinessId', id);
  },
  setCreatedBy: ({ commit }, id) => {
    commit('setCreatedBy', id);
  },
  setBusinessUsers: ({ commit }, users) => {
    commit('setBusinessUsers', users);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};

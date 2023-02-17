import BonusProService from '@/services/BonusProService'

const state = {
  id: null,
  plan_id: null,
  plan_name: null,
  business_id: null,
  created_by: null,
  password_set: false,
  password: undefined,
  password_confirmation: undefined,
  start_month: null,
  start_year: null,
  hygiene_plan: 0,
  separate_fund: 0,
  use_business_address: 1,
  type_of_practice: "",
  rolling_average: "",
  distribution_type: "",
  current_step: "",
  address: {
    address1: null,
    address2: null,
    city: null,
    zip: null,
    phone: null
  }
};

const mutations = {
  setInitialSetupData: (state, data) => {
    for (const prop in data) {
      if (prop === 'months' || prop === 'users' || prop === 'address') {
        continue;
      }
      state[prop] = data[prop];
    }

    if (data && data.address) {
      state.address = data.address;
    }
  },
  update: (state, { prop, value }) => {
    let partial;

    if (prop.indexOf('.') < 0) {
      state[prop] = value;
    } else {
      partial = prop.split('.');
      state[partial[0]][partial[1]] = value;
    }
  }
};

const actions = {
  saveDraft: ({ commit, rootState }) => {
    commit('update', { prop: 'business_id', value: rootState.bonusPro.global.businessId });
    commit('update', { prop: 'created_by', value: rootState.bonusPro.global.createdBy });
    return new Promise((resolve, reject) => {
      BonusProService.save(state, 'initialSetupDraft')
        .then((data) => {
          commit('setInitialSetupData', data.plan);
          resolve({ success: true });
        })
        .catch((err) => {
          reject(err.errors);
        })
    });
  },
  save: ({ commit, dispatch, state, rootState }) => {
    commit('update', { prop: 'business_id', value: rootState.bonusPro.global.businessId });
    commit('update', { prop: 'created_by', value: rootState.bonusPro.global.createdBy });

    return new Promise((resolve, reject) => {
      BonusProService.save(state, 'initialSetup')
        .then((data) => {
          commit('setInitialSetupData', data.plan);
          if (rootState.bonusPro.planData.months.length === 0) {
            dispatch('bonusPro/planData/initializeMonths', {}, { root: true });
          }
          resolve({ success: true });
        })
        .catch((err) => {
          reject(err.errors);
        })
    });
  },
  updateProperty: ({ commit }, payload) => {
    commit('update', payload);
  },
  setInitialSetupData: ({ commit, dispatch, rootState }, payload) => {
    const defaultCurrentStep = 'initialSetup';

    if (!payload) {
     return dispatch('bonusPro/ui/updateStep', 'initialSetup', { root: true });
    }
    
    commit('setInitialSetupData', payload);

    commit('update', { prop: 'password_set', value: 1 });

    if (payload.months.length > 0) {
      dispatch('bonusPro/planData/setMonths', payload.months, { root: true });
    } else {
      dispatch('bonusPro/planData/initializeMonths', {}, { root: true });
    }

    if (payload.users && payload.users.length > 0) {
      dispatch('bonusPro/employeesAndFunds/setEmployees', payload.users, { root: true });
    }

    if (payload.funds && payload.funds.length > 0) {
      dispatch('bonusPro/employeesAndFunds/setFunds', payload.funds, { root: true });
    }

    dispatch('bonusPro/ui/updateStep', payload.current_step || defaultCurrentStep, {
      root: true
    });

    dispatch('bonusPro/planData/runCalculations', null, { root: true });
  }
};

const getters = {
  bonusDistributionType: (state) => {
    return state.distribution_type;
  },
  hygienePlan (state) {
    return state.hygiene_plan
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
};

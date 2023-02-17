import BonusProService from '@/services/BonusProService'

const state = {
  id: null,
  staff_bonus_percentage: null,
  hygiene_bonus_percentage: null,
  errors: null
};

const mutations = {
  setBonusPercentageData: (state, data) => {
    for (const prop in data) {
      state[prop] = data[prop];
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
  },
  updatePlanId: (state, id) => {
    state.id = id;
  },
  setErrors: (state, errors) => {
    state.errors = errors;
  },
  clearErrors: (state) => {
    state.errors = null;
  }
};

const actions = {
  clearErrors: ({ commit }) => {
    commit('clearErrors');
  },
  save: ({ commit, state, rootState }) => {
    commit('updatePlanId', rootState.bonusPro.initialSetup.id);

    return new Promise((resolve, reject) => {
      BonusProService.save(state, 'bonusPercentage')
        .then(() => {
          window.location.href = '/bonuspro';
        })
        .catch((err) => {
          commit('setErrors', err.errors);
          reject(err.errors);
        })
    });
  },
  saveDraft: ({ commit, state, rootState }) => {
    commit('updatePlanId', rootState.bonusPro.initialSetup.id);

    return new Promise((resolve, reject) => {
      BonusProService.save(state, 'bonusPercentageDraft')
        .then(() => {
          window.location.href = '/bonuspro';
        })
        .catch((err) => {
          commit('setErrors', err.errors);
          reject(err.errors);
        })
    });
  },
  updateProperty: ({ commit }, payload) => {
    commit('update', payload);
  },
  setBonusPercentageData: ({ commit, dispatch, rootState }, payload) => {
    commit('setBonusPercentageData', payload);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};

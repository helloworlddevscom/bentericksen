export default {
  strict: process.env.NODE_ENV !== 'production',
  namespaced: true,
  state: {
    stripe_pk: null,
    csrf_token: null
  },
  mutations: {
    SET_STATE(state, data) {
      Object.assign(state, data)
    }
  },
  actions: {
    setState({commit}, data) {
      commit('SET_STATE', data)
    }
  }
}
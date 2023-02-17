export default {
  strict: process.env.NODE_ENV !== 'production',
  namespaced: true,
  state: {
    full_name: ''
  },
  mutations: {
    SET_DATA(state, data) {
      Object.assign(state, data)
    }
  },
  actions: {
    setData({commit}, data) {
      commit('SET_DATA', data)
    }
  },
  getters: {
    full_name: (state) => state.employee.full_name
  }
}
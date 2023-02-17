import { SET_STATE } from './types'

export default {
  setState({commit}, state) {
    commit(SET_STATE, state)
  }
}
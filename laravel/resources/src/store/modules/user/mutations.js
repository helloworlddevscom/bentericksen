import { SET_STATE } from "./types";

export default {
  [SET_STATE](state, seed) {
    Object.assign(state, seed)
  }
}

const store = {
  state: {
    chargeLater: false,
    chargeLaterOption: '',
    chargeNow: false,
    chargeNowOption: '',
    hideManageCardModalAction: '',
    showManageCardModalAction: '',
    displayManageCardModal: false,
    paymentType: '',
    savePaymentMode: 'ignore',
    makeDefaultPayment: false,
    existingCardInfo: {
      exp_month: '',
      exp_year: '',
      fullName: ''
    },
    newCardInfo: {
      fullName: '',
      line1: '',
      line2: '',
      city: '',
      state: '',
      postal_code: ''
    },
    planOptionSelection: {
      price_id: '',
      id: '',
      description: '',
      price: undefined
    },
    submitButtonAction: '',
    submitButtonLabel: '',
    manageCard: false,
    replaceMode: false,
    editCard: false
  },
  mutations: {
    chargeOptionUpdated: function (state, data) {
      if (data === "charge_now") {
        state.chargeLaterOption = '';
        state.chargeLater = false;
      }
      if (data === "charge_later") {
        state.chargeNowOption = '';
        state.chargeNow = false;
      }
    },
    chargeNowOptionSelected: function (state, data) {
      state.chargeLater = false;
      state.chargeNow = true;
      state.chargeNowOption = data;
      switch (data) {
        case "autopay":
          state.paymentType = "subscription";
          state.makeDefaultPayment = true;
          break;
        case "onetime_save":
          state.paymentType = "one_time";
          state.savePaymentMode = "save";
          state.makeDefaultPayment = true;
          break;
        case "onetime_ignore":
          state.paymentType = "one_time";
          state.savePaymentMode = "ignore";
          state.makeDefaultPayment = false;
          break;
      }
    },
    chargeLaterOptionSelectUI: function (state) {
      state.chargeNow = false;
      state.chargeLater = true;
      state.chargeNowOption = '';
      state.chargeLaterOption = "save_only";
      state.paymentType = "none";
      state.savePaymentMode = "ignore";
      state.makeDefaultPayment = false;
    },
    submitButtonUpdate: function (state, data) {
      switch (data) {
        case "autopay":
          if (state.manageCard) {
            state.submitButtonAction = "updatePurchaseCC";
          } else {
            state.submitButtonAction = "purchaseCC";
          }
          state.submitButtonLabel = "Charge";
          break;
        case "onetime_save":
          if (state.manageCard) {
            state.submitButtonAction = "updatePurchaseCC";
          } else {
            state.submitButtonAction = "purchaseCC";
          }
          state.submitButtonLabel = "Charge";
          break;
        case "onetime_ignore":
          state.submitButtonAction = "purchaseCC";
          state.submitButtonLabel = "Charge";
          break;
        case "save_only":
          if (state.manageCard) {
            state.submitButtonAction = "updateCC";
          } else {
            state.submitButtonAction = "addCC";
          }
          state.submitButtonLabel = "Save";
          break;
        case "submit":
          state.submitButtonLabel = "Submit";
          break;
      }
    },
    showManageCardModalAction: function (state, data) {
      state.displayManageCardModal = true;
      if (data === "add") {
        state.showManageCardModalAction = "replace";
        return;
      }
      state.showManageCardModalAction = "delete";
    },
    displayManageCardModal: function (state, data) {
      state.displayManageCardModal = data;
    },
    manageCard: function (state, data) {
      state.manageCard = data;
    },
    replaceMode: function (state, data) {
      state.replaceMode = data;
    },
    updateExistingCard: function (state, data) {
      Object.assign(state.existingCardInfo, data);
    },
    updateNewCard: function (state, data) {
      Object.assign(state.newCardInfo, data);
    },
    planOptionSelected: function (state, data) {
      Object.assign(state.planOptionSelection, data);
    },
    paymentType: function (state, data) {
      state.paymentType = data;
    },
    savePaymentMode: function (state, data) {
      state.savePaymentMode = data;
    },
    editCard: function (state, data) {
      state.editCard = data;
    }
  },
  actions: {
    submitButtonUpdate: function (context, data) {
      context.commit('submitButtonUpdate', data);
    },
    chargeLaterOptionSelectUI: function (context, data) {
      context.commit('chargeLaterOptionSelectUI', data);
    },
    showManageCardModalAction: function (context, data) {
      context.commit('showManageCardModalAction', data);
    },
    editCardAction: function (context, data) {
      context.commit('editCard', data);
    },
    displayManageCardModal: function (context, data) {
      context.commit('displayManageCardModal', data);
    },
    replaceMode: function (context, data) {
      context.commit('replaceMode', data);
    },
    manageCard: function (context, data) {
      context.commit('manageCard', data);
    },
    chargeNowOptionSelected: function (context, data) {
      context.commit('chargeNowOptionSelected', data);
    },
    chargeOptionUpdated: function (context, data) {
      context.commit('chargeOptionUpdated', data);
    },
    planOptionSelected: function (context, data) {
      context.commit('planOptionSelected', data);
    },
    updateExistingCard: function (context, data) {
      context.commit('updateExistingCard', data);
    },
    updateNewCard: function (context, data) {
      context.commit('updateNewCard', data);
    },
    paymentType: function (context, data) {
      context.commit('paymentType', data);
    },
    savePaymentMode: function (context, data) {
      context.commit('savePaymentMode', data);
    }
  },
  getters: {
    ignoreState: function (state) {
      return state.manageCard && !state.replaceMode;
    }
  }
}

export const storeConfig = {
  strict: process.env.NODE_ENV !== 'production',
  namespaced: true,
  ...store
}

export default storeConfig
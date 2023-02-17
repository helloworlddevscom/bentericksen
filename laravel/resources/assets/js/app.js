/* eslint no-unused-vars: ["error", { "varsIgnorePattern": "app" }] */
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import "@babel/polyfill";
import Vue from 'vue'
import store from './store/'
import moment from 'moment'
import { ObserveVisibility } from 'vue-observe-visibility'
import { paymentStore } from './paymentStore/store'

Vue.directive('observe-visibility', ObserveVisibility)

Vue.component('bonuspro-plan-create-form', require('./components/bonuspro/CreateForm.vue').default);
Vue.component('progress-bar', require('./components/bonuspro/ProgressBar.vue').default);
Vue.component('form-initial-setup', require('./components/bonuspro/form/InitialSetup.vue').default);
Vue.component('form-plan-data', require('./components/bonuspro/form/PlanData.vue').default);

Vue.component('form-employees', require('./components/bonuspro/form/Employees.vue').default);
Vue.component('form-employee-list', require('./components/bonuspro/form/EmployeeList.vue').default);
Vue.component('form-buttons', require('./components/bonuspro/Buttons.vue').default);
Vue.component('form-add-funds', require('./components/bonuspro/form/AddFunds.vue').default);
Vue.component('form-add-employees', require('./components/bonuspro/form/AddEmployees.vue').default);
Vue.component('form-employee-data', require('./components/bonuspro/form/EmployeeData.vue').default);
Vue.component('form-set-bonus-percentage', require('./components/bonuspro/form/SetBonusPercentage.vue').default);

Vue.component('bp-edit-form', require('./components/bonuspro/EditForm.vue').default);
Vue.component('bp-edit-form-reports', require('./components/bonuspro/form/edit/Reports.vue').default);
Vue.component('bp-edit-form-settings', require('./components/bonuspro/form/edit/Settings.vue').default);
Vue.component('bp-edit-form-snapshot', require('./components/bonuspro/form/edit/Snapshot.vue').default);
Vue.component('bp-edit-form-snapshot-table', require('./components/bonuspro/form/edit/SnapshotTable.vue').default);
Vue.component('bp-edit-form-monthly-data', require('./components/bonuspro/form/edit/MonthlyData.vue').default);
Vue.component('form-modal-month', require('./components/bonuspro/form/edit/MonthModal.vue').default);
Vue.component('form-modal-confirmation', require('./components/bonuspro/Confirmation.vue').default);
Vue.component('business-list', require('./components/BusinessList.vue').default);
Vue.component('login-list', require('./components/LoginList.vue').default);
Vue.component('email-list', require('./components/EmailList.vue').default);
Vue.component('vue-payment', require('./components/payment/form/Payment.vue').default);

Vue.filter('phone', function (phone) {
  return phone ? phone.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3') : null;
});

Vue.filter('percentage', function (value, decimals) {
  if (typeof value === 'undefined' || isNaN(value)) {
    return '-';
  }

  value = parseFloat(value);

  return isNaN(value) ? '-' : value.toFixed(decimals || 1) + '%';
});

Vue.filter('dollar', function (value) {
  if (typeof value === 'undefined' || isNaN(value)) {
    return '-';
  }
  if (typeof value === 'string') {
    value = (parseFloat(value.replace('/[$,]/', ''))).toFixed(2);
  }
  return '$' + value.toLocaleString('en-US', {
    currency: 'USD',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  });
});

Vue.filter('titlecase', function (value) {
  return value.replace(
    /\b\w+/g,
    function(txt) {
      return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    }
  );
});

Vue.filter('date', (value) => {
  return moment(value).format('MM/DD/YYYY')
})

Vue.filter('time', (value) => {
  return moment(String(value)).format('h:mm A');
})

Vue.filter('month', (value) => {
  if (value) {
    return moment().month(value - 1).format('MMM')
  }
  return ''
})

Vue.filter('bp-dollar', function (value) {
  if (typeof value === 'undefined' || isNaN(value)) {
    return '-'
  }
  if (typeof value === 'string') {
    value = parseFloat(value.replace('/[$,]/', ''))
  }
  return '$' + value.toLocaleString('en-US', {
    currency: 'USD',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  })
})

export const EventBus = new Vue();

const app = new Vue({
  el: '#app',
  store
});

const payment = new Vue({
  el: '#payment',
  store: paymentStore
});

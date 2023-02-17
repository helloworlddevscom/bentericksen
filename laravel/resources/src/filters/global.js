import Vue from 'vue'
import moment from 'moment'

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
    value = parseFloat(value.replace('/[$,]/', ''));
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

Vue.filter('ucfirst', (s) => `${s[0].toUpperCase()}${s.slice(1)}` )
const baseConfig = require('./wdio.conf.js')
const selectn = require('selectn')

const browsersStackMap = {
  windows: {
    ie: {
        'os' : 'Windows',
        'os_version' : '10',
        'browser' : 'IE',
        'browser_version': '11.0',
        'resolution': '2048x1536'
    },
    chrome: {
        'os' : 'Windows',
        'os_version' : '10',
        'browser' : 'Chrome',
        'browser_version': 'latest',
        'resolution': '2048x1536'
    },
    firefox: {
      'os' : 'Windows',
      'os_version' : '10',
      'browser' : 'Firefox',
      'browser_version': 'latest',
      'resolution': '2048x1536'
    },
    edge: {
      'os' : 'Windows',
      'os_version' : '10',
      'browserName' : 'Edge',
      'browser_version' : 'latest-beta',
      'resolution': '2048x1536'
    }
  },
  mac: {
    safari: {
        'os' : 'OS X',
        'os_version' : 'Catalina',
        'browser' : 'Safari',
        'browser_version': '13.1',
        'resolution': '1920x1080'
    },
    chrome: {
        'os' : 'OS X',
        'os_version' : 'Catalina',
        'browser' : 'Chrome',
        'browser_version': 'latest',
        'resolution': '1920x1080'
    },
    firefox: {
      'os' : 'OS X',
      'os_version' : 'Catalina',
      'browser' : 'Firefox',
      'browser_version': 'latest',
      'resolution': '1920x1080'
    },
    edge: {
      'os' : 'OS X',
      'os_version' : 'Catalina',
      'browser' : 'Edge',
      'browser_version': 'latest',
      'resolution': '1920x1080'
    }
  }
}

exports.config = Object.assign(baseConfig.config, {
    baseUrl: process.env.APP_URL,
    user: process.env.BROWSER_STACK_USER,
    key: process.env.BROWSER_STACK_KEY,
    maxInstances: 1,
    services: [
        ['browserstack', {
            browserstackLocal: true,
            debug: true
        }]
    ],
    capabilities: [selectn(process.env.BROWSER, browsersStackMap)]
})

const baseConfig = require('./wdio.conf.js')

exports.config = Object.assign(baseConfig.config, {
    maxInstances: 1,
    capabilities: [{
        maxInstances: 1,
        browserName: 'chrome',
        'goog:chromeOptions': {
          args: [
            '--headless',
            '--no-sandbox',
            '--disable-infobars',
            '--disable-dev-shm-usage',
            '--high-dpi-support=1',
            '--disable-popup-blocking',
            'window-size=1920,1080'
          ]
        }
    }],
})

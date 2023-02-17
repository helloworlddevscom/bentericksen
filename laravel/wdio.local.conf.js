const baseConfig = require('./wdio.conf.js')

process.env.APP_URL = 'http://hrdirector.localhost'

exports.config = Object.assign(baseConfig.config, {
    baseUrl: 'http://hrdirector.localhost',
    maxInstances: 1,
    capabilities: [{
        maxInstances: 1,
        browserName: 'chrome',
        'goog:chromeOptions': {
            args: ['--no-sandbox', '--disable-infobars', '--disable-dev-shm-usage', 'window-size=1920,1080']    
        }        
    }],
})

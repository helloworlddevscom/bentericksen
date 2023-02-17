const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')

describe('BEM-659: Policy Compare tool', () => {
  it('Will policy compare', () => {
    const APP_URL = process.env.APP_URL || 'http://hrdirector.localhost'

    LoginPage.open()
    LoginPage.login()

    AdminBusiness.create()
    AdminBusiness.build({
      radios: ['#bonuspro_enabled_yes']
    })
    
    $('a=POLICIES').click()

    $('a=RESET POLICIES').click()
    
    browser.pause(1000)

    $('a=Reset Policies').click()

    browser.pause(5000)

    browser.url('/user/policies')

    $$('a=EDIT')[0].click()

    browser.pause(4000)

    browser.switchToFrame($('.cke_wysiwyg_frame'))

    $('body').addValue('test')

    browser.switchToParentFrame()

    browser.pause(2000)

    $('button=SAVE').click()

    browser.url('/user/policies')

    $$('a=COMPARE')[0].click()

    browser.switchWindow($$('a=COMPARE')[0].getAttribute('href'))

    expect($('ins.diffmod')).toHaveTextContaining('test')

    browser.pause(2000)

    browser.switchWindow(`${APP_URL}/user/policies`)

    $$('a=COMPARE')[1].click()

    browser.switchWindow(
        $$('a=COMPARE')[1].getAttribute('href')
    )

    expect($('.diff-grid').$('.message')).toHaveText('No Changes Found')

    browser.pause(2000)
  })
})

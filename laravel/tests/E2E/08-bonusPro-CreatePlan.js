const LoginPage =  require('../pageObjects/Login.page')
const AdminBusiness =  require('../pageObjects/AdminBusiness.page')
const BonusPro = require('../pageObjects/BonusPro.page')

describe('Bonus Pro Plan Creation', () => {
  it('Can complete basic plan setup workflow', () => {

    LoginPage.open()
    LoginPage.login()

    // Previous: AdminBusiness.open()
    AdminBusiness.create()

    // AdminBusiness.create({
    AdminBusiness.build({
        radios: ['#bonuspro_enabled_yes']
    })

    BonusPro.open()
    BonusPro.create()

    $('[name=staff_bonus_percentage]').click()
    browser.pause(500)
    $('[name=staff_bonus_percentage]').clearValue()
    browser.pause(500)
    $('[name=staff_bonus_percentage]').setValue('35')

    browser.pause(500)

    browser.execute((elem) => {
      const event = new Event('blur')
      elem.dispatchEvent(event)
    }, $('[name=staff_bonus_percentage]'))

    $('button=Finish').click()

    browser.pause(2000)

    expect($('td=E2E01 - E2E TEST PLAN')).toBeDisplayed()
  })
})

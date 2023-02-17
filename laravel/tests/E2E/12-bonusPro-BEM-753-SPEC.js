const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')
const BonusPro = require('../pageObjects/BonusPro.page')

describe('BEM-753: BonusPro: Staff Salaries populating on Set Bonus % Screen', () => {
  it('Will populate staff salaries on step 4 during creation', () => {
    LoginPage.open()
    LoginPage.login()

    AdminBusiness.create()
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

    browser.pause(2000)

    expect($('[name="current_staff_salaries"]')).toHaveValue('$37,363')

  })
})

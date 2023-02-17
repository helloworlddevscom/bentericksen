const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')
const BonusPro = require('../pageObjects/BonusPro.page')

describe('BEM-754 Bonus Pro Plan Creation', () => {
  it('Will create a basic plan with no hygiene plan and display both admin/assistant and hygiene employees', () => {
    LoginPage.open()
    LoginPage.login()

    // Previous
    // AdminBusiness.open()
    // AdminBusiness.create({
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

    $('button=Finish').click()

    browser.pause(2000)

    BonusPro.loginToPlan()
    BonusPro.editOpenMonth()
    browser.pause(2000)

   $('button=VIEW').click()

    browser.pause(2000)

    const employees = $('#month-data').$('#staff').$('tbody').$$('tr')

    expect(employees).toBeElementsArrayOfSize({ eq: 2 })

    expect($('[name="hours-total"]')).toHaveValue('92')

    expect($('[name="salary-total"]')).toHaveValue('$2,002')
  })
})

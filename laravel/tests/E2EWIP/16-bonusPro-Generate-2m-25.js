const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')
const BonusPro = require('../pageObjects/BonusPro.page')

import { seedConfig } from '../../resources/assets/js/store/mock-data/16-BonusPro-seed';
// import { seedConfig } from '../../resources/assets/js/store/mock-data/seeded-bonusPro-2months';

describe('Bonus Pro Plan Creation and Months validation', () => {
  it('Can complete basic plan setup workflow', () => {
    LoginPage.open()
    LoginPage.login()

    AdminBusiness.create()

    AdminBusiness.generate({
      seedConfig,
      radios: ['#bonuspro_enabled_yes']
    })

    BonusPro.open()
    BonusPro.generatePlan({
      seedConfig
    })

    $('[name=staff_bonus_percentage]').click()
    browser.pause(500)
    $('[name=staff_bonus_percentage]').clearValue()
    browser.pause(500)
    $('[name=staff_bonus_percentage]').setValue('25')

    browser.pause(500)

    browser.execute((elem) => {
      const event = new Event('blur')
      elem.dispatchEvent(event)
    }, $('[name=staff_bonus_percentage]'))

    browser.pause(2000)

    $('button=Finish').click()

    browser.pause(2000)

    BonusPro.loginToPlan()

    BonusPro.editOpenMonth_gen(({
      seedConfig
    }))

    expect($('[name="totalBonusesToBePaid"]')).toHaveValue('$4,594')

    $('#month-data').$('button=accept').scrollIntoView()
    $('#month-data').$('button=accept').click()
    browser.pause(2000)
    $('#month-data').$('button=YES').click()

    browser.pause(2000)

    // expect($('td=02 - BonusPro 2 - month average')).toBeDisplayed()
  })
})

const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')
const BonusPro = require('../pageObjects/BonusPro.page')

import { seedConfig } from '../../resources/assets/js/store/mock-data/15-BonusPro-seed-BEM-769';

describe('BEM-769:  Bonus Pro Plan Creation via Spec from mock-data Import', () => {
  it('Can complete basic plan setup workflow from mock data and verify PandC salaries and Staff salaires for 25% bonus', () => {
    LoginPage.open()
    LoginPage.login()

    AdminBusiness.create()
    AdminBusiness.build({
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

    expect($('[name="production_collection_salaries"]')).toHaveValue('$53,017')
    expect($('[name="current_staff_salaries"]')).toHaveValue('$17,438')
  })
})

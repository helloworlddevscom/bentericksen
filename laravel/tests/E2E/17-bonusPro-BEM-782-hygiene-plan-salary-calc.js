const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')
const BonusPro = require('../pageObjects/BonusPro.page')

import { seedConfig } from '../../resources/assets/js/store/mock-data/17-BonusPro-seed-BEM-872';

describe('Bonus Pro Plan Creation with Hygiene Plan', () => {
  xit('Can complete plan setup with hygiene plan data set to 10% bonus and verify calculation totals', () => {
    LoginPage.open()
    LoginPage.login()

    // AdminBusiness.viewAs({
    //   seedConfig,
    //   properties: { businessName: "E2E Test 1655409200961" }
    // });

    AdminBusiness.create()
    AdminBusiness.build({
      radios: ['#bonuspro_enabled_yes']
    })

    BonusPro.open()
    BonusPro.generatePlan({
      seedConfig,
      radios: ['#create_hygiene_plan_yes']
    })

    $('[name=staff_bonus_percentage]').click()
    browser.pause(500)
    $('[name=staff_bonus_percentage]').clearValue()
    browser.pause(500)
    $('[name=staff_bonus_percentage]').setValue('10')

    browser.pause(500)

    browser.execute((elem) => {
      const event = new Event('blur')
      elem.dispatchEvent(event)
    }, $('[name=staff_bonus_percentage]'))

    browser.pause(2000)

    expect($('[name="production_collection_salaries"]')).toHaveValue('$97,333')
    expect($('[name="current_staff_salaries"]')).toHaveValue('$6,975')

    $('[name=hygiene_bonus_percentage]').click()
    browser.pause(500)
    $('[name=hygiene_bonus_percentage]').clearValue()
    browser.pause(500)
    $('[name=hygiene_bonus_percentage]').setValue('10')

    browser.pause(500)

    browser.execute((elem) => {
      const event = new Event('blur')
      elem.dispatchEvent(event)
    }, $('[name=hygiene_bonus_percentage]'))

    browser.pause(2000)

    expect($('[name="hygiene_production_collection_salaries"]')).toHaveValue('$35,208')
    expect($('[name="hygiene_current_staff_salaries"]')).toHaveValue('$2,989')

    $('button=Finish').click()

    browser.pause(2000)

    BonusPro.loginToPlan()
    browser.pause(2000)

    $('#snapshot').$('#staff').click()

    const staffData = $('#snapshot').$('#staff').$('tbody').$$('tr')

    browser.pause(2000)
  })
})

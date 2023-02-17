const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')
const BonusPro = require('../pageObjects/BonusPro.page')

import { seedConfig } from '../../resources/assets/js/store/mock-data/at-bonusPro-3m-20p-hygiene-hours-seed'
import { validateSnapshot } from '../utils/validateSnapshot'

describe('Bonus Pro Plan Validation for at-bonusPro-3m-20p-hours', () => {
  it('Can generate plan setup, enter 2 months, validate snapshot data', () => {
    const { planData } = seedConfig.modules
    
    // Below are input percentage (and expected) results
    // during set Bonus Percentage screen on last page of
    // bonusPro plan setup.

    // NOTE:  String dollar values expecting $ in value.
    // (JB): @todo  refactor $ in string:  All single number type

    // in webdriver: decoder (when a test fails):
    // "Expected" = values from the E2E test.  Defined here
    // "Received" = from DOM element/calculated result.
    seedConfig.modules.initialSetup.state.plan_name += ' - hygiene'

    const templateParams = {
      init: {
        staff_bonus_percentage: 20,
        production_collection_salaries: "$38,771",
        current_staff_salaries: "$14,050",
        hygiene_bonus_percentage: 35,
        hygiene_production_collection_salaries: "$10,381",
        hygiene_current_staff_salaries: "$5,600"
      },
      staff: {},
      hygiene: {}
    }

    // E2E test will always be the last 6 months.   Need to adjust the snapshot matching in mock data
    // to always be the last 6 months of data.

    // This is slice 7 months because the system automatically creates the placeholder for the active
    // month, even if no data is present.  So grabbing 7 months, but we'll only check the first 6
    // for data matching in the system.
    const activeSixMonth = planData.state.months.slice(-7)

    LoginPage.open()
    LoginPage.login()

    // For Viewing as business already created.   Nice to speed up testing if
    // you can use an existing business
    // AdminBusiness.viewAs({
    //   seedConfig,
    //   properties: { businessName: "E2E Test 1655409200961" }
    // });
    AdminBusiness.create()
    AdminBusiness.generate({
      seedConfig,
      radios: ['#bonuspro_enabled_yes']
    })

    BonusPro.open()
    BonusPro.generatePlan({
      seedConfig,
      radios: ['#create_hygiene_plan_yes'],
      generateEmployees: 5,
      generateEmployeeTypes: ['admin_assistant', 'admin_assistant', 'admin_assistant', 'hygienist', 'hygienist']
    })
    // For Viewing as business already created.   Nice to speed up testing if
    // you can use an existing plan.   Will take bonusPro plan name from VUEX
    // Objects (as created during setup)
    // BonusPro.viewPlan({
    //   properties: { planName: bonusProPlanName }
    // });

    $('[name=staff_bonus_percentage]').click()
    browser.pause(500)
    $('[name=staff_bonus_percentage]').clearValue()
    browser.pause(500)
    $('[name=staff_bonus_percentage]').setValue(templateParams.init.staff_bonus_percentage)

    browser.pause(500)

    browser.execute((elem) => {
      const event = new Event('blur')
      elem.dispatchEvent(event)
    }, $('[name=staff_bonus_percentage]'))

    browser.pause(2000)

    expect($('[name="production_collection_salaries"]')).toHaveValue(templateParams.init.production_collection_salaries)
    expect($('[name="current_staff_salaries"]')).toHaveValue(templateParams.init.current_staff_salaries)

    // Hygiene Plan Validation - also in TDD base
    $('[name=hygiene_bonus_percentage]').click()
    browser.pause(500)
    $('[name=hygiene_bonus_percentage]').clearValue()
    browser.pause(500)
    $('[name=hygiene_bonus_percentage]').setValue(templateParams.init.hygiene_bonus_percentage)
    //
    browser.pause(500)
    //
    browser.execute((elem) => {
      const event = new Event('blur')
      elem.dispatchEvent(event)
    }, $('[name=hygiene_bonus_percentage]'))
    //
    browser.pause(2000)
    //
    expect($('[name="hygiene_production_collection_salaries"]')).toHaveValue(templateParams.init.hygiene_production_collection_salaries)
    expect($('[name="hygiene_current_staff_salaries"]')).toHaveValue(templateParams.init.hygiene_current_staff_salaries)

    $('button=Finish').click()
    browser.pause(2000)

    BonusPro.loginToPlan()

    browser.pause(2000)

    // Decoder:
    // (key): Value in VUEX result/month
    // (value): ID on table associated with input element.

    BonusPro.editGenerateOpenMonth({
      seedConfig,
      properties: { month: 1 }
    })

    browser.pause(2000)

    BonusPro.editGenerateOpenMonth({
      seedConfig,
      properties: { month: 0 }
    })

    browser.pause(2000)

    $('a=Snapshot').click()
    
    browser.pause(2000)

    validateSnapshot($('#snapshot').$('#staff').$('tbody').$$('tr'), {
      production_amount: { id: 'net-production', type: 'dollar', row: 0 },
      collection_amount: { id: 'net-collection', type: 'dollar', row: 1 },
      production_collection_average: { id: 'production-collection-avg', type: 'dollar', row: 2 },
      staffSalaryBonusTotal: { id: 'salary-bonus-total', type: 'dollar', row: 3 },
      staffSalaryPercentageOfPandC: { id: 'salary-p-and-c-average', type: 'percentage', row: 4 }
    }, { shift: 1 }, activeSixMonth)

    $('#snapshot').$('a=Hygiene').click()

    browser.pause(2000)
  
    validateSnapshot($('#snapshot').$('#hygiene').$('tbody').$$('tr'), {
      hygiene_production_amount: { id: 'net-production', type: 'dollar', row: 0 },
      productionAverage: { id: 'production-avg', type: 'dollar', row: 1 },
      hygienistsSalaryBonusTotal: { id: 'salary-bonus-total', type: 'dollar', row: 2 },
      hygienistsSalaryPercentageOfProd: { id: 'salary-p-and-c-average', type: 'percentage', row: 3 }
    }, { shift: 1 }, activeSixMonth)
  })
})

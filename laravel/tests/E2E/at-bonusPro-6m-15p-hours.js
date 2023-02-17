const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')
const BonusPro = require('../pageObjects/BonusPro.page')

import { seedConfig } from '../../resources/assets/js/store/mock-data/at-bonusPro-6m-15p-hours-seed';
import { validateSnapshot } from '../utils/validateSnapshot'

describe('Bonus Pro Plan Validation for at-bonusPro-6m-15p-hours', () => {
  it('Can generate plan setup, enter 2 months, validate snapshot data', () => {
    const { planData, initialSetup } = seedConfig.modules;

    const rollingAve = initialSetup.state.rolling_average;
    const bonusProPlanName = initialSetup.state.plan_name;

    // Below are input percentage (and expected) results
    // during set Bonus Percentage screen on last page of
    // bonusPro plan setup.

    // NOTE:  String dollar values expecting $ in value.
    // (JB): @todo  refactor $ in string:  All single number type

    // in webdriver: decoder (when a test fails):
    // "Expected" = values from the E2E test.  Defined here
    // "Received" = from DOM element/calculated result.

    const templateParams = {
      init: {
        staff_bonus_percentage: 15,
        production_collection_salaries: "$88,361",
        current_staff_salaries: "$9,869"
        // hygiene_bonus_percentage: 10,
        // hygiene_production_collection_salaries: "$90,208",
        // hygiene_current_staff_salaries: "$2,989"
      },
      staff: {
      },
      hygiene: {
      }
    }

    // E2E test will always be the last 6 months.   Need to adjust the snapshot matching in mock data
    // to always be the last 6 months of data.

    // This is slice 7 months because the system automatically creates the placeholder for the active
    // month, even if no data is present.  So grabbing 7 months, but we'll only check the first 6
    // for data matching in the system.
    const activeSixMonth = planData.state.months.slice(-7);

    // Create our number formatter.
    const dollarFormatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    });

    const percentageFormatter = new Intl.NumberFormat("en-US", {
      style: 'percent',
      minimumFractionDigits: 1,
      maximumFractionDigits: 2
    });

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
      seedConfig
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
    // $('[name=hygiene_bonus_percentage]').click()
    // browser.pause(500)
    // $('[name=hygiene_bonus_percentage]').clearValue()
    // browser.pause(500)
    // $('[name=hygiene_bonus_percentage]').setValue(templateParams.init.hygiene_bonus_percentage)
    //
    // browser.pause(500)
    //
    // browser.execute((elem) => {
    //   const event = new Event('blur')
    //   elem.dispatchEvent(event)
    // }, $('[name=hygiene_bonus_percentage]'))
    //
    // browser.pause(2000)
    //
    // expect($('[name="hygiene_production_collection_salaries"]')).toHaveValue(templateParams.init.hygiene_production_collection_salaries)
    // expect($('[name="hygiene_current_staff_salaries"]')).toHaveValue(templateParams.init.hygiene_current_staff_salaries)

    $('button=Finish').click()
    browser.pause(2000)

    BonusPro.loginToPlan()
    browser.pause(2000)

    // Start Validating Staff Salary/Summaries
    // This is the tbody element
    // $('#snapshot').$('a=Staff').click();
    // const staff = $('#snapshot').$('#staff').$('tbody').$$('tr');

    // Start Validating Hygiene Salary/Summaries
    // $('#snapshot').$('a=Hygiene').click();
    // const hygiene = $('#snapshot').$('#hygiene').$('tbody').$$('tr');

    // Decoder:
    // (key): Value in VUEX result/month
    // (value): ID on table associated with input element.
    const keys = {
      production_amount: { id: 'net-production', type: 'dollar', row: 0 },
      collection_amount: { id: 'net-collection', type: 'dollar', row: 1 },
      production_collection_average: { id: 'production-collection-avg', type: 'dollar', row: 2 },
      staffSalaryBonusTotal: { id: 'salary-bonus-total', type: 'dollar', row: 3 },
      staffSalaryPercentageOfPandC: { id: 'salary-p-and-c-average', type: 'percentage', row: 4 }
      //
      // hygiene_production_amount: 'net-production',
      // hygienistsSalaryBonusTotal: 'salary-bonus-total',
      // hygienistsSalaryPercentageOfProd: 'salary-p-and-c-average'
    }

    browser.pause(2000)

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
    const staff = $('#staff').$('tbody').$$('tr');
    validateSnapshot(staff, keys, { shift: 1 }, activeSixMonth)
  })
})

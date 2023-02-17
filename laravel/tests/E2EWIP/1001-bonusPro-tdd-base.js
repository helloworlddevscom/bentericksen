const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')
const BonusPro = require('../pageObjects/BonusPro.page')

import { seedConfig } from '../../resources/assets/js/store/mock-data/1001-bonusPro-tdd-seed';

describe('Bonus Pro Plan Validation with Hygiene Plan', () => {
  it('Can generate plan setup with hygiene plan data with valid staff salaries', () => {
    const { planData, initialSetup } = seedConfig.modules;
    const rollingAve = initialSetup.state.rolling_average;
    const bonusProPlanName = initialSetup.state.plan_name;

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
    // Previous
    // BonusPro.viewPlan({
    //   properties: { planName: bonusProPlanName }
    // });


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

    $('#snapshot').$('a=Hygiene').click();
    // This is the tbody element

    const hygiene = $('#snapshot').$('#hygiene').$('tbody').$$('tr');

    // This is Net Production
    const productionRow = hygiene[0].$$('td');
    productionRow.forEach((data, index) => {
      if (index > 0) {
        const hygieneData = data.$('input.net-production');
        const value = dollarFormatter.format(activeSixMonth[index - 1].hygiene_production_amount);
        expect(hygieneData).toHaveValue(value);
      }
    });

    // This is Hygiene Production Average (NOTE:  In dollars as well, not percentage)
    const productionAvgRow = hygiene[1].$$('td');
    productionAvgRow.forEach((data, index) => {
      if (index > rollingAve) {
        const hygieneData = data.$('input.production-avg');
        const value = dollarFormatter.format(activeSixMonth[index - 1].productionAverage);
        expect(hygieneData).toHaveValue(value);
      }
    });

    // This is the salary-bonus-total' Row
    const bonusRow = hygiene[2].$$('td');
    bonusRow.forEach((data, index) => {
      if (index > 1) {
        const hygieneData = data.$('input.salary-bonus-total');
        const value = dollarFormatter.format(activeSixMonth[index - 1].hygienistsSalaryBonusTotal);
        expect(hygieneData).toHaveValue(value);
      }
    });

    // This is the salary-p-and-c-average Row
    const salaryPandCRow = hygiene[3].$$('td');
    salaryPandCRow.forEach((data, index) => {
      if (index > rollingAve) {
        const hygieneData = data.$('input.salary-p-and-c-average');
        const value = percentageFormatter.format(activeSixMonth[index - 1].hygienistsSalaryPercentageOfProd);
        expect(hygieneData).toHaveValue(value);
      }
    });
  })
})

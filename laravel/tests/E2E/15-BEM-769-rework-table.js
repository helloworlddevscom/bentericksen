const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')
const BonusPro = require('../pageObjects/BonusPro.page')

import { seedConfig } from '../../resources/assets/js/store/mock-data/15-BonusPro-seed-BEM-769'

describe('BEM-769:  Bonus Pro Plan Creation via Spec from mock-data Import', () => {
  it.skip('WILL UPDATE IN BEM-753. Can complete basic plan setup workflow with correct bonus percentage salaries calculations', () => {
    LoginPage.open()
    LoginPage.login()

    AdminBusiness.open()

    AdminBusiness.generate({
      seedConfig,
      radios: ['#bonuspro_enabled_yes']
    })

    BonusPro.open()
    BonusPro.generatePlan({
      seedConfig
    })
    expect($('[name="production_collection_salaries"]')).toHaveValue('$37,869')
    expect($('[name="current_staff_salaries"]')).toHaveValue('$24,413')
  })
})

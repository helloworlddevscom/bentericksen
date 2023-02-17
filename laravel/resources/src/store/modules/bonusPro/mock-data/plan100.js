import ui from '../modules/ui'
import global from '../modules/global'
import initialSetup from '../modules/initialSetup'
import planData from '../modules/planData'
import employeesAndFunds from '../modules/employeesAndFunds'
import bonusPercentage from '../modules/bonusPercentage'

/**
 * Mock data for unit testing
 *
 * Plan 100 : no funds or separate hygiene plan
 */
const activeMonth = {
  'year': 2018,
  'month': 1,
  'finalized': 1,
  'production_amount': 30000,
  'collection_amount': 33000,
  'hygiene_production_amount': 0,
  'employee_data': [
    {
      'user_id': 101,
      'gross_pay': '500.00',
      'hours_worked': '160.00',
      'amount_received': '0.00',
      'percentage': '0.00',
      'bp_employee_type': 'admin/assistant'
    },

    {
      'user_id': 103,
      'gross_pay': '500.00',
      'hours_worked': '160.00',
      'amount_received': '0.00',
      'percentage': '0.00',
      'bp_employee_type': 'hygienist'
    },

    {
      'user_id': 104,
      'gross_pay': '500.00',
      'hours_worked': '160.00',
      'amount_received': '0.00',
      'percentage': '0.00',
      'bp_employee_type': 'admin/assistant'
    }
  ],
  'funds': [],
  staffSalaryBonusTotal: 0,
  staffSalaryPercentageOfPandC: 0,
  hygienistsSalaryPercentageOfProd: 0,
  bonusesToBePaid: 0,
  productionAverage: 0,
  productionCollectionAverage: 0,
  staffSalariesTotal: 0,
  productionCollectionAverageDisabled: true,
  collectionRatio: 0,
  staffBonusToBePaid: 0,
  hygieneBonusToBePaid: 0,
  fundsBonusToBePaid: 0
}

export const plan100 = {
  'initialSetup': {
    plan_id: '100',
    plan_name: 'No Hygiene Plan, No Funds',
    completed: 1,
    distribution_type: 'fixed_percentage',
    draft: 0,
    hygiene_plan: 0,
    rolling_average: 2,
    separate_fund: 0,
    start_month: 7,
    start_year: 2018,
    status: null,
    type_of_practice: 'general',
    use_business_address: 1,
    staff_bonus_percentage: 10,
    hygiene_bonus_percentage: 0
  },
  'planData': {
    activeMonth: activeMonth,
    plan_id: '100',
    months: [
      {
        'year': 2018,
        'month': 2,
        'finalized': 1,
        'production_amount': 10000,
        'collection_amount': 9000,
        'hygiene_production_amount': 0,
        'employee_data': [
          {
            'user_id': 101,
            'gross_pay': '1000.00',
            'hours_worked': '160.00',
            'amount_received': '0.00',
            'percentage': '0.00'
          }
        ],
        'funds': [],
        productionCollectionAverageDisabled: true
      },
      activeMonth,
      {
        'year': 2018,
        'month': 3,
        'finalized': 1,
        'production_amount': 10000,
        'collection_amount': 9000,
        'hygiene_production_amount': 0,
        'employee_data': [
          {
            'user_id': 101,
            'gross_pay': '1000.00',
            'hours_worked': '160.00',
            'amount_received': '0.00',
            'percentage': '0.00'
          }
        ],
        'funds': [],
        productionCollectionAverageDisabled: true
      },
      {
        'year': 2018,
        'month': 4,
        'finalized': 1,
        'production_amount': 10000,
        'collection_amount': 9000,
        'hygiene_production_amount': 0,
        'employee_data': [
          {
            'user_id': 101,
            'gross_pay': '1000.00',
            'hours_worked': '160.00',
            'amount_received': '0.00',
            'percentage': '0.00'
          }
        ],
        'funds': []
      },
      {
        'year': 2018,
        'month': 5,
        'finalized': 1,
        'production_amount': 10000,
        'collection_amount': 9000,
        'hygiene_production_amount': 0,
        'employee_data': [
          {
            'user_id': 101,
            'gross_pay': '1000.00',
            'hours_worked': '160.00',
            'amount_received': '0.00',
            'percentage': '0.00'
          }
        ],
        'funds': []
      },
      {
        'year': 2018,
        'month': 6,
        'finalized': 1,
        'production_amount': 9000,
        'collection_amount': 8000,
        'hygiene_production_amount': 0,
        'employee_data': [
          {
            'user_id': 101,
            'gross_pay': '1000.00',
            'hours_worked': '160.00',
            'amount_received': '0.00',
            'percentage': '0.00'
          }
        ],
        'funds': []
      },
      {
        'year': 2018,
        'month': 7,
        'finalized': 0,
        'production_amount': 0,
        'collection_amount': 0,
        'hygiene_production_amount': 0,
        'employee_data': [
          {
            'user_id': 101,
            'gross_pay': '1000.00',
            'hours_worked': '160.00',
            'amount_received': '0.00',
            'percentage': '0.00'
          }
        ],
        'funds': []
      }
    ]
  },
  'employeesAndFunds': {
    'activeEmployee': null,
    'employees': [
      {
        'id': 101,
        'bp_bonus_percentage': 8.00,
        'bp_eligibility_date': '01/2018',
        'bp_employee_type': 'admin/assistant',
        'first_name': 'Employee',
        'last_name': '101',
        'email': 'e101@example.com',
        'bp_eligible': 1
      },
      {
        'id': 102,
        'bp_bonus_percentage': 20.00,
        'bp_eligibility_date': '01/2018',
        'bp_employee_type': 'admin/assistant',
        'first_name': 'Employee',
        'last_name': '102',
        'email': 'e102@example.com',
        'bp_eligible': 1
      },
      {
        'id': 103,
        'bp_bonus_percentage': 5.00,
        'bp_eligibility_date': '01/2018',
        'bp_employee_type': 'hygienist',
        'first_name': 'Employee',
        'last_name': '103',
        'email': 'e103@example.com',
        'bp_eligible': 1
      },
      {
        'id': 104,
        'bp_bonus_percentage': 10.00,
        'bp_eligibility_date': '01/2018',
        'bp_employee_type': 'admin/assistant',
        'first_name': 'Employee',
        'last_name': '104',
        'email': 'e104@example.com',
        'bp_eligible': 1
      }
    ],
    'funds': [],
    'errors': {
      'employees': null,
      'funds': null
    }
  },
  'bonusPercentage': {
    'hygiene_bonus_percentage': '11.00',
    'staff_bonus_percentage': '25.00'
  },
  activeMonth: null,
  plan_id: 42
}

export const storeConfig100 = {
  strict: process.env.NODE_ENV !== 'production',
  modules: {
    ui,
    global,
    initialSetup: {
      namespaced: true,
      state: plan100.initialSetup,
      getters: initialSetup.getters,
      mutations: initialSetup.mutations,
      actions: initialSetup.actions
    },
    planData: {
      namespaced: true,
      state: plan100.planData,
      getters: planData.getters,
      mutations: planData.mutations,
      actions: planData.actions
    },
    employeesAndFunds: {
      namespaced: true,
      state: plan100.employeesAndFunds,
      getters: employeesAndFunds.getters,
      mutations: employeesAndFunds.mutations,
      actions: employeesAndFunds.actions
    },
    bonusPercentage: {
      namespaced: true,
      state: plan100.bonusPercentage,
      getters: bonusPercentage.getters,
      mutations: bonusPercentage.mutations,
      actions: bonusPercentage.actions
    }
  }
}

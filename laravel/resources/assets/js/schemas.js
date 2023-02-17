/**
 * Initial State for the monthly data. For the instances where the employee is being added to the plan.
 * @param userId
 * @param monthId
 * @param month
 * @param year
 * @returns {{id: null, user_id: *, month_id: *, month: *, year: *, gross_pay: number, hours_worked: number, amount_received: number, percentage: number}}
 */
export function monthlyData (userId, monthId, month, year) {
  return {
    id: null,
    user_id: userId,
    month_id: monthId,
    month: month,
    year: year,
    gross_pay: 0.00,
    hours_worked: 0.00,
    amount_received: 0.00,
    percentage: 0.00
  }
}

/**
 * Initial state for Fund data Vuex store.
 * @param fundId
 * @param monthId
 * @param amountReceived
 * @returns obj
 */
export function fundData (fundId, monthId, amountReceived) {
  return {
    id: null,
    fund_id: fundId,
    month_id: monthId,
    amount_received: parseFloat(amountReceived)
  }
}

/**
 * Initial State for Employee data Vuex store.
 *
 * @returns obj
 */
export function employeeFields () {
  return {
    first_name: null,
    last_name: null,
    email: null,
    address1: null,
    address2: null,
    city: null,
    state: "",
    postal_code: null,
    phone1_type: 'home',
    phone1: null,
    hired: null,
    dob: null,
    business_id: null,
    bp_eligibility_date: null,
    bp_bonus_percentage: null,
    bp_eligible: true,
    bp_employee_type: 'admin/assistant',
    monthlyData: []
  }
}

/**
 * Returns initial state for Month (Vuex Store)
 * @returns obj
 */
export function month () {
  return {
    id: null,
    month: null,
    year: null,
    finalized: 0,
    fundAmountPaid: 0,
    staffHoursTotal: 0,
    collectionRatio: 0,
    collection_amount: 0,
    production_amount: 0,
    productionAverage: 0,
    staffSalariesTotal: 0,
    staffBonusToBePaid: 0,
    fundsBonusToBePaid: 0,
    hygienistsHoursTotal: 0,
    hygieneBonusToBePaid: 0,
    staffSalaryBonusTotal: 0,
    hygienistsSalariesTotal: 0,
    hygiene_production_amount: 0,
    hygienistsSalaryBonusTotal: 0,
    productionCollectionAverage: 0,
    staffSalaryPercentageOfPandC: 0,
    hygienistsSalaryPercentageOfProd: 0,
    productionCollectionAverageDisabled: false,
    employee_data: [],
    funds: []
  }
}

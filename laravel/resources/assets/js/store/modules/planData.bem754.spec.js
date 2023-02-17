import { createLocalVue } from '@vue/test-utils'
import Vuex from 'vuex'
import test from 'ava'

// mock data for the tests
import { bem754Config } from '../mock-data/bem-754'

test.beforeEach('new Vuex.Store()', (t) => {
  const localVue = createLocalVue()
  
  localVue.use(Vuex)

  const store = new Vuex.Store(bem754Config)

  t.context.store = store
})

test('planData:updateField', (t) => {
  const { store } = t.context

  store.dispatch('planData/updateField', { prop: 'months.1.id', value: 1 })

  t.is(store.state.planData.months[1].id, 1)
})

test('planData:calculateCollectionRatio', (t) => {
  const { store } = t.context

  store.dispatch('planData/calculateCollectionRatio', store.getters['planData/activeMonth'])

  t.is(store.state.planData.months[1].collectionRatio, 110.00000000000001)
})

test('planData:calculateProductionAverage', t => {
  const { store } = t.context

  store.dispatch('planData/calculateProductionAverage', 1)

  t.is(store.state.planData.months[1].productionAverage, 0)
})


test('planData:calculateHoursTotals', (t) => {
  const { store } = t.context

  store.dispatch('planData/calculateHoursTotals', store.getters['planData/activeMonth'])

  t.is(store.state.planData.months[1].staffHoursTotal, 1000)
})

test('planData:calculateSalaryTotals', (t) => {
  const { store } = t.context

  store.dispatch('planData/calculateSalaryTotals', store.getters['planData/activeMonth'])

  t.is(store.state.planData.months[1].staffSalariesTotal, 1000)
  t.is(store.state.planData.months[1].hygienistsSalariesTotal, 0)
})

test('planData:calculateSalaryBonusTotals', (t) => {
  const { store } = t.context

  store.dispatch('planData/calculateSalaryBonusTotals', store.getters['planData/activeMonth'])

  t.is(store.state.planData.months[1].staffSalaryBonusTotal, 5075)
  t.is(store.state.planData.months[1].hygienistsSalaryBonusTotal, 0)
})

test('planData:calculateStaffSalaryPercentageOfPandC', (t) => {
  const { store } = t.context

  store.dispatch('planData/calculateStaffSalaryPercentageOfPandC', store.getters['planData/activeMonth'])

  t.is(store.state.planData.months[1].staffSalaryPercentageOfPandC, 1.9704433497536946)
})

test('planData:calculateHygienistsSalaryPercentageOfProd', (t) => {
  const { store } = t.context

  store.dispatch('planData/calculateHygienistsSalaryPercentageOfProd', store.getters['planData/activeMonth'])

  t.is(store.state.planData.months[1].hygienistsSalaryPercentageOfProd, NaN)
})

test('planData:calculateBonusesToBePaid', (t) => {
  const { store } = t.context

  store.dispatch('planData/calculateBonusesToBePaid', store.getters['planData/activeMonth'])

  t.is(store.state.planData.months[1].staffBonusToBePaid, 4075)
  t.is(store.state.planData.months[1].hygieneBonusToBePaid, 0)
  t.is(store.state.planData.months[1].fundsBonusToBePaid, 0)
})

test('planData:calculateEmployeeBonusPercentage', (t) => {
  const { store } = t.context

  store.dispatch('planData/calculateEmployeeBonusPercentage', store.getters['planData/activeMonth'])

  t.is(store.state.planData.months[1].employee_data[0].percentage, 50)
})

test('planData:calculateFundsBonusAmount', (t) => {
  const { store } = t.context

  store.dispatch('planData/calculateFundsBonusAmount', store.getters['planData/activeMonth'])

  t.is(store.state.planData.months[1].employee_data[0].percentage, 50)
})

test('planData:calculateEmployeeBonusAmount', (t) => {
  const { store } = t.context

  store.dispatch('planData/calculateEmployeeBonusAmount', store.getters['planData/activeMonth'])

  t.is(store.state.planData.months[1].employee_data[0].amount_received, 2037.5)
  t.is(store.state.planData.months[1].employee_data[1].amount_received, 2037.5)
})

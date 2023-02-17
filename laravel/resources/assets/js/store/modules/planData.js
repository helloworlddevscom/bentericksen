import moment from 'moment'
import BonusProService from "../../services/BonusProService";
import { compareMonths } from '../../utils'
import { month } from "../../schemas";
import { cloneDeep } from 'lodash';

export const state = {
  months: [],
  activeMonth: null,
  plan_id: null
}

export const getters = {
  activeMonths: (state, getters, rootState) => {
    // returns the six newest finalized months on the plan, sorted by date
    return state.months
      .filter(mon => mon.finalized || !rootState.initialSetup.completed)
      .sort((mon1, mon2) => compareMonths(mon1, mon2))
      .slice(-6);  // always the 6 newest months
  },
  months: (state) => state.months,

  netProductionAverage: (state, getters) => {
    let total = 0;
    let count = 0;

    getters.activeMonths.forEach((el) => {
      if (el.production_amount) {
        const value = el.production_amount;
        total += parseFloat(value);
      }
      count++;
    });

    return total / count;
  },
  netCollectionAverage: (state, getters) => {
    let total = 0;
    let count = 0;

    getters.activeMonths.forEach(el => {
      if (el.collection_amount) {
        const value = el.collection_amount;
        total += parseFloat(value);
      }
      count++;
    });

    return total / count;
  },
  productionCollectionAverage: (state, getters) => {
    let total = 0;
    let count = 0;

    getters.activeMonths.forEach(el => {
      if (!el.productionCollectionAverageDisabled) {
        const value = el.productionCollectionAverage;
        total += parseFloat(value);
        count++;
      }
    });

    return total / count;
  },
  collectionRatioAverage: (state, getters) => {
    const npo = getters.netProductionAverage;
    const nco = getters.netCollectionAverage;
    if (isNaN(npo) || isNaN(nco) || npo === 0) {
      return 0;
    }
    return nco / npo * 100;
  },
  staffSalaryBonusTotalsAverage: (state, getters) => {
    let total = 0;

    getters.activeMonths.forEach((el, i) => {
      if (i === 0 || isNaN(el.staffSalaryBonusTotal) || el.staffSalaryBonusTotal === '-') {
        return;
      }

      const value = el.staffSalaryBonusTotal;
      total += parseFloat(value);
    });

    return total / (getters.activeMonths.length - 1);
  },
  hygienistsSalaryBonusTotalsAverage: (state, getters) => {
    let total = 0;

    getters.activeMonths.forEach((el, i) => {
      if (i === 0 || isNaN(el.hygienistsSalaryBonusTotal) || el.hygienistsSalaryBonusTotal === '-') {
        return;
      }
      total += el.hygienistsSalaryBonusTotal;
    });

    return total / (getters.activeMonths.length - 1);
  },
  hygieneProductionAverage: (state, getters) => {
    let total = 0;

    getters.activeMonths.forEach(el => {
      if (el.hygiene_production_amount) {
        const value = el.hygiene_production_amount;
        total += parseFloat(value);
      }
    });

    return total / getters.activeMonths.length;
  },
  productionAverage: (state, getters) => {
    let total = 0;
    let count = 0;

    getters.activeMonths.forEach(el => {
      if (!el.productionCollectionAverageDisabled) {
        const value = el.productionAverage;
        total += parseFloat(value);
        count++;
      }
    });

    return total / count;
  },
  staffSalaryAverage: (state, getters) => {
    let total = 0;
    let count = 0;

    getters.activeMonths.forEach(el => {
      // This is the code to only use the rolling average values
      // if (!el.productionCollectionAverageDisabled) {
      const value = el.staffSalariesTotal;
      total += parseFloat(value);
      count++;
      // }
    });

    return total / count;
  },
  staffSalaryPercentageOfPandCAverage: (state, getters) => {
    let total = 0;
    let count = 0;

    getters.activeMonths.forEach(el => {
      if (!el.productionCollectionAverageDisabled) {
        const value = el.staffSalaryPercentageOfPandC;
        total += parseFloat(value);
        count++;
      }
    });

    return total / count;
  },
  hygienistsSalaryPercentageOfProdAverage: (state, getters) => {
    let total = 0;
    let count = 0;

    getters.activeMonths.forEach(el => {
      if (!el.productionCollectionAverageDisabled) {
        const value = el.hygienistsSalaryPercentageOfProd;
        total += parseFloat(value);
        count++;
      }
    });

    return total / count;
  },
  activeMonth (state) {
    return state.activeMonth
  },
  activeMonthHoursTotal (state, getters, rootState, rootGetters) {
    return (key = 'staffHoursTotal') => {
      if (rootGetters['initialSetup/hygienePlan']) {
        return getters.activeMonth[key]
      }

      return getters.activeMonth.staffHoursTotal + getters.activeMonth.hygienistsHoursTotal
    }
  },
  monthHoursTotal (state, getters, rootState, rootGetters) {
    return (month, key = 'staffHoursTotal') => {
      if (rootGetters['initialSetup/hygienePlan']) {
        return month[key]
      }

      return month.staffHoursTotal + month.hygienistsHoursTotal
    }
  },
  employeeHoursTotal (state, getters, rootState, rootGetters) {
    return (employees) => employees
      .reduce((out, {monthEmployee, employee}) => {
        if (employee.bp_employee_type === 'hygienist' && rootGetters['initialSetup/hygienePlan']) {
          out.hygienistsHoursTotal += parseFloat(monthEmployee.hours_worked)
        } else {
          out.staffHoursTotal += parseFloat(monthEmployee.hours_worked)
        }
        return out
      }, { staffHoursTotal: 0, hygienistsHoursTotal: 0 })
  },
  activeMonthSalariesTotal (state, getters, rootState, rootGetters) {
    return (key = 'staffSalariesTotal') => {
      if (rootGetters['initialSetup/hygienePlan']) {
        return getters.activeMonth[key]
      }

      return getters.activeMonth.staffSalariesTotal + getters.activeMonth.hygienistsSalariesTotal
    }
  },
  monthSalariesTotal (state, getters, rootState, rootGetters) {
    return (month, key = 'staffSalariesTotal') => {
      if (rootGetters['initialSetup/hygienePlan']) {
        return month[key]
      }

      return month.staffSalariesTotal + month.hygienistsSalariesTotal
    }
  },
  employeeSalariesTotal (state, getters, rootState, rootGetters) {
    return (employees) => employees
      .reduce((out, {monthEmployee, employee}) => {
        if (employee.bp_employee_type === 'hygienist' && rootGetters['initialSetup/hygienePlan']) {
          out.hygienistsSalariesTotal += parseFloat(monthEmployee.gross_pay)
        } else {
          out.staffSalariesTotal += parseFloat(monthEmployee.gross_pay)
        }
        return out
      }, { staffSalariesTotal: 0, hygienistsSalariesTotal: 0 })
  },
}

export const mutations = {
  // These functions are only used after initialization is set.
  // Used to set the active month in a plan
  setEmployeeMonthlyData: (state, payload) => {
    const index = state.months.findIndex((el) => {
      return el.id === payload.month.id
    });

    // MonthlyData is an array of employee's.   All the employee data for a particular month
    // During initialization, we are setting each month individually,
    state.months[index].employee_data = payload.monthlyData;
  },
    // These functions are only used after initialization is set.
    // Used to set the active month in a plan
  setFundsMonthlyData: (state, payload) => {
    const index = state.months.findIndex((el) => {
      return el.id === payload.month.id
    });

    state.months[index].funds = payload.monthlyData;
  },
  setMonth: (state, month) => {
    state.months.push(month);
  },
  setActiveMonth: (state, month) => {
    state.activeMonth = month;
  },
  clearActiveMonth: (state) => {
    state.activeMonth = null;
  },
  updateActiveMonthField: (state, { prop, value }) => {
    const index = state.months.findIndex((el) => {
      return el.id === state.activeMonth.id
    });

    if (prop !== 'collectionRatio' &&
      prop !== 'productionCollectionAverageDisabled' &&
      prop !== 'id' &&
      value !== '-') {
      value = parseFloat(value);
    }

    state.months[index][prop] = value;
    state.activeMonth[prop] = value;
  },
  updateField: (state, { prop, value }) => {
    const [property, index, field] = prop.split('.');
    if (field !== 'collectionRatio' &&
      field !== 'productionCollectionAverageDisabled' &&
      field !== 'id' &&
      value !== '-') {
      value = parseFloat(value);
    }

    state[property][index][field] = value;
  },
  updatePlanId: (state, id) => {
    state.plan_id = id;
  },
  closeOpenMonth: (state) => {
    const index = state.months.findIndex((el) => {
      return !el.finalized;
    });
    state.months[index].finalized = 1;
  },
  addNewMonth: (state, month) => {
    state.months.push(month);
  },
  updateHoursTotals: (state, { staff, hygienists, month }) => {
    const index = state.months.findIndex((el) => {
      return el.id === month.id;
    });

    state.months[index].staffHoursTotal = staff;
    state.months[index].hygienistsHoursTotal = hygienists;
  },
  updateCollectionRatio: (state, { month, ratio }) => {
    const index = state.months.findIndex((el) => {
      return (el.month === month.month) && (el.year === month.year);
    });

    state.months[index].collectionRatio = ratio;
  },
  updateSalaryTotals: (state, { staff, hygienists, month }) => {
    const index = state.months.findIndex((el) => {
      return el.id === month.id;
    });

    state.months[index].staffSalariesTotal = staff;
    state.months[index].hygienistsSalariesTotal = hygienists;
  },

  updateSalaryBonusTotals: (state, { staff, hygienists, month }) => {
    const index = state.months.findIndex((el) => {
      return el.id === month.id;
    });

    state.months[index].staffSalaryBonusTotal = staff;
    state.months[index].hygienistsSalaryBonusTotal = hygienists;
  },
  updateStaffSalaryPercentageOfPandC: (state, { value, month }) => {
    const index = state.months.findIndex((el) => {
      return el.id === month.id;
    });

    state.months[index].staffSalaryPercentageOfPandC = value;
  },
  updateHygienistsSalaryPercentageOfProd: (state, { value, month }) => {
    const index = state.months.findIndex((el) => {
      return el.id === month.id;
    });

    state.months[index].hygienistsSalaryPercentageOfProd = value;
  },
  updateBonusesToBePaid: (state, { staff, hygiene, funds, month }) => {
    const index = state.months.findIndex((el) => {
      return el.id === month.id;
    });

    state.months[index].staffBonusToBePaid = staff;
    state.months[index].hygieneBonusToBePaid = hygiene;
    state.months[index].fundsBonusToBePaid = funds;
  },
  updateEmployeeBonusPercentage: (state, { month, employeeIndex, bonusPercentage }) => {
    const monthIndex = state.months.findIndex((el) => {
      return el.id === month.id;
    })

    state.months[monthIndex].employee_data[employeeIndex].percentage = bonusPercentage
  },
  updateGrandTotalPaid: (state, { month, fundIndex, bonusAmount }) => {
    const monthIndex = state.months.findIndex((el) => {
      return el.id === month.id;
    });
    state.months[monthIndex].fundAmountPaid = parseFloat(bonusAmount);
  },
  updateFundBonusAmount: (state, { month, fundId, bonusAmount }) => {
    const monthIndex = state.months.findIndex((el) => {
      return el.id === month.id;
    });
    const fundIndex = state.months[monthIndex].funds.findIndex((el) => {
      return el.fund_id === fundId
    });

    if (fundIndex >= 0) {
      state.months[monthIndex].funds[fundIndex].amount_received = parseFloat(bonusAmount);
    }
  },
  updateEmployeeBonusAmount: (state, { month, employeeIndex, bonusAmount }) => {
    const monthIndex = state.months.findIndex((el) => {
      return el.id === month.id;
    })
    
    state.months[monthIndex].employee_data[employeeIndex].amount_received = parseFloat(bonusAmount);
  }
}

export const actions = {
  setActiveMonth: ({ commit, dispatch, state }, id) => {
    const month = state.months.find((el) => {
      return el.id === id;
    });

    commit('setActiveMonth', month);
    dispatch('runCalculations');
  },
  updateActiveMonth: ({ commit, state }) => {
    const month = state.months.find((el) => {
      return el.id === state.activeMonth.id;
    });

    commit('setActiveMonth', month);
  },
  clearActiveMonth: ({ commit }) => {
    commit('clearActiveMonth');
  },
  save: ({ commit, state, rootState }) => {
    commit('updatePlanId', rootState.initialSetup.id);

    return new Promise((resolve, reject) => {
      BonusProService.save({
        id: state.plan_id,
        activeMonth: state.activeMonth,
        months: state.months
      }, 'planData')
        .then((data) => {
          data.plan.months.forEach((el, i) => {
            commit('updateField', { prop: 'months.' + i + '.id', value: parseInt(el.id) })
          });
          resolve({ success: true });
        })
        .catch((err) => {
          reject(err.errors);
        })
    });
  },
  addNewMonth: ({ commit, rootState, dispatch }, monthData) => {
    const newMonth = Object.assign({}, month());
    newMonth.id = monthData.id;
    newMonth.month = monthData.month;
    newMonth.year = monthData.year;
    newMonth.employee_data = monthData.employee_data;
    newMonth.funds = monthData.funds;

    // This step changes the local state for the previous month to finalized
    commit('closeOpenMonth');

    // This step creates a new local month in planData with values from response
    commit('addNewMonth', newMonth);
  },
  setEmployeeMonthlyData: ({ commit }, payload) => {
    commit('setEmployeeMonthlyData', payload);
  },
  setFundsMonthlyData: ({ commit }, payload) => {
    commit('setFundsMonthlyData', payload);
  },
  setMonths: ({ commit, dispatch, rootState }, months) => {
    const mo = month();
    const rollingAverage = rootState.initialSetup.rolling_average;

    months.forEach((el, i) => {
      const newMonth = Object.assign({}, mo, el);
      newMonth.productionCollectionAverageDisabled = i < rollingAverage;
      commit('setMonth', newMonth);
    });
  },
  initializeMonths: ({ commit, rootState }) => {
    const startMonth = rootState.initialSetup.start_month;
    const startYear = rootState.initialSetup.start_year;
    const rollingAverage = rootState.initialSetup.rolling_average;
    let k = 0;
    let date;

    if (!startMonth || !startYear) {
      return;
    }

    for (let i = 6; i >= 1; i--) {
      const newMonth = month();
      date = moment().month(startMonth - 1).year(startYear).subtract(i, 'months');
      newMonth.month = date.format('M');
      newMonth.year = date.format('YYYY');
      // checking if month is enabled for calculations based on the Plan's rolling average.
      newMonth.productionCollectionAverageDisabled = k < (rollingAverage - 1);
      commit('setMonth', newMonth);
      k++;
    }
  },
  updateField: ({ commit, dispatch }, { prop, value }) => {
    commit('updateField', {
      prop: prop,
      value: value
    });

    setTimeout(() => {
      dispatch('runCalculations');
    }, 100);
  },
  updateActiveMonthField: ({ commit, dispatch }, { prop, value }) => {
    commit('updateActiveMonthField', {
      prop: prop,
      value: value
    });
    setTimeout(() => {
      dispatch('runCalculations');
    }, 100);
  },
  runCalculations: ({ commit, dispatch, state }) => {
    // Future fix.  runCalculation really only needs to run on latest, non-finalized month
    // Below code should be sufficient.  Possible issue as this function is also called
    // during initialSetup.  Looking through the Collection and Production averages which call index,
    // There exists looping functions which seem to handle all the calculations needed.
    // The forEach should be able to be removed and replaced with latest month only.
    // const el = state.months[state.months.length - 1];
    // const index = state.months.indexOf(el);
    state.months.forEach((el, index) => {
      dispatch('calculateCollectionRatio', el);
      if (index >= 0) {
        // calculating averages. The order matters.
        dispatch('calculateProductionCollectionAverage', index);
        dispatch('calculateProductionAverage', index);

        // calculating salaries/hours for bonus calculations.
        dispatch('calculateHoursTotals', el);
        dispatch('calculateSalaryTotals', el);
        dispatch('calculateStaffSalaryPercentageOfPandC', el);
        dispatch('calculateHygienistsSalaryPercentageOfProd', el);

        // calculating the bonuses and percentages.
        // BUG in monthly calculation loop.   Upon monthly entry, all previous months
        // are assumed to be "real", so the code recalculates and assigns a bonus
        // to the initialization months.
        if (index > 5) {
          dispatch('calculateBonusesToBePaid', el);
        }
        dispatch('calculateSalaryBonusTotals', el);
        dispatch('calculateEmployeeBonusPercentage', el);
        dispatch('calculateFundsBonusAmount', el);
        dispatch('calculateEmployeeBonusAmount', el);
      }
    });

    if (state.activeMonth) {
      dispatch('updateActiveMonth');
    }
  },
  calculateCollectionRatio: ({ commit }, month) => {
    let ratio = 0;
    const prodAmount = parseFloat(month.production_amount);
    const collectionAmount = parseFloat(month.collection_amount);

    // calculating Collection Ratio only if prod and collection are entered
    if (prodAmount !== 0 && collectionAmount !== 0) {
      ratio = (collectionAmount / prodAmount) * 100;
    }

    commit('updateCollectionRatio', { month: month, ratio: ratio })
  },
  calculateBonusesToBePaid: ({ commit, rootState }, month) => {
    const staffBonusPercentage = parseFloat(rootState.initialSetup.staff_bonus_percentage);
    const hygieneBonusPercentage = parseFloat(rootState.initialSetup.hygiene_bonus_percentage);
    const staffP = staffBonusPercentage - month.staffSalaryPercentageOfPandC;
    const hygieneP = hygieneBonusPercentage - month.hygienistsSalaryPercentageOfProd;
    let staffBonusToBePaid = 0;
    let hygieneBonusToBePaid = 0;
    let fundsBonusToBePaid = 0;
    let amount = 0;

    if (staffP > 0) {
      staffBonusToBePaid = month.productionCollectionAverage * (staffP / 100);
    }

    if (hygieneP > 0) {
      hygieneBonusToBePaid = month.productionAverage * (hygieneP / 100);
    }

    rootState.employeesAndFunds.funds.forEach((fund) => {
      amount = (fund.fund_type === 'amount')
        ? parseFloat(fund.fund_amount) : parseFloat(staffBonusToBePaid * (fund.fund_amount / 100));
      fundsBonusToBePaid += amount;
    });

    commit('updateBonusesToBePaid', {
      staff: staffBonusToBePaid,
      hygiene: hygieneBonusToBePaid,
      funds: fundsBonusToBePaid < staffBonusToBePaid ? fundsBonusToBePaid : staffBonusToBePaid,
      month: month
    });
  },
  calculateHygienistsSalaryPercentageOfProd: ({ commit }, month) => {
    let value = '-';

    if (!month.productionCollectionAverageDisabled) {
      value = (month.hygienistsSalariesTotal / month.productionAverage) * 100;
    }

    commit('updateHygienistsSalaryPercentageOfProd', { value: value, month: month });
  },
  calculateStaffSalaryPercentageOfPandC: ({ commit }, month) => {
    let value = '-';

    if (!month.productionCollectionAverageDisabled) {
      value = (month.staffSalariesTotal / month.productionCollectionAverage) * 100;
    }

    commit('updateStaffSalaryPercentageOfPandC', { value: value, month: month });
  },
  calculateHoursTotals: ({ commit, rootState, rootGetters }, month) => {
    let staffTotal = 0;
    let hygienistsTotal = 0;

    if (!month.employee_data) {
      return;
    }

    rootGetters['employeesAndFunds/allEmployees'](month).forEach(({monthEmployee, employee}) => {
      if (employee.bp_employee_type === 'hygienist' && rootGetters['initialSetup/hygienePlan']) {
        hygienistsTotal += parseFloat(monthEmployee.hours_worked)
      } else {
        staffTotal += parseFloat(monthEmployee.hours_worked)
      }
    })
    

    commit('updateHoursTotals', { staff: staffTotal, hygienists: hygienistsTotal, month: month })
  },
  calculateSalaryTotals: ({ commit, rootState, rootGetters }, month) => {
    let staffTotal = 0;
    let hygienistsTotal = 0;

    if (!month.employee_data) {
      return;
    }

    rootGetters['employeesAndFunds/allEmployees'](month).forEach(({monthEmployee, employee}) => {
      if (employee.bp_employee_type === 'hygienist' && rootGetters['initialSetup/hygienePlan']) {
        hygienistsTotal += parseFloat(monthEmployee.gross_pay)
      } else {
        staffTotal += parseFloat(monthEmployee.gross_pay)
      }
    })
    
    commit('updateSalaryTotals', { staff: staffTotal, hygienists: hygienistsTotal, month })
  },

  // Bonus calculation amount for each employee is being stored in `amount_received`
  // So to calculate the total salary + bonus, we want to sum these 2 values
  calculateSalaryBonusTotals: ({ commit, rootState, rootGetters }, month) => {
    let staffTotal = 0
    let hygienistsTotal = 0
    const hasHygiene = rootState.initialSetup.hygiene_plan

    if (!month.employee_data) {
      return
    }

    rootGetters['employeesAndFunds/allEmployees'](month).forEach(({monthEmployee, employee}) => {
      if (hasHygiene) {
        if (employee.bp_employee_type === 'hygienist') {
          hygienistsTotal += parseFloat(monthEmployee.gross_pay)
          hygienistsTotal += parseFloat(monthEmployee.amount_received)
        } else {
          staffTotal += parseFloat(monthEmployee.gross_pay)
          staffTotal += parseFloat(monthEmployee.amount_received)
        }
      } else {
        staffTotal += parseFloat(monthEmployee.gross_pay)
        staffTotal += parseFloat(monthEmployee.amount_received)

        hygienistsTotal = 0
      }
    })

    commit('updateSalaryBonusTotals', { staff: staffTotal, hygienists: hygienistsTotal, month })
  },
  calculateFundsBonusAmount: ({ commit, state, rootState }, month) => {
    if (month.finalized) {
      return;
    }
    let grandTotalPaid = 0;
    // ordering funds based on StartMonth/StartYear dates on Fund Object.
    // Since sorting "changes" the state, we need to deep clone the array to set the new order.
    const fundsCopy = cloneDeep(month.funds);
    fundsCopy.sort(function (a, b) {
      const fundA = rootState.employeesAndFunds.funds.find((el) => {
        return el.id === a.fund_id;
      });
      const fundB = rootState.employeesAndFunds.funds.find((el) => {
        return el.id === b.fund_id;
      });
      const diff = moment({ year: fundA.fund_start_year, month: fundA.fund_start_month - 1 })
        .diff(moment({ year: fundB.fund_start_year, month: fundB.fund_start_month - 1 }));

      return isNaN(diff) ? 0 : diff;
    });

    fundsCopy.forEach((fund) => {
      const fundData = rootState.employeesAndFunds.funds.find((el) => {
        return el.id === fund.fund_id
      });
      let amount = 0; // this is this amount to be paid for the current fund.

      if (fundData.fund_type === 'amount') {
        amount = parseFloat(fundData.fund_amount);
      } else {
        amount = parseFloat(month.staffBonusToBePaid * (fundData.fund_amount / 100));
      }

      // if the amount to be paid is greater than the amount available (total to be paid and total already paid)
      // we'll only pay the balance.
      if (amount > parseFloat(month.staffBonusToBePaid - grandTotalPaid)) {
        amount = parseFloat(month.staffBonusToBePaid - grandTotalPaid);
      }

      // Add to the grand total already paid to funds.
      grandTotalPaid += amount;

      commit('updateFundBonusAmount', { month: month, fundId: fund.fund_id, bonusAmount: amount });
    });

    commit('updateGrandTotalPaid', { month: month, bonusAmount: grandTotalPaid });
  },
  calculateEmployeeBonusAmount: ({ commit, state, rootState, rootGetters }, month) => {
    if (!state.activeMonth) {
      return;
    }
    let totalBonus = month.staffBonusToBePaid;

    if (month.funds.length > 0) {
      month.funds.forEach((fund) => {
        totalBonus -= fund.amount_received;
      });

      if (totalBonus < 0) {
        totalBonus = 0;
      }
    }

    rootGetters['employeesAndFunds/allEmployees'](month).forEach(({monthEmployee, employee, index}) => {
      const { percentage } = monthEmployee
      let amount = 0
      

      if (employee.bp_employee_type === 'hygienist' && rootGetters['initialSetup/hygienePlan']) {
        amount = month.hygieneBonusToBePaid > 0 ? month.hygieneBonusToBePaid * (percentage / 100) : 0
      } else {
        amount = totalBonus * (percentage / 100)
      }

      if (amount < 0) {
        amount = 0
      }
      
      commit('updateEmployeeBonusAmount', { month: month, employeeIndex: index, bonusAmount: amount });
    })
  },
  calculateEmployeeBonusPercentage: ({ commit, state, rootState, getters, rootGetters }, month) => {
    if (!state.activeMonth) {
      return
    }

    const equalShareDistType = rootState.initialSetup.distribution_type == 'equal_share'
    const eligibleEmployees = rootGetters['employeesAndFunds/eligibleEmployees'](month)
    const numberOfHygienistsInPlan = equalShareDistType && rootGetters['initialSetup/hygienePlan'] && eligibleEmployees
      .filter(({employee}) => employee.bp_employee_type === 'hygienist')
      .length || 0
    const numberOfStaffInPlan = equalShareDistType && eligibleEmployees
      .filter(({employee}) => employee.bp_employee_type !== (rootGetters['initialSetup/hygienePlan'] ? 'hygienist' : false))
      .length || 0
    const { staffHoursTotal, hygienistsHoursTotal } = getters.employeeHoursTotal(eligibleEmployees)
    const { staffSalariesTotal, hygienistsSalariesTotal } = getters.employeeSalariesTotal(eligibleEmployees)

    rootGetters['employeesAndFunds/ineligibleEmployees'](month).forEach(({ index }) => {
      commit('updateEmployeeBonusPercentage', { month, employeeIndex: index, bonusPercentage: 0 })
    })

    eligibleEmployees.forEach(({monthEmployee, employee, index}) => {
      let percentage = 0

      switch (rootState.initialSetup.distribution_type) {
        case 'equal_share':
          if (employee.bp_employee_type === 'hygienist' && rootGetters['initialSetup/hygienePlan']) {
            percentage = 100 / numberOfHygienistsInPlan
          } else {
            percentage = 100 / numberOfStaffInPlan
          }
          break

        case 'fixed_percentage':
          percentage = employee.bp_bonus_percentage
          break

        case 'salary':
          const grossPay = parseFloat(monthEmployee.gross_pay)

          if (employee.bp_employee_type === 'hygienist' && rootGetters['initialSetup/hygienePlan']) {
            percentage = hygienistsSalariesTotal > 0 ? (grossPay / hygienistsSalariesTotal) * 100 : 0
          } else {
            percentage = staffSalariesTotal > 0 ? (grossPay / staffSalariesTotal) * 100 : 0
          }
          break

        case 'hours':
          const hours = monthEmployee.hours_worked;
          
          if (employee.bp_employee_type === 'hygienist' && rootGetters['initialSetup/hygienePlan']) {
            percentage = (hours / hygienistsHoursTotal) * 100
          } else {
            percentage = (hours / staffHoursTotal) * 100
          }
          break
      }

      commit('updateEmployeeBonusPercentage', { month, employeeIndex: index, bonusPercentage: percentage })
    })
  },
  calculateProductionCollectionAverage: ({ commit, rootState }, index) => {
    const rollingAverage = rootState.initialSetup.rolling_average;
    let prodAverage = 0;
    let colAverage = 0;
    let average = '-';
    const months = rootState.planData.months;
    const isDisabled = months[index].productionCollectionAverageDisabled;

    // if month is enabled, calculate the productionCollectionAverage.
    if (!isDisabled) {
      // loop through all the months *UNTIL* the rolling average and adding the total amounts for
      // production and collection
      for (let k = 0; k < rollingAverage; k++) {
        prodAverage += parseFloat(months[index - k].production_amount);
        colAverage += parseFloat(months[index - k].collection_amount);
      }
      // formula for the average (with the rolling average).
      average = ((prodAverage + colAverage) / rollingAverage) / 2;
    }

    commit('updateField', { prop: 'months.' + index + '.productionCollectionAverage', value: average })
  },
  calculateProductionAverage: ({ commit, rootState }, index) => {
    const rollingAverage = rootState.initialSetup.rolling_average;
    const months = rootState.planData.months;
    const isEnabled = !months[index].productionCollectionAverageDisabled;
    let hygieneProdAverage = 0;
    let average = '-';

    if (isEnabled) {
      for (let k = 0; k < rollingAverage; k++) {
        hygieneProdAverage += parseFloat(months[index - k].hygiene_production_amount);
      }
      average = hygieneProdAverage / rollingAverage;
    }

    commit('updateField', { prop: 'months.' + index + '.productionAverage', value: average })
  },
  updateProductionCollectionAverages: ({ commit, dispatch, rootState }) => {
    const months = rootState.planData.months;
    const rollingAverage = parseFloat(rootState.initialSetup.rolling_average);

    if (months.length > 0) {
      for (let i = 0; i < months.length; i++) {
        const productionCollectionAverageDisabled = i < rollingAverage;
        commit('updateField', {
          prop: 'months.' + i + '.productionCollectionAverageDisabled',
          value: productionCollectionAverageDisabled
        });
      }
      dispatch('runCalculations');
    }
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
};

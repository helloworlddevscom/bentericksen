import BonusProService from "../../services/BonusProService";
import { compareMonths, employeeEligibleForBonus } from '../../utils'
import { monthlyData, fundData, employeeFields } from "../../schemas";

const state = {
  employees: [],
  funds: [],
  activeEmployee: null,
  errors: {
    employees: null,
    funds: null
  }
};

const getters = {
  activeMonthlyData: (state, getters, rootState) => {
    if (!state.activeEmployee) {
      return [];
    }
    return state.activeEmployee.monthlyData
      .filter(mon => {
        if (!rootState.initialSetup.completed) {
          return true;
        }
        const plan_month = rootState.planData.months.find(m => m.id === mon.month_id);
        if (typeof plan_month !== 'undefined') {
          return plan_month.finalized === 1
        }
        return true;
      })
      .sort((mon1, mon2) => compareMonths(mon1, mon2))
      .slice(-6);
  },
  eligibleEmployees: (state) => {
    return (month) => month.employee_data
        .map((employee, index) => ({
          index,
          monthEmployee: employee,
          employee: state.employees.find((el) => el.id === employee.user_id)
        }))
        .filter(({ employee }) => employeeEligibleForBonus(employee, {
          month: month.month,
          year: month.year
        }))
  },
  ineligibleEmployees: (state) => {
    return (month) => month.employee_data
        .map((employee, index) => ({
          index,
          monthEmployee: employee,
          employee: state.employees.find((el) => el.id === employee.user_id)
        }))
        .filter(({ employee }) => !employeeEligibleForBonus(employee, {
          month: month.month,
          year: month.year
        }))
  },
  allEmployees: (state) => {
    return (month) => month.employee_data
        .map((employee, index) => ({
          index,
          monthEmployee: employee,
          employee: state.employees.find((el) => el.id === employee.user_id)
        }))
  }
};

const mutations = {
  setErrors: (state, { type, errors }) => {
    state.errors[type] = errors;
  },
  clearErrors: (state, type) => {
    state.errors[type] = null;
  },
  setEmployees: (state, employees) => {
    state.employees = employees;
  },
  setFunds: (state, funds) => {
    state.funds = funds;
  },
  addFund: (state, fund) => {
    state.funds.push(fund);
  },
  updateFund: (state, fund) => {
    state.funds.map((el, i) => {
      if (el.id === fund.id) {
        state.funds.splice(i, 1, fund);
      }
    });
  },
  removeFund: (state, id) => {
    state.funds.map((el, i) => {
      if (el.id === id) {
        state.funds.splice(i, 1);
      }
    });
  },
  removeEmployee: (state, id) => {
    state.employees.map((el, i) => {
      if (el.id === id) {
        state.employees.splice(i, 1);
      }
    });
  },
  addEmployeeToPlan: (state, employee) => {
    const index = state.employees.findIndex((el) => {
      return el.id === employee.id;
    });

    if (index >= 0) {
      state.employees.splice(index, 1, employee);
    } else {
      state.employees.push(employee);
    }
  },
  updateEmployeeField: (state, { prop, value }) => {
    if (prop.indexOf('.') < 0) {
      state.activeEmployee[prop] = value;
    } else {
      const [attribute, monthIndex, field] = prop.split('.');
      value = parseFloat(value);
      state.activeEmployee[attribute][monthIndex][field] = value;
    }
  },
  setActiveEmployee: (state, employee) => {
    state.activeEmployee = employee;
  },
  updateEmployeeMonthlyDataField: (state, { prop, value, employeeId, monthId }) => {
    const employeeIndex = state.employees.findIndex((el) => {
      return el.id === employeeId;
    });

    const monthIndex = state.employees[employeeIndex].monthlyData.findIndex((el) => {
      return el.month_id === monthId;
    });

    state.employees[employeeIndex].monthlyData[monthIndex][prop] = value;
  },
  addUserMonth: (state, { month, index }) => {
    state.employees[index].monthlyData.push(month);
  },
  updateFundMonthlyData: (state, month) => {
    const fundIndex = state.funds.findIndex((el) => {
      return el.id === month.fund_id;
    });

    const monthIndex = state.funds[fundIndex].monthlyData.findIndex((el) => {
      return month.month_id ? el.month_id === month.month_id : el.month_id === month.id;
    });

    if (monthIndex < 0) {
      state.funds[fundIndex].monthlyData.push(month);
    } else {
      state.funds[fundIndex].monthlyData[monthIndex] = month;
    }
  },
  updateUserMonthlyData: (state, month) => {
    const employeeIndex = state.employees.findIndex((el) => {
      return el.id === month.user_id;
    });

    const monthIndex = state.employees[employeeIndex].monthlyData.findIndex((el) => {
      return month.month_id ? el.month_id === month.month_id : el.month_id === month.id;
    });

    if (monthIndex < 0) {
      state.employees[employeeIndex].monthlyData.push(month);
    } else {
      state.employees[employeeIndex].monthlyData[monthIndex] = month;
    }
  }
};

const actions = {
  saveMonthData: ({ commit, dispatch, rootState }, monthId) => {
    const monthlyData = [];
    const obj = {};
    const fundsData = [];

    rootState.employeesAndFunds.employees.forEach((el) => {
      const month = el.monthlyData.find((m) => {
        return m.month_id === monthId;
      });

      monthlyData.push(month);
    });

    rootState.employeesAndFunds.funds.forEach((el) => {
      const month = el.monthlyData.find((m) => {
        return m.month_id === monthId;
      });

      fundsData.push(month);
    });

    obj.planId = rootState.initialSetup.id;
    obj.monthlyData = monthlyData;
    obj.fundsData = fundsData;
    obj.activeMonthData = {
      monthId: rootState.planData.activeMonth.id,
      hygiene_production_amount: rootState.planData.activeMonth.hygiene_production_amount,
      production_amount: rootState.planData.activeMonth.production_amount,
      collection_amount: rootState.planData.activeMonth.collection_amount,
      production_collection_average: rootState.planData.activeMonth.productionCollectionAverage,
      staff_percentage: rootState.initialSetup.staff_bonus_percentage,
      hygiene_percentage: rootState.initialSetup.hygiene_bonus_percentage
    };

    return new Promise((resolve, reject) => {
      BonusProService.save(obj, 'saveMonthData')
        .then((response) => {
          dispatch('parseMonthlyData', {
            monthlyData: response.monthlyData,
            fundsData: response.fundsData,
            newMonth: response.newMonth
          });
          resolve({ success: true, message: response.message });
        })
        .catch((err) => {
          reject(err.errors);
        })
    });
  },
  // This only gets called when adding a single month after initialization is complete
  parseMonthlyData: ({ commit, state, dispatch }, { monthlyData, fundsData, newMonth }) => {
    dispatch('planData/addNewMonth', newMonth, { root: true });

    monthlyData.forEach((month) => {
      commit('updateUserMonthlyData', month)
    });

    fundsData.forEach((month) => {
      commit('updateFundMonthlyData', month);
    });

    dispatch('setEmployeesMonthlyData');
    dispatch('setFundsMonthlyData');
  },
  clearErrors: ({ commit }, type) => {
    commit('clearErrors', type);
  },
  updateEmployeeField: ({ commit }, { prop, value }) => {
    commit('updateEmployeeField', { prop, value });
  },
  updateEmployeeMonthlyData: ({ commit, dispatch }, { prop, value, employeeId, monthId }) => {
    commit('updateEmployeeMonthlyDataField', { prop, value, employeeId, monthId });
    dispatch('planData/runCalculations', null, { root: true });
  },
  createEmployee: ({ state, commit, dispatch, rootState }) => {
    const employee = Object.assign({}, rootState.employeesAndFunds.activeEmployee);

    for (const prop in employee) {
      if (employee.hasOwnProperty(prop) && (employee[prop] === null || employee[prop] === '')) {
        delete employee[prop];
      }
    }

    employee.plan_id = rootState.initialSetup.id;
    employee.distribution_type = rootState.initialSetup.distribution_type;

    commit('clearErrors', 'employees');

    return new Promise((resolve, reject) => {
      BonusProService.save(employee, 'createEmployee')
        .then((data) => {
          commit('addEmployeeToPlan', data.employeeData);
          dispatch('global/addUserToBusiness', data.employeeData, { root: true });
          dispatch('setEmployeesMonthlyData');
          dispatch('setEmployees', state.employees)
          resolve({ success: true });
        })
        .catch((err) => {
          commit('setErrors', { type: 'employees', errors: err.errors });
          reject({ success: false });
        })
    });
  },
  addExistingEmployeeToPlan: ({ state, commit, dispatch, rootState }) => {
    const employee = Object.assign({}, rootState.employeesAndFunds.activeEmployee);

    for (const prop in employee) {
      if (employee.hasOwnProperty(prop) && (employee[prop] === null || employee[prop] === '')) {
        delete employee[prop];
      }
    }

    employee.plan_id = rootState.initialSetup.id;
    employee.distribution_type = rootState.initialSetup.distribution_type;

    commit('clearErrors', 'employees');

    return new Promise((resolve, reject) => {
      BonusProService.save(employee, 'addEmployee')
        .then(() => {
          commit('addEmployeeToPlan', employee);
          dispatch('setEmployeesMonthlyData');
          resolve({ success: true });
        })
        .catch((err) => {
          commit('setErrors', { type: 'employees', errors: err.errors });
          reject({ success: false });
        })
    });
  },
  removeEmployee: ({ commit, dispatch, rootState }, id) => {
    return new Promise((resolve, reject) => {
      BonusProService.delete({ id: id, plan_id: rootState.initialSetup.id }, 'removeEmployee')
        .then(() => {
          commit('removeEmployee', id);
          dispatch('setEmployeesMonthlyData');
          resolve({ success: true });
        })
        .catch((err) => {
          commit('setErrors', { type: 'employees', errors: err.errors });
          reject({ success: false });
        })
    });
  },
  removeFund: ({ commit, dispatch }, id) => {
    return new Promise((resolve, reject) => {
      BonusProService.delete({ id: id }, 'removeFund')
        .then((data) => {
          commit('removeFund', data.fund_id);
          dispatch('setFundsMonthlyData');
          resolve({ success: true });
        })
        .catch((err) => {
          reject(err.errors);
        })
    });
  },
  updateFund: ({ commit, dispatch, state, rootState }, updateFundData) => {
    updateFundData.plan_id = rootState.initialSetup.id;
    commit('clearErrors', 'funds');
    return new Promise((resolve, reject) => {
      BonusProService.save(updateFundData, 'updateFund')
        .then((data) => {
          const fund = data.fund;
          fund.monthlyData = [];
          rootState.planData.months.forEach((el) => {
            const data = el.funds.find((fnd) => {
              return fnd.fund_id === fund.id;
            });
            const amount = (fund.fund_type === 'amount') ? parseFloat(fund.fund_amount) : 0;
            fund.monthlyData.push(data || fundData(fund.id, el.id, amount));
          });
          commit('updateFund', fund);
          resolve({ success: true });
        })
        .catch((err) => {
          commit('setErrors', { type: 'funds', errors: err.errors });
          reject({ success: false });
        })
    });
  },
  addFund: ({ commit, dispatch, state, rootState }, newFundData) => {
    newFundData.plan_id = rootState.initialSetup.id;
    commit('clearErrors', 'funds');
    return new Promise((resolve, reject) => {
      BonusProService.save(newFundData, 'addFund')
        .then((data) => {
          const fund = data.fund;
          fund.monthlyData = [];
          rootState.planData.months.forEach((el) => {
            fund.monthlyData.push(fundData(fund.id, el.id, 0));
          });
          commit('addFund', fund);
          dispatch('setFundsMonthlyData');
          resolve({ success: true });
        })
        .catch((err) => {
          commit('setErrors', { type: 'funds', errors: err.errors });
          reject({ success: false });
        })
    });
  },
  setFunds: ({ commit, rootState }, funds) => {
    const months = rootState.planData.months;
    funds.map((fund) => {
      fund.monthlyData = [];
      months.forEach((el) => {
        const data = el.funds.find((fnd) => {
          return fnd.fund_id === fund.id;
        });
        const amount = (fund.fund_type === 'amount') ? parseFloat(fund.fund_amount) : 0;
        fund.monthlyData.push(data || fundData(fund.id, el.id, amount));
      });
    });
    commit('setFunds', funds);
  },
  // Need to dispatch a different mutation here to set the initial state on the plan
  setEmployeesMonthlyData: ({ state, commit, rootState, dispatch }) => {
    if (rootState.ui.currentStep === 'employees') {
      // initialization
      const months = rootState.planData.months;
      months.forEach((month) => {
        const employeeMonthly = [];
        state.employees.map((employee) => {
          const empData = employee.monthlyData.find((el) => {
            return el.month_id === month.id
          });
          employeeMonthly.push(empData);
        });
        dispatch('planData/setEmployeeMonthlyData', { month: month, monthlyData: employeeMonthly }, { root: true });
      });
      dispatch('planData/runCalculations', null, { root: true });
    } else {
      const month = rootState.planData.months[rootState.planData.months.length - 1];
      const employeeMonthly = [];
      state.employees.map((employee) => {
        const empData = employee.monthlyData.find((el) => {
          return el.month_id === month.id
        });
        employeeMonthly.push(empData);
      });
      dispatch('planData/setEmployeeMonthlyData', { month: month, monthlyData: employeeMonthly }, { root: true });
    }
  },
  setFundsMonthlyData: ({ commit, rootState, dispatch }) => {
    const month = rootState.planData.months[rootState.planData.months.length - 1];
    const fundsMonthly = [];
    rootState.employeesAndFunds.funds.map((funds) => {
      const empData = funds.monthlyData.find((el) => {
        return el.month_id === month.id
      });
      fundsMonthly.push(empData);
    });
    dispatch('planData/setFundsMonthlyData', { month: month, monthlyData: fundsMonthly }, { root: true });
  },
  setEmployees: ({ commit, rootState }, employees) => {
    const months = rootState.planData.months;
    employees.map((employee) => {
      employee.monthlyData = [];
      months.forEach((el) => {
        const data = el.employee_data.find((emp) => {
          return emp.user_id === employee.id;
        });

        if (data) {
          data.month = el.month;
          data.year = el.year;
          employee.monthlyData.push(data);
        } else {
          employee.monthlyData.push(monthlyData(employee.id, el.id, el.month, el.year));
        }
      });
    });

    commit('setEmployees', employees);
  },
  setActiveEmployee: ({ commit, rootState }, id) => {
    let employee = null;
    const months = rootState.planData.months;

    if (id) {
      employee = rootState.employeesAndFunds.employees.find((el) => {
        return el.id === id;
      });

      if (!employee) {
        employee = rootState.global.businessUsers.find((el) => {
          return el.id === id;
        });

        // re-assigning the business employees to a new object
        employee = Object.assign({}, employee);
      }

      if (!employee.monthlyData) {
        employee.monthlyData = [];
        months.forEach((el) => {
          employee.monthlyData.push(monthlyData(employee.id, el.id, el.month, el.year));
        });
      }
    } else {
      employee = employeeFields();
      employee.business_id = rootState.global.businessId;
      months.forEach((el) => {
        employee.monthlyData.push(monthlyData(employee.id, el.id, el.month, el.year));
      });
    }
    commit('setActiveEmployee', employee);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
};

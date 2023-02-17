import moment from "moment/moment";

export function compareMonths (mon1, mon2) {
    // moment uses zero-based month indexes (0-11), but the DB contains 1-based
    // (1-12) so we need to subtract 1 from the month number.
  const diff = moment({ year: mon1.year, month: mon1.month - 1 })
        .diff(moment({ year: mon2.year, month: mon2.month - 1 }));
  return isNaN(diff) ? 0 : diff;
}

/**
 * Determines if a month is within the six months to display on the plan data
 * tables
 * @param {object} plan
 *   The plan object, e.g., rootScope.initialSetup
 * @param {object} month
 *   The month data object from the current month (one of the elements of
 *     rootScope.planData.months)
 * @returns {boolean}
 */
export function isInLastSixMonths (plan, month) {
  // TODO: the logic here is based on the legacy BonusPro app, which would
  // show the 6 months immediately before the plan start. We might need
  // to redo this logic if we change that behavior - KD

  // moment uses zero-based month indexes (0-11), but the DB contains 1-based
  // (1-12) so we need to subtract one from the month number.
  const plan_month = { year: plan.start_year, month: plan.start_month - 1 };
  const compare_month = { year: month.year, month: month.month - 1 };

  // ending month is the month before the plan start.
  const end_month = moment(plan_month).subtract(1, 'months');
  // starting month is the sixth month before the plan start.
  const start_month = moment(plan_month).subtract(6, 'months');
  const compare_date = moment(compare_month);
  return (compare_date >= start_month && compare_date <= end_month);
}

/**
 * Determines if a given month is visible on the tables
 */
export function isVisibleMonth (planData, mon) {
  const plan_months = planData.activeMonths();
  const visible_month = plan_months.filter(month => {
    return month.year === mon.year && month.month === mon.month;
  });
  return visible_month.length > 0;
}

/**
 * Determine if an employee is eligibile for a bonus based on eligibility date set, as an abbreviated date string (ex. '10/2033') and bp_eligible flag
 * @param {string, number} employee
 * @param {number, number} month
 * @returns boolean
 */
export function employeeEligibleForBonus({ bp_eligibility_date, bp_eligible }, { month, year }) {
  
  if (!bp_eligibility_date || !bp_eligible) {
    console.log(bp_eligibility_date, bp_eligible)
    return false
  }

  const eligibleDate = new Date(...bp_eligibility_date.split('/').map((n, i) => !i ? +n - 1 : +n).reverse(), 1)
  return (new Date(year, month - 1, 1) >= eligibleDate) && bp_eligible == 1
}
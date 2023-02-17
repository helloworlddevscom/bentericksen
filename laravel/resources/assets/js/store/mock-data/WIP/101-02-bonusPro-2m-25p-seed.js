import ui from "../modules/ui";
import global from "../modules/global";
import initialSetup from "../modules/initialSetup";
import planData from "../modules/planData";
import employeesAndFunds from "../modules/employeesAndFunds";
import bonusPercentage from "../modules/bonusPercentage";

const planParams = {
  plan_name: "2m-25p-hours",
  rolling_average: 2,
  distribution_type: "hours"
}

// NOTE:  Initial column only used during validation BEFORE entering monthly data.
const templateParams = {
  production_amount: ["67000.00", "54000.00", "72000.00", "68000.00", "69000.00", "75000.00", "72000.00"],
  collection_amount: ["65000.00", "54000.00", "56000.00", "74500.00", "70000.00", "65000.00", "77500.00"],
  production_collection_average: ["-", "59000.00", "67625.00", "70375.00", "69750.00", "72375.00"],
  staffSalaryBonusTotal: ["-", "13400", "13000", "12925", "13000", "18094"],
  staffSalaryPercentageOfPandC: ["-", ".227", ".192", ".184", ".186", ".187"],
  //
  hygiene_production_amount: ["28000.00", "28000.00", "28000.00", "28000.00", "29500.00", "30279.00", "30279.00"]
}

// These match in order as in the employeesAndFunds.state.employees object order
const employeeParams = {
  hours_worked: ["147.00", "147.00", "147.00", "145.00"],
  gross_pay: ["3600.00", "1800.00", "2600.00", "5500.00"]
}

function genRandom () {
  const result = Math.random() * 100000;
  return result.toFixed(0);
}

const randomNum = genRandom();

const activeMonth = {
  id: 241,
  month: 7,
  year: 2021,
  finalized: 0,
  fundAmountPaid: 0,
  staffHoursTotal: 1000,
  collectionRatio: 110.00000000000001,
  collection_amount: 33000,
  production_amount: 30000,
  productionAverage: 0,
  staffSalariesTotal: 1000,
  staffBonusToBePaid: 4075,
  fundsBonusToBePaid: 0,
  hygienistsHoursTotal: 0,
  hygieneBonusToBePaid: 0,
  staffSalaryBonusTotal: 0,
  hygienistsSalariesTotal: 0,
  hygiene_production_amount: "0.00",
  hygienistsSalaryBonusTotal: 0,
  productionCollectionAverage: 50750,
  staffSalaryPercentageOfPandC: 0,
  hygienistsSalaryPercentageOfProd: "__vue_devtool_nan__",
  productionCollectionAverageDisabled: false,
  employee_data: "REFERENCE TO employeesAndFundsState",
  funds: [],
  plan_id: 38,
  production_collection_average: "35000.00",
  staff_percentage: null,
  hygiene_percentage: null,
  created_at: "2021-07-28T21:03:35.000000Z",
  updated_at: "2021-07-29T23:49:30.000000Z"
};

const initialSetupState = {
  id: 38,
  plan_id: "02",
  plan_name: planParams.plan_name,
  business_id: 2360,
  created_by: 6468,
  password_set: 1,
  password: "__vue_devtool_undefined__",
  password_confirmation: "__vue_devtool_undefined__",
  start_month: 7,
  start_year: 2021,
  hygiene_plan: 0,
  separate_fund: 0,
  use_business_address: 1,
  type_of_practice: "general",
  rolling_average: planParams.rolling_average,
  distribution_type: planParams.distribution_type,
  current_step: "setBonusPercentage",
  address: {
    address1: "Technology Street",
    address2: "Apt 11",
    city: "Washington",
    zip: "20001",
    phone: "1112223344"
  },
  staff_bonus_percentage: "10.00",
  hygiene_bonus_percentage: "35.00",
  status: null,
  draft: 0,
  completed: 1,
  created_at: "2021-07-28T20:55:22.000000Z",
  updated_at: "2021-07-28T21:03:35.000000Z",
  last_login: "2021-08-02 07:49:10",
  remember_token: null,
  reset_by: null,
  funds: []
};

const planDataState = {
  // activeMonth,
  "months":
  [
    {
      "id": 21,
      "month": 7,
      "year": 2021,
      "finalized": 1,
      "fundAmountPaid": 0,
      "staffHoursTotal": 722,
      "collectionRatio": 97.01492537313433,
      "collection_amount": templateParams.collection_amount[0],
      "production_amount": templateParams.production_amount[0],
      "productionAverage": "-",
      "staffSalariesTotal": 18800,
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 0,
      "staffSalaryBonusTotal": 0,
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": templateParams.hygiene_production_amount[0],
      "hygienistsSalaryBonusTotal": 11000,
      "productionCollectionAverage": "-",
      "staffSalaryPercentageOfPandC": "not used",
      "hygienistsSalaryPercentageOfProd": "-",
      "productionCollectionAverageDisabled": true,
      "employee_data": "REFERENCE TO employeesAndFundsState",
      "funds":
          [],
      "plan_id": 4,
      "production_collection_average": "not used",
      "staff_percentage": "25.00",
      "hygiene_percentage": "35.00",
      "created_at": "2022-05-17T16:48:27.000000Z",
      "updated_at": "2022-05-17T16:56:16.000000Z"
    },
    {
      "id": 22,
      "month": 8,
      "year": 2021,
      "finalized": 1,
      "fundAmountPaid": 0,
      "staffHoursTotal": 755,
      "collectionRatio": 100,
      "collection_amount": templateParams.collection_amount[1],
      "production_amount": templateParams.production_amount[1],
      "productionAverage": "-",
      "staffSalariesTotal": "WHERE IS THIS UPDATED",
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 10412.5,
      "staffSalaryBonusTotal": templateParams.staffSalaryBonusTotal[0],
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": templateParams.hygiene_production_amount[1],
      "hygienistsSalaryBonusTotal": 11200,
      "productionCollectionAverage": "-",
      "staffSalaryPercentageOfPandC": templateParams.staffSalaryPercentageOfPandC[0],
      "hygienistsSalaryPercentageOfProd": "-",
      "productionCollectionAverageDisabled": true,
      "employee_data": "REFERENCE TO employeesAndFundsState",
      "funds":
          [],
      "plan_id": 4,
      "production_collection_average": templateParams.production_collection_average[0],
      "staff_percentage": "25.00",
      "hygiene_percentage": "35.00",
      "created_at": "2022-05-17T16:48:27.000000Z",
      "updated_at": "2022-05-17T16:56:16.000000Z"
    },
    {
      "id": 23,
      "month": 9,
      "year": 2021,
      "finalized": 1,
      "fundAmountPaid": 0,
      "staffHoursTotal": 733,
      "collectionRatio": 77.77777777777779,
      "collection_amount": templateParams.collection_amount[2],
      "production_amount": templateParams.production_amount[2],
      "productionAverage": 28250,
      "staffSalariesTotal": "WHERE IS THIS UPDATED",
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 10412.5,
      "staffSalaryBonusTotal": templateParams.staffSalaryBonusTotal[1],
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": templateParams.hygiene_production_amount[2],
      "hygienistsSalaryBonusTotal": 10800,
      "productionCollectionAverage": 59000,
      "staffSalaryPercentageOfPandC": templateParams.staffSalaryPercentageOfPandC[1],
      "hygienistsSalaryPercentageOfProd": 0,
      "productionCollectionAverageDisabled": false,
      "employee_data": "REFERENCE TO employeesAndFundsState",
      "funds":
          [],
      "plan_id": 4,
      "production_collection_average": templateParams.production_collection_average[1],
      "staff_percentage": "25.00",
      "hygiene_percentage": "35.00",
      "created_at": "2022-05-17T16:48:27.000000Z",
      "updated_at": "2022-05-17T16:56:16.000000Z"
    },
    {
      "id": 24,
      "month": 10,
      "year": 2021,
      "finalized": 1,
      "fundAmountPaid": 0,
      "staffHoursTotal": 713,
      "collectionRatio": 109.55882352941177,
      "collection_amount": templateParams.collection_amount[3],
      "production_amount": templateParams.production_amount[3],
      "productionAverage": 29750,
      "staffSalariesTotal": "WHERE IS THIS UPDATED",
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 10412.5,
      "staffSalaryBonusTotal": templateParams.staffSalaryBonusTotal[2],
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": templateParams.hygiene_production_amount[3],
      "hygienistsSalaryBonusTotal": 11000,
      "productionCollectionAverage": 67625,
      "staffSalaryPercentageOfPandC": templateParams.staffSalaryPercentageOfPandC[2],
      "hygienistsSalaryPercentageOfProd": 0,
      "productionCollectionAverageDisabled": false,
      "employee_data": "REFERENCE TO employeesAndFundsState",
      "funds":
          [],
      "plan_id": 4,
      "production_collection_average": templateParams.production_collection_average[2],
      "staff_percentage": "25.00",
      "hygiene_percentage": "35.00",
      "created_at": "2022-05-17T16:48:27.000000Z",
      "updated_at": "2022-05-17T16:56:16.000000Z"
    },
    {
      "id": 25,
      "month": 11,
      "year": 2021,
      "finalized": 1,
      "fundAmountPaid": 0,
      "staffHoursTotal": 716,
      "collectionRatio": 101.44927536231884,
      "collection_amount": templateParams.collection_amount[4],
      "production_amount": templateParams.production_amount[4],
      "productionAverage": 30750,
      "staffSalariesTotal": "WHERE IS THIS UPDATED",
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 10412.5,
      "staffSalaryBonusTotal": templateParams.staffSalaryBonusTotal[3],
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": templateParams.hygiene_production_amount[4],
      "hygienistsSalaryBonusTotal": 11200,
      "productionCollectionAverage": 70375,
      "staffSalaryPercentageOfPandC": templateParams.staffSalaryPercentageOfPandC[3],
      "hygienistsSalaryPercentageOfProd": 0,
      "productionCollectionAverageDisabled": false,
      "employee_data": "REFERENCE TO employeesAndFundsState",
      "funds":
          [],
      "plan_id": 4,
      "production_collection_average": templateParams.production_collection_average[3],
      "staff_percentage": "25.00",
      "hygiene_percentage": "35.00",
      "created_at": "2022-05-17T16:48:27.000000Z",
      "updated_at": "2022-05-17T16:56:16.000000Z"
    },
    {
      "id": 26,
      "month": 12,
      "year": 2021,
      "finalized": 1,
      "fundAmountPaid": 0,
      "staffHoursTotal": 718,
      "collectionRatio": 86.66666666666667,
      "collection_amount": templateParams.collection_amount[5],
      "production_amount": templateParams.production_amount[5],
      "productionAverage": 29889.5,
      "staffSalariesTotal": "WHERE IS THIS UPDATED",
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 10412.5,
      "staffSalaryBonusTotal": templateParams.staffSalaryBonusTotal[4],
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": templateParams.hygiene_production_amount[5],
      "hygienistsSalaryBonusTotal": 10800,
      "productionCollectionAverage": 69750,
      "staffSalaryPercentageOfPandC": templateParams.staffSalaryPercentageOfPandC[4],
      "hygienistsSalaryPercentageOfProd": 0,
      "productionCollectionAverageDisabled": false,
      "employee_data": "REFERENCE TO employeesAndFundsState",
      "funds":
          [],
      "plan_id": 4,
      "production_collection_average": templateParams.production_collection_average[4],
      "staff_percentage": "25.00",
      "hygiene_percentage": "35.00",
      "created_at": "2022-05-17T16:48:27.000000Z",
      "updated_at": "2022-05-17T16:56:16.000000Z"
    },
    {
      "id": 91,
      "month": 7,
      "year": 2022,
      "finalized": 1,
      "fundAmountPaid": 0,
      "staffHoursTotal": 586,
      "collectionRatio": 107.63888888888889,
      "collection_amount": templateParams.collection_amount[6],
      "production_amount": templateParams.production_amount[6],
      "productionAverage": 29889.5,
      "staffSalariesTotal": "WHERE IS THIS UPDATED",
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 10412.5,
      "staffSalaryBonusTotal": templateParams.staffSalaryBonusTotal[5],
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": templateParams.hygiene_production_amount[6],
      "hygienistsSalaryBonusTotal": 10800,
      "productionCollectionAverage": 69750,
      "staffSalaryPercentageOfPandC": templateParams.staffSalaryPercentageOfPandC[5],
      "hygienistsSalaryPercentageOfProd": 0,
      "productionCollectionAverageDisabled": false,
      "employee_data": "REFERENCE TO employeesAndFundsState",
      "funds":
        [],
      "plan_id": 13,
      "production_collection_average": templateParams.production_collection_average[5],
      "staff_percentage": "10.00",
      "hygiene_percentage": "10.00",
      "created_at": "2022-07-05T22:37:13.000000Z",
      "updated_at": "2022-07-05T22:41:23.000000Z"
    }
  ],
  "activeMonth": null,
  "plan_id": 1
};
const employeesAndFundsState = {
  employees:
  [
    {
      "id": 179,
      "business_id": 15,
      "classification_id": null,
      "email": `jeff+brown-Asst+${randomNum}@helloworlddevs.com`,
      "last_login": null,
      "hired": null,
      "rehired": null,
      "job_title_id": null,
      "dob": null,
      "age": null,
      "status": "enabled",
      "terminated": null,
      "can_rehire": null,
      "benefit_date": null,
      "years_of_service": 0,
      "benefit_years_of_service": 0,
      "on_leave": null,
      "first_name": "Sonya",
      "middle_name": null,
      "last_name": "Brown-Asst",
      "suffix": null,
      "prefix": null,
      "address1": null,
      "address2": null,
      "city": null,
      "state": null,
      "postal_code": null,
      "phone1": null,
      "phone1_type": "home",
      "phone2": null,
      "phone2_type": null,
      "included_in_employee_count": 1,
      "can_access_system": 1,
      "receives_benefits": 1,
      "employee_wizard": 0,
      "created_at": "2022-07-05T22:36:09.000000Z",
      "updated_at": "2022-07-05T22:36:09.000000Z",
      "employee_status": null,
      "position_title": null,
      "bp_eligibility_date": "07/2022",
      "bp_bonus_percentage": null,
      "bp_employee_type": "admin/assistant",
      "bp_eligible": 1,
      "main_role": "employee",
      "pivot":
      {
        "plan_id": 13,
        "user_id": 179
      },
      "roles":
      [
        {
          "id": 5,
          "name": "employee",
          "display_name": "Employee",
          "description": null,
          "created_at": null,
          "updated_at": null,
          "pivot":
          {
            "user_id": 179,
            "role_id": 5
          }
        }
      ],
      "monthlyData":
      [
        {
          "id": 421,
          "month_id": 85,
          "user_id": 179,
          "hours_worked": "145.00",
          "gross_pay": "2500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:09.000000Z",
          "updated_at": "2022-07-05T22:36:09.000000Z",
          "month": 1,
          "year": 2022
        },
        {
          "id": 422,
          "month_id": 86,
          "user_id": 179,
          "hours_worked": "152.00",
          "gross_pay": "2700.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:09.000000Z",
          "updated_at": "2022-07-05T22:36:09.000000Z",
          "month": 2,
          "year": 2022
        },
        {
          "id": 423,
          "month_id": 87,
          "user_id": 179,
          "hours_worked": "147.00",
          "gross_pay": "2600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:09.000000Z",
          "updated_at": "2022-07-05T22:36:09.000000Z",
          "month": 3,
          "year": 2022
        },
        {
          "id": 424,
          "month_id": 88,
          "user_id": 179,
          "hours_worked": "142.00",
          "gross_pay": "2300.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:09.000000Z",
          "updated_at": "2022-07-05T22:36:09.000000Z",
          "month": 4,
          "year": 2022
        },
        {
          "id": 425,
          "month_id": 89,
          "user_id": 179,
          "hours_worked": "139.00",
          "gross_pay": "2100.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:09.000000Z",
          "updated_at": "2022-07-05T22:36:09.000000Z",
          "month": 5,
          "year": 2022
        },
        {
          "id": 426,
          "month_id": 90,
          "user_id": 179,
          "hours_worked": "142.00",
          "gross_pay": "2300.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:09.000000Z",
          "updated_at": "2022-07-05T22:36:09.000000Z",
          "month": 6,
          "year": 2022
        },
        {
          "id": 451,
          "month_id": 91,
          "user_id": 179,
          "hours_worked": employeeParams.hours_worked[0],
          "gross_pay": employeeParams.gross_pay[0],
          "amount_received": "0.00",
          "percentage": "25.09",
          "created_at": "2022-07-05T22:37:13.000000Z",
          "updated_at": "2022-07-05T22:41:23.000000Z",
          "month": 7,
          "year": 2022
        }
      ]
    },
    {
      "id": 180,
      "business_id": 15,
      "classification_id": null,
      "email": `jeff+jones-asst+${randomNum}@helloworlddevs.com`,
      "last_login": null,
      "hired": null,
      "rehired": null,
      "job_title_id": null,
      "dob": null,
      "age": null,
      "status": "enabled",
      "terminated": null,
      "can_rehire": null,
      "benefit_date": null,
      "years_of_service": 0,
      "benefit_years_of_service": 0,
      "on_leave": null,
      "first_name": "Tom",
      "middle_name": null,
      "last_name": "Jones-Asst",
      "suffix": null,
      "prefix": null,
      "address1": null,
      "address2": null,
      "city": null,
      "state": null,
      "postal_code": null,
      "phone1": null,
      "phone1_type": "home",
      "phone2": null,
      "phone2_type": null,
      "included_in_employee_count": 1,
      "can_access_system": 1,
      "receives_benefits": 1,
      "employee_wizard": 0,
      "created_at": "2022-07-05T22:36:23.000000Z",
      "updated_at": "2022-07-05T22:36:23.000000Z",
      "employee_status": null,
      "position_title": null,
      "bp_eligibility_date": "07/2022",
      "bp_bonus_percentage": null,
      "bp_employee_type": "admin/assistant",
      "bp_eligible": 1,
      "main_role": "employee",
      "pivot":
      {
        "plan_id": 13,
        "user_id": 180
      },
      "roles":
      [
        {
          "id": 5,
          "name": "employee",
          "display_name": "Employee",
          "description": null,
          "created_at": null,
          "updated_at": null,
          "pivot":
          {
            "user_id": 180,
            "role_id": 5
          }
        }
      ],
      "monthlyData":
      [
        {
          "id": 427,
          "month_id": 85,
          "user_id": 180,
          "hours_worked": "142.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:23.000000Z",
          "updated_at": "2022-07-05T22:36:23.000000Z",
          "month": 1,
          "year": 2022
        },
        {
          "id": 428,
          "month_id": 86,
          "user_id": 180,
          "hours_worked": "147.00",
          "gross_pay": "1850.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:23.000000Z",
          "updated_at": "2022-07-05T22:36:23.000000Z",
          "month": 2,
          "year": 2022
        },
        {
          "id": 429,
          "month_id": 87,
          "user_id": 180,
          "hours_worked": "145.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:23.000000Z",
          "updated_at": "2022-07-05T22:36:23.000000Z",
          "month": 3,
          "year": 2022
        },
        {
          "id": 430,
          "month_id": 88,
          "user_id": 180,
          "hours_worked": "142.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:23.000000Z",
          "updated_at": "2022-07-05T22:36:23.000000Z",
          "month": 4,
          "year": 2022
        },
        {
          "id": 431,
          "month_id": 89,
          "user_id": 180,
          "hours_worked": "147.00",
          "gross_pay": "1850.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:23.000000Z",
          "updated_at": "2022-07-05T22:36:23.000000Z",
          "month": 5,
          "year": 2022
        },
        {
          "id": 432,
          "month_id": 90,
          "user_id": 180,
          "hours_worked": "145.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:23.000000Z",
          "updated_at": "2022-07-05T22:36:23.000000Z",
          "month": 6,
          "year": 2022
        },
        {
          "id": 452,
          "month_id": 91,
          "user_id": 180,
          "hours_worked": employeeParams.hours_worked[1],
          "gross_pay": employeeParams.gross_pay[1],
          "amount_received": "0.00",
          "percentage": "25.09",
          "created_at": "2022-07-05T22:37:13.000000Z",
          "updated_at": "2022-07-05T22:41:23.000000Z",
          "month": 7,
          "year": 2022
        }
      ]
    },
    {
      "id": 182,
      "business_id": 15,
      "classification_id": null,
      "email": `jeff+smith-admin+${randomNum}@helloworlddevs.com`,
      "last_login": null,
      "hired": null,
      "rehired": null,
      "job_title_id": null,
      "dob": null,
      "age": null,
      "status": "enabled",
      "terminated": null,
      "can_rehire": null,
      "benefit_date": null,
      "years_of_service": 0,
      "benefit_years_of_service": 0,
      "on_leave": null,
      "first_name": "Mary",
      "middle_name": null,
      "last_name": "Smith-Admin",
      "suffix": null,
      "prefix": null,
      "address1": null,
      "address2": null,
      "city": null,
      "state": null,
      "postal_code": null,
      "phone1": null,
      "phone1_type": "home",
      "phone2": null,
      "phone2_type": null,
      "included_in_employee_count": 1,
      "can_access_system": 1,
      "receives_benefits": 1,
      "employee_wizard": 0,
      "created_at": "2022-07-05T22:36:50.000000Z",
      "updated_at": "2022-07-05T22:36:50.000000Z",
      "employee_status": null,
      "position_title": null,
      "bp_eligibility_date": "07/2022",
      "bp_bonus_percentage": null,
      "bp_employee_type": "hygienist",
      "bp_eligible": 1,
      "main_role": "employee",
      "pivot":
      {
        "plan_id": 13,
        "user_id": 182
      },
      "roles":
      [
        {
          "id": 5,
          "name": "employee",
          "display_name": "Employee",
          "description": null,
          "created_at": null,
          "updated_at": null,
          "pivot":
          {
            "user_id": 182,
            "role_id": 5
          }
        }
      ],
      "monthlyData":
      [
        {
          "id": 439,
          "month_id": 85,
          "user_id": 182,
          "hours_worked": "145.00",
          "gross_pay": "3500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:50.000000Z",
          "updated_at": "2022-07-05T22:36:50.000000Z",
          "month": 1,
          "year": 2022
        },
        {
          "id": 440,
          "month_id": 86,
          "user_id": 182,
          "hours_worked": "152.00",
          "gross_pay": "3750.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:50.000000Z",
          "updated_at": "2022-07-05T22:36:50.000000Z",
          "month": 2,
          "year": 2022
        },
        {
          "id": 441,
          "month_id": 87,
          "user_id": 182,
          "hours_worked": "147.00",
          "gross_pay": "3600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:50.000000Z",
          "updated_at": "2022-07-05T22:36:50.000000Z",
          "month": 3,
          "year": 2022
        },
        {
          "id": 442,
          "month_id": 88,
          "user_id": 182,
          "hours_worked": "142.00",
          "gross_pay": "3400.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:50.000000Z",
          "updated_at": "2022-07-05T22:36:50.000000Z",
          "month": 4,
          "year": 2022
        },
        {
          "id": 443,
          "month_id": 89,
          "user_id": 182,
          "hours_worked": "139.00",
          "gross_pay": "3375.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:50.000000Z",
          "updated_at": "2022-07-05T22:36:50.000000Z",
          "month": 5,
          "year": 2022
        },
        {
          "id": 444,
          "month_id": 90,
          "user_id": 182,
          "hours_worked": "142.00",
          "gross_pay": "3500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:36:50.000000Z",
          "updated_at": "2022-07-05T22:36:50.000000Z",
          "month": 6,
          "year": 2022
        },
        {
          "id": 454,
          "month_id": 91,
          "user_id": 182,
          "hours_worked": employeeParams.hours_worked[2],
          "gross_pay": employeeParams.gross_pay[2],
          "amount_received": "0.00",
          "percentage": "25.09",
          "created_at": "2022-07-05T22:37:13.000000Z",
          "updated_at": "2022-07-05T22:41:23.000000Z",
          "month": 7,
          "year": 2022
        }
      ]
    },
    {
      "id": 183,
      "business_id": 15,
      "classification_id": null,
      "email": `jeff+turner_hyg+${randomNum}@helloworlddevs.com`,
      "last_login": null,
      "hired": null,
      "rehired": null,
      "job_title_id": null,
      "dob": null,
      "age": null,
      "status": "enabled",
      "terminated": null,
      "can_rehire": null,
      "benefit_date": null,
      "years_of_service": 0,
      "benefit_years_of_service": 0,
      "on_leave": null,
      "first_name": "Tina",
      "middle_name": null,
      "last_name": "Turner-Hyg",
      "suffix": null,
      "prefix": null,
      "address1": null,
      "address2": null,
      "city": null,
      "state": null,
      "postal_code": null,
      "phone1": null,
      "phone1_type": "home",
      "phone2": null,
      "phone2_type": null,
      "included_in_employee_count": 1,
      "can_access_system": 1,
      "receives_benefits": 1,
      "employee_wizard": 0,
      "created_at": "2022-07-05T22:37:02.000000Z",
      "updated_at": "2022-07-05T22:37:02.000000Z",
      "employee_status": null,
      "position_title": null,
      "bp_eligibility_date": "07/2022",
      "bp_bonus_percentage": null,
      "bp_employee_type": "hygienist",
      "bp_eligible": 1,
      "main_role": "employee",
      "pivot":
      {
        "plan_id": 13,
        "user_id": 183
      },
      "roles":
      [
        {
          "id": 5,
          "name": "employee",
          "display_name": "Employee",
          "description": null,
          "created_at": null,
          "updated_at": null,
          "pivot":
          {
            "user_id": 183,
            "role_id": 5
          }
        }
      ],
      "monthlyData":
      [
        {
          "id": 445,
          "month_id": 85,
          "user_id": 183,
          "hours_worked": "145.00",
          "gross_pay": "5500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:37:02.000000Z",
          "updated_at": "2022-07-05T22:37:02.000000Z",
          "month": 1,
          "year": 2022
        },
        {
          "id": 446,
          "month_id": 86,
          "user_id": 183,
          "hours_worked": "152.00",
          "gross_pay": "5600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:37:02.000000Z",
          "updated_at": "2022-07-05T22:37:02.000000Z",
          "month": 2,
          "year": 2022
        },
        {
          "id": 447,
          "month_id": 87,
          "user_id": 183,
          "hours_worked": "147.00",
          "gross_pay": "5400.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:37:02.000000Z",
          "updated_at": "2022-07-05T22:37:02.000000Z",
          "month": 3,
          "year": 2022
        },
        {
          "id": 448,
          "month_id": 88,
          "user_id": 183,
          "hours_worked": "145.00",
          "gross_pay": "5500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:37:02.000000Z",
          "updated_at": "2022-07-05T22:37:02.000000Z",
          "month": 4,
          "year": 2022
        },
        {
          "id": 449,
          "month_id": 89,
          "user_id": 183,
          "hours_worked": "152.00",
          "gross_pay": "5600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:37:02.000000Z",
          "updated_at": "2022-07-05T22:37:02.000000Z",
          "month": 5,
          "year": 2022
        },
        {
          "id": 450,
          "month_id": 90,
          "user_id": 183,
          "hours_worked": "147.00",
          "gross_pay": "5400.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2022-07-05T22:37:02.000000Z",
          "updated_at": "2022-07-05T22:37:02.000000Z",
          "month": 6,
          "year": 2022
        },
        {
          "id": 455,
          "month_id": 91,
          "user_id": 183,
          "hours_worked": employeeParams.hours_worked[3],
          "gross_pay": employeeParams.gross_pay[3],
          "amount_received": "0.00",
          "percentage": "24.74",
          "created_at": "2022-07-05T22:37:13.000000Z",
          "updated_at": "2022-07-05T22:41:23.000000Z",
          "month": 7,
          "year": 2022
        }
      ]
    }
  ],
  funds: [],
  activeEmployee: null,
  errors: { employees: null, funds: null }
};

// Debug:
// console.log(" --- DISPLAY --- ");
// console.log("found - ", planDataState.months[2].hygiene_production_amount);
// console.log("display -", templateParams.hygiene_production_amount[0]);

export const seedConfig = {
  strict: true,
  modules: {
    ui,
    global,
    bonusPercentage,
    initialSetup: {
      namespaced: true,
      state: initialSetupState,
      getters: initialSetup.getters,
      mutations: initialSetup.mutations,
      actions: initialSetup.actions
    },
    planData: {
      namespaced: true,
      state: planDataState,
      getters: planData.getters,
      mutations: planData.mutations,
      actions: planData.actions
    },
    employeesAndFunds: {
      namespaced: true,
      state: employeesAndFundsState,
      getters: employeesAndFunds.getters,
      mutations: employeesAndFunds.mutations,
      actions: employeesAndFunds.actions
    }
  }
}

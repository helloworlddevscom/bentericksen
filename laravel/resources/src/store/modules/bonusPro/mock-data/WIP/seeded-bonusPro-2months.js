import ui from "../modules/ui";
import global from "../modules/global";
import initialSetup from "../modules/initialSetup";
import planData from "../modules/planData";
import employeesAndFunds from "../modules/employeesAndFunds";
import bonusPercentage from "../modules/bonusPercentage";

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
  staffSalaryBonusTotal: 5575,
  hygienistsSalariesTotal: 0,
  hygiene_production_amount: "0.00",
  hygienistsSalaryBonusTotal: 0,
  productionCollectionAverage: 50750,
  staffSalaryPercentageOfPandC: 1.9704433497536946,
  hygienistsSalaryPercentageOfProd: "__vue_devtool_nan__",
  productionCollectionAverageDisabled: false,
  employee_data: [
    {
      id: 403,
      month_id: 241,
      user_id: 16254,
      hours_worked: 500,
      gross_pay: 500,
      amount_received: 2037.5,
      percentage: 50,
      created_at: "2021-07-28T21:03:35.000000Z",
      updated_at: "2021-07-28T21:03:35.000000Z",
      month: 7,
      year: 2021
    },
    {
      id: 404,
      month_id: 241,
      user_id: 16253,
      hours_worked: 500,
      gross_pay: 500,
      amount_received: 2037.5,
      percentage: 50,
      created_at: "2021-07-28T21:03:35.000000Z",
      updated_at: "2021-07-28T21:03:35.000000Z",
      month: 7,
      year: 2021
    }
  ],
  funds: [],
  plan_id: 38,
  production_collection_average: "35000.00",
  staff_percentage: null,
  hygiene_percentage: null,
  created_at: "2021-07-28T21:03:35.000000Z",
  updated_at: "2021-07-29T23:49:30.000000Z"
};

const initialSetupState = {

  "id": 4,
  "plan_id": "02",
  "plan_name": "BonusPro 2 - month average",
  "business_id": 5,
  "created_by": 118,
  "password_set": 1,
  "password": "__vue_devtool_undefined__",
  "password_confirmation": "__vue_devtool_undefined__",
  "start_month": 10,
  "start_year": 2021,
  "hygiene_plan": 0,
  "separate_fund": 0,
  "use_business_address": 1,
  "type_of_practice": "general",
  "rolling_average": 2,
  "distribution_type": "hours",
  "current_step": "setBonusPercentage",
  "address":
  {
    address1: "Technology Street",
    address2: "Apt 11",
    city: "Washington",
    zip: "20001",
    phone: "1112223344"
  },
  "staff_bonus_percentage": null,
  "hygiene_bonus_percentage": null,
  "status": null,
  "draft": 0,
  "completed": 0,
  "created_at": "2021-10-07T17:49:02.000000Z",
  "updated_at": "2021-10-07T17:50:06.000000Z",
  "last_login": "2021-10-07 10:50:59",
  "remember_token": null,
  "reset_by": null,
  "funds": []
};

const planDataState = {
  "months": [
    {
      "id": 21,
      "month": 4,
      "year": 2021,
      "finalized": 0,
      "fundAmountPaid": 0,
      "staffHoursTotal": 0,
      "collectionRatio": 97.01492537313433,
      "collection_amount": "65000.00",
      "production_amount": "67000.00",
      "productionAverage": 0,
      "staffSalariesTotal": 0,
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 0,
      "staffSalaryBonusTotal": 0,
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": "0.00",
      "hygienistsSalaryBonusTotal": 0,
      "productionCollectionAverage": 0,
      "staffSalaryPercentageOfPandC": 0,
      "hygienistsSalaryPercentageOfProd": 0,
      "productionCollectionAverageDisabled": true,
      "employee_data": [
        {
          "id": 67,
          "month_id": 21,
          "user_id": 119,
          "hours_worked": "142.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 4,
          "year": 2021
        },
        {
          "id": 73,
          "month_id": 21,
          "user_id": 120,
          "hours_worked": "145.00",
          "gross_pay": "3500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 4,
          "year": 2021
        },
        {
          "id": 79,
          "month_id": 21,
          "user_id": 121,
          "hours_worked": "140.00",
          "gross_pay": "2500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 4,
          "year": 2021
        },
        {
          "id": 85,
          "month_id": 21,
          "user_id": 122,
          "hours_worked": "145.00",
          "gross_pay": "5500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 4,
          "year": 2021
        }],
      "funds": [],
      "plan_id": 4,
      "production_collection_average": "0.00",
      "staff_percentage": null,
      "hygiene_percentage": null,
      "created_at": "2021-10-07T17:49:11.000000Z",
      "updated_at": "2021-10-07T17:49:11.000000Z"
    },
    {
      "id": 22,
      "month": 5,
      "year": 2021,
      "finalized": 0,
      "fundAmountPaid": 0,
      "staffHoursTotal": 601,
      "collectionRatio": 100,
      "collection_amount": "54000.00",
      "production_amount": "54000.00",
      "productionAverage": "-",
      "staffSalariesTotal": 13900,
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 0,
      "staffSalaryBonusTotal": 13900,
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": "0.00",
      "hygienistsSalaryBonusTotal": 0,
      "productionCollectionAverage": "-",
      "staffSalaryPercentageOfPandC": "-",
      "hygienistsSalaryPercentageOfProd": "-",
      "productionCollectionAverageDisabled": true,
      "employee_data": [
        {
          "id": 68,
          "month_id": 22,
          "user_id": 119,
          "hours_worked": "147.00",
          "gross_pay": "1850.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 5,
          "year": 2021
        },
        {
          "id": 74,
          "month_id": 22,
          "user_id": 120,
          "hours_worked": "152.00",
          "gross_pay": "3750.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 5,
          "year": 2021
        },
        {
          "id": 80,
          "month_id": 22,
          "user_id": 121,
          "hours_worked": "150.00",
          "gross_pay": "2700.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 5,
          "year": 2021
        },
        {
          "id": 86,
          "month_id": 22,
          "user_id": 122,
          "hours_worked": "152.00",
          "gross_pay": "5600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 5,
          "year": 2021
        }],
      "funds": [],
      "plan_id": 4,
      "production_collection_average": "60000.00",
      "staff_percentage": null,
      "hygiene_percentage": null,
      "created_at": "2021-10-07T17:49:11.000000Z",
      "updated_at": "2021-10-07T17:49:11.000000Z"
    },
    {
      "id": 23,
      "month": 6,
      "year": 2021,
      "finalized": 0,
      "fundAmountPaid": 0,
      "staffHoursTotal": 586,
      "collectionRatio": 77.77777777777779,
      "collection_amount": "56000.00",
      "production_amount": "72000.00",
      "productionAverage": 0,
      "staffSalariesTotal": 13400,
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 0,
      "staffSalaryBonusTotal": 13400,
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": "0.00",
      "hygienistsSalaryBonusTotal": 0,
      "productionCollectionAverage": 59000,
      "staffSalaryPercentageOfPandC": 22.71186440677966,
      "hygienistsSalaryPercentageOfProd": "__vue_devtool_nan__",
      "productionCollectionAverageDisabled": false,
      "employee_data": [
        {
          "id": 69,
          "month_id": 23,
          "user_id": 119,
          "hours_worked": "145.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 6,
          "year": 2021
        },
        {
          "id": 75,
          "month_id": 23,
          "user_id": 120,
          "hours_worked": "147.00",
          "gross_pay": "3600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 6,
          "year": 2021
        },
        {
          "id": 81,
          "month_id": 23,
          "user_id": 121,
          "hours_worked": "147.00",
          "gross_pay": "2600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 6,
          "year": 2021
        },
        {
          "id": 87,
          "month_id": 23,
          "user_id": 122,
          "hours_worked": "147.00",
          "gross_pay": "5400.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 6,
          "year": 2021
        }],
      "funds": [],
      "plan_id": 4,
      "production_collection_average": "59000.00",
      "staff_percentage": null,
      "hygiene_percentage": null,
      "created_at": "2021-10-07T17:49:11.000000Z",
      "updated_at": "2021-10-07T17:49:11.000000Z"
    },
    {
      "id": 24,
      "month": 7,
      "year": 2021,
      "finalized": 0,
      "fundAmountPaid": 0,
      "staffHoursTotal": 571,
      "collectionRatio": 109.55882352941177,
      "collection_amount": "74500.00",
      "production_amount": "68000.00",
      "productionAverage": 0,
      "staffSalariesTotal": 13000,
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 0,
      "staffSalaryBonusTotal": 13000,
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": "0.00",
      "hygienistsSalaryBonusTotal": 0,
      "productionCollectionAverage": 67625,
      "staffSalaryPercentageOfPandC": 19.223659889094268,
      "hygienistsSalaryPercentageOfProd": "__vue_devtool_nan__",
      "productionCollectionAverageDisabled": false,
      "employee_data": [
        {
          "id": 70,
          "month_id": 24,
          "user_id": 119,
          "hours_worked": "142.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 7,
          "year": 2021
        },
        {
          "id": 76,
          "month_id": 24,
          "user_id": 120,
          "hours_worked": "142.00",
          "gross_pay": "3400.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 7,
          "year": 2021
        },
        {
          "id": 82,
          "month_id": 24,
          "user_id": 121,
          "hours_worked": "142.00",
          "gross_pay": "2300.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 7,
          "year": 2021
        },
        {
          "id": 88,
          "month_id": 24,
          "user_id": 122,
          "hours_worked": "145.00",
          "gross_pay": "5500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 7,
          "year": 2021
        }],
      "funds": [],
      "plan_id": 4,
      "production_collection_average": "67625.00",
      "staff_percentage": null,
      "hygiene_percentage": null,
      "created_at": "2021-10-07T17:49:11.000000Z",
      "updated_at": "2021-10-07T17:49:11.000000Z"
    },
    {
      "id": 25,
      "month": 8,
      "year": 2021,
      "finalized": 0,
      "fundAmountPaid": 0,
      "staffHoursTotal": 577,
      "collectionRatio": 101.44927536231884,
      "collection_amount": "70000.00",
      "production_amount": "69000.00",
      "productionAverage": 0,
      "staffSalariesTotal": 12925,
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 0,
      "staffSalaryBonusTotal": 12925,
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": "0.00",
      "hygienistsSalaryBonusTotal": 0,
      "productionCollectionAverage": 70375,
      "staffSalaryPercentageOfPandC": 18.365896980461812,
      "hygienistsSalaryPercentageOfProd": "__vue_devtool_nan__",
      "productionCollectionAverageDisabled": false,
      "employee_data": [
        {
          "id": 71,
          "month_id": 25,
          "user_id": 119,
          "hours_worked": "147.00",
          "gross_pay": "1850.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 8,
          "year": 2021
        },
        {
          "id": 77,
          "month_id": 25,
          "user_id": 120,
          "hours_worked": "139.00",
          "gross_pay": "3375.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 8,
          "year": 2021
        },
        {
          "id": 83,
          "month_id": 25,
          "user_id": 121,
          "hours_worked": "139.00",
          "gross_pay": "2100.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 8,
          "year": 2021
        },
        {
          "id": 89,
          "month_id": 25,
          "user_id": 122,
          "hours_worked": "152.00",
          "gross_pay": "5600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 8,
          "year": 2021
        }],
      "funds": [],
      "plan_id": 4,
      "production_collection_average": "70375.00",
      "staff_percentage": null,
      "hygiene_percentage": null,
      "created_at": "2021-10-07T17:49:11.000000Z",
      "updated_at": "2021-10-07T17:49:11.000000Z"
    },
    {
      "id": 26,
      "month": 9,
      "year": 2021,
      "finalized": 0,
      "fundAmountPaid": 0,
      "staffHoursTotal": 571,
      "collectionRatio": 86.66666666666667,
      "collection_amount": "65000.00",
      "production_amount": "75000.00",
      "productionAverage": 0,
      "staffSalariesTotal": 13000,
      "staffBonusToBePaid": 0,
      "fundsBonusToBePaid": 0,
      "hygienistsHoursTotal": 0,
      "hygieneBonusToBePaid": 0,
      "staffSalaryBonusTotal": 13000,
      "hygienistsSalariesTotal": 0,
      "hygiene_production_amount": "0.00",
      "hygienistsSalaryBonusTotal": 0,
      "productionCollectionAverage": 69750,
      "staffSalaryPercentageOfPandC": 18.63799283154122,
      "hygienistsSalaryPercentageOfProd": "__vue_devtool_nan__",
      "productionCollectionAverageDisabled": false,
      "employee_data": [
        {
          "id": 72,
          "month_id": 26,
          "user_id": 119,
          "hours_worked": "140.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 9,
          "year": 2021
        },
        {
          "id": 78,
          "month_id": 26,
          "user_id": 120,
          "hours_worked": "142.00",
          "gross_pay": "3500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 9,
          "year": 2021
        },
        {
          "id": 84,
          "month_id": 26,
          "user_id": 121,
          "hours_worked": "142.00",
          "gross_pay": "2300.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 9,
          "year": 2021
        },
        {
          "id": 90,
          "month_id": 26,
          "user_id": 122,
          "hours_worked": "147.00",
          "gross_pay": "5400.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 9,
          "year": 2021
        }],
      "funds": [],
      "plan_id": 4,
      "production_collection_average": "69750.00",
      "staff_percentage": null,
      "hygiene_percentage": null,
      "created_at": "2021-10-07T17:49:11.000000Z",
      "updated_at": "2021-10-07T17:49:11.000000Z"
    }],
  "activeMonth": null,
  "plan_id": null
};

const employeesAndFundsState = {
  "employees": [
    {
      "id": 121,
      "business_id": 5,
      "classification_id": null,
      "email": "jeff+brown-Asst+1633628928398@helloworlddevs.com",
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
      "created_at": "2021-10-07T17:49:52.000000Z",
      "updated_at": "2021-10-07T17:49:52.000000Z",
      "employee_status": null,
      "position_title": null,
      "bp_eligibility_date": "10/2021",
      "bp_bonus_percentage": null,
      "bp_employee_type": "admin/assistant",
      "bp_eligible": 1,
      "main_role": "employee",
      "pivot":
      {
        "plan_id": 4,
        "user_id": 121
      },
      "roles": [
        {
          "id": 5,
          "name": "employee",
          "display_name": "Employee",
          "description": null,
          "created_at": null,
          "updated_at": null,
          "pivot":
          {
            "user_id": 121,
            "role_id": 5
          }
        }],
      "monthlyData": [
        {
          "id": 79,
          "month_id": 21,
          "user_id": 121,
          "hours_worked": "140.00",
          "gross_pay": "2500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 4,
          "year": 2021
        },
        {
          "id": 80,
          "month_id": 22,
          "user_id": 121,
          "hours_worked": "150.00",
          "gross_pay": "2700.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 5,
          "year": 2021
        },
        {
          "id": 81,
          "month_id": 23,
          "user_id": 121,
          "hours_worked": "147.00",
          "gross_pay": "2600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 6,
          "year": 2021
        },
        {
          "id": 82,
          "month_id": 24,
          "user_id": 121,
          "hours_worked": "142.00",
          "gross_pay": "2300.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 7,
          "year": 2021
        },
        {
          "id": 83,
          "month_id": 25,
          "user_id": 121,
          "hours_worked": "139.00",
          "gross_pay": "2100.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 8,
          "year": 2021
        },
        {
          "id": 84,
          "month_id": 26,
          "user_id": 121,
          "hours_worked": "142.00",
          "gross_pay": "2300.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:52.000000Z",
          "updated_at": "2021-10-07T17:49:52.000000Z",
          "month": 9,
          "year": 2021
        }]
    },
    {
      "id": 119,
      "business_id": 5,
      "classification_id": null,
      "email": "jeff+jones-asst+1633628928398@helloworlddevs.com",
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
      "created_at": "2021-10-07T17:49:26.000000Z",
      "updated_at": "2021-10-07T17:49:26.000000Z",
      "employee_status": null,
      "position_title": null,
      "bp_eligibility_date": "10/2021",
      "bp_bonus_percentage": null,
      "bp_employee_type": "admin/assistant",
      "bp_eligible": 1,
      "main_role": "employee",
      "pivot":
      {
        "plan_id": 4,
        "user_id": 119
      },
      "roles": [
        {
          "id": 5,
          "name": "employee",
          "display_name": "Employee",
          "description": null,
          "created_at": null,
          "updated_at": null,
          "pivot":
          {
            "user_id": 119,
            "role_id": 5
          }
        }],
      "monthlyData": [
        {
          "id": 67,
          "month_id": 21,
          "user_id": 119,
          "hours_worked": "142.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 4,
          "year": 2021
        },
        {
          "id": 68,
          "month_id": 22,
          "user_id": 119,
          "hours_worked": "147.00",
          "gross_pay": "1850.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 5,
          "year": 2021
        },
        {
          "id": 69,
          "month_id": 23,
          "user_id": 119,
          "hours_worked": "145.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 6,
          "year": 2021
        },
        {
          "id": 70,
          "month_id": 24,
          "user_id": 119,
          "hours_worked": "142.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 7,
          "year": 2021
        },
        {
          "id": 71,
          "month_id": 25,
          "user_id": 119,
          "hours_worked": "147.00",
          "gross_pay": "1850.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 8,
          "year": 2021
        },
        {
          "id": 72,
          "month_id": 26,
          "user_id": 119,
          "hours_worked": "140.00",
          "gross_pay": "1800.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:26.000000Z",
          "updated_at": "2021-10-07T17:49:26.000000Z",
          "month": 9,
          "year": 2021
        }]
    },
    {
      "id": 120,
      "business_id": 5,
      "classification_id": null,
      "email": "jeff+smith-admin+1633628928398@helloworlddevs.com",
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
      "created_at": "2021-10-07T17:49:39.000000Z",
      "updated_at": "2021-10-07T17:49:39.000000Z",
      "employee_status": null,
      "position_title": null,
      "bp_eligibility_date": "10/2021",
      "bp_bonus_percentage": null,
      "bp_employee_type": "admin/assistant",
      "bp_eligible": 1,
      "main_role": "employee",
      "pivot":
      {
        "plan_id": 4,
        "user_id": 120
      },
      "roles": [
        {
          "id": 5,
          "name": "employee",
          "display_name": "Employee",
          "description": null,
          "created_at": null,
          "updated_at": null,
          "pivot":
          {
            "user_id": 120,
            "role_id": 5
          }
        }],
      "monthlyData": [
        {
          "id": 73,
          "month_id": 21,
          "user_id": 120,
          "hours_worked": "145.00",
          "gross_pay": "3500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 4,
          "year": 2021
        },
        {
          "id": 74,
          "month_id": 22,
          "user_id": 120,
          "hours_worked": "152.00",
          "gross_pay": "3750.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 5,
          "year": 2021
        },
        {
          "id": 75,
          "month_id": 23,
          "user_id": 120,
          "hours_worked": "147.00",
          "gross_pay": "3600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 6,
          "year": 2021
        },
        {
          "id": 76,
          "month_id": 24,
          "user_id": 120,
          "hours_worked": "142.00",
          "gross_pay": "3400.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 7,
          "year": 2021
        },
        {
          "id": 77,
          "month_id": 25,
          "user_id": 120,
          "hours_worked": "139.00",
          "gross_pay": "3375.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 8,
          "year": 2021
        },
        {
          "id": 78,
          "month_id": 26,
          "user_id": 120,
          "hours_worked": "142.00",
          "gross_pay": "3500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:49:39.000000Z",
          "updated_at": "2021-10-07T17:49:39.000000Z",
          "month": 9,
          "year": 2021
        }]
    },
    {
      "id": 122,
      "business_id": 5,
      "classification_id": null,
      "email": "jeff+turner_hyg+1633628928398@helloworlddevs.com",
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
      "created_at": "2021-10-07T17:50:05.000000Z",
      "updated_at": "2021-10-07T17:50:05.000000Z",
      "employee_status": null,
      "position_title": null,
      "bp_eligibility_date": "10/2021",
      "bp_bonus_percentage": null,
      "bp_employee_type": "hygienist",
      "bp_eligible": 1,
      "main_role": "employee",
      "pivot":
      {
        "plan_id": 4,
        "user_id": 122
      },
      "roles": [
        {
          "id": 5,
          "name": "employee",
          "display_name": "Employee",
          "description": null,
          "created_at": null,
          "updated_at": null,
          "pivot":
          {
            "user_id": 122,
            "role_id": 5
          }
        }],
      "monthlyData": [
        {
          "id": 85,
          "month_id": 21,
          "user_id": 122,
          "hours_worked": "145.00",
          "gross_pay": "5500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 4,
          "year": 2021
        },
        {
          "id": 86,
          "month_id": 22,
          "user_id": 122,
          "hours_worked": "152.00",
          "gross_pay": "5600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 5,
          "year": 2021
        },
        {
          "id": 87,
          "month_id": 23,
          "user_id": 122,
          "hours_worked": "147.00",
          "gross_pay": "5400.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 6,
          "year": 2021
        },
        {
          "id": 88,
          "month_id": 24,
          "user_id": 122,
          "hours_worked": "145.00",
          "gross_pay": "5500.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 7,
          "year": 2021
        },
        {
          "id": 89,
          "month_id": 25,
          "user_id": 122,
          "hours_worked": "152.00",
          "gross_pay": "5600.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 8,
          "year": 2021
        },
        {
          "id": 90,
          "month_id": 26,
          "user_id": 122,
          "hours_worked": "147.00",
          "gross_pay": "5400.00",
          "amount_received": "0.00",
          "percentage": "0.00",
          "created_at": "2021-10-07T17:50:05.000000Z",
          "updated_at": "2021-10-07T17:50:05.000000Z",
          "month": 9,
          "year": 2021
        }]
    }],
  "funds": [],
  "activeEmployee": null,
  "errors":
  {
    "employees": null,
    "funds": null
  }
};

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

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
  id: 38,
  plan_id: "02",
  plan_name: "BonusPro 2 - month average",
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
  rolling_average: 2,
  distribution_type: "hours",
  current_step: "setBonusPercentage",
  address: {
    address1: "Technology Street",
    address2: "Apt 11",
    city: "Washington",
    zip: "20001",
    phone: "1112223344"
  },
  staff_bonus_percentage: "10.00",
  hygiene_bonus_percentage: null,
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
  months: [
    {
      id: 235,
      month: 1,
      year: 2021,
      finalized: 1,
      fundAmountPaid: 0,
      staffHoursTotal: 0,
      collectionRatio: 11197.01492537313433,
      collection_amount: "65000.00",
      production_amount: "67000.00",
      productionAverage: 0,
      staffSalariesTotal: 0,
      staffBonusToBePaid: 0,
      fundsBonusToBePaid: 0,
      hygienistsHoursTotal: 0,
      hygieneBonusToBePaid: 0,
      staffSalaryBonusTotal: 0,
      hygienistsSalariesTotal: 0,
      hygiene_production_amount: "0.00",
      hygienistsSalaryBonusTotal: 0,
      productionCollectionAverage: 0,
      staffSalaryPercentageOfPandC: 0,
      hygienistsSalaryPercentageOfProd: 0,
      productionCollectionAverageDisabled: true,
      employee_data: [
        {
          id: 391,
          month_id: 235,
          user_id: 16253,
          hours_worked: "145.00",
          gross_pay: "3500.00",
          amount_received: "0.00",
          percentage: "0.00",
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 1,
          year: 2021
        },
        {
          id: 397,
          month_id: 235,
          user_id: 16254,
          hours_worked: "142.00",
          gross_pay: "1800.00",
          amount_received: "0.00",
          percentage: "0.00",
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 1,
          year: 2021
        },
        {
          id: 404,
          month_id: 235,
          user_id: 16255,
          hours_worked: "140.00",
          gross_pay: "2500.00",
          amount_received: "0.00",
          percentage: "0.00",
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 1,
          year: 2021
        },
        {
          id: 411,
          month_id: 235,
          user_id: 16256,
          hours_worked: "145.00",
          gross_pay: "5500.00",
          amount_received: "0.00",
          percentage: "0.00",
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 1,
          year: 2021
        }
      ],
      funds: [],
      plan_id: 38,
      production_collection_average: "0.00",
      staff_percentage: "10.00",
      hygiene_percentage: null,
      created_at: "2021-07-28T20:57:26.000000Z",
      updated_at: "2021-07-29T23:49:30.000000Z"
    },
    {
      id: 236,
      month: 2,
      year: 2021,
      finalized: 1,
      fundAmountPaid: 0,
      staffHoursTotal: 57,
      collectionRatio: 100,
      collection_amount: "54000.00",
      production_amount: "54000.00",
      productionAverage: "-",
      staffSalariesTotal: 50600,
      staffBonusToBePaid: 0,
      fundsBonusToBePaid: 0,
      hygienistsHoursTotal: 0,
      hygieneBonusToBePaid: 0,
      staffSalaryBonusTotal: 50600,
      hygienistsSalariesTotal: 0,
      hygiene_production_amount: "0.00",
      hygienistsSalaryBonusTotal: 0,
      productionCollectionAverage: "-",
      staffSalaryPercentageOfPandC: "-",
      hygienistsSalaryPercentageOfProd: "-",
      productionCollectionAverageDisabled: true,
      employee_data: [
        {
          id: 392,
          month_id: 236,
          user_id: 16253,
          hours_worked: "152.00",
          gross_pay: "3750.00",
          amount_received: 0,
          percentage: 0.7000000000000001,
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 2,
          year: 2021
        },
        {
          id: 398,
          month_id: 236,
          user_id: 16254,
          hours_worked: "147.00",
          gross_pay: "1850.00",
          amount_received: 0,
          percentage: 5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 2,
          year: 2021
        },
        {
          id: 405,
          month_id: 236,
          user_id: 16255,
          hours_worked: "150.00",
          gross_pay: "2700.00",
          amount_received: 0,
          percentage: 5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 2,
          year: 2021
        },
        {
          id: 412,
          month_id: 236,
          user_id: 16256,
          hours_worked: "152.00",
          gross_pay: "5600.00",
          amount_received: 0,
          percentage: 5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 2,
          year: 2021
        }
      ],
      funds: [],
      plan_id: 38,
      production_collection_average: "0.00",
      staff_percentage: "10.00",
      hygiene_percentage: null,
      created_at: "2021-07-28T20:57:26.000000Z",
      updated_at: "2021-07-29T23:49:30.000000Z"
    },
    {
      id: 237,
      month: 3,
      year: 2021,
      finalized: 1,
      fundAmountPaid: 0,
      staffHoursTotal: 1007,
      collectionRatio: 77.77777777777779,
      collection_amount: "56000.00",
      production_amount: "72000.00",
      productionAverage: 0,
      staffSalariesTotal: 1000600,
      staffBonusToBePaid: 0,
      fundsBonusToBePaid: 0,
      hygienistsHoursTotal: 0,
      hygieneBonusToBePaid: 0,
      staffSalaryBonusTotal: 1000600,
      hygienistsSalariesTotal: 0,
      hygiene_production_amount: "0.00",
      hygienistsSalaryBonusTotal: 0,
      productionCollectionAverage: 59000,
      staffSalaryPercentageOfPandC: 1695.9322033898306,
      hygienistsSalaryPercentageOfProd: "__vue_devtool_nan__",
      productionCollectionAverageDisabled: false,
      employee_data: [
        {
          id: 393,
          month_id: 237,
          user_id: 16253,
          hours_worked: "147.00",
          gross_pay: "3600.00",
          amount_received: 0,
          percentage: 0.7000000000000001,
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 3,
          year: 2021
        },
        {
          id: 399,
          month_id: 237,
          user_id: 16254,
          hours_worked: "145.00",
          gross_pay: "1800.00",
          amount_received: 0,
          percentage: 100,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 3,
          year: 2021
        },
        {
          id: 406,
          month_id: 237,
          user_id: 16255,
          hours_worked: "147.00",
          gross_pay: "2600.00",
          amount_received: 0,
          percentage: 100,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 3,
          year: 2021
        },
        {
          id: 413,
          month_id: 237,
          user_id: 16256,
          hours_worked: "147.00",
          gross_pay: "5400.00",
          amount_received: 0,
          percentage: 100,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 3,
          year: 2021
        }
      ],
      funds: [],
      plan_id: 38,
      production_collection_average: "59000.00",
      staff_percentage: "10.00",
      hygiene_percentage: null,
      created_at: "2021-07-28T20:57:27.000000Z",
      updated_at: "2021-07-29T23:49:30.000000Z"
    },
    {
      id: 238,
      month: 4,
      year: 2021,
      finalized: 1,
      fundAmountPaid: 0,
      staffHoursTotal: 18,
      collectionRatio: 108.8235294117647,
      collection_amount: "74500.00",
      production_amount: "68000.00",
      productionAverage: 0,
      staffSalariesTotal: 10300,
      staffBonusToBePaid: 0,
      fundsBonusToBePaid: 0,
      hygienistsHoursTotal: 0,
      hygieneBonusToBePaid: 0,
      staffSalaryBonusTotal: 10300,
      hygienistsSalariesTotal: 0,
      hygiene_production_amount: "0.00",
      hygienistsSalaryBonusTotal: 0,
      productionCollectionAverage: 67500,
      staffSalaryPercentageOfPandC: 15.259259259259258,
      hygienistsSalaryPercentageOfProd: "__vue_devtool_nan__",
      productionCollectionAverageDisabled: false,
      employee_data: [
        {
          id: 394,
          month_id: 238,
          user_id: 16253,
          hours_worked: "142.00",
          gross_pay: "3400.00",
          amount_received: 0,
          percentage: 0.8,
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 4,
          year: 2021
        },
        {
          id: 400,
          month_id: 238,
          user_id: 16254,
          hours_worked: "142.00",
          gross_pay: "1800.00",
          amount_received: 0,
          percentage: 1,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 4,
          year: 2021
        },
        {
          id: 407,
          month_id: 238,
          user_id: 16255,
          hours_worked: "142.00",
          gross_pay: "2300.00",
          amount_received: 0,
          percentage: 1,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 4,
          year: 2021
        },
        {
          id: 414,
          month_id: 238,
          user_id: 16256,
          hours_worked: "145.00",
          gross_pay: "5500.00",
          amount_received: 0,
          percentage: 1,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 4,
          year: 2021
        }
      ],
      funds: [],
      plan_id: 38,
      production_collection_average: "67500.00",
      staff_percentage: "10.00",
      hygiene_percentage: null,
      created_at: "2021-07-28T20:57:27.000000Z",
      updated_at: "2021-07-29T23:49:30.000000Z"
    },
    {
      id: 239,
      month: 5,
      year: 2021,
      finalized: 1,
      fundAmountPaid: 0,
      staffHoursTotal: 20,
      collectionRatio: 101.44927536231884,
      collection_amount: "70000.00",
      production_amount: "69000.00",
      productionAverage: 0,
      staffSalariesTotal: 8000,
      staffBonusToBePaid: 0,
      fundsBonusToBePaid: 0,
      hygienistsHoursTotal: 0,
      hygieneBonusToBePaid: 0,
      staffSalaryBonusTotal: 8000,
      hygienistsSalariesTotal: 0,
      hygiene_production_amount: "0.00",
      hygienistsSalaryBonusTotal: 0,
      productionCollectionAverage: 70250,
      staffSalaryPercentageOfPandC: 11.387900355871885,
      hygienistsSalaryPercentageOfProd: "__vue_devtool_nan__",
      productionCollectionAverageDisabled: false,
      employee_data: [
        {
          id: 395,
          month_id: 239,
          user_id: 16253,
          hours_worked: "139.00",
          gross_pay: "3375.00",
          amount_received: 0,
          percentage: 1.5,
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 5,
          year: 2021
        },
        {
          id: 401,
          month_id: 239,
          user_id: 16254,
          hours_worked: "147.00",
          gross_pay: "1850.00",
          amount_received: 0,
          percentage: 0.5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 5,
          year: 2021
        },
        {
          id: 408,
          month_id: 239,
          user_id: 16255,
          hours_worked: "139.00",
          gross_pay: "2100.00",
          amount_received: 0,
          percentage: 0.5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 5,
          year: 2021
        },
        {
          id: 415,
          month_id: 239,
          user_id: 16256,
          hours_worked: "152.00",
          gross_pay: "5600.00",
          amount_received: 0,
          percentage: 0.5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 5,
          year: 2021
        }
      ],
      funds: [],
      plan_id: 38,
      production_collection_average: "70250.00",
      staff_percentage: "10.00",
      hygiene_percentage: null,
      created_at: "2021-07-28T20:57:27.000000Z",
      updated_at: "2021-07-29T23:49:30.000000Z"
    },
    {
      id: 240,
      month: 6,
      year: 2021,
      finalized: 1,
      fundAmountPaid: 0,
      staffHoursTotal: 2,
      collectionRatio: 86.66666666666667,
      collection_amount: "65000.00",
      production_amount: "75000.00",
      productionAverage: 0,
      staffSalariesTotal: 1030,
      staffBonusToBePaid: 5944.999999999999,
      fundsBonusToBePaid: 0,
      hygienistsHoursTotal: 0,
      hygieneBonusToBePaid: 0,
      staffSalaryBonusTotal: 1053.7800000000002,
      hygienistsSalariesTotal: 0,
      hygiene_production_amount: "0.00",
      hygienistsSalaryBonusTotal: 0,
      productionCollectionAverage: 69750,
      staffSalaryPercentageOfPandC: 1.4767025089605736,
      hygienistsSalaryPercentageOfProd: "__vue_devtool_nan__",
      productionCollectionAverageDisabled: false,
      employee_data: [
        {
          id: 396,
          month_id: 240,
          user_id: 16253,
          hours_worked: "142.00",
          gross_pay: "3500.00",
          amount_received: 5.944999999999999,
          percentage: 0.1,
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 6,
          year: 2021
        },
        {
          id: 402,
          month_id: 240,
          user_id: 16254,
          hours_worked: "140.00",
          gross_pay: "1800.00",
          amount_received: 5.944999999999999,
          percentage: 0.1,
          created_at: "2021-07-28T21:02:53.000000Z",
          updated_at: "2021-07-28T21:02:53.000000Z",
          month: 6,
          year: 2021
        },
        {
          id: 409,
          month_id: 240,
          user_id: 16255,
          hours_worked: "142.00",
          gross_pay: "2300.00",
          amount_received: 5.944999999999999,
          percentage: 0.1,
          created_at: "2021-07-28T21:02:53.000000Z",
          updated_at: "2021-07-28T21:02:53.000000Z",
          month: 6,
          year: 2021
        },
        {
          id: 416,
          month_id: 240,
          user_id: 16256,
          hours_worked: "147.00",
          gross_pay: "5400.00",
          amount_received: 5.944999999999999,
          percentage: 0.1,
          created_at: "2021-07-28T21:02:53.000000Z",
          updated_at: "2021-07-28T21:02:53.000000Z",
          month: 6,
          year: 2021
        }
      ],
      funds: [],
      plan_id: 38,
      production_collection_average: "69750.00",
      staff_percentage: "10.00",
      hygiene_percentage: null,
      created_at: "2021-07-28T20:57:27.000000Z",
      updated_at: "2021-07-29T23:49:30.000000Z"
    }
  ],
  plan_id: 38
};
const employeesAndFundsState = {
  employees: [
    {
      id: 16254,
      business_id: 2360,
      classification_id: null,
      email: `jeff+jones-asst+${Date.now()}@helloworlddevs.com`,
      last_login: null,
      hired: null,
      rehired: null,
      job_title_id: null,
      dob: null,
      age: null,
      status: "enabled",
      terminated: null,
      can_rehire: null,
      benefit_date: null,
      years_of_service: 0,
      benefit_years_of_service: 0,
      on_leave: null,
      first_name: "Tom",
      middle_name: null,
      last_name: "Jones-Asst",
      suffix: null,
      prefix: null,
      address1: null,
      address2: null,
      city: null,
      state: null,
      postal_code: null,
      phone1: null,
      phone1_type: "home",
      phone2: null,
      phone2_type: null,
      included_in_employee_count: 1,
      can_access_system: 1,
      receives_benefits: 1,
      employee_wizard: 0,
      accepted_terms: "0000-00-00 00:00:00",
      created_at: "2021-07-28T21:02:52.000000Z",
      updated_at: "2021-07-28T21:02:52.000000Z",
      job_reports_to: null,
      job_location: null,
      job_department: null,
      employee_status: null,
      position_title: null,
      bp_eligibility_date: "07/2021",
      bp_bonus_percentage: null,
      bp_employee_type: "admin/assistant",
      bp_eligible: 1,
      main_role: "employee",
      pivot: { plan_id: 38, user_id: 16254 },
      roles: [
        {
          id: 5,
          name: "employee",
          display_name: "Employee",
          description: "",
          created_at: "2015-09-02T11:01:50.000000Z",
          updated_at: "2015-09-02T11:01:50.000000Z",
          pivot: { user_id: 16254, role_id: 5 }
        }
      ],
      monthlyData: [
        {
          id: 397,
          month_id: 235,
          user_id: 16254,
          hours_worked: "142.00",
          gross_pay: "1800.00",
          amount_received: "0.00",
          percentage: "0.00",
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 1,
          year: 2021
        },
        {
          id: 398,
          month_id: 236,
          user_id: 16254,
          hours_worked: "147.00",
          gross_pay: "1850.00",
          amount_received: 0,
          percentage: 5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 2,
          year: 2021
        },
        {
          id: 399,
          month_id: 237,
          user_id: 16254,
          hours_worked: "145.00",
          gross_pay: "1800.00",
          amount_received: 0,
          percentage: 100,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 3,
          year: 2021
        },
        {
          id: 400,
          month_id: 238,
          user_id: 16254,
          hours_worked: "142.00",
          gross_pay: "1800.00",
          amount_received: 0,
          percentage: 1,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 4,
          year: 2021
        },
        {
          id: 401,
          month_id: 239,
          user_id: 16254,
          hours_worked: "147.00",
          gross_pay: "1850.00",
          amount_received: 0,
          percentage: 0.5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 5,
          year: 2021
        },
        {
          id: 402,
          month_id: 240,
          user_id: 16254,
          hours_worked: "140.00",
          gross_pay: "1800.00",
          amount_received: 5.944999999999999,
          percentage: 0.1,
          created_at: "2021-07-28T21:02:53.000000Z",
          updated_at: "2021-07-28T21:02:53.000000Z",
          month: 6,
          year: 2021
        }
        // {
        //   id: 403,
        //   month_id: 241,
        //   user_id: 16254,
        //   hours_worked: 500,
        //   gross_pay: 500,
        //   amount_received: 2037.5,
        //   percentage: 50,
        //   created_at: "2021-07-28T21:03:35.000000Z",
        //   updated_at: "2021-07-28T21:03:35.000000Z",
        //   month: 7,
        //   year: 2021
        // }
      ]
    },
    {
      id: 16253,
      business_id: 2360,
      classification_id: null,
      email: `jeff+smith-admin+${Date.now()}@helloworlddevs.com`,
      last_login: null,
      hired: null,
      rehired: null,
      job_title_id: null,
      dob: null,
      age: null,
      status: "enabled",
      terminated: null,
      can_rehire: null,
      benefit_date: null,
      years_of_service: 0,
      benefit_years_of_service: 0,
      on_leave: null,
      first_name: "Mary",
      middle_name: null,
      last_name: "Smith-Admin",
      suffix: null,
      prefix: null,
      address1: null,
      address2: null,
      city: null,
      state: null,
      postal_code: null,
      phone1: null,
      phone1_type: "home",
      phone2: null,
      phone2_type: null,
      included_in_employee_count: 1,
      can_access_system: 1,
      receives_benefits: 1,
      employee_wizard: 0,
      accepted_terms: "0000-00-00 00:00:00",
      created_at: "2021-07-28T21:01:16.000000Z",
      updated_at: "2021-07-28T21:01:16.000000Z",
      job_reports_to: null,
      job_location: null,
      job_department: null,
      employee_status: null,
      position_title: null,
      bp_eligibility_date: "07/2021",
      bp_bonus_percentage: null,
      bp_employee_type: "admin/assistant",
      bp_eligible: 1,
      main_role: "employee",
      pivot: { plan_id: 38, user_id: 16253 },
      roles: [
        {
          id: 5,
          name: "employee",
          display_name: "Employee",
          description: "",
          created_at: "2015-09-02T11:01:50.000000Z",
          updated_at: "2015-09-02T11:01:50.000000Z",
          pivot: { user_id: 16253, role_id: 5 }
        }
      ],
      monthlyData: [
        {
          id: 391,
          month_id: 235,
          user_id: 16253,
          hours_worked: "145.00",
          gross_pay: "3500.00",
          amount_received: "0.00",
          percentage: "0.00",
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 1,
          year: 2021
        },
        {
          id: 392,
          month_id: 236,
          user_id: 16253,
          hours_worked: "152.00",
          gross_pay: "3750.00",
          amount_received: 0,
          percentage: 0.7000000000000001,
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 2,
          year: 2021
        },
        {
          id: 393,
          month_id: 237,
          user_id: 16253,
          hours_worked: "147.00",
          gross_pay: "3600.00",
          amount_received: 0,
          percentage: 0.7000000000000001,
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 3,
          year: 2021
        },
        {
          id: 394,
          month_id: 238,
          user_id: 16253,
          hours_worked: "142.00",
          gross_pay: "3400.00",
          amount_received: 0,
          percentage: 0.8,
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 4,
          year: 2021
        },
        {
          id: 395,
          month_id: 239,
          user_id: 16253,
          hours_worked: "139.00",
          gross_pay: "3375.00",
          amount_received: 0,
          percentage: 1.5,
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 5,
          year: 2021
        },
        {
          id: 396,
          month_id: 240,
          user_id: 16253,
          hours_worked: "142.00",
          gross_pay: "3500.00",
          amount_received: 5.944999999999999,
          percentage: 0.1,
          created_at: "2021-07-28T21:01:16.000000Z",
          updated_at: "2021-07-28T21:01:16.000000Z",
          month: 6,
          year: 2021
        }
        // {
        //   id: 404,
        //   month_id: 241,
        //   user_id: 16253,
        //   hours_worked: 500,
        //   gross_pay: 500,
        //   amount_received: 2037.5,
        //   percentage: 50,
        //   created_at: "2021-07-28T21:03:35.000000Z",
        //   updated_at: "2021-07-28T21:03:35.000000Z",
        //   month: 7,
        //   year: 2021
        // }
      ]
    },
    {
      id: 16255,
      business_id: 2360,
      classification_id: null,
      email: `jeff+brown-Asst+${Date.now()}@helloworlddevs.com`,
      last_login: null,
      hired: null,
      rehired: null,
      job_title_id: null,
      dob: null,
      age: null,
      status: "enabled",
      terminated: null,
      can_rehire: null,
      benefit_date: null,
      years_of_service: 0,
      benefit_years_of_service: 0,
      on_leave: null,
      first_name: "Sonya",
      middle_name: null,
      last_name: "Brown-Asst",
      suffix: null,
      prefix: null,
      address1: null,
      address2: null,
      city: null,
      state: null,
      postal_code: null,
      phone1: null,
      phone1_type: "home",
      phone2: null,
      phone2_type: null,
      included_in_employee_count: 1,
      can_access_system: 1,
      receives_benefits: 1,
      employee_wizard: 0,
      accepted_terms: "0000-00-00 00:00:00",
      created_at: "2021-07-28T21:02:52.000000Z",
      updated_at: "2021-07-28T21:02:52.000000Z",
      job_reports_to: null,
      job_location: null,
      job_department: null,
      employee_status: null,
      position_title: null,
      bp_eligibility_date: "07/2021",
      bp_bonus_percentage: null,
      bp_employee_type: "admin/assistant",
      bp_eligible: 1,
      main_role: "employee",
      pivot: { plan_id: 38, user_id: 16255 },
      roles: [
        {
          id: 5,
          name: "employee",
          display_name: "Employee",
          description: "",
          created_at: "2015-09-02T11:01:50.000000Z",
          updated_at: "2015-09-02T11:01:50.000000Z",
          pivot: { user_id: 16255, role_id: 5 }
        }
      ],
      monthlyData: [
        {
          id: 404,
          month_id: 235,
          user_id: 16255,
          hours_worked: "140.00",
          gross_pay: "2500.00",
          amount_received: "0.00",
          percentage: "0.00",
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 1,
          year: 2021
        },
        {
          id: 405,
          month_id: 236,
          user_id: 16255,
          hours_worked: "150.00",
          gross_pay: "2700.00",
          amount_received: 0,
          percentage: 5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 2,
          year: 2021
        },
        {
          id: 406,
          month_id: 237,
          user_id: 16255,
          hours_worked: "147.00",
          gross_pay: "2600.00",
          amount_received: 0,
          percentage: 100,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 3,
          year: 2021
        },
        {
          id: 407,
          month_id: 238,
          user_id: 16255,
          hours_worked: "142.00",
          gross_pay: "2300.00",
          amount_received: 0,
          percentage: 1,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 4,
          year: 2021
        },
        {
          id: 408,
          month_id: 239,
          user_id: 16255,
          hours_worked: "139.00",
          gross_pay: "2100.00",
          amount_received: 0,
          percentage: 0.5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 5,
          year: 2021
        },
        {
          id: 409,
          month_id: 240,
          user_id: 16255,
          hours_worked: "142.00",
          gross_pay: "2300.00",
          amount_received: 5.944999999999999,
          percentage: 0.1,
          created_at: "2021-07-28T21:02:53.000000Z",
          updated_at: "2021-07-28T21:02:53.000000Z",
          month: 6,
          year: 2021
        }
        // {
        //   id: 410,
        //   month_id: 241,
        //   user_id: 16255,
        //   hours_worked: "147.00",
        //   gross_pay: "2600.00",
        //   amount_received: 2037.5,
        //   percentage: 50,
        //   created_at: "2021-07-28T21:03:35.000000Z",
        //   updated_at: "2021-07-28T21:03:35.000000Z",
        //   month: 7,
        //   year: 2021
        // }
      ]
    },
    {
      id: 16256,
      business_id: 2360,
      classification_id: null,
      email: `jeff+turner_hyg+${Date.now()}@helloworlddevs.com`,
      last_login: null,
      hired: null,
      rehired: null,
      job_title_id: null,
      dob: null,
      age: null,
      status: "enabled",
      terminated: null,
      can_rehire: null,
      benefit_date: null,
      years_of_service: 0,
      benefit_years_of_service: 0,
      on_leave: null,
      first_name: "Tina",
      middle_name: null,
      last_name: "Turner-Hyg",
      suffix: null,
      prefix: null,
      address1: null,
      address2: null,
      city: null,
      state: null,
      postal_code: null,
      phone1: null,
      phone1_type: "home",
      phone2: null,
      phone2_type: null,
      included_in_employee_count: 1,
      can_access_system: 1,
      receives_benefits: 1,
      employee_wizard: 0,
      accepted_terms: "0000-00-00 00:00:00",
      created_at: "2021-07-28T21:02:52.000000Z",
      updated_at: "2021-07-28T21:02:52.000000Z",
      job_reports_to: null,
      job_location: null,
      job_department: null,
      employee_status: null,
      position_title: null,
      bp_eligibility_date: "07/2021",
      bp_bonus_percentage: null,
      bp_employee_type: "hygienist",
      bp_eligible: 1,
      main_role: "employee",
      pivot: { plan_id: 38, user_id: 16256 },
      roles: [
        {
          id: 5,
          name: "employee",
          display_name: "Employee",
          description: "",
          created_at: "2015-09-02T11:01:50.000000Z",
          updated_at: "2015-09-02T11:01:50.000000Z",
          pivot: { user_id: 16256, role_id: 5 }
        }
      ],
      monthlyData: [
        {
          id: 411,
          month_id: 235,
          user_id: 16256,
          hours_worked: "145.00",
          gross_pay: "5500.00",
          amount_received: "0.00",
          percentage: "0.00",
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 1,
          year: 2021
        },
        {
          id: 412,
          month_id: 236,
          user_id: 16256,
          hours_worked: "152.00",
          gross_pay: "5600.00",
          amount_received: 0,
          percentage: 5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 2,
          year: 2021
        },
        {
          id: 413,
          month_id: 237,
          user_id: 16256,
          hours_worked: "147.00",
          gross_pay: "5400.00",
          amount_received: 0,
          percentage: 100,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 3,
          year: 2021
        },
        {
          id: 414,
          month_id: 238,
          user_id: 16256,
          hours_worked: "145.00",
          gross_pay: "5500.00",
          amount_received: 0,
          percentage: 1,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 4,
          year: 2021
        },
        {
          id: 415,
          month_id: 239,
          user_id: 16256,
          hours_worked: "152.00",
          gross_pay: "5600.00",
          amount_received: 0,
          percentage: 0.5,
          created_at: "2021-07-28T21:02:52.000000Z",
          updated_at: "2021-07-28T21:02:52.000000Z",
          month: 5,
          year: 2021
        },
        {
          id: 416,
          month_id: 240,
          user_id: 16256,
          hours_worked: "147.00",
          gross_pay: "5400.00",
          amount_received: 5.944999999999999,
          percentage: 0.1,
          created_at: "2021-07-28T21:02:53.000000Z",
          updated_at: "2021-07-28T21:02:53.000000Z",
          month: 6,
          year: 2021
        }
        // {
        //   id: 417,
        //   month_id: 241,
        //   user_id: 16256,
        //   hours_worked: "145.00",
        //   gross_pay: "5500.00",
        //   amount_received: 2037.5,
        //   percentage: 50,
        //   created_at: "2021-07-28T21:03:35.000000Z",
        //   updated_at: "2021-07-28T21:03:35.000000Z",
        //   month: 7,
        //   year: 2021
        // }
      ]
    }
  ],
  funds: [],
  activeEmployee: null,
  errors: { employees: null, funds: null }
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
const test = require('ava');

test('foo', t => {
	t.pass();
});

//import { isInLastSixMonths, compareMonths } from './utils'
//
//describe('utility functions', () => {
//    it('checks whether months are within the plan date range', () => {
//
//        let plan1 = { start_year: 2018, start_month: 2 };
//        expect(isInLastSixMonths(plan1, { year: 2017, month: 1 })).toBe(false);
//        expect(isInLastSixMonths(plan1, { year: 2017, month: 7,  })).toBe(false);
//        expect(isInLastSixMonths(plan1, { year: 2017, month: 8 })).toBe(true);
//        expect(isInLastSixMonths(plan1, { year: 2017, month: 9 })).toBe(true);
//        expect(isInLastSixMonths(plan1, { year: 2017, month: 10 })).toBe(true);
//        expect(isInLastSixMonths(plan1, { year: 2017, month: 11 })).toBe(true);
//        expect(isInLastSixMonths(plan1, { year: 2017, month: 12 })).toBe(true);
//        expect(isInLastSixMonths(plan1, { year: 2018, month: 1 })).toBe(true);
//        expect(isInLastSixMonths(plan1, { year: 2018, month: 2 })).toBe(false);
//        expect(isInLastSixMonths(plan1, { year: 2018, month: 9 })).toBe(false);
//
//        let plan2 = { start_year: 2000, start_month: 6 };
//        expect(isInLastSixMonths(plan2, { year: 1999, month: 11 })).toBe(false);
//        expect(isInLastSixMonths(plan2, { year: 1999, month: 12 })).toBe(true);
//        expect(isInLastSixMonths(plan2, { year: 2000, month: 1 })).toBe(true);
//        expect(isInLastSixMonths(plan2, { year: 2000, month: 5 })).toBe(true);
//        expect(isInLastSixMonths(plan2, { year: 2000, month: 6 })).toBe(false);
//    });
//
//    it('can compare months for sorting', () => {
//        // basic assertions
//        expect(compareMonths({ year: 2018, month: 2 }, { year: 2018, month: 1 })).toBeGreaterThan(0);
//        expect(compareMonths({ year: 2018, month: 1 }, { year: 2018, month: 1 })).toBe(0);
//        expect(compareMonths({ year: 2018, month: 1 }, { year: 2018, month: 2 })).toBeLessThan(0);
//
//        // edge cases (wrap around years, correct handling of 1 and 12)
//        expect(compareMonths({ year: 2018, month: 1 }, { year: 2017, month: 12 })).toBeGreaterThan(0);
//        expect(compareMonths({ year: 2018, month: 12 }, { year: 2018, month: 1 })).toBeGreaterThan(0);
//        expect(compareMonths({ year: 1999, month: 12 }, { year: 2018, month: 1 })).toBeLessThan(0);
//
//        // invalid edge cases - month out of range - these should return zero
//        expect(compareMonths({ year: 2018, month: 13 }, { year: 2018, month: 1 })).toBe(0);
//        expect(compareMonths({ year: 2018, month: 1 }, { year: 2018, month: 0 })).toBe(0);
//
//        // test it with 1 real array
//        let values = [
//            { "year": 2018, "month": 1 },
//            { "year": 2017, "month": 12 },
//            { "year": 2018, "month": 6 },
//            { "year": 2016, "month": 1 },
//            { "year": 2018, "month": 3 },
//        ];
//        values.sort((mon1, mon2) => compareMonths(mon1, mon2));
//        expect(values[0].year).toBe(2016);
//        expect(values[1].year).toBe(2017);
//        expect(values[1].month).toBe(12);
//        expect(values[2].year).toBe(2018);
//        expect(values[2].month).toBe(1);
//        expect(values[3].year).toBe(2018);
//        expect(values[3].month).toBe(3);
//        expect(values[4].year).toBe(2018);
//        expect(values[4].month).toBe(6);
//
//    });
//});

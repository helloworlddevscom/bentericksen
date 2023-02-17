const dollarFormatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0
});

const percentageFormatter = new Intl.NumberFormat("en-US", {
  style: 'percent',
  minimumFractionDigits: 1,
  maximumFractionDigits: 2
})

const percentageFormatter2 = (value, decimals = 1) => {
  if (typeof value === 'undefined' || isNaN(value)) {
    return '-'
  }

  value = parseFloat(value)

  return isNaN(value) ? '-' : value.toFixed(decimals) + '%'
}


/**
 *
 * @param objDom
 * @param keys
 * @param shift
 */
export function validateSnapshot (objDom, keys, { shift = 0 }, activeSixMonth) {

  for (const [key, value] of Object.entries(keys)) {
    // NEED to match up to row instance
    // const bonusRow = hygiene[2].$$('td');
    const evalRow = objDom[value.row].$$('td');
    evalRow.forEach((data, index) => {
      const monthKey = index + shift;
      // Skip first pass data[0] no matter what.   This is the row headers.  No data
      if (key === 'production_amount' || key === 'collection_amount') {
        if (index > 0) {
          const dataDom = data.$(`input.${value.id}`);
          const result = dollarFormatter.format(activeSixMonth[monthKey - 1][key]);
          expect(dataDom).toHaveValue(result);
        }
      } else {
        // For non-collections, first data is empty (rolling average min is 2 months).  No data.
        if (index > 1) {
          // For non-average months (with '-'), just skip comparison check
          if (activeSixMonth[index - 1][key] !== '-') {
            let result;
            if (value.type === 'percentage') {
              result = percentageFormatter2(activeSixMonth[monthKey - 1][key] * 100)
            } else {
              result = dollarFormatter.format(activeSixMonth[monthKey - 1][key])
            }
            const dataDom = data.$(`input.${value.id}`);
            expect(dataDom).toHaveValue(result);
          }
        }
      }
    })
  }
}
import Base from './BaseService'

function getUrl (action) {
  let url = '';
  switch (action) {
    case 'initialSetup':
      url += 'save-initial-setup';
      break;
    case 'initialSetupDraft':
      url += 'save-initial-setup-draft';
      break;
    case 'planData':
      url += 'save-plan-data';
      break;
    case 'addFund':
      url += 'save-fund';
      break;
    case 'updateFund':
      url += 'update-fund';
      break;
    case 'removeFund':
      url += 'remove-fund';
      break;
    case 'addEmployee':
      url += 'add-employee';
      break;
    case 'removeEmployee':
      url += 'remove-employee';
      break;
    case 'createEmployee':
      url += 'create-employee';
      break;
    case 'bonusPercentage':
      url += 'save-bonus-percentage';
      break;
    case 'bonusPercentageDraft':
      url += 'save-bonus-percentage-draft';
      break;
    case 'saveMonthData':
      url += 'save-month-data';
      break;
    case 'generateReport':
      url += 'generate-report';
      break;
  }
  if (!url) {
    return
  }
  return '/bonuspro/' + url;
}

export default {
  save: (data, action) => {
    const url = getUrl(action);
    if (!url) {
      return Promise.resolve({})
    }
    return Base.post(url, data);
  },
  delete: (data, action) => {
    const url = getUrl(action);
    if (!url) {
      return Promise.resolve({})
    }
    return Base.delete(url, data);
  },
  generateReport: (data) => {
    const url = getUrl('generateReport');

    return Base.post(url, data);
  }
}


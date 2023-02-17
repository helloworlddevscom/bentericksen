import ui from './modules/ui'
import global from './modules/global'
import initialSetup from './modules/initialSetup'
import planData from './modules/planData'
import employeesAndFunds from './modules/employeesAndFunds'
import bonusPercentage from './modules/bonusPercentage'

export const storeConfig = {
  strict: process.env.NODE_ENV !== 'production',
  namespaced: true,
  modules: {
    ui,
    global,
    initialSetup,
    planData,
    employeesAndFunds,
    bonusPercentage
  }
}

export default storeConfig

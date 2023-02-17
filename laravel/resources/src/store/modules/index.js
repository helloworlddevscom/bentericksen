const requireModule = require.context('.', true, /index.js$/)
const modules = {}

requireModule.keys().forEach(fileName => {
  if (fileName === './index.js') {
    return
  }

  const moduleName = fileName.replace(/(\.\/|\/index\.js)/g, '')

  modules[moduleName] = {
    namespaced: true,
    ...requireModule(fileName).default
  }
})

export default modules

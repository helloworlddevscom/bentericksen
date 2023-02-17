import Vue from 'vue'
import { App, plugin } from '@inertiajs/inertia-vue'
import store from '@/store'
import adminLegacyLoader from '@/lib/legacy'
import userLegacyStarted from '@/lib/legacy/user'
import '@/directives/global'

Vue.config.productionTip = false

Vue.use(plugin)

const el = document.getElementById('app')

new Vue({
  store,
  render: h => h(App, {
    props: {
      initialPage: JSON.parse(el.dataset.page),
      resolveComponent: (name) => {
        return import('@/pages/' + name + '.vue').then(async (module) => {
          const page = module.default
          if (page.layout) {
            return page
          }
          const Layout = await import('@/layouts/main')
          page.layout = Layout.default
          return page
        })
      }
    }
  })
}).$mount(el)

window.location.pathname.includes('/admin') && adminLegacyLoader()
window.location.pathname.includes('/user') && userLegacyStarted()


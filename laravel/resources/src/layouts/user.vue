<template>
  <main>
    <span v-html="banner"></span>
    <Header :user="user" />

    <Menu
      :policy_updates_run="policy_updates_run"
      :policies_pending="policies_pending"
      :employee_count_warning="employee_count_warning"
      :manual_regenerate="manual_regenerate"
      :benefit_create_warning="benefit_create_warning" />
    
    <Banner v-if="policy_updates.length && !(hasRole('manager', impersonated) && !permissions('m121', impersonated))" :policies_count="policy_updates.length" />

    <article>
      <slot></slot>
    </article>

    <Footer />
  </main>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import Footer from '@/components/Footer'
import Header from '@/components/Admin/Layout/Header'
import Menu from '@/components/User/Layout/Menu'
import acl from '@/mixins/acl'

import '@/assets/styles/global.scss'
import '@/assets/styles/global-user.scss'
import 'font-awesome/scss/font-awesome.scss'
import '@/lib/bootstrap/bootstrap-3-3-1.min.css'

import '@/filters/global'

export default {
  components: {
    Link,
    Header,
    Menu,
    Footer,
    Banner: () => import('@/components/User/Layout/Banner')
  },
  mixins: [acl],
  props: {
    user: {
      type: Object
    },
    config: {
      type: Object
    },
    impersonated: {
      type: Object
    },
    manual: {
      type: String
    },
    viewUser: {
      type: Object
    },
    banner: {
      type: String
    },
    policy_updates_run: {
      type: Boolean
    },
    policies_pending: {
      type: Boolean
    },
    policy_updates: {
      type: Array
    },
    employee_count_warning: {
      type: Object
    },
    manual_regenerate: {
      type: Boolean
    },
    benefit_create_warning: {
      type: Boolean
    },
    storeDispatch: {
      type: Array,
      default: () => ([])
    }
  },
  data() {
    return {
      userFields: ['user', 'impersonated', 'manual', 'viewUser', 'banner']
    }
  },
  created() {
    const userData = this.userFields
      .reduce((out, key) => {
        out[key] = this.$props[key]
        return out
      }, {})
  
    this.$store.dispatch('user/setState', userData)
    this.$store.dispatch('config/setState', this.config)

    if (this.storeDispatch.length) {
      this.$store.dispatch(this.storeDispatch[0], this.storeDispatch[1])
    }
  }
}
</script>
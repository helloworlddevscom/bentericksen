<template>
  <main>
    <Header :user="user" />

    <Menu />
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
import Menu from '@/components/Admin/Layout/Menu'

import '@/assets/styles/global.scss'
import '@/assets/styles/global-admin.scss'
import 'font-awesome/scss/font-awesome.scss'
import '@/lib/bootstrap/bootstrap-3-3-1.min.css'

import '@/filters/global'

export default {
  components: {
    Link,
    Header,
    Menu,
    Footer,
  },
  props: {
    _response: {
      type: Object
    },
    user: {
      type: Object
    }

  }
}
</script>
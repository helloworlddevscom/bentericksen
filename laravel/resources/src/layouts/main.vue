<template>
  <main>
    <Header/>

    <article>
      <slot></slot>
    </article>

    <Footer />
  </main>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'
import Footer from '@/components/Footer'
import Header from '@/components/Header'

export default {
  components: {
    Link,
    Header,
    Footer,
  },
  props: {
    _response: {
      type: Object
    }
  }
}
</script>
<template>
  <div>Hello new Employee view</div>  
</template>
<script>
import Layout from '@/layouts/user'

export default {
  layout: Layout,
  props: {
    business: {
      type: Object
    }
  },
}
</script>

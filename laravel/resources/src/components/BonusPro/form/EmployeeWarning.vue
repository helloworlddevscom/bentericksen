<template>
  <div>
    <b-modal v-model="showModal" @hidden="$emit('cancel')" no-fade>
          <div class="my-4">
            <p>Are you sure you want to remove this employee?</p>
          </div>
          <template slot="modal-footer">
            <button @click.prevent="cancel"
                    class="btn btn-secondary">Cancel</button>
            <button @click.prevent="confirm"
                    class="btn btn-danger">Remove Employee</button>
          </template>
    </b-modal>
    <div v-if="showModal" class="modal-backdrop"></div>
  </div>
</template>

<script>
  import { BModal } from 'bootstrap-vue'

  export default {
    components: {
      'b-modal': BModal
    },
    props: {
      show: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        showModal: false
      }
    },
    watch: {
      show: {
        immediate: true,
        handler (value) {
          this.showModal = value
        }
      }
    },
    methods: {
      confirm () {
        this.$emit('confirm')
      },
      cancel () {
        this.$emit('cancel')
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import '~bootstrap/scss/functions';
  @import '~bootstrap/scss/variables';
  @import '~bootstrap/scss/mixins';
  @import "~bootstrap/scss/modal";
  @import "~bootstrap-vue/src/components/modal/modal";
</style>


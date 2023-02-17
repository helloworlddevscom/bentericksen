<template>
  <div class="modal fade in manage-card-modal" :class="{ 'active': isActive }" id=" modalPrimary
  " tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
          </button>
        </div>
        <div class="modal-body" v-if="modalCopy==='replace'">
          <div class="row">
            <div class="col-md-12 text-center">
              <p>This will {{ modalCopy }} current card information on file.  Proceed?</p>
            </div>
          </div>
        </div>
        <div class="modal-body" v-if="modalCopy==='delete'">
          <div class="row">
            <div class="col-md-12 text-center">
              <div class="row radio">
                <label><input name="deleteOption" type="radio" v-model="deleteOption" v-bind:value="'invoice'" checked>Leave the card on file, stop Auto-Pay, switch to invoicing.</label>
              </div>
              <div class="row radio">
                <label><input name="deleteOption" type="radio" v-model="deleteOption" v-bind:value="'cancel'">Remove the card completely; stop the subscription.</label>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer text-center">
          <button type="button" class="btn btn-default btn-primary" data-dismiss="modal"
                  @click="hideManageCardModal('cancel')">CANCEL
          </button>
          <button type="submit" class="btn btn-default btn-primary" @click="hideManageCardModal('confirm')">CONFIRM
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from '@/lib/legacy/event-bus'

export default {
  name: "ManageCardModal",
  props: {
    modalCopy: String
  },
  data () {
    return {
      isActive: true,
      deleteOption: 'invoice'
    }
  },
  methods: {
    hideManageCardModal (action) {
      this.isActive = false;
      this.$store.dispatch('payments/displayManageCardModal', false);
      if (this.modalCopy === "replace" && action === "confirm") {
        this.$store.dispatch('payments/replaceMode', true);
        this.$store.dispatch('payments/manageCard', false);
        return;
      }
      if (this.modalCopy !== "delete" || action !== "confirm") {
        return;
      }
      if (this.deleteOption === "invoice") {
        EventBus.$emit('invoiceSubscription');
        return;
      }
      if (this.deleteOption === "cancel") {
        EventBus.$emit('cancelSubscription');
        EventBus.$emit('deleteCC');
      }
    }
  }
}
</script>

<style scoped>
.active {
  display: block;
}
</style>

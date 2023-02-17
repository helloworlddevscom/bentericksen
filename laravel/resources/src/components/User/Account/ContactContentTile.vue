<template>
  <div class="mini-content account_box even">
    <div class="row">
      <label for="business_name" class="col-md-4 col-md-offset-1 control-label">Name:</label>
      <div class="col-md-6 padding-top no-padding-left">
        {{ business.name }}
      </div>
    </div>
    <div class="row">
      <label for="business_address1" class="col-md-4 col-md-offset-1 control-label">Address 1:</label>
      <div class="col-md-6 padding-top no-padding-left">
        {{ business.address1 }}
      </div>
    </div>
    <div class="row">
      <label for="business_address2" class="col-md-4 col-md-offset-1 control-label">Address 2:</label>
      <div class="col-md-6 padding-top no-padding-left">
        {{ business.address2 }}
      </div>
    </div>
    <div class="row">
      <label for="business_city" class="col-md-4 col-md-offset-1 control-label">City, State, ZIP:</label>
      <div class="col-md-2 padding-top no-padding-left">
        {{ business.city }}
      </div>
      <div class="col-md-2 padding-top text-center">
        {{ business.state }}
      </div>
      <div class="col-md-2 padding-top no-padding-left">
        {{ business.postal_code }}
      </div>
    </div>
    <div class="row">
      <label for="business_phone1" class="col-md-4 col-md-offset-1 control-label">Phone 1:</label>
      <div class="col-md-4 padding-top no-padding-left">
        {{ business.address1}}
      </div>
      <div class="col-md-3 padding-top no-padding-left">
        {{ business.phone1_type.toUpperCase() }}
      </div>
    </div>
    <div class="row">
      <label for="business_phone2" class="col-md-4 col-md-offset-1 control-label">Phone 2:</label>
      <div class="col-md-4 padding-top no-padding-left">
        {{ business.phone2 }}
      </div>
      <div class="col-md-3 padding-top no-padding-left">
        {{ business.phone2_type.toUpperCase() }}
      </div>
    </div>
    <div class="row">
      <label for="business_phone3" class="col-md-4 col-md-offset-1 control-label">Phone 3:</label>
      <div class="col-md-4 padding-top no-padding-left">
        {{ business.phone3 }}
      </div>
      <div class="col-md-3 padding-top no-padding-left">
        {{ business.phone3_type.toUpperCase() }}
      </div>
    </div>
    <div class="row">
      <label for="business_fax" class="col-md-4 col-md-offset-1 control-label">Fax:</label>
      <div class="col-md-6 padding-top no-padding-left">
        {{ business.fax }}
      </div>
    </div>
    <div class="row">
      <label for="business_primary_type" class="col-md-4 col-md-offset-1 control-label">Type:</label>
      <div class="col-md-6 padding-top no-padding-left">
        {{ business.type }}
      </div>
    </div>
    <div class="row">
      <label for="business_webite" class="col-md-4 col-md-offset-1 control-label">Website:</label>
      <div class="col-md-6 padding-top no-padding-left">
        <a target="_blank">{{ business.website }}</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 text-center buttons">
        <a @click="editContactInfo" class="btn btn-default btn-primary btn-xs modal-button" id="edit_contact_info">EDIT</a>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  props: {
    business: Object
  },
  methods: {
    editContactInfo() {
      window.location.href = `/user/account/${this.business.id}/edit`
    }
  }
}
</script>
<style lang="scss">
h4 {
  font-size: 18px;
  margin-top: 10px;
  margin-bottom: 10px;
}

.mini-heading {
  text-align: center;
  background: var(--main-primary);
  border-radius: 5px 5px 0 0;
  color: #fff;
  padding: 1px;
  margin-top: 12px !important;

  .account_box {
    min-height: 293px;
  }

  .even {
    background: #f4f9ff !important;
  }
}
</style>

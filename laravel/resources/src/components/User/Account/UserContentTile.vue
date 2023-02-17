<template>
  <div class="mini-content account_box even users">
    <div class="odd">
      <div class="row">
        <label for="business_primary_fullname" class="col-md-4 col-md-offset-1 control-label no-padding-both">Primary/Owner
          Name:</label>
        <div class="col-md-6 padding-top">
          {{ business.primary.prefix }} {{ business.primary.first_name }} {{ business.primary.middle_name }}
          {{ business.primary.last_name }} {{ business.primary.suffix }}
        </div>
      </div>
      <div class="row">
        <label for="business_contact_email" class="col-md-4 col-md-offset-1 control-label no-padding-both">Primary/Owner
          Email:</label>
        <div class="col-md-6 padding-top" style="overflow:hidden;">
          <a :href="`mailto:${ business.primary.email }`">{{ business.primary.email }}</a>
        </div>
      </div>
    </div>


    <div v-for="secondary in getBusinessSecondaries(true)">
      <div class="row">
        <label for="business_secondary_1_first_name" class="col-md-4 col-md-offset-1 control-label no-padding-both">Secondary
          {{ $index + 1 }} Name:</label>
        <div class="col-md-6 padding-top">
          {{ secondary.prefix }} {{ secondary.first_name }} {{ secondary.middle_name }} {{ secondary.last_name }}
          {{ secondary.suffix }}
        </div>
      </div>
      <div class="row">
        <label for="business_secondary_1_email" class="col-md-4 col-md-offset-1 control-label no-padding-both">Secondary
          {{ $index + 1 }} Email:</label>
        <div class="col-md-6 padding-top" style="overflow:hidden;">
          <a :href="`mailto:${ secondary.email }`">{{ secondary.email }}</a>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-md-offset-6 buttons">
          <div class="col-md-5">
            <button type="button" class="btn btn-default btn-primary btn-xs btn-modal" data-toggle="modal"
                    :data-target="`#modalSecondary${ secondary.index }`">EDIT
            </button>
          </div>
          <div class="col-md-6">
            <form method="post" :action="`/user/account/secondary/${ secondary.index }`">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="DELETE">
              <button type="submit" class="btn btn-default btn-primary btn-xs">DELETE</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="odd" v-for="secondary in getBusinessSecondaries(false)">
      <div class="row">
        <div class="col-md-12 text-center buttons">
          <button type="button" class="btn btn-default btn-primary btn-xs btn-modal" data-toggle="modal"
                  :data-target="`#modalSecondary${ secondary.index }`">ADD ANOTHER EMAIL
          </button>
        </div>
      </div>
    </div>

    <div v-for="secondary in business.secondary">
      <div class="modal fade" :id="`modalSecondary${ secondary.index }`" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            
            <form method="POST" :action="`/user/account/secondary/${business.id}`" accept-charset="UTF-8">
            <div class="edit edit_status">
              <div class="edit edit_salary">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title text-center" id="modalLabel">Edit Contact Information</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="row">
                          <label :for="`prefix${secondary.index}`" class="col-md-4 col-md-offset-1 control-label">Secondary {{ secondary.index }} Prefix:</label>
                          <div class="col-md-5">
                            <input :id="`prefix${secondary.index}`" class="form-control" placeholder="Prefix" :name="`secondary[${secondary.index}][prefix]`" type="text">
                          </div>
                        </div>
                        <div class="row">
                          <label :for="`firstName${secondary.index}`" class="col-md-4 col-md-offset-1 control-label">Secondary {{ secondary.index }} First Name:</label>
                          <div class="col-md-5">
                            <input :id="`firstName${secondary.index}`" class="form-control" placeholder="First Name" :name="`secondary[${secondary.index}][first_name]`" type="text">
                          </div>
                        </div>
                        <div class="row">
                          <label :for="`middleName${secondary.index}`" class="col-md-4 col-md-offset-1 control-label">Secondary {{ secondary.index }} Middle Name:</label>
                          <div class="col-md-5">
                            <input :id="`middleName${secondary.index}`" class="form-control" placeholder="Middle Name" :name="`secondary[${secondary.index}][middle_name]`" type="text">
                          </div>
                        </div>
                        <div class="row">
                          <label :for="`lastName${secondary.index}`" class="col-md-4 col-md-offset-1 control-label">Secondary {{ secondary.index }} Last Name:</label>
                          <div class="col-md-5">
                            <input :id="`lastName${secondary.index}`" class="form-control" placeholder="Last Name" :name="`secondary[${secondary.index}][last_name]`" type="text">
                          </div>
                        </div>
                        <div class="row">
                          <label :for="`suffix${secondary.index}`" class="col-md-4 col-md-offset-1 control-label">Secondary {{ secondary.index }} Suffix Name:</label>
                          <div class="col-md-5">
                            <input :id="`suffix${secondary.index}`" class="form-control" placeholder="Suffix" :name="`secondary[${secondary.index}][suffix]`" type="text">
                          </div>
                        </div>
                        <div class="row">
                          <label :for="`email${secondary.index}`" class="col-md-4 col-md-offset-1 control-label">Secondary {{ secondary.index }} Email:</label>
                          <div class="col-md-5">
                            <input :id="`email${secondary.index}`" class="form-control" placeholder="email" :name="`secondary[${secondary.index}][email]`" type="text">
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer text-center">
                  <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">CANCEL</button>
                  <button type="submit" class="btn btn-default btn-primary">SAVE</button>
                </div>
              </div>
            </div>
            </form>
          </div>
        </div>
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
    editContactInfo () {
      window.location.href = `/user/account/${this.business.id}/edit`
    },
    getBusinessSecondaries (active) {
      let count = 0;
      return this.business.secondary.filter((sec) => {
        if (active) {
          return sec.is_enabled
        }
        if(!sec.is_enabled && count < 1) { // we only want 1 of possibly 2 disabled secondaries (1 edit button at a time)
          count++;
          return sec;
        }
      })
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

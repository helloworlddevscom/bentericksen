<template>
  <div id="main_body">
    
    <div class="container" id="main">
      <div class="row main_wrap">
        <div class="col-md-12 content">
          <div class="row">
            <h3 class="text-center">Account Information</h3>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="mini-heading">
                <h4>Contact Information</h4>
              </div>
              <ContactContentTile :business="business"/>
            </div>
            <div class="col-md-4 no-padding-both">
              <div class="mini-heading">
                <h4>Users Who Receive Business Emails</h4>
              </div>
              <div class="mini-content account_box even">
                <UserContentTile :business="business" />
              </div>
            </div>
            <div class="col-md-4">
              <AnnualSupportAgreement :business="business" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <LicenceAgreementModal />
  </div>
</template>
<script>

import Layout from '@/layouts/user'
import ContactContentTile from '@/components/User/Account/ContactContentTile'
import UserContentTile from '@/components/User/Account/UserContentTile'
import LicenseContentTile from '@/components/User/Account/LicenseContentTile'
import AnnualSupportAgreement from '@/components/User/Account/AnnualSupportAgreement'
import LicenceAgreementModal from '@/components/User/Account/modals/LicenceAgreement.vue'

export default {
  components: {
    ContactContentTile,
    UserContentTile,
    LicenseContentTile,
    AnnualSupportAgreement,
    LicenceAgreementModal
  },
  props: {
    business: {
      type: Object
    }
  },
  layout: Layout,
  async mounted() {
    await import('@/lib/jquery')
    await import('@/lib/bootstrap/bootstrap-3-3-1.min')
  }
}
</script>

<template>
  <div>
    <div class="mini-heading">
        <h4>Annual Support Agreement</h4>
    </div>
    <div class="content account_box even">
        <div class="table-responsive odd">
            <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="form-group">
                        Type
                    </th>
                    <th class="form-group">
                        Expires
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="business.asa">
                    <td>{{ business.asa.type | ucfirst }}</td>
                    <td>{{ business.asa.expiration }}</td>
                  </tr>
                </tbody>
            </table>
            
            <div class="payment-ui" v-if="business.enable_payments">
                <table class="table table-striped stacked margin-bottom-none">
                    <thead>
                    <tr>
                        <th class="form-group">
                            Status
                        </th>
                        <th class="form-group">
                            Auto Pay?
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                      <tr v-if="business.asa">
                          <td>{{ business.status | ucfirst }}</td>
                          <td>{{ business.payment_type === "subscription" ? "Yes" : "No" }}</td>
                      </tr>
                    </tbody>
                </table>
                <div class="content odd">
                    <div class="row text-center">
                        <strong>Payment Type</strong><br />
                        <span v-if="business.defaultCardInfo">
                          {{ business.defaultCardInfo.brand }} {{ business.defaultCardInfo.last4 }}
                        </span>
                        <span v-else>
                          None
                        </span>
                    </div>
                </div>
                <div class="content even">
                    <div class="row">
                        <div class="col-md-12" id="payment">
                            <Payment />
                        </div>
                        <!--
                          <div class="col-md-12 text-center buttons">
                            <a href="http://bentericksen.com/annual-support-agreements.html" target="_blank" class="btn btn-default btn-primary btn-xs btn-modal" id="edit_asa_info">RENEW / UPGRADE</a>
                          </div>
                        -->                                
                    </div>
                </div>
            </div>
        </div>
        <div class="row text-center">
            <h4 class="col-md-12">License Agreement</h4>
            <div class="col-md-12 text-center">
                <button type="button" class="btn btn-default btn-primary btn-xs btn-modal" id="view_license_agreement" data-toggle="modal" data-target="#licenseAgreement">
                    <em>view > ></em></button>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import Payment from '@/components/Payment/Payment'

export default {
  props: {
    business: {
      type: Object,
      required: true
    }
  },
  components: {
    Payment
  }
}
</script>

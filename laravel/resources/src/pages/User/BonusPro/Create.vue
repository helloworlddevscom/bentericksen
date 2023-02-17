<template>
  <div id="main_body">
    <div class="container" id="main">
        <div class="row main_wrap">
            <div class="col-md-12 content">
                <div class="content">
                    <div id="app">
                        <div class="col-md-12 form-horizontal content">
                            <BonusProPlanCreateForm
                                :plan-data="plan"
                                :bonus-percentage="plan"
                                :states="states"
                                :business-users="businessUsers"
                                :created-by="impersonated.id"
                                :business-id="impersonated.business_id" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import Layout from '@/layouts/user'
import BonusProPlanCreateForm from '@/components/BonusPro/CreateForm'

export default {
  props: {
    plan: {
      type: Object
    },
    states: {
      type: Object
    },
    businessUsers: {
      type: Array
    },
    impersonated: {
      type: Object
    }
  },
  components: {
    BonusProPlanCreateForm
  },
  layout: Layout
}
</script>
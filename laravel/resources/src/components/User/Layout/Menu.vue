<template>
  <div id="main_navigation_wrap">
      <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
          <div class="row">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#admin_navigation">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
            </div>
            <div class="collapse navbar-collapse center-block" id="admin_navigation">
              <ul class="nav navbar-nav">
                  <li v-if="impersonated.bonuspro_only">
                    <span class="nav-slot disabled"><i class="fa fa-tachometer navigation_img"></i>DASHBOARD</span>
                  </li>
                  <li v-else-if="permissions('m240', impersonated)">
                    <a href="/user">
                      <i class="fa fa-tachometer navigation_img"></i>DASHBOARD
                    </a>
                  </li>
                  <li>
                    <span v-if="impersonated.bonuspro_only" class="nav-slot disabled">
                      <i class="fa fa-copy navigation_img"></i>Policies
                    </span>
                    <template v-else>
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-copy navigation_img"></i>POLICIES
                      </a>
                      <ul class="dropdown-menu">
                        <li v-if="!manual">
                          <a v-if="policy_updates_run" href="#" class="js-force-modal"
                              data-toggle='modal'
                              data-dismiss='modal'
                              data-target='#modalPolicyUpdatesReminder'>CREATE POLICY
                              MANUAL</a>
                          <a v-else-if="policies_pending" href="#" data-toggle='modal' data-dismiss='modal' data-target='#modalPoliciesPending'>CREATE POLICY MANUAL</a>
                          <a href="/user/policies/createManual" class="createModal">CREATE POLICY MANUAL</a>
                        </li>
                        <li>
                          <a v-if="employee_count_warning" href="#" class="js-force-modal"
                            data-toggle='modal' data-dismiss='modal'
                            data-target='#modalEmployeeCountWarning'>VIEW/PRINT POLICY MANUAL</a>
                          <a v-else-if="policy_updates_run" href="#" class="js-force-modal"
                            data-toggle='modal' data-dismiss='modal'
                            data-target='#modalPolicyUpdatesReminder'>VIEW/PRINT POLICY MANUAL</a>
                          <a v-else href="/user/policies/manual" target="_blank">VIEW/PRINT POLICY MANUAL</a>
                        </li>
                        <li v-if="permissions('m120', impersonated) == 'View/Edit'">
                          <a v-if="policy_updates_run" href="#" class="js-force-modal" data-toggle='modal'
                            data-dismiss='modal' data-target='#modalPolicyUpdatesReminder'>POLICY EDITOR</a>
                          <a v-else-if="employee_count_warning" href="/user/policies" class="js-force-modal" data-toggle='modal'
                            data-dismiss='modal' data-target='#modalEmployeeCountWarning'>POLICY EDITOR</a>
                          <a v-else href="/user/policies">POLICY EDITOR</a>
                        </li>
                        <li v-if="permissions('m121', impersonated)">
                          <a v-if="manual_regenerate" href="#" class="js-force-modal"
                            data-toggle='modal'
                            data-dismiss='modal'
                            data-target='#modalManualOutOfDate'>
                            BENEFITS SUMMARY
                          </a>
                          <a v-else-if="policy_updates_run" href="#" class="js-force-modal"
                            data-toggle='modal'
                            data-dismiss='modal'
                            data-target='#modalPolicyUpdatesReminder'>
                            BENEFITS SUMMARY
                          </a>
                          <a v-else-if="!policy_updates_run && benefit_create_warning" href="#" class="js-force-modal"
                            data-toggle='modal'
                            data-dismiss='modal'
                            data-target='#modalBenefitsSummaryCreate'>
                            BENEFITS SUMMARY
                          </a>
                          <a v-else href="/user/policies/benefits-summary" target="_blank">
                            BENEFITS SUMMARY
                          </a>
                        </li>
                        <li v-if="hasRole('admin', viewUser)"><a href="/user/policies/reset" class="policiesReset">RESET POLICIES</a></li>
                      </ul>
                    </template>
                  </li>
                  <li>
                    <span v-if="impersonated.business && !impersonated.business.hrdirector_enabled"
                      class="nav-slot disabled">
                      <i class="fa fa-users navigation_img"></i>Employees
                    </span>
                    <template v-else>
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-users navigation_img"></i>EMPLOYEES
                      </a>
                      <ul class="dropdown-menu">
                        <li v-if="permissions('m140', impersonated)"><a href="/user/employees">EMPLOYEE LIST</a></li>
                        <li><a href="/user/employees/time-off-requests">TIME OFF REQUESTS</a></li>
                        <li><a href="/user/employees/number">EMPLOYEE NUMBER</a></li>
                        <li v-if="hasRole(['admin', 'consultant'], viewUser)"><a href="/user/employees/excel">EXCEL IMPORT</a></li>
                      </ul>
                    </template>
                  </li>
                  <li v-if="permissions('m180', impersonated) != 'No Access'">
                    <span v-if="impersonated.business && !impersonated.business.hrdirector_enabled"
                     class="nav-slot disabled"><i class="fa fa-file-text-o navigation_img"></i>FORMS</span>
                    <template v-else-if="hasRole('admin', viewUser)">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text-o navigation_img"></i>FORMS</a>
                      <ul class="dropdown-menu">
                          <li><a href="/user/forms">FORMS</a></li>
                          <li><a href="/user/forms/reset">RESET FORMS</a></li>
                      </ul>
                    </template>
                    <a v-else href="/user/forms"><i class="fa fa-file-text-o navigation_img"></i>FORMS</a>
                  </li>
                  <li v-if="permissions('m160', impersonated)">
                    <span v-if="impersonated.business && !impersonated.business.hrdirector_enabled"
                      class="nav-slot disabled">
                        <i class="fa fa-comment-o navigation_img"></i>JOB DESCRIPTIONS
                    </span>
                    <a v-else href="/user/job-descriptions">
                      <i class="fa fa-comment-o navigation_img"></i>JOB DESCRIPTIONS
                    </a>
                  </li>
                  <li v-if="impersonated.bonuspro_enabled">
                    <a href="/bonuspro" class="bonuspro--icon"><img src="/assets/images/bonuspro-icon-white.png">BonusPro</a>
                  </li>
                  <li v-if="impersonated.bonuspro_only">
                    <span class="nav-slot disabled"><i class="fa fa-wrench navigation_img"></i>TOOLS</span>
                  </li>
                  <li v-else>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-wrench navigation_img"></i>TOOLS
                    </a>
                    <ul class="dropdown-menu">
                        <li v-if="!(hasRole('manager', impersonated) && !permissions('m200', impersonated))">
                          <a href="/user/faqs">HR FAQ'S</a>
                        </li>
                        <li v-if="!(hasRole('manager', impersonated) && !permissions('m201', impersonated))">
                          <a href="/user/calculators">CALCULATORS</a>
                        </li>
                        <li>
                          <a href="https://bentericksen.com/employment-compliance-alerts/" target="_blank">COMPLIANCE ALERTS</a>
                        </li>
                        <li>
                          <a href="//bentericksen.com/reference-background-checks.html" target="_blank">
                            BACKGROUND CHECKS</a>
                        </li>
                        <li>
                          <a href="//bentericksen.com/drake-p3-assessments.html" target="_blank">
                            DRAKE P3 ASSESSMENTS
                          </a>
                        </li>
                    </ul>
                  </li>
                  <li v-if="impersonated.bonuspro_only">
                    <span class="nav-slot disabled"><i class="fa fa-gears navigation_img"></i>SETTINGS</span>
                  </li>
                  <li v-else>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-gears navigation_img"></i>SETTINGS
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/user/settings">GENERAL SETTINGS</a></li>
                        <li><a href="/user/licensure-certifications">LICENSURE/CERTIFICATION</a></li>
                        <li v-if="!hasRole('manager', viewUser)"><a href="/user/permissions">PERMISSIONS</a></li>
                    </ul>
                  </li>
                  <li v-if="impersonated.bonuspro_only">
                    <span class="nav-slot disabled"><i class="fa fa-user navigation_img"></i>ACCOUNT</span>
                  </li>
                  <li v-else-if="permissions('m260', impersonated)">
                    <a href="/user/account"><i class="fa fa-user navigation_img"></i>ACCOUNT</a>
                  </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import acl from '@/mixins/acl'

export default {
  props: {
    policy_updates_run: {
      type: Boolean
    },
    policies_pending: {
      type: Boolean
    },
    employee_count_warning: {
      type: Object
    },
    manual_regenerate: {
      type: Boolean
    },
    benefit_create_warning: {
      type: Boolean
    }
  },
  mixins: [acl],
  computed: {
    ...mapGetters({
      manual: 'user/manual',
      impersonated: 'user/impersonated',
      viewUser: 'user/viewUser'
    }),
  }
}
</script>

<style lang="scss">

</style>
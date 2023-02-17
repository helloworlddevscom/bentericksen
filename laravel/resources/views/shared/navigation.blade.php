{{--
Variables available in this view:

From \Bentericksen\ViewComposers\BannerViewComposer:
* $impersonated \App\User The user who is logged in, or being impersonated.
* $manual string The token used to create a link to the Business' Policy Manual

From \Bentericksen\ViewComposers\BusinessEmployeeCountViewComposer:
* $employee_count_warning boolean Whether to show the modal to input the employee count

From \Bentericksen\ViewComposers\CreateManualViewComposer:
* $manual_regenerate boolean Whether the manual needs to be regenerated

From \Bentericksen\ViewComposers\NavigationViewComposer
* $navigation \Bentericksen\Layout\Navigation Data about the links.

From \Bentericksen\ViewComposers\PolicyFinalizationViewComposer:
* $policies_pending boolean Whether there are any policies pending finalization

From \Bentericksen\ViewComposers\PolicyUpdateViewComposer
* $policy_updates_run boolean Whether the Policy Updates logic ran.
* $policy_updates array Policy Updates that need to be accepted.
* $old_policies array The existing versions of the Policies that have updates.

From multiple view composers:
* $modals The modals that can appear on this page. This is an array that gets added onto by several composers.

--}}
@if(($viewUser->status == 'enabled') && ($viewUser->can_access_system == 1))
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
                            @if ($impersonated->bonuspro_only)
                                <li class="{{ $navigation->getClasses('dashboard') }}">
                                    <span class="nav-slot disabled"><i class="fa fa-tachometer navigation_img"></i>DASHBOARD</span>
                                </li>
                            @elseif( $impersonated->permissions('m240') )
                                <li class="{{ $navigation->getClasses('dashboard') }}">
                                    <a href="/user"><i class="fa fa-tachometer navigation_img"></i>DASHBOARD</a>
                                </li>
                            @endif
                            <li class="{{ $navigation->getClasses('policies') }}">
                                @if ($impersonated->bonuspro_only)
                                    <span class="nav-slot disabled"><i class="fa fa-copy navigation_img"></i>Policies</span>
                                @else
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                            class="fa fa-copy navigation_img"></i>POLICIES</a>
                                    <ul class="dropdown-menu">
                                        @if (empty($manual))
                                            <li>
                                                @if (!empty($policy_updates_run))
                                                    <a href="#" class="js-force-modal"
                                                        data-toggle='modal'
                                                        data-dismiss='modal'
                                                        data-target='#modalPolicyUpdatesReminder'>UPDATE POLICY
                                                        MANUAL</a>
                                                @elseif ($policies_pending)
                                                    <a href="#" data-toggle='modal' data-dismiss='modal'
                                                        data-target='#modalPoliciesPending'>UPDATE POLICY MANUAL</a>
                                                @else
                                                    <a href="/user/policies/createManual"
                                                        class="createModal">UPDATE POLICY MANUAL</a>
                                                @endif
                                            </li>
                                        @else
                                            <li>
                                                @if (!empty($employee_count_warning))
                                                    <a href="#" class="js-force-modal"
                                                        data-toggle='modal' data-dismiss='modal'
                                                        data-target='#modalEmployeeCountWarning'>VIEW/PRINT POLICY MANUAL</a>
                                                @elseif (!empty($policy_updates_run) && !$viewUser->hasRole('admin'))
                                                    <a href="#" class="js-force-modal"
                                                        data-toggle='modal' data-dismiss='modal'
                                                        data-target='#modalPolicyUpdatesReminder'>VIEW/PRINT POLICY MANUAL</a>
                                                @else
                                                    <a href="/user/policies/manual" target="_blank">VIEW/PRINT POLICY MANUAL</a>
                                                @endif
                                            </li>
                                        @endif
                                        @if( $impersonated->permissions('m120') == "View/Edit")
                                            <li>
                                                @if( !empty($policy_updates_run) && !$viewUser->hasRole('admin'))
                                                    <a href="#" class="js-force-modal" data-toggle='modal'
                                                        data-dismiss='modal' data-target='#modalPolicyUpdatesReminder'>POLICY EDITOR</a>
                                                @elseif (!empty($employee_count_warning))
                                                    <a href="/user/policies" class="js-force-modal" data-toggle='modal'
                                                        data-dismiss='modal' data-target='#modalEmployeeCountWarning'>POLICY EDITOR</a>
                                                @else
                                                    <a href="/user/policies">POLICY EDITOR</a>
                                                @endif
                                            </li>
                                        @endif
                                        @if( $impersonated->permissions('m121'))
                                            @if (!empty($manual_regenerate))
                                                <li><a href="#" class="js-force-modal"
                                                        data-toggle='modal'
                                                        data-dismiss='modal'
                                                        data-target='#modalManualOutOfDate'>
                                                        BENEFITS SUMMARY
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    @if( !empty($policy_updates_run) && !$viewUser->hasRole('admin') )
                                                        <a href="#" class="js-force-modal"
                                                            data-toggle='modal'
                                                            data-dismiss='modal'
                                                            data-target='#modalPolicyUpdatesReminder'>
                                                            BENEFITS SUMMARY
                                                        </a>
                                                    @elseif( empty($policy_updates_run) && $benefit_create_warning )
                                                        <a href="#" class="js-force-modal"
                                                            data-toggle='modal'
                                                            data-dismiss='modal'
                                                            data-target='#modalBenefitsSummaryCreate'>
                                                            BENEFITS SUMMARY
                                                        </a>
                                                    @else
                                                         <a href="/user/policies/benefits-summary" target="_blank">
                                                            BENEFITS SUMMARY
                                                         </a>
                                                    @endif
                                                </li>
                                            @endif
                                        @endif
                                        @if( $viewUser->hasRole('admin') )
                                            <li><a href="/user/policies/reset" class="policiesReset">RESET POLICIES</a></li>
                                        @endif
                                    </ul>
                                @endif
                            </li>

                            <li class="{{ $navigation->getClasses('employees') }}">
                                @if ($impersonated->business && !$impersonated->business->hrdirector_enabled)
                                    <span class="nav-slot disabled"><i class="fa fa-users navigation_img"></i>Employees</span>
                                @else
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                            class="fa fa-users navigation_img"></i>EMPLOYEES</a>
                                    <ul class="dropdown-menu">
                                        @if( $impersonated->permissions('m140'))
                                            <li><a href="/user/employees">EMPLOYEE LIST</a></li>
                                        @endif
                                        <li><a href="/user/employees/time-off-requests">TIME OFF REQUESTS</a></li>
                                        <li><a href="/user/employees/number">EMPLOYEE NUMBER</a></li>
                                        @if( $viewUser->hasRole(['admin', 'consultant']) )
                                            <li><a href="/user/employees/excel">EXCEL IMPORT</a></li>
                                        @endif
                                    </ul>
                                @endif
                            </li>
                            @if( $impersonated->permissions('m180') !== 'No Access')
                                <li class="{{ $navigation->getClasses('forms') }}">
                                    @if ($impersonated->business && !$impersonated->business->hrdirector_enabled)
                                        <span class="nav-slot disabled"><i class="fa fa-file-text-o navigation_img"></i>FORMS</span>
                                    @else
                                        @if( $viewUser->hasRole('admin') )
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text-o navigation_img"></i>FORMS</a>
                                            <ul class="dropdown-menu">
                                                <li><a href="/user/forms">FORMS</a></li>
                                                <li><a href="/user/forms/reset">RESET FORMS</a></li>
                                            </ul>
                                        @else
                                            <a href="/user/forms"><i class="fa fa-file-text-o navigation_img"></i>FORMS</a>
                                        @endif
                                    @endif
                                </li>
                            @endif

                            @if ($impersonated->permissions('m160'))
                                <li class="{{ $navigation->getClasses('job descriptions') }}">
                                    @if ($impersonated->business && !$impersonated->business->hrdirector_enabled)
                                        <span class="nav-slot disabled"><i class="fa fa-comment-o navigation_img"></i>JOB
                                        DESCRIPTIONS</span>
                                    @else
                                        <a href="/user/job-descriptions"><i class="fa fa-comment-o navigation_img"></i>JOB
                                            DESCRIPTIONS</a>
                                    @endif
                                </li>
                            @endif

                            @if ($impersonated->bonuspro_enabled)
                                <li class="{{ $navigation->getClasses('bonuspro') }}">
                                    <a href="/bonuspro" class="bonuspro--icon"><img src="/assets/images/bonuspro-icon-white.png">BonusPro</a>
                                </li>
                            @endif

                            @if ($viewUser->sop_enabled || isset($impersonated) && $impersonated->sop_enabled)
                                    <li class="{{ $navigation->getClasses('sops') }}">
                                        <a href="/streamdent/login"><i class="fa fa-file-text-o navigation_img"></i>SOPs</a>
                                    </li>
                            @endif

                            @if ($impersonated->bonuspro_only)
                                <li class="{{ $navigation->getClasses('tool') }}">
                                    <span class="nav-slot disabled"><i class="fa fa-wrench navigation_img"></i>TOOLS</span>
                                </li>
                            @else
                            <li class="{{ $navigation->getClasses('tool') }}">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                        class="fa fa-wrench navigation_img"></i>TOOLS</a>
                                <ul class="dropdown-menu">
                                    @if( $impersonated->hasRole('manager') && !$impersonated->permissions('m200'))

                                    @else
                                        <li><a href="/user/faqs">HR FAQ'S</a></li>
                                    @endif
                                    @if( $impersonated->hasRole('manager') && !$impersonated->permissions('m201'))

                                    @else
                                        <li><a href="/user/calculators">CALCULATORS</a></li>
                                    @endif
                                        <li><a href="https://bentericksen.com/employment-compliance-alerts/" target="_blank">COMPLIANCE ALERTS</a></li>
                                        <li><a href="//bentericksen.com/reference-background-checks.html" target="_blank">BACKGROUND
                                            CHECKS</a></li>
                                        <li><a href="//bentericksen.com/drake-p3-assessments.html" target="_blank">DRAKE P3
                                            ASSESSMENTS</a></li>
{{--                                        Hiding temporarily for BEM-620 request--}}
{{--                                        Uncomment also in /bentericksen/Layout/navigation.php  --}}
{{--                                        <li><a href="//bentericksen.com/hr-employment-compliance-webinars.html"--}}
{{--                                            target="_blank">WEBINARS</a></li>--}}
                                </ul>
                            </li>
                            @endif
                            @if ($impersonated->bonuspro_only)
                                <li class="{{ $navigation->getClasses('settings') }}">
                                    <span class="nav-slot disabled"><i class="fa fa-gears navigation_img"></i>SETTINGS</span>
                                </li>
                            @else
                            <li class="{{ $navigation->getClasses('settings') }}">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gears navigation_img"></i>SETTINGS</a>
                                <ul class="dropdown-menu">
                                    <li><a href="/user/settings">GENERAL SETTINGS</a></li>
                                    <li><a href="/user/licensure-certifications">LICENSURE/CERTIFICATION</a></li>
                                    @if( !$impersonated->hasRole('manager') )
                                        <li><a href="/user/permissions">PERMISSIONS</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if ($impersonated->bonuspro_only)
                                <li class="{{ $navigation->getClasses('account') }}">
                                    <span class="nav-slot disabled"><i class="fa fa-user navigation_img"></i>ACCOUNT</span>
                                </li>
                            @elseif( $impersonated->permissions('m260'))
                                <li class="{{ $navigation->getClasses('account') }}">
                                    <a href="/user/account"><i class="fa fa-user navigation_img"></i>ACCOUNT</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
@endif
{{-- Fix for BENT-435. --}}
{{-- @todo: REFACTOR this pattern. Research best approach to account for Business level permissions. --}}
@if($impersonated->hasRole('manager') && !$impersonated->permissions('m121'))

@else
    @if(!empty($policy_updates_run) && !empty($policy_updates))
        @include('user.modals.banner', ['policies_count' => count($policy_updates)])
    @elseif ($impersonated->needs_policy_manual_regeneration)
      @include('user.modals.manual-regenerate')
    @endif

@endif

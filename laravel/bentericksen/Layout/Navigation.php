<?php

namespace Bentericksen\Layout;

use App\User;
use Bentericksen\Layout\Navigation\Link;

class Navigation
{
    /**
     * TEMPORARY: This should be managed in the DB.
     * @todo: create menu editor.
     * @var array
     */
    protected $linkData = [
        [
            'url'         => '/user',
            'label'       => 'Dashboard',
            'icon'        => 'fa-tachometer',
            'enabled'     => true,
            'display'     => true,
            'roles'       => [
                'owner',
                'manager',
            ],
            'permissions' => [
                'm240' => true,
            ],
            'classes'     => [
                'nav-root',
            ],
        ],
        [
            'url'         => '/user/policies',
            'label'       => 'Policies',
            'icon'        => 'fa-copy',
            'enabled'     => true,
            'display'     => true,
            'roles'       => [
                'owner',
                'manager'
            ],
            'permissions' => [
                'hrdirector' => true,
            ],
            'classes'     => [
                'nav-root',
            ],
            'children'    => [
                [
                    'url'         => '/user/policies/createManual',
                    'label'       => 'Create Policy Manual',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [
                        'owner',
                        'manager',
                    ],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                        'createModal'
                    ],
                ],
                [
                    'url'         => '/user/policies/manual',
                    'label'       => 'View/Print Policy Manual',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [
                        'owner',
                        'manager',
                    ],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                        'viewManual'
                    ],
                ],
                [
                    'url'         => '/user/policies',
                    'label'       => 'Policy Editor',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [
                        'owner',
                        'manager',
                    ],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                        'editPolicies'
                    ],
                ],
                [
                    'url'         => '/user/policies/benefits-summary',
                    'label'       => 'Benefits Summary',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [
                        'owner',
                        'manager',
                    ],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                        'viewBenefitsSummary'
                    ],
                ],
                [
                    'url'         => '/user/policies/reset',
                    'label'       => 'Reset Policies',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [
                        'admin'
                    ],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                    ],
                ],
            ]
        ],
        [
            'url'         => '/user/employees',
            'label'       => 'Employees',
            'icon'        => 'fa-users',
            'enabled'     => true,
            'display'     => true,
            'roles'       => [
                'owner',
                'manager'
            ],
            'permissions' => [],
            'classes'     => [
                'nav-root',
            ],
            'children'    => [
                [
                    'url'         => '/user/employees',
                    'label'       => 'Employee List',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [
                        'owner',
                        'manager',
                    ],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                    ],
                ],
                [
                    'url'         => '/user/employees/time-off-requests',
                    'label'       => 'Time Off Requests',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [
                        'owner',
                        'manager',
                    ],
                    'permissions' => [
                        'hrdirector' => true,
                    ],
                    'classes'     => [
                        'nav-child',
                    ],
                ],
                [
                    'url'         => '/user/employees/number',
                    'label'       => 'Employee Number ',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [
                        'owner',
                        'manager',
                    ],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                    ],
                ],
                [
                    'url'         => '/user/employees/excel',
                    'label'       => 'Excel Import',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [
                        'admin',
                        'consultant'
                    ],
                    'permissions' => [
                        'hrdirector' => true,
                    ],
                    'classes'     => [
                        'nav-child',
                    ],
                ],
            ]
        ],
        [
            'url'         => '/user/forms',
            'label'       => 'Forms',
            'icon'        => 'fa-file-text-o',
            'enabled'     => true,
            'display'     => true,
            'roles'       => [
                'owner',
                'manager'
            ],
            'permissions' => [
                'hrdirector' => true,
                'm180'       => true,
            ],
            'classes'     => [
                'nav-root',
            ],
            'children'    => []
        ],
        [
            'url'         => '/user/sops',
            'label'       => 'SOPs',
            'icon'        => 'fa-file-text-o',
            'enabled'     => true,
            'display'     => true,
            'roles'       => [
                'owner',
                'manager'
            ],
            'permissions' => [
                'hrdirector' => true,
                'm180'       => true,
            ],
            'classes'     => [
                'nav-root',
            ],
            'children'    => []
        ],
        [
            'url'         => '/user/job-descriptions',
            'label'       => 'Job Descriptions',
            'icon'        => 'fa-comment-o',
            'enabled'     => true,
            'display'     => true,
            'roles'       => [
                'owner',
                'manager',
            ],
            'permissions' => [
                'hrdirector' => true,
            ],
            'classes'     => [
                'nav-root',
            ],
            'children'    => []
        ],
        [
            'url'         => '/bonuspro',
            'label'       => 'BonusPro',
            'icon'        => 'fa-money',
            'enabled'     => true,
            'display'     => true,
            'roles'       => [
                'owner',
                'manager',
            ],
            'permissions' => [
                'bonuspro' => true,
            ],
            'classes'     => [
                'nav-root',
            ],
        ],
        [
            'url'         => '/user',
            'label'       => 'Tool',
            'icon'        => 'fa-wrench',
            'enabled'     => true,
            'display'     => true,
            'roles'       => [],
            'permissions' => [],
            'classes'     => [
                'nav-root',
            ],
            'children'    => [
                [
                    'url'         => '/user/faqs',
                    'label'       => 'HR FAQ\'s',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                    ],
                ],
                [
                    'url'         => '/user/calculators',
                    'label'       => 'Calculators',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                    ]
                ],
                [
                    'url'         => '//bentericksen.com/reference-background-checks.html',
                    'label'       => 'Background Checks',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                    ]
                ],
                [
                    'url'         => '//bentericksen.com/drake-p3-assessments.html',
                    'label'       => 'Drake P3 Assessments',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                    ]
                ],
//                Hiding temporarily for BEM-620 request
//                Uncomment also in laravel/resources/views/shared/navigation.blade.php
//                [
//                    'url'         => '//bentericksen.com/hr-employment-compliance-webinars.html',
//                    'label'       => 'Webinars',
//                    'enabled'     => true,
//                    'display'     => true,
//                    'roles'       => [],
//                    'permissions' => [],
//                    'classes'     => [
//                        'nav-child',
//                    ]
//                ],
            ]
        ],
        [
            'url'         => '/user',
            'label'       => 'Settings',
            'icon'        => 'fa-gears',
            'enabled'     => true,
            'display'     => true,
            'roles'       => [],
            'permissions' => [],
            'classes'     => [
                'nav-root',
            ],
            'children'    => [
                [
                    'url'         => '/user/settings',
                    'label'       => 'General Settings',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                    ]
                ],
                [
                    'url'         => '/user/licensure-certifications',
                    'label'       => 'Licensure/Certification',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [
                        'owner',
                        'manager',
                    ],
                    'permissions' => [
                        'hrdirector' => true,
                    ],
                    'classes'     => [
                        'nav-child',
                    ]
                ],
                [
                    'url'         => '/user/permissions',
                    'label'       => 'Permissions',
                    'enabled'     => true,
                    'display'     => true,
                    'roles'       => [],
                    'permissions' => [],
                    'classes'     => [
                        'nav-child',
                    ]
                ],
            ]
        ],
        [
            'url'         => '/user/account',
            'label'       => 'Account',
            'icon'        => 'fa-user',
            'enabled'     => true,
            'display'     => true,
            'roles'       => [],
            'permissions' => [],
            'classes'     => [
                'nav-root',
            ],
            'children'    => []
        ],
    ];

    /**
     * @var array
     */
    protected $links = [];

    /**
     * @var User
     */
    private $user;
    /**
     * @var bool
     */
    private $runPolicyUpdates;
    /**
     * @var bool
     */
    private $hasPendingPolicies;
    /**
     * @var bool
     */
    private $manualRegenerate;

    function __construct(User $user)
    {
        $this->user = $user;
        $this->runPolicyUpdates = session()->get('policyUpdatesPending');
        $this->hasPendingPolicies = $this->user->business->hasPendingPolicies();
        $this->manualRegenerate = session()->get('manualRegenerate');
        $this->init();
    }

    /**
     * @return array
     */
    public function render()
    {
        return $this->links;
    }

    private function init()
    {
        foreach ($this->linkData as $link) {
            $link = $this->setClasses($link);

            array_push($this->links, new Link($link));
        }
    }

    /**
     * parsing link classes based on roles/permissions and state of link.
     *
     * @param $link
     * @return mixed
     */
    private function setClasses($link)
    {
        if ($link['enabled']) {
            array_push($link['classes'], 'enabled');
        } else {
            array_push($link['classes'], 'disabled');
        }

        $this->verifyActiveLink($link);

        // If the link has children, make a recursive call
        if (!empty($link['children'])) {
            foreach ($link['children'] as $index => $child) {
                $link['children'][$index] = $this->setClasses($child);
            }
        }

        return $link;
    }

    /**
     * Verifying the current route to set the active state.
     *
     * @param $link
     */
    private function verifyActiveLink(&$link): void
    {
        // getting current route (minus the root)
        $route = str_replace(url('/'), '', url()->current());

        if ($link['url'] == $route) {
            if (strtolower($link['label']) !== 'tool' && strtolower($link['label']) !== 'settings') {
                // setting the active state if the routes match.
                array_push($link['classes'], 'active');
            }
        } else if (strpos($route, $link['url']) !== false) {
            // Skipping the dashboard link for HR Director routes.
            if (strtolower($link['label']) !== 'dashboard') {
                // if this is a child route.
                if (!empty($link['children'])) {
                    foreach ($link['children'] as $child) {
                        // if one of child routes match, activate parent
                        if ($child['url'] == $route) {
                            array_push($link['classes'], 'active');
                            break;
                        }
                    }
                } else {
                    array_push($link['classes'], 'active');
                }
            }
        }
    }

    /**
     * Returns the parent link classes based on the label name
     * @param $name
     * @return string
     */
    public function getClasses($name)
    {
        $link = null;
        foreach ($this->links as $obj) {
            if (strtolower($obj->data['label']) == $name) {
                $link = $obj;
                break;
            }
        }

        return $link ? implode(' ', $link->data['classes']) : '';
    }
}

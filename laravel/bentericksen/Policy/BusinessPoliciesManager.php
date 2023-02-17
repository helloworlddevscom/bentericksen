<?php

namespace Bentericksen\Policy;

use Illuminate\Support\Facades\DB;
use App\Business;
use App\Policy;
use App\PolicyTemplate;

/**
 * Class BusinessPoliciesManager
 *
 * Utility class to generate the Policy list for a new Business, or update the Policy list for a Business when it is
 * edited, notably when changing parameters like the employee count.
 *
 * @package Bentericksen\Policy
 */
class BusinessPoliciesManager
{
    protected $business;

    protected $rules;

    protected $fullReset = false;

    public function __construct(Business $business, PolicyRules $rules, $fullReset = false)
    {
        $this->business = $business;
        $this->rules = $rules;
        if ($fullReset) {
            $this->fullReset = $fullReset;
        }
    }

    /**
     * Updates the Business' Policy list to match their new parameters (state, employee count, etc.). Called
     * when various properties of the Business model are changed (e.g., employee count)
     *
     * Note: This will add new policies that didn't apply before and now do. It will also delete old Policies
     * that used to apply and no longer apply.
     */
    public function update()
    {
        //remove all policies if using full reset.
        if ($this->fullReset) {
            $this->updateAndReset();
        } else {
            $this->removeDisabledPolicies();
            // find the template IDs of all policies that currently exist for the Business
            $templates_in_use = Policy::where('business_id', $this->business->id)
                ->where('template_id', '>', 0)
                ->pluck('template_id')
                ->all();

            // build array of new templates that this business doesn't already have
            // (new templates, that didn't previously apply, may now apply due to changes in the employee count)
            $templates = PolicyTemplate::where('status', 'enabled')
                ->effective()
                ->whereNotIn('id', $templates_in_use)
                ->get()
                ->filter(function($template) {
                    return $this->rules->all($template);
                });
            
            $templates->each(function($template) {
              DB::beginTransaction();
              $this->add($template);
              DB::commit();
            });

            // Remove existing policies that no longer apply to the current business
            // (e.g., if employee count increases above 50, policies for "under 50 employees" no longer apply)
            $templates = PolicyTemplate::whereIn('id', $templates_in_use)->get();
            foreach ($templates as $template) {
                //check if rules to use template match current settings for business
                if ($this->rules->all($template) !== true) {
                    try {
                        Policy::where('business_id', $this->business->id)
                            ->where('template_id', $template->id)
                            ->update([
                                'delete_reason' => 'Policy rules mismatch'
                            ]);

                        Policy::where('business_id', $this->business->id)
                            ->where('template_id', $template->id)
                            ->delete();
                    } catch (\Exception $e) {
                        // delete failed; do nothing
                    }
                }
            }
            
            
        }
    }

    /**
     * Resets all the Business' policies to match the global templates.
     *
     * This deletes all the existing Policy rows for the Business and creates new ones based on
     * all applicable PolicyTemplates.
     *
     * @throws \Exception
     */
    private function updateAndReset()
    {
        Policy::where('business_id', $this->business->id)
            ->update([
                'delete_reason' => 'Policy Full Reset'
            ]);

        Policy::where('business_id', $this->business->id)->delete();

        //get all the templates that policies are based on
        $templates = PolicyTemplate::where('status', 'enabled')->effective()->get();

        foreach ($templates as $template) {
            //check if rules to use template match current settings for business
            if ($this->rules->all($template) === true) {
                $this->add($template);
            }
        }
    }

    /**
     * Adds a policy to the Business based on the provided template, handling any special logic like stubs.
     *
     * @param PolicyTemplate $template
     */
    private function add($template)
    {
      Policy::where('template_id', $template->id
        )->where('business_id', $this->business->id)
        ->update([
          'delete_reason' => 'Multiple Policy Generation Calls'
        ]);
        //ensure duplicate policies cannot be entered into the system.
        Policy::where('template_id', $template->id)->where('business_id', $this->business->id)->delete();

        // Some policies have multiple templates, representing multiple versions of the policy that the
        // Business owner/manager must choose from. "Stubs" are created for these, which are a placeholder
        // in the Policy Editor which prompts the owner/manager to choose their preferred template.
        switch ($template->id) {
            case 276: // DENTAL BENEFITS PDBA
            case 277: // DENTAL BENEFITS
                $special_extra = [
                    'benefit' => 'dental',
                    'type' => 'choose',
                    'policies' => [276, 277],
                    'default' => 277,
                ];

                $this->addStub($template, 'Dental Benefits', $special_extra);
                break;

            case 281: // PTO
            case 282: // VACATION BENEFITS
                $special_extra = [
                    'benefit' => 'pto/vacation',
                    'type' => 'choose',
                    'policies' => [281, 282],
                    'default' => 282,
                ];
                $this->addStub($template, 'Vacation/PTO', $special_extra);
                break;

            case 346: // PTO - CA
            case 347: // Vacation Benefits - CA
                $special_extra = [
                    'benefit' => 'pto/vacation',
                    'type' => 'choose',
                    'policies' => [346, 347, ],
                    'default' => 346,
                ];
                $this->addStub($template, 'Vacation/PTO', $special_extra);
                break;

            default:
                // all others are normal policies that don't use stubs.
                $data = $this->setTemplateData($template);
                $this->createPolicy($data);
        }
    }

    /**
     * Helper function to add a stub policy. This is a placeholder that allows the owner/manager to choose between
     * two different versions of the same policy.
     *
     * @param PolicyTemplate $template
     * @param string $title
     * @param array $special_extra
     */
    private function addStub($template, $title, $special_extra)
    {
        $stub = Policy::where('business_id', $this->business->id)
            ->where('manual_name', $title)
            ->where('special', 'stub')
            ->first();

        $templateData = $this->setTemplateData($template, true, $special_extra);

        $stubData = array_merge($templateData, [
            'manual_name' => $title,
            'content' => '<h1>If you see this, you need still need to select between multiple policy types</h1>',
            'special' => 'stub',
            'special_extra' => json_encode($special_extra)
        ]);

        if (is_null($stub)) {
            $this->createPolicy($stubData);
            return;
        }

        unset($stubData['order']);

        // This is the condition when a policy has been selected, or policy is disabled at stub level
        $activePolicy = Policy::where('business_id', $this->business->id)
            ->where('manual_name', $title)
            ->where([
                ['special', 'selected']
            ])->orWhere('special_extra',$stub->id)->first();


        if (!is_null($activePolicy) && $activePolicy->template_id == $template->id) {
            $activePolicy->update([
                'content' => $templateData['content'],
                'content_raw' => $templateData['content_raw']
            ]);
        }

        // if active policy has been selected, close out stub.
        if (!is_null($activePolicy)) {
            if ($activePolicy->special_extra == $stub->id) {
                $stubData['status'] = 'closed';
                $stub->update($stubData);
            }
        }
    }

    /**
     * Creates an array of data used to create a new policy, based on a template.
     *
     * @param PolicyTemplate $template
     * @param bool $is_stub
     * @return array
     */
    private function setTemplateData(PolicyTemplate $template, $isStub = false, $special_extra = null)
    {
        if (empty($template->category_id)) {
            $category_id = 0;
        }

        if (empty($special_extra)) {
            $special_extra = ['default' => null];
        }

        $data = [
            'business_id' => $this->business->id,
            'category_id' => $template->category_id,
            'template_id' => $isStub ? $special_extra['default'] : $template->id,
            'manual_name' => $template->manual_name,
            'content' => $template->content,
            'content_raw' => $template->content,
            'status' => $template->status,
            'requirement' => $template->getRequirement($this->business),
            'include_in_benefits_summary' => $template->include_in_benefits_summary,
            'order' => $template->order,
        ];

        return $data;
    }

    /**
     * Helper method to create a new policy. Handles reordering of existing policies when inserting a new one
     * @param array $data
     */
    private function createPolicy($data) {
        // before saving, reorder any policies that should be below this one
        Policy::where('business_id', $this->business->id)
            ->where('category_id', $data['category_id'])
            ->where('order', '>=', $data['order'])
            ->update([
                'order' => DB::raw('`order` + 1')
            ]);
        // save the new policy
        Policy::create($data);
    }

    public function removeDisabledPolicies() {
        $disabledPolicies = PolicyTemplate::where('status', 'disabled')
            ->get()
            ->pluck('id');
            
        Policy::where('business_id', $this->business->id)
            ->whereIn('template_id', $disabledPolicies)
            ->update([
                'delete_reason' => 'Policy template disabled'
            ]);

        Policy::where('business_id', $this->business->id)
            ->whereIn('template_id', $disabledPolicies)
            ->delete();
    }
}

<?php

namespace Bentericksen\PrintServices;

use App\Category;
use App\User;

class BenefitSummaryPrintService
{

    /**
     * @var \App\Business
     */
    protected $business;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var string
     */
    protected $html;

    /**
     * @var array
     */
    protected $policyInformation;


    public function __construct($business, $filename)
    {
        $this->business = $business;
        $this->filename = $filename;

        $this->fetchPolicies();
        $this->buildHTML();
    }


    /**
     * Generate Manual
     *
     * @throws \Exception
     * @throws \Throwable
     */
    public function generate()
    {
        $snappy = \App::make('snappy.pdf');
        $options = [
            'margin-top'    => 20,
            'margin-right'  => 20,
            'margin-bottom' => 20,
            'margin-left'   => 20,
        ];

        $owner = User::find( $this->business->primary_user_id );
        $snappy->setOption('cover', view('pdf.benefits-summary.cover')
            ->with('business', $this->business)
            ->with('owner', $owner)
            ->render());
        $snappy->setOption('footer-html', base_path('resources/views/pdf/manual/footer.html'));
        $snappy->setOption('footer-spacing', '0');
        $snappy->generateFromHtml($this->getHTML(), storage_path('bentericksen/policy/' . $this->filename . '_summary' ), $options );
    }

    /**
     * HTML getter.
     *
     * @return null|string|string[]
     */
    public function getHTML()
    {
        $html = mb_convert_encoding($this->html, 'HTML-ENTITIES', 'UTF-8');
        return $html;
    }


    /**
     * Builds manual.
     */
    private function buildHTML()
    {
        $body = view('pdf.benefits-summary.policy')
            ->with('policies', $this->policyInformation)
            ->render();

        $this->html = $body;
    }

    /**
     * Fetches policies that are going to be included in the Benefits manual
     * based on the flag "include_in_benefits_summary"
     */
    private function fetchPolicies()
    {
        $categories = Category::where('grouping', 'policies')->get();

        $this->policyInformation = [];

        foreach ($categories as $category) {
            $policies = $this->business->policies()
                ->where('status', 'enabled')
                ->where('include_in_benefits_summary', 1)
                ->where('category_id', $category->id)
                ->orderBy('order')
                ->get();

            if ($policies->count() > 0) {
                foreach ($policies as $policy) {
                    array_push($this->policyInformation, $policy);
                }
            }
        }

        return $this;
    }
}

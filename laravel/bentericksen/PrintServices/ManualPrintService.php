<?php

namespace Bentericksen\PrintServices;

use App\Policy;
use App\Category;
use App\PolicyTemplate;
use Illuminate\Support\Facades\Storage;

class ManualPrintService
{

    protected $business;

    protected $filename;

    protected $categories;

    protected $policies;

    public function __construct($business, $filename)
    {
        $this->business = $business;
        $this->filename = $filename;

        $categories = Category::where('grouping', 'policies')
            ->orderBy('order', 'asc')
            ->get();

        //check for stubs
        $this->checkForStubs();

        $policies = $this->business->getSortedPolicies();

        $temp = [];
        foreach ($categories as $category) {
            $tpols = [];
            foreach ($policies as $policy) {
                if ($policy->category_id === $category->id) {
                    $tpols[] = $policy;
                }
            }

            if (count($tpols) > 0) {
                $temp[] = [
                    'category' => $category,
                    'policies' => $tpols,
                ];
            }
        }

        $this->policyInformation = $temp;
        $this->buildHTML();
    }

    private function checkForStubs()
    {
        $policies = Policy::where('business_id', $this->business->id)
            ->where('status', 'enabled')
            ->where('special', 'stub')
            ->orderBy('order', 'ASC')
            ->get();

        foreach ($policies as $policy) {
            $extras = json_decode($policy->special_extra);
            $template = PolicyTemplate::findOrFail($extras->default);

            $data = [
                'business_id' => $this->business->id,
                'category_id' => $template->category_id,
                'template_id' => $template->id,
                'manual_name' => $template->manual_name,
                'content' => $template->content,
                'content_raw' => $template->content,
                'status' => 'enabled',
                'order' => $template->order,
                'requirement' => $template->getRequirement($this->business),
                'updated_at' => '0000-00-00 00:00:00',
                'special' => 'selected',
            ];

            $new = Policy::create($data);
            $policy->status = "closed";
            $policy->save();
        }
    }

    public function generate()
    {
        $this->backup();

        $snappy = \App::make('snappy.pdf');
        $options = [
            'margin-top' => 20,
            'margin-right' => 20,
            'margin-bottom' => 20,
            'margin-left' => 20,
        ];
        //dd(file_exists(base_path('resources/views/pdf/manual/toc.xsl')));
        $snappy->setOption('cover', view('pdf.manual.cover')
            ->with('business', $this->business)
            ->render());
        $snappy->setOption('toc', true);
        $snappy->setOption('xsl-style-sheet', base_path('resources/views/pdf/manual/toc.xsl'));
        $snappy->setOption('footer-html', base_path('resources/views/pdf/manual/footer.html'));
        $snappy->setOption('footer-spacing', '0');
        $snappy->generateFromHtml($this->getHTML(), storage_path('bentericksen/policy/' . $this->filename), $options);
    }

    public function getHTML()
    {
        $html = mb_convert_encoding($this->html, 'HTML-ENTITIES', 'UTF-8');
        return $html;
    }

    private function buildHTML()
    {
        $body = view('pdf.manual.policy')
            ->with('policyInformation', $this->policyInformation)
            ->render();

        $this->html = $body;
    }

    public function backup(): bool
    {
      $business = $this->business;
      $id = $business->id;
      $state = $business->state;
      $name = $business->name;
      $time = date('Y-m-d', strtotime($business->manual_created_at));
      $source = storage_path("bentericksen/policy/{$business->manual}");
      $folder = storage_path(sprintf('bentericksen/policy-archive/%s', $id));
      $destination = storage_path(
        sprintf('bentericksen/policy-archive/%s/%s-%s-%s.pdf', $id, $state, $time, $name )
      );

      if (!is_file($source)) {
        return false;
      }

      if (!is_dir($folder) && !mkdir($folder)) {
        return false;
      }

      if (copy($source, $destination)) {
        $this->purge();
        return true;
      }

      return false;
    }

    public function purge(): void
    {
      if ($this->business->finalized) {
        return;
      }

      $id = $this->business->id;
      $folder = storage_path(sprintf('bentericksen/policy-archive/%s/', $id));

      if (!($files = scandir($folder, 1))) {
        return;
      }

      $files = array_slice(array_diff($files, ['..', '.']), 2);

      array_walk($files, function($file) use($id) {
        $file = storage_path(
          sprintf('bentericksen/policy-archive/%s/%s', $id, $file )
        );
        unlink($file);
      });
    }
}

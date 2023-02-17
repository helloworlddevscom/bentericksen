<?php

namespace Bentericksen\Manual;

use App\Policy;
use App\Category;

class ManualService
{
	protected $business;
	protected $filename;
	protected $categories;
	protected $policies;
	
	public function __construct( $business, $filename )
	{
		$this->business = $business;
		$this->filename = $filename;
		
		$categories = Category::where('grouping', 'policies')
									->orderBy('order', 'asc')
									->get();
		
		$policies = Policy::where('business_id', $this->business->id)
								->where('status', 'enabled')
								->orderBy('category_id', 'ASC')
								->orderBy('order', 'ASC')
								->get();
		$temp = [];
		foreach( $categories AS $category )
		{
			$tpols = [];
			foreach( $policies AS $policy )
			{
				if($policy->category_id === $category->id)
				{
					$tpols[] = $policy;
				}
			}
			
			if( count($tpols) > 0 )
			{
				$temp[] = [
					'category' => $category,
					'policies' => $tpols,
				];
			}
		}
		
		$this->policyInformation = $temp;
		
		$this->buildHTML();
	}
	
	public function generate()
	{
		$snappy = \App::make('snappy.pdf');
		$options = [
			'margin-top'    => 25,
			'margin-right'  => 25,
			'margin-bottom' => 25,
			'margin-left'   => 25,
		];		
		$snappy->setOption('cover', view('manual.cover')->render());
		$snappy->setOption('toc', true);
		$snappy->setOption('footer-html', base_path('resources/views/manual/footer.html'));
		$snappy->setOption('footer-spacing', '10');		
		$snappy->generateFromHtml($this->getHTML(), storage_path('bentericksen/policy/' . $this->filename ), $options );	

		/*
		$pdf = new \PDFMerger;

		$pdf->addPDF(storage_path('bentericksen/policy/' . $this->filename .  '_cover'), '1');
		$pdf->addPDF(storage_path('bentericksen/policy/' . $this->filename .  '_body'), 'all');

		$pdf->merge('file', storage_path('bentericksen/policy/' . $this->filename ));		
		*/
	}
	
	public function getHTML()
	{
		$html = mb_convert_encoding($this->html, 'HTML-ENTITIES', 'UTF-8');
		return $html;
	}
	
	private function buildHTML()
	{
		$body = view('manual.policy')->with('policyInformation', $this->policyInformation)
									 ->render();
									 
		$this->html = $body;
	}
}
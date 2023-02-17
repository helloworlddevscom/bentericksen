<?php

namespace Bentericksen\Forms;

use Carbon\Carbon;

class FormService
{
	protected $business;
	protected $form;
	protected $filename;
	protected $folder;
	protected $html;
	
	public function __construct( $business, $form, $filename )
	{
		$this->business = $business;
		$this->form = $form;
		$this->filename = $filename; 
		
		$now = Carbon::now();
		$year = $now->format('Y');
		$month = $now->format('m');
		$this->folder = "bentericksen/forms/{$year}/{$month}/";
		
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
		
		$path = storage_path($this->folder);
		
		if (!file_exists($path)) {
			\File::makeDirectory($path, 0775, true);
		}		
		
		$this->buildHTML();
		
		$snappy->generateFromHtml($this->getHTML(), storage_path($this->folder . $this->filename), $options );	
			
		$this->form->folder = $this->folder;
		$this->form->file = $this->filename;
		$this->form->save();		
			
	}
	
	public function getHTML()
	{
		$html = mb_convert_encoding($this->html, 'HTML-ENTITIES', 'UTF-8');
		return $html;
	}
	
	private function buildHTML()
	{
	
		$results = \DB::select( \DB::raw("SELECT forms.*, form_templates.name FROM forms, form_templates WHERE forms.id = :form_id AND forms.template_id = form_templates.id"), [
		   'form_id' => $this->form->id,
		 ]);
		 
	 
		if( count($results) > 0 )
		{
			$body = view('forms.form')->with('form', $results[0])
										->render();
		} else {
			$body = "";
		}
									 
		$this->html = $body;
	}
}
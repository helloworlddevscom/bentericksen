<?php

namespace Bentericksen\Forms;

class GenerateFile
{
	protected $form;
	
	public function __construct( $form )
	{
		$this->form = $form;
		$this->generateFile();
	}
	
	private function generatefile()
	{
		$file = str_random(40);
		$now = \Carbon\Carbon::now();
		$year = $now->format('Y');
		$month = $now->format('m');
		$folder = "bentericksen/forms/{$year}/{$month}/";
		$path = storage_path($folder);
		$manual = new \Bentericksen\Forms\HTML( $this->form );
		//$manual->setHeader('<div id="header"><h1>Widgets Express</h1></div>', ['#header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }'] );
		$manual->setFooter(
			'<div id="footer">
				<div class="top-bar"></div>
				<div class="copyright">
					Copyright &copy; 2016 Bent Ericksen & Associates &bull; All Rights Reserved
				</div>
				<div class="print-page">
					<p>Printed Date: ' . $now->format('m/d/Y') . '</p>
					<p class="page_count">Page </p>
				</div>
			</div>', 
			[
				'#footer { position: fixed; left: 0px; bottom: -2in; right: 0px; height: 2in; }', 
				'#footer .page_count:after { content: counter(page, null); }', '.page_count { text-align: right;}',
				'.top-bar { width: 100%; height:2px; border-top: 4px solid #b19292; border-bottom: 1px solid #b19292; }',
				'.copyright { margin-bottom: 0;}',
				'.print-page p { width: 49.5%; display: inline-block; margin-top: 0; }',
				'.copyright, .print-page p, .copyright { font-size: 11px; color: #b19292; }',
				'thead:before, thead:after { display: none; }',
				'tbody:before, tbody:after { display: none; }',
				'table { page-break-inside: avoid;}',
			]
		);
		//str_replace('</form>', '</form></div>', str_replace('<form', '<div style="max-width: 6.27in;"><form', //
		$pdf = \PDF::loadHTML( $manual->html() );

		if (!file_exists($path)) {
			\File::makeDirectory($path, 0775, true);
		}		
		
		$pdf->save( storage_path($folder . $file ) );		
		$this->form->folder = $folder;
		$this->form->file = $file;
		$this->form->save();
	}
	
}
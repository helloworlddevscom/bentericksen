<?php

namespace Bentericksen\Forms;

class GeneratePreview
{
	protected $form;
	protected $pdf;
	
	public function __construct( $form )
	{
		$this->form = $form;
		$this->generateFile();
	}
	
	public function pdf()
	{
		return $this->pdf;
	}
	
	private function generatefile()
	{
		$manual = new \Bentericksen\Forms\HTML( $this->form );
		$pdf = \PDF::loadHTML( $manual->html() );
		$this->pdf = $pdf;
	}
	
}
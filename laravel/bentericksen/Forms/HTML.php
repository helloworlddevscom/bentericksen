<?php

namespace Bentericksen\Forms;

use App\Business;
use App\Category;
use App\Policy;

class HTML
{
	protected $form;
	
	protected $html;
	protected $header;
	protected $footer;
	
	protected $docType;
	protected $styles = [
		'.category-heading { border:1px solid #000; padding: 10px; font-size:20px; text-align:center; text-transform:uppercase; background: #dedede; margin-bottom: 20px;}',
		'.page-break { page-break-after: always; }',
		'.policy-heading { text-align: center; text-transform:uppercase; font-size: 16px; }',
		'.policy-body { font-size: 11px; margin-bottom: 20px;}',
		'ol.arrow, ol.arrows, al { list-style: none; margin-left: 0; padding: 0 40px; display: block; }',
		'ol.arrow li:before, ol.arrows li:before { font-family: DejaVu Sans; content: "\27A2" !important; margin: 0 5px 0 -15px !important; }',
		'div.bordered { border: 3px solid #000;	padding: 5px; font-weight: 600; }',
		'div.test { width: 80%; overflow:hidden; word-wrap: break-word; }',
		'thead:before, thead:after { display: none; }',
		'tbody:before, tbody:after { display: none; }',
		'table { page-break-inside: avoid; }',
	];
	

	public function __construct( $form, $page = null )
	{
		$this->form = $form;
											
		if( !is_null( $page ) )
		{
			$this->setStyles( $page );
		} else {
			$this->setStyles(['@page { margin: 0.25in, 0.25in, 2in, 0.25in; }']);
		}
	}
	
	public function setDocType( $docType = null )
	{
		if( is_null( $docType ) )
		{
			$docType = "<!DOCTYPE html>";
		}
		
		$this->docType = $docType;
	}
	
	public function setStyles( $styles )
	{
		if(is_array( $styles ) )
		{
			foreach( $styles AS $style )
			{
				$this->setStyles( $style );
			}
		} else {
			$this->styles[] = $styles;	
		}
	}
	
	public function setHeader( $content, $styles )
	{
		$this->header = $content;
		$this->setStyles( $styles );
	}
	
	public function setFooter( $content, $styles )
	{
		$this->footer = $content;
		$this->setStyles( $styles );		
	}
	
	public function html()
	{
		
		$this->generate();		
		return $this->html;
	}
	
	public function generate()
	{
		$this->html .= $this->docType;
		$this->html .= '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
		$this->html .= '<style>' . implode('', $this->styles) . '</style>';
		$this->html .= '<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>';
		$this->html .= "</head><body>";		
		$this->html .= $this->header;
		//$this->html .= $this->footer;
		
		$content = str_replace('<al>', '<ol class="arrows">', str_replace('</al>', "</ol>", $this->form->description) );
		   
		$this->html .=  "<div class='policy-body'><div class='form-body'>" . $content . "</div></div>";		


		$this->html .= "</body></html>";
	}
	
}
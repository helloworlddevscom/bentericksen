<?php
namespace Bentericksen\Traits;

trait WYSIWYGSpaceStrip {
	public function stripSpaces($string) {
		$space = '<p>&nbsp;</p>';
		$arrow = '<ol class="arrow"></ol>';
		$arrowSpace = '<ol class="arrow"><li>&nbsp;</li></ol>';

		$string = str_replace("\r\n", "", $string);
		
		if(strpos($string, $space) !== false)
		{
			$string = str_replace($space, ' ', $string);
		}
		
		if(strpos($string, $arrow) !== false)
		{
			$string = str_replace($arrow, '', $string);
		}
		
		if(strpos($string, $arrowSpace) !== false)
		{
			$string = str_replace($arrowSpace, '', $string);
		}
		
		return $string;
	}
}
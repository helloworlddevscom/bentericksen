<?php

namespace Bentericksen\Help;

use Illuminate\Contracts\View\View;
use DB;

class Help
{
	private $helps;

	public function __construct()
	{
		
	}

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('help', $this);
    }

	public function text($id, $size="xs", $text=null)
	{
		$helpCode = $this->split($id);
		
		$this->helps = DB::table( 'help' )
						->where( 'section', $helpCode[0] )
						->where( 'sub_section', $helpCode[1] )
						->first();
		
		if( ! is_null($this->helps) )
		{			
			$return = "<a class='btn btn-xs' id='modal_{$id}' data-toggle='modal' data-target='#modalHelp_{$id}'>" . (is_null($text) ? $this->helps->title : $text) . "</a>";
			$help = $this->buildModal($id, $return);
			return $help;
		}
		
		return false;
		
	}
	
	public function button($id, $size="xs", $text=null)
	{
		$helpCode = $this->split($id);
		
		$this->helps = DB::table( 'help' )
						->where( 'section', $helpCode[0] )
						->where( 'sub_section', $helpCode[1] )
						->first();
		
		if( ! is_null($this->helps) )
		{		
			if(!is_null($text) && $text != "")
			{
				$return = "<a class='btn btn-{$size}' id='modal_{$id}' data-toggle='modal' data-target='#modalHelp_{$id}'>{$text}</a>";
			} else {
				$return = "<a class='btn btn-{$size} icon_grey' id='modal_{$id}' data-toggle='modal' data-target='#modalHelp_{$id}'><i class='fa fa-question-circle fa-lg'></i></a>";
			}
		
			$help = $this->buildModal($id, $return);
		
			return $help;
		}
		
		return false;
	}

	private function buildModal($id, $return)
	{		
		$return .= "<div class='modal fade' id='modalHelp_{$id}' tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'>
								<div class='help help_{$id}'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
										<h4 class='modal-title text-center' id='modalLabel'>{$this->helps->title}</h4>
									</div>
									<div class='modal-body'>
										<div class='row'>
											<div class='col-md-12'>"
												 . str_replace("<!--td {border: 1px solid #ccc;}br {mso-data-placement:same-cell;}-->", '', $this->helps->answer) .
											"</div>
										</div>
									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-default btn-primary' data-dismiss='modal'>CLOSE</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					";
		
		return $return ;		
	}
	
	private function split($id)
	{
		$helpCode[0] = substr($id, 0, 1);
		$helpCode[1] = substr($id, 1);
		
		return $helpCode;
	}
	
}
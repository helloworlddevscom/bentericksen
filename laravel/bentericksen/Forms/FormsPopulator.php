<?php

namespace Bentericksen\Forms;

use Illuminate\Support\Collection;

use App\Business;
use App\Form;
use Bentericksen\Expressions\Engine;

class FormsPopulator
{
  private $business;
  
  private $templates;
  
  private $rules;

  /**
   * Undocumented variable
   *
   * @var [type]
   */
  private $engine;

  /**
   * Undocumented function
   *
   * @param Business $business
   * @param Collection $templates
   * @param Collection $rules
   */
  public function __construct(Business $business, Collection $templates, Collection $rules)
  {
    $this->business = $business;
    $this->templates = $templates;
    $this->rules = $rules;
    $this->engine = new Engine();
  }

  public function forms()
  {
    return $this->templates->filter(function($template) {
      return $this->isFormTemplateApplicable($template);
    });
  }

  public function isFormTemplateApplicable($template) 
  {
    return $this->rules->reduce(function($carry, $rule) use($template) {
      return !$carry ? false : $this->engine->evaluate($rule->expression, [
        'form' => $template,
        'business' => $this->business
      ]);
    }, true);
  }

  public function ids() 
  {
    return $this->forms()->pluck('id')->sort();
  }
}
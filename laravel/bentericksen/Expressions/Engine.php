<?php

namespace Bentericksen\Expressions;

use Bentericksen\Expressions\Providers\StringExpressionLanguageProvider;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class Engine
{
  private $expressionLanguage;

  public function __construct()
  {
    $this->expressionLanguage = new ExpressionLanguage();
    $this->expressionLanguage->registerProvider(new StringExpressionLanguageProvider());
  }

  /**
   * evaluate
   *
   * @param string $expression
   * @param array $variables
   * @return boolean
   */
  public function evaluate(string $expression, array $variables = [])
  {
    return $this->expressionLanguage->evaluate($expression, $variables);
  }

  /**
   * debug
   *
   * @param string $expression
   * @param array $variables
   * @return NodeList
   */
  public function debug(string $expression, array $variables = [])
  {
    return $this->expressionLanguage->parse($expression, $variables)->getNodes();
  }
}
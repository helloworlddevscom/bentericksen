<?php

namespace Bentericksen\Expressions\Providers;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * StringExpressionLanguageProvider
 */
class StringExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
  /**
   * getFunctions
   *
   * @return array
   */
  public function getFunctions()
  {
    return [
      ExpressionFunction::fromPhp('strtolower'),
      ExpressionFunction::fromPhp('json_decode')
    ];
  }
}
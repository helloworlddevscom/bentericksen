<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;

use Bentericksen\Expressions\Engine;

/**
 * Class ExpressionsTest - tests for expressions engine setup
 */
class ExpressionsTest extends TestCase
{

  private $engine;
  
  protected function setUp(): void
  {
    $this->engine = new Engine();
    parent::setUp();
  }

  public function testBasicExpression()
  {
    $result = $this->engine->evaluate('1 + 1');
    $this->assertEquals($result, 2);
  }


  public function testJsonDecode()
  {
    $result = $this->engine->evaluate('json_decode(\'["a", "b"]\')');
    $this->assertEquals($result, ["a", "b"]);

    $result = $this->engine->evaluate('foo[\'bar\'] in json_decode(test[\'subject\'])', [
      'foo' => [
        'bar' => 'a'
      ],
      'test' => [
        'subject' => '["a", "b"]'
      ]
    ]);
    $this->assertEquals($result, true);

    $result = $this->engine->evaluate('foo[\'bar\'] in json_decode(\'["a", "b"]\')', [
      'foo' => [
        'bar' => 'c'
      ]
    ]);

    $this->assertEquals($result, false);
  }


}
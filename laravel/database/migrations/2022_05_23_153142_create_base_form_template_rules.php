<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\FormTemplateRule;

class CreateBaseFormTemplateRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      FormTemplateRule::create([
        'name' => 'employee_numbers',
        'expression' => '(!form.min_employee and !form.max_employee) or (business.additional_employees >= form.min_employee and business.additional_employees <= form.max_employee)'
      ]);

      FormTemplateRule::create([
        'name' => 'state',
        'expression' => '(!form.state) or ("ALL" in form.state) or (business.state in form.state) or (business.state != "MT" && "Non-MT" in form.state)'
      ]);

      FormTemplateRule::create([
        'name' => 'industries',
        'expression' => '(!form.industries) or ("ALL" in form.industries) or (business.type in form.industries)'
      ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      FormTemplateRule::whereIn('name', ['employee_numbers', 'state', 'industries'])->delete();
    }
}

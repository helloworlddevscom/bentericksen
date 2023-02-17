<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Category;

class ReorderFormCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Category::create([
        'business_id' => 0,
        'name' => 'Leave of Absence Forms',
        'grouping' => 'forms',
        'order' => 3
      ]);

      Category::create([
        'business_id' => 0,
        'name' => 'Notices & Posters',
        'grouping' => 'forms',
        'order' => 4
      ]);

      Category::where('id', 25)->update(['order' => 0]);
      Category::where('id', 26)->update(['order' => 1]);
      Category::where('id', 27)->update(['order' => 2]);
      Category::where('id', 28)->update(['order' => 5]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Category::where('id', 25)->update(['order' => 1]);
      Category::where('id', 26)->update(['order' => 2]);
      Category::where('id', 27)->update(['order' => 3]);
      Category::where('id', 28)->update(['order' => 4]);
      Category::where('name', 'Leave of Absence Forms')->where('grouping', 'forms')->delete();
      Category::where('name', 'Notices & Posters')->where('grouping', 'forms')->delete();
    }
}

<?php

use App\Salary;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSalariesTableUpdateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumns('users', ['salary', 'salary_rate'])) {
            // Temporarily renaming columns
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('salary', 'salary_old');
                $table->renameColumn('salary_rate', 'salary_rate_old');
            });

            // update all current salaries to match user model.
            $salaries = Salary::with('user')->get();
            foreach ($salaries as $salary) {
                $salary->update(['salary' => (float) $salary->user->salary_old, 'rate' => $salary->user->salary_rate_old]);
            }

            // Getting all users without a salary record.
            $ids = Salary::all()->pluck('user_id');
            $users = User::whereNotIn('id', $ids)->get();

            // creating a record for all users without a salary record.
            $salaries = [];
            foreach ($users as $user) {
                $array = [];
                $array['user_id'] = $user->id;
                $array['salary'] = (float) $user->salary_old;
                $array['rate'] = $user->salary_rate_old ? $user->salary_rate_old : 'hour';
                $array['effective_at'] = now();
                $array['salary_updated'] = now();
                array_push($salaries, $array);
            }
            Salary::insert($salaries);

            // dropping temporary columns.
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['salary_old', 'salary_rate_old']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

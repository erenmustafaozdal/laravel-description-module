<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Description;
use Carbon\Carbon;

class AddDateColumnToDescriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( ! Schema::hasColumn('descriptions', 'date')) {
            Schema::table('descriptions', function ($table) {
                $table->date('date')->nullable()->after('title');
            });
            // verileri taşı
            $descriptions = Description::whereIn('category_id',[
                config('ezelnet.seed.description_category.campaigns'),
                config('ezelnet.seed.description_category.education_activities')
            ])->get();
            foreach($descriptions as $description) {
                if ( ! is_null($description->extras->where('type','date')->first())) {
                    $description->date = Carbon::parse($description->extras->where('type', 'date')->first()->pivot->value);
                    $description->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('descriptions', 'date')) {
            Schema::table('descriptions', function ($table) {
                $table->dropColumn('date');
            });
        }
    }
}

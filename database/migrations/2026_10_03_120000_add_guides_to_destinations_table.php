<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('best_months', 40)->nullable()->after('tips_id'); // comma list of month numbers, e.g. "4,5,6"
            $table->text('best_time_en')->nullable()->after('best_months');
            $table->text('best_time_id')->nullable()->after('best_time_en');
            $table->text('packing_en')->nullable()->after('best_time_id');
            $table->text('packing_id')->nullable()->after('packing_en');
            $table->text('provided_en')->nullable()->after('packing_id');
            $table->text('provided_id')->nullable()->after('provided_en');
            $table->text('safety_en')->nullable()->after('provided_id');
            $table->text('safety_id')->nullable()->after('safety_en');
        });

        foreach (require database_path('data/destination_guides.php') as $slug => $g) {
            DB::table('destinations')->where('slug', $slug)->update([
                'best_months' => implode(',', $g['months']),
                'best_time_en' => $g['best_time']['en'],
                'best_time_id' => $g['best_time']['id'],
                'packing_en' => $g['packing']['en'],
                'packing_id' => $g['packing']['id'],
                'provided_en' => $g['provided']['en'] ?? null,
                'provided_id' => $g['provided']['id'] ?? null,
                'safety_en' => $g['safety']['en'],
                'safety_id' => $g['safety']['id'],
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn([
                'best_months', 'best_time_en', 'best_time_id', 'packing_en', 'packing_id',
                'provided_en', 'provided_id', 'safety_en', 'safety_id',
            ]);
        });
    }
};

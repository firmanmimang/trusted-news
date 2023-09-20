<?php

use App\Models\News;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('the_rich_text', function (Blueprint $table) {
            foreach (DB::table(News::TABLE)->oldest('id')->cursor() as $post)
            {
                DB::table('rich_texts')->insert([
                    'field' => 'body',
                    'body' => '<div>' . $post->body . '</div>',
                    'record_type' => (new News())->getMorphClass(),
                    'record_id' => $post->id,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                ]);
            }
            
            Schema::table(News::TABLE, function (Blueprint $table) {
                $table->dropColumn('body');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('the_rich_text', function (Blueprint $table) {
            //
        });
    }
};

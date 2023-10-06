<?php

use App\Models\Fabric\Integration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UpdateIntegrationsTableAddSlugColumn extends Migration
{
    public $skipPrimaryKeyChecks = true;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->string('slug')->after('name')->nullable();
        });

        /**
         * Create a slug for each integration, appending an integer if the slug already exists.
         */
        Integration::chunk(100, fn ($integrations) => $integrations
            ->each(function ($integration) {
                $original = Str::slug($integration->name);
                $slug = $original;
                $count = 1;

                while (Integration::whereSlug($slug)->exists()) {
                    $slug = "{$original}-" . $count++;
                }

                $integration->update(['slug' => $slug]);
            }));

        Schema::table('integrations', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}

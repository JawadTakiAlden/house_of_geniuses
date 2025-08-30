<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lesions', function (Blueprint $table) {
            $table->text("about")->nullable();
            $table->text("learned_values")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesions', function (Blueprint $table) {
            $table->dropColumn("about");
            $table->dropColumn("learned_values");
        });
    }
};

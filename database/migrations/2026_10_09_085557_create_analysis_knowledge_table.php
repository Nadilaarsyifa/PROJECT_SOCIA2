<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::create('analysis_knowledge', function (Blueprint $table) {
            $table->unsignedBigInteger('id_analysis');
            $table->unsignedBigInteger('id_knowledge');

            $table->primary(['id_analysis', 'id_knowledge']);

            $table->foreign('id_analysis')
                ->references('id_analysis')
                ->on('ai_analyses')
                ->cascadeOnDelete();

            $table->foreign('id_knowledge')
                ->references('id_knowledge')
                ->on('knowledge_bases')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_knowledge');
    }
};

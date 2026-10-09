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
        Schema::create('action_checklists', function (Blueprint $table) {
            $table->id('id_action');
            $table->text('action');
            $table->string('status')->default('Pending');
            $table->text('notes')->nullable();
            $table->unsignedInteger('action_order')->default(1);

            $table->unsignedBigInteger('id_analysis');

            $table->foreign('id_analysis')
                ->references('id_analysis')
                ->on('ai_analyses')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_checklists');
    }
};

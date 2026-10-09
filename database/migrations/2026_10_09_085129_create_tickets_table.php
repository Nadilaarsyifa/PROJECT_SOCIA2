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
        Schema::create('tickets', function (Blueprint $table) {
            $table->string('no_ticket')->primary();
            $table->string('offense_id')->nullable();
            $table->string('detection');
            $table->string('risk_level')->nullable();
            $table->dateTime('event_time')->nullable();
            $table->string('status')->default('Open');
            $table->text('raw_message');
            $table->timestamp('inserted_at')->useCurrent();

            $table->unsignedBigInteger('id_user');

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

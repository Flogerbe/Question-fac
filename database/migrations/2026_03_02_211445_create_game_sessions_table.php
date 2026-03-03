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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('temps_total')->default(0)->comment('Temps total en secondes');
            $table->boolean('joker_fifty')->default(false)->comment('Joker 50/50 utilisé');
            $table->boolean('joker_public')->default(false)->comment('Joker Vote du public utilisé');
            $table->boolean('joker_coach')->default(false)->comment('Joker Question au coach utilisé');
            $table->boolean('completed')->default(false);
            $table->boolean('counted')->default(false)->comment('Si false: partie non comptée (anti-triche silencieux)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};

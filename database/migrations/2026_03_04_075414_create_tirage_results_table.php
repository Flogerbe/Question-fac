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
        Schema::create('tirage_results', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['esprit_club', 'champion', 'bonus']);
            $table->foreignId('game_session_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rang')->default(1);
            $table->timestamp('drawn_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tirage_results');
    }
};

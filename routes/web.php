<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\JerseyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\LeaderboardController;
use App\Http\Controllers\Admin\JerseyController as AdminJerseyController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PlayersController;
use App\Http\Controllers\Admin\TirageController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// === INSTALLATION (première utilisation) ===
Route::get('/install', [InstallController::class, 'index'])->name('install');
Route::post('/install', [InstallController::class, 'store'])->name('install.store');

// === FRONT ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/regles', [HomeController::class, 'regles'])->name('regles');
Route::get('/classement', [HomeController::class, 'classement'])->name('classement');

// Quiz
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
Route::post('/quiz/demarrer', [QuizController::class, 'demarrer'])->name('quiz.demarrer');
Route::get('/quiz/jouer', [QuizController::class, 'jouer'])->name('quiz.jouer');
Route::post('/quiz/repondre', [QuizController::class, 'repondre'])->name('quiz.repondre');
Route::post('/quiz/joker', [QuizController::class, 'joker'])->name('quiz.joker');
Route::post('/quiz/verifier', [QuizController::class, 'verifier'])->name('quiz.verifier');
Route::get('/quiz/resultat', [QuizController::class, 'resultat'])->name('quiz.resultat');
Route::post('/quiz/terminer', [QuizController::class, 'terminer'])->name('quiz.terminer');

// Vote maillot
Route::get('/maillot', [JerseyController::class, 'index'])->name('jersey.index');
Route::post('/maillot/voter', [JerseyController::class, 'voter'])->name('jersey.voter');

// === BACK-OFFICE ADMIN ===
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('maintenance/migrate', [DashboardController::class, 'migrate'])->name('maintenance.migrate');
    Route::post('maintenance/composer', [DashboardController::class, 'composerInstall'])->name('maintenance.composer');

    // Questions
    Route::resource('questions', QuestionController::class);
    Route::post('questions/{question}/toggle', [QuestionController::class, 'toggle'])->name('questions.toggle');

    // Classement
    Route::get('classement', [LeaderboardController::class, 'index'])->name('leaderboard');
    Route::delete('classement/{session}', [LeaderboardController::class, 'destroy'])->name('leaderboard.destroy');

    // Tirage au sort
    Route::get('tirage', [TirageController::class, 'index'])->name('tirage.index');
    Route::post('tirage/{type}', [TirageController::class, 'draw'])->name('tirage.draw');
    Route::delete('tirage/{type}', [TirageController::class, 'reset'])->name('tirage.reset');

    // Vote maillot
    Route::get('maillot', [AdminJerseyController::class, 'index'])->name('jersey.index');
    Route::post('maillot', [AdminJerseyController::class, 'store'])->name('jersey.store');
    Route::put('maillot/{option}', [AdminJerseyController::class, 'update'])->name('jersey.update');
    Route::delete('maillot/{option}', [AdminJerseyController::class, 'destroy'])->name('jersey.destroy');
    Route::delete('maillot/{option}/votes', [AdminJerseyController::class, 'resetVotes'])->name('jersey.resetVotes');

    // Joueurs
    Route::get('joueurs', [PlayersController::class, 'index'])->name('players.index');
    Route::delete('joueurs/{player}', [PlayersController::class, 'destroy'])->name('players.destroy');

    // Paramètres
    Route::get('parametres', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('parametres', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('parametres/reset', [SettingsController::class, 'resetColors'])->name('settings.reset');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Player;
use App\Models\GameSession;
use App\Models\GameAnswer;
use App\Models\SiteSetting;

class QuizController extends Controller
{
    const POINTS_BASE = 500;
    const BONUS_MAX = 500;
    const TEMPS_QUESTION = 30;

    public function index()
    {
        return view('quiz.index');
    }

    public function demarrer(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:50|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'nom'    => 'required|string|max:50|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/',
        ], [
            'prenom.required' => 'Votre prénom est obligatoire.',
            'prenom.max'      => 'Le prénom ne peut pas dépasser 50 caractères.',
            'prenom.regex'    => 'Le prénom ne peut contenir que des lettres, espaces et tirets.',
            'nom.required'    => 'Votre nom est obligatoire.',
            'nom.max'         => 'Le nom ne peut pas dépasser 50 caractères.',
            'nom.regex'       => 'Le nom ne peut contenir que des lettres, espaces et tirets.',
        ]);

        $prenom = trim($request->prenom);
        $nom    = trim($request->nom);
        $ip = $request->ip();
        $ipHash = Player::hashIp($ip);
        $browserToken = $request->input('browser_token');

        $mode = SiteSetting::get('participation_mode') ?? 'once';
        $max  = max(1, (int)(SiteSetting::get('participation_nb') ?? 1));

        $blocked = false;
        if ($mode === 'once') {
            $blocked = Player::countPlays($ip, $browserToken, false) >= $max;
        } elseif ($mode === 'par_jour') {
            $blocked = Player::countPlays($ip, $browserToken, true) >= $max;
        }
        // mode 'illimite' → $blocked stays false

        if ($blocked) {
            return redirect()->route('quiz.index')
                ->with('already_played', $mode);
        }

        $player = Player::create([
            'prenom' => $prenom,
            'nom'    => $nom,
            'ip_hash' => $ipHash,
            'browser_token' => $browserToken ? hash('sha256', $browserToken . config('app.key')) : null,
            'played_at' => now(),
        ]);

        $session = GameSession::create([
            'player_id' => $player->id,
            'score' => 0,
            'temps_total' => 0,
            'counted' => true,
        ]);

        // Mélanger aléatoirement l'ordre des questions pour chaque partie
        $questionIds = Question::active()->pluck('id')->toArray();
        shuffle($questionIds);

        $request->session()->put('quiz_session_id', $session->id);
        $request->session()->put('quiz_question_ids', $questionIds);
        $request->session()->put('quiz_question_index', 0);
        $request->session()->put('quiz_start_time', now()->timestamp);

        return redirect()->route('quiz.jouer');
    }

    private function getSessionQuestions(Request $request): \Illuminate\Support\Collection
    {
        $questionIds = $request->session()->get('quiz_question_ids', []);
        if (empty($questionIds)) {
            return collect();
        }
        $questionsById = Question::with('answers')->whereIn('id', $questionIds)->get()->keyBy('id');
        return collect($questionIds)->map(fn($id) => $questionsById->get($id))->filter()->values();
    }

    public function jouer(Request $request)
    {
        $sessionId = $request->session()->get('quiz_session_id');
        if (!$sessionId) {
            return redirect()->route('quiz.index');
        }

        $gameSession = GameSession::findOrFail($sessionId);
        $questionIndex = $request->session()->get('quiz_question_index', 0);
        $questions = $this->getSessionQuestions($request);
        if ($questions->isEmpty()) {
            return redirect()->route('quiz.index');
        }

        if ($questionIndex >= count($questions)) {
            return redirect()->route('quiz.resultat');
        }

        $question = $questions[$questionIndex];
        $answers = $question->answers->shuffle();

        // Jokers déjà utilisés
        $jokersUsed = [
            'fifty' => $gameSession->joker_fifty,
            'public' => $gameSession->joker_public,
            'coach' => $gameSession->joker_coach,
        ];

        // Si joker 50/50 actif pour cette question
        $fiftyAnswers = $request->session()->get('fifty_answers_' . $question->id, null);

        return view('quiz.jouer', [
            'question' => $question,
            'answers' => $answers,
            'questionIndex' => $questionIndex,
            'totalQuestions' => count($questions),
            'jokersUsed' => $jokersUsed,
            'tempsQuestion' => self::TEMPS_QUESTION,
            'fiftyAnswers' => $fiftyAnswers,
            'gameSession' => $gameSession,
        ]);
    }

    public function repondre(Request $request)
    {
        $sessionId = $request->session()->get('quiz_session_id');
        if (!$sessionId) {
            return redirect()->route('quiz.index');
        }

        $gameSession = GameSession::findOrFail($sessionId);
        $questionIndex = $request->session()->get('quiz_question_index', 0);
        $questions = Question::active()->get();
        $question = $questions[$questionIndex];

        $answerId = $request->input('answer_id');
        $tempsReponse = min((int) $request->input('temps_reponse', self::TEMPS_QUESTION), self::TEMPS_QUESTION);

        $answer = $answerId ? Answer::find($answerId) : null;
        $isCorrect = $answer && $answer->is_correct && $answer->question_id == $question->id;

        $points = 0;
        if ($isCorrect) {
            $tempsRestant = max(0, self::TEMPS_QUESTION - $tempsReponse);
            $bonusVitesse = (int) (self::BONUS_MAX * ($tempsRestant / self::TEMPS_QUESTION));
            $points = self::POINTS_BASE + $bonusVitesse;
        }

        GameAnswer::create([
            'game_session_id' => $gameSession->id,
            'question_id' => $question->id,
            'answer_id' => $answerId,
            'is_correct' => $isCorrect,
            'temps_reponse' => $tempsReponse,
            'points_gagnes' => $points,
        ]);

        $gameSession->score += $points;
        $gameSession->temps_total += $tempsReponse;
        $gameSession->save();

        $request->session()->put('quiz_question_index', $questionIndex + 1);
        $request->session()->put('last_answer_correct', $isCorrect);
        $request->session()->put('last_points', $points);
        $request->session()->put('last_answer_id', $answerId);
        $request->session()->put('last_correct_answer_id', $question->correctAnswer()?->id);

        // Nettoyer le joker 50/50 de la session pour cette question
        $request->session()->forget('fifty_answers_' . $question->id);

        $nextIndex = $questionIndex + 1;
        if ($nextIndex >= count($questions)) {
            $gameSession->completed = true;
            $gameSession->save();
            return redirect()->route('quiz.resultat');
        }

        return redirect()->route('quiz.jouer');
    }

    public function verifier(Request $request)
    {
        $sessionId = $request->session()->get('quiz_session_id');
        if (!$sessionId) {
            return response()->json(['error' => 'Session invalide'], 400);
        }

        $gameSession = GameSession::findOrFail($sessionId);
        $questionIndex = $request->session()->get('quiz_question_index', 0);
        $questions = Question::active()->get();
        $question = $questions[$questionIndex];

        $answerId = $request->input('answer_id');
        $tempsReponse = min((int) $request->input('temps_reponse', self::TEMPS_QUESTION), self::TEMPS_QUESTION);

        $answer = $answerId ? Answer::find($answerId) : null;
        $isCorrect = $answer && $answer->is_correct && $answer->question_id == $question->id;
        $correctAnswer = $question->answers->where('is_correct', true)->first();

        $points = 0;
        if ($isCorrect) {
            $tempsRestant = max(0, self::TEMPS_QUESTION - $tempsReponse);
            $bonusVitesse = (int) (self::BONUS_MAX * ($tempsRestant / self::TEMPS_QUESTION));
            $points = self::POINTS_BASE + $bonusVitesse;
        }

        GameAnswer::create([
            'game_session_id' => $gameSession->id,
            'question_id'     => $question->id,
            'answer_id'       => $answerId,
            'is_correct'      => $isCorrect,
            'temps_reponse'   => $tempsReponse,
            'points_gagnes'   => $points,
        ]);

        $gameSession->score += $points;
        $gameSession->temps_total += $tempsReponse;
        $gameSession->save();

        $nextIndex = $questionIndex + 1;
        $request->session()->put('quiz_question_index', $nextIndex);
        $request->session()->forget('fifty_answers_' . $question->id);

        $isLast = $nextIndex >= count($questions);
        if ($isLast) {
            $gameSession->completed = true;
            $gameSession->save();
        }

        return response()->json([
            'is_correct'        => $isCorrect,
            'correct_answer_id' => $correctAnswer?->id,
            'points'            => $points,
            'next_url'          => $isLast ? route('quiz.resultat') : route('quiz.jouer'),
        ]);
    }

    public function joker(Request $request)
    {
        $sessionId = $request->session()->get('quiz_session_id');
        if (!$sessionId) {
            return response()->json(['error' => 'Session invalide'], 400);
        }

        $gameSession = GameSession::findOrFail($sessionId);
        $type = $request->input('type');
        $questionId = $request->input('question_id');
        $question = Question::with('answers')->findOrFail($questionId);

        switch ($type) {
            case 'fifty':
                if ($gameSession->joker_fifty) {
                    return response()->json(['error' => 'Joker déjà utilisé'], 400);
                }
                $gameSession->joker_fifty = true;
                $gameSession->save();

                $correct = $question->answers->where('is_correct', true)->first();
                $wrong = $question->answers->where('is_correct', false)->shuffle()->take(1)->first();
                $keptIds = [$correct->id, $wrong->id];

                $request->session()->put('fifty_answers_' . $question->id, $keptIds);
                return response()->json(['kept_ids' => $keptIds]);

            case 'public':
                if ($gameSession->joker_public) {
                    return response()->json(['error' => 'Joker déjà utilisé'], 400);
                }
                $gameSession->joker_public = true;
                $gameSession->save();

                $percentages = $this->generatePublicVote($question);
                return response()->json(['percentages' => $percentages]);

            case 'coach':
                if ($gameSession->joker_coach) {
                    return response()->json(['error' => 'Joker déjà utilisé'], 400);
                }
                $gameSession->joker_coach = true;
                $gameSession->save();

                return response()->json(['hint' => $question->coach_hint ?? 'Le coach n\'a pas d\'indice pour cette question !']);

            default:
                return response()->json(['error' => 'Type de joker invalide'], 400);
        }
    }

    private function generatePublicVote(Question $question): array
    {
        $answers = $question->answers;
        $correctId = $question->answers->where('is_correct', true)->first()->id;

        // Génère des pourcentages biaisés vers la bonne réponse
        $correctPct = rand(55, 78);
        $remaining = 100 - $correctPct;
        $wrongAnswers = $answers->where('is_correct', false);
        $count = $wrongAnswers->count();
        $percentages = [];

        foreach ($answers as $answer) {
            if ($answer->is_correct) {
                $percentages[$answer->id] = $correctPct;
            } else {
                // Distribue aléatoirement le reste
                if ($count > 0) {
                    $pct = ($count === 1) ? $remaining : rand(1, $remaining - ($count - 1));
                    $percentages[$answer->id] = $pct;
                    $remaining -= $pct;
                    $count--;
                }
            }
        }

        return $percentages;
    }

    public function resultat(Request $request)
    {
        $sessionId = $request->session()->get('quiz_session_id');
        if (!$sessionId) {
            return redirect()->route('quiz.index');
        }

        $gameSession = GameSession::with(['player', 'gameAnswers'])->findOrFail($sessionId);

        if (!$gameSession->completed) {
            return redirect()->route('quiz.jouer');
        }

        $rank = null;
        if ($gameSession->counted) {
            $rank = GameSession::where('completed', true)
                ->where('counted', true)
                ->where(function ($q) use ($gameSession) {
                    $q->where('score', '>', $gameSession->score)
                      ->orWhere(function ($q2) use ($gameSession) {
                          $q2->where('score', $gameSession->score)
                             ->where('temps_total', '<', $gameSession->temps_total);
                      });
                })->count() + 1;
        }

        $totalQuestions = Question::active()->count();
        $correctAnswers = $gameSession->gameAnswers->where('is_correct', true)->count();

        // Nettoie la session quiz
        $request->session()->forget(['quiz_session_id', 'quiz_question_index', 'quiz_start_time']);

        return view('quiz.resultat', compact('gameSession', 'rank', 'totalQuestions', 'correctAnswers'));
    }

    public function terminer(Request $request)
    {
        return redirect()->route('home');
    }
}

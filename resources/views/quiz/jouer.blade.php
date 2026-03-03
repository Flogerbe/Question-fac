@extends('layouts.fac')
@section('title', 'Question '.($questionIndex+1).'/'.$totalQuestions.' - Quiz FAC')
@section('styles')
<style>
.quiz-wrapper{max-width:800px;margin:0 auto;padding:1.5rem;}
.progress-bar-wrap{background:var(--gris-card);border-radius:10px;height:8px;margin-bottom:1.2rem;overflow:hidden;}
.progress-bar{height:100%;background:linear-gradient(90deg,var(--orange),var(--orange-lt));border-radius:10px;transition:width .3s;}
.question-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;}
.q-num{color:#888;font-size:.9rem;}
.q-score{color:var(--orange);font-weight:700;font-size:.9rem;}
.timer-wrap{text-align:center;margin-bottom:1.2rem;}
.timer{font-size:2.5rem;font-weight:900;color:var(--orange);line-height:1;transition:color .3s;}
.timer.warning{color:#ffcc00;}
.timer.danger{color:var(--rouge);animation:pulse .5s infinite;}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:.5;}}
.question-text{background:linear-gradient(135deg,var(--gris-card),var(--bleu-mid));border:1px solid rgba(244,120,32,.3);border-radius:12px;padding:1.8rem;font-size:1.2rem;font-weight:600;line-height:1.5;color:var(--blanc);margin-bottom:1.5rem;text-align:center;}
.answers-grid{display:grid;grid-template-columns:1fr 1fr;gap:.8rem;margin-bottom:1.5rem;}
.answer-btn{background:var(--gris-card);border:2px solid rgba(244,120,32,.2);border-radius:10px;padding:1rem 1.2rem;color:var(--blanc);font-size:1rem;cursor:pointer;transition:all .2s;text-align:left;display:flex;align-items:center;gap:.8rem;width:100%;}
.answer-btn:hover:not(:disabled){border-color:var(--orange);background:rgba(244,120,32,.1);transform:translateY(-2px);}
@media(max-width:600px){
    .quiz-wrapper{padding:1rem;}
    .answers-grid{grid-template-columns:1fr;}
    .jokers-bar{gap:.5rem;}
    .joker-btn{font-size:.78rem;padding:.5rem .8rem;}
    .question-text{font-size:1.05rem;padding:1.2rem;}
    .timer{font-size:2rem;}
    .answer-btn{padding:.8rem 1rem;font-size:.95rem;}
}
@media(max-width:400px){
    .jokers-bar{flex-direction:column;align-items:stretch;}
    .joker-btn{justify-content:center;}
}
.answer-btn:disabled{cursor:not-allowed;}
.answer-btn.hidden-fifty{opacity:.2;pointer-events:none;}
.answer-btn.correct{border-color:#22C55E !important;background:rgba(34,197,94,.2) !important;}
.answer-btn.correct .answer-letter{background:rgba(34,197,94,.35);color:#22C55E;}
.answer-btn.wrong{border-color:#EF4444 !important;background:rgba(239,68,68,.2) !important;}
.answer-btn.wrong .answer-letter{background:rgba(239,68,68,.35);color:#EF4444;}
.feedback-msg{text-align:center;font-size:1.05rem;font-weight:700;padding:.6rem 1rem;margin-bottom:1rem;border-radius:8px;}
.feedback-msg.ok{background:rgba(34,197,94,.15);color:#22C55E;border:1px solid rgba(34,197,94,.4);}
.feedback-msg.ko{background:rgba(239,68,68,.15);color:#F87171;border:1px solid rgba(239,68,68,.4);}
.answer-letter{width:28px;height:28px;border-radius:50%;background:rgba(244,120,32,.2);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;color:var(--orange);flex-shrink:0;}
.jokers-bar{display:flex;gap:1rem;justify-content:center;margin-bottom:1.5rem;flex-wrap:wrap;}
.joker-btn{background:var(--gris-card);border:2px solid rgba(244,120,32,.3);border-radius:10px;padding:.6rem 1.2rem;color:var(--texte-mut);font-size:.85rem;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:.5rem;}
.joker-btn:hover:not([disabled]){border-color:var(--orange);color:var(--orange);}
.joker-btn[disabled]{opacity:.35;cursor:not-allowed;border-color:rgba(255,255,255,.1);color:var(--texte-mut);}
.joker-result{background:var(--gris-dark);border:1px solid var(--orange);border-radius:10px;padding:1rem 1.2rem;margin-bottom:1rem;}
.public-bars{display:grid;gap:.6rem;margin-top:.8rem;}
.public-bar-item{display:flex;align-items:center;gap:.8rem;font-size:.9rem;}
.public-bar-bg{flex:1;background:rgba(255,255,255,.1);border-radius:4px;height:20px;overflow:hidden;}
.public-bar-fill{height:100%;background:linear-gradient(90deg,var(--orange),var(--orange-lt));border-radius:4px;transition:width .8s ease;}
</style>
@endsection

@section('content')
<div class="quiz-wrapper">
    <div class="progress-bar-wrap">
        <div class="progress-bar" style="width:{{ (($questionIndex+1)/$totalQuestions)*100 }}%"></div>
    </div>

    <div class="question-header">
        <span class="q-num">Question {{ $questionIndex + 1 }} / {{ $totalQuestions }}</span>
        <span class="q-score">Score : <strong>{{ number_format($gameSession->score, 0, ',', ' ') }} pts</strong></span>
    </div>

    <div class="timer-wrap">
        <div class="timer" id="timer">{{ $tempsQuestion }}</div>
        <div style="font-size:.75rem;color:#666;margin-top:2px;">secondes restantes</div>
    </div>

    <div class="question-text">{{ $question->question }}</div>

    <div id="coach-result" class="joker-result" style="display:none;">
        <span style="color:var(--orange);font-weight:700;">🎓 Le coach vous souffle : </span>
        <span id="coach-hint" style="color:#ddd;font-style:italic;"></span>
    </div>

    <div id="public-result" class="joker-result" style="display:none;">
        <strong style="color:var(--orange);">👥 Résultats du vote du public :</strong>
        <div class="public-bars" id="public-bars"></div>
    </div>

    <div class="jokers-bar">
        <button class="joker-btn" id="joker-fifty" {{ $jokersUsed['fifty'] ? 'disabled' : '' }} onclick="useJoker('fifty')">
            ✂️ 50/50{{ $jokersUsed['fifty'] ? ' ✓' : '' }}
        </button>
        <button class="joker-btn" id="joker-public" {{ $jokersUsed['public'] ? 'disabled' : '' }} onclick="useJoker('public')">
            👥 Vote du public{{ $jokersUsed['public'] ? ' ✓' : '' }}
        </button>
        <button class="joker-btn" id="joker-coach" {{ $jokersUsed['coach'] ? 'disabled' : '' }} onclick="useJoker('coach')">
            🎓 Question au coach{{ $jokersUsed['coach'] ? ' ✓' : '' }}
        </button>
    </div>

    <div id="feedback-msg" style="display:none;"></div>

    <form id="answer-form" action="{{ route('quiz.repondre') }}" method="POST">
        @csrf
        <input type="hidden" name="temps_reponse" id="temps_reponse" value="{{ $tempsQuestion }}">
        <input type="hidden" name="answer_id" id="answer_id_input" value="">

        <div class="answers-grid">
            @php $letters = ['A','B','C','D']; @endphp
            @foreach($answers as $idx => $answer)
            <button type="button"
                class="answer-btn {{ $fiftyAnswers && !in_array($answer->id, $fiftyAnswers) ? 'hidden-fifty' : '' }}"
                id="answer-{{ $answer->id }}"
                data-id="{{ $answer->id }}"
                onclick="selectAnswer({{ $answer->id }}, this)"
                {{ $fiftyAnswers && !in_array($answer->id, $fiftyAnswers) ? 'disabled' : '' }}>
                <div class="answer-letter">{{ $letters[$idx] ?? ($idx+1) }}</div>
                <span>{{ $answer->reponse }}</span>
            </button>
            @endforeach
        </div>
    </form>
</div>

@section('scripts')
<script>
const TEMPS_MAX = {{ $tempsQuestion }};
const QUESTION_ID = {{ $question->id }};
const ANSWERS_DATA = {!! json_encode($answers->map(fn($a) => ['id' => $a->id, 'reponse' => $a->reponse])) !!};
let timeLeft = TEMPS_MAX;
let answered = false;
let timerInterval;

function startTimer() {
    timerInterval = setInterval(() => {
        timeLeft--;
        const timerEl = document.getElementById('timer');
        timerEl.textContent = timeLeft;
        if (timeLeft <= 5) timerEl.className = 'timer danger';
        else if (timeLeft <= 10) timerEl.className = 'timer warning';
        if (timeLeft <= 0) { clearInterval(timerInterval); if (!answered) autoSubmit(); }
    }, 1000);
}

function selectAnswer(answerId, btn) {
    if (answered) return;
    answered = true;
    clearInterval(timerInterval);
    document.querySelectorAll('.answer-btn').forEach(b => b.disabled = true);

    const tempsReponse = TEMPS_MAX - timeLeft;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('{{ route("quiz.verifier") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ answer_id: answerId, temps_reponse: tempsReponse })
    })
    .then(r => r.json())
    .then(data => {
        // Colorer la réponse choisie
        if (data.is_correct) {
            btn.classList.add('correct');
        } else {
            btn.classList.add('wrong');
            // Montrer la bonne réponse en vert
            const correctBtn = document.getElementById('answer-' + data.correct_answer_id);
            if (correctBtn) correctBtn.classList.add('correct');
        }
        // Message feedback
        const fb = document.getElementById('feedback-msg');
        fb.className = 'feedback-msg ' + (data.is_correct ? 'ok' : 'ko');
        fb.textContent = data.is_correct
            ? '✅ Bonne réponse ! +' + data.points + ' pts'
            : '❌ Mauvaise réponse…';
        fb.style.display = 'block';

        setTimeout(() => window.location.href = data.next_url, 1600);
    })
    .catch(() => {
        // Fallback : soumission classique
        document.getElementById('answer_id_input').value = answerId;
        document.getElementById('temps_reponse').value = tempsReponse;
        document.getElementById('answer-form').submit();
    });
}

function autoSubmit() {
    if (answered) return;
    answered = true;
    document.getElementById('temps_reponse').value = TEMPS_MAX;
    document.getElementById('answer-form').submit();
}

function useJoker(type) {
    const btn = document.getElementById('joker-' + type);
    btn.setAttribute('disabled', '');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('{{ route("quiz.joker") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ type: type, question_id: QUESTION_ID })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { btn.removeAttribute('disabled'); return; }

        if (type === 'fifty') {
            document.querySelectorAll('.answer-btn').forEach(b => {
                const id = parseInt(b.getAttribute('data-id'));
                if (!data.kept_ids.includes(id)) {
                    b.classList.add('hidden-fifty');
                    b.setAttribute('disabled', '');
                }
            });
            btn.textContent = '✂️ 50/50 ✓';
        } else if (type === 'public') {
            const container = document.getElementById('public-bars');
            container.innerHTML = '';
            ANSWERS_DATA.forEach(ans => {
                const pct = data.percentages[ans.id] || 0;
                const div = document.createElement('div');
                div.className = 'public-bar-item';
                div.innerHTML = '<span style="min-width:120px;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.85rem;">' + ans.reponse + '</span>'
                    + '<div class="public-bar-bg"><div class="public-bar-fill" style="width:0%"></div></div>'
                    + '<span style="width:35px;text-align:right;color:var(--orange);font-weight:700;">' + pct + '%</span>';
                container.appendChild(div);
                setTimeout(() => div.querySelector('.public-bar-fill').style.width = pct + '%', 100);
            });
            document.getElementById('public-result').style.display = 'block';
            btn.textContent = '👥 Vote du public ✓';
        } else if (type === 'coach') {
            document.getElementById('coach-hint').textContent = data.hint;
            document.getElementById('coach-result').style.display = 'block';
            btn.textContent = '🎓 Question au coach ✓';
        }
    })
    .catch(() => btn.removeAttribute('disabled'));
}

startTimer();
</script>
@endsection
@endsection

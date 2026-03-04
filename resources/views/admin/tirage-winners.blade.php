@php $medals = ['🥇','🥈','🥉','4️⃣','5️⃣','6️⃣','7️⃣','8️⃣','9️⃣','🔟']; @endphp

@if($results && $results->count() > 0)
    <h3>🎉 Gagnants tirés</h3>
    @foreach($results as $i => $r)
    <div class="winner-item">
        <span class="winner-rank">{{ $medals[$i] ?? ($i+1) }}</span>
        <span class="winner-name">{{ $r->gameSession->player->full_name }}</span>
        <span class="winner-score">
            {{ number_format($r->gameSession->score, 0, ',', ' ') }} pts
            · {{ $r->gameSession->gameAnswers->where('is_correct', true)->count() }}/{{ $totalQuestions }} ✓
        </span>
    </div>
    @endforeach
    <form method="POST" action="{{ route('admin.tirage.reset', $type) }}" onsubmit="return confirm('Réinitialiser ce tirage ?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-reset">🗑️ Réinitialiser ce tirage</button>
    </form>
@else
    <p class="no-winners">Aucun tirage effectué.</p>
@endif

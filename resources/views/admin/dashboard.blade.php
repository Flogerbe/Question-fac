@extends('layouts.admin')
@section('title', 'Tableau de bord')
@section('page-title', '📊 Tableau de bord')

@section('content')
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem;margin-bottom:2rem;">
    @php
    $cards = [
        ['label'=>'Questions actives', 'val'=>$stats['questions_actives'].'/'.$stats['total_questions'], 'icon'=>'❓', 'color'=>'#F7941D'],
        ['label'=>'Parties jouées', 'val'=>$stats['total_parties'], 'icon'=>'🎮', 'color'=>'#3498db'],
        ['label'=>'Parties valides', 'val'=>$stats['parties_valides'], 'icon'=>'✅', 'color'=>'#2ecc71'],
        ['label'=>'Joueurs uniques', 'val'=>$stats['total_joueurs'], 'icon'=>'👤', 'color'=>'#9b59b6'],
        ['label'=>'Votes maillot', 'val'=>$stats['total_votes_maillot'], 'icon'=>'👕', 'color'=>'#e67e22'],
    ];
    @endphp
    @foreach($cards as $card)
    <div style="background:white;border-radius:12px;padding:1.5rem;border-left:4px solid {{ $card['color'] }};box-shadow:0 2px 8px rgba(0,0,0,.06);">
        <div style="font-size:1.8rem;margin-bottom:.4rem;">{{ $card['icon'] }}</div>
        <div style="font-size:1.8rem;font-weight:900;color:{{ $card['color'] }};">{{ $card['val'] }}</div>
        <div style="font-size:.8rem;color:#888;margin-top:.2rem;">{{ $card['label'] }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.06);">
        <h2 style="font-size:1rem;font-weight:700;color:#111;margin-bottom:1rem;border-bottom:2px solid var(--orange);padding-bottom:.5rem;">🏆 Top 5 scores</h2>
        @if($topScores->count() > 0)
        <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
            <thead>
                <tr style="color:#888;border-bottom:1px solid #f0f0f0;">
                    <th style="padding:.4rem;text-align:left;">Rang</th>
                    <th style="padding:.4rem;text-align:left;">Prénom</th>
                    <th style="padding:.4rem;text-align:right;">Score</th>
                    <th style="padding:.4rem;text-align:right;">Temps</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topScores as $i => $s)
                <tr style="border-bottom:1px solid #f5f5f5;">
                    <td style="padding:.5rem .4rem;">{{ ['🥇','🥈','🥉','4','5'][$i] }}</td>
                    <td style="padding:.5rem .4rem;font-weight:600;">{{ $s->player->full_name }}</td>
                    <td style="padding:.5rem .4rem;text-align:right;color:var(--orange);font-weight:700;">{{ number_format($s->score,0,',',' ') }}</td>
                    <td style="padding:.5rem .4rem;text-align:right;color:#888;">{{ floor($s->temps_total/60) }}m{{ $s->temps_total%60 }}s</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color:#aaa;text-align:center;padding:1rem;">Aucune partie terminée.</p>
        @endif
    </div>

    <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.06);">
        <h2 style="font-size:1rem;font-weight:700;color:#111;margin-bottom:1rem;border-bottom:2px solid var(--orange);padding-bottom:.5rem;">🚀 Actions rapides</h2>
        <div style="display:flex;flex-direction:column;gap:.8rem;">
            <a href="{{ route('admin.questions.create') }}" style="background:#F7941D;color:white;padding:.7rem 1rem;border-radius:8px;text-decoration:none;font-weight:600;font-size:.9rem;text-align:center;">+ Ajouter une question</a>
            <a href="{{ route('admin.questions.index') }}" style="background:#f8f8f8;color:#333;padding:.7rem 1rem;border-radius:8px;text-decoration:none;font-size:.9rem;text-align:center;border:1px solid #e5e7eb;">❓ Gérer les questions ({{ $stats['total_questions'] }})</a>
            <a href="{{ route('admin.leaderboard') }}" style="background:#f8f8f8;color:#333;padding:.7rem 1rem;border-radius:8px;text-decoration:none;font-size:.9rem;text-align:center;border:1px solid #e5e7eb;">🏆 Voir le classement complet</a>
            <a href="{{ route('admin.jersey.index') }}" style="background:#f8f8f8;color:#333;padding:.7rem 1rem;border-radius:8px;text-decoration:none;font-size:.9rem;text-align:center;border:1px solid #e5e7eb;">👕 Gérer le vote maillot</a>
            <form method="POST" action="{{ route('admin.maintenance.migrate') }}" onsubmit="return confirm('Appliquer les mises à jour de la base de données ?')">
                @csrf
                <button type="submit" style="width:100%;background:#1e293b;color:#94a3b8;padding:.7rem 1rem;border-radius:8px;font-size:.9rem;text-align:center;border:1px solid #334155;cursor:pointer;">🔄 Mettre à jour la BDD</button>
            </form>
        </div>
    </div>
</div>
@endsection

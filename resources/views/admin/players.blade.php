@extends('layouts.admin')
@section('title', 'Joueurs')
@section('page-title', '👥 Joueurs ayant participé')

@section('content')
<div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.06);">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <p style="color:#666;font-size:.9rem;">{{ $players->count() }} joueur(s) enregistré(s)</p>
    </div>

    @if($players->isEmpty())
        <p style="text-align:center;color:#aaa;padding:2rem;">Aucun joueur pour l'instant.</p>
    @else
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
            <thead>
                <tr style="border-bottom:2px solid #f0f0f0;">
                    <th style="text-align:left;padding:.7rem 1rem;color:#333;font-weight:700;">Prénom</th>
                    <th style="text-align:center;padding:.7rem 1rem;color:#333;font-weight:700;">Parties</th>
                    <th style="text-align:center;padding:.7rem 1rem;color:#333;font-weight:700;">Meilleur score</th>
                    <th style="text-align:center;padding:.7rem 1rem;color:#333;font-weight:700;">Comptabilisé</th>
                    <th style="text-align:left;padding:.7rem 1rem;color:#333;font-weight:700;">Date de jeu</th>
                    <th style="text-align:center;padding:.7rem 1rem;color:#333;font-weight:700;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($players as $player)
                @php
                    $bestSession = $player->gameSessions->sortByDesc('score')->first();
                    $counted = $bestSession && $bestSession->counted;
                @endphp
                <tr style="border-bottom:1px solid #f5f5f5;transition:background .15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">
                    <td style="padding:.8rem 1rem;font-weight:600;color:#111;">{{ $player->full_name }}</td>
                    <td style="padding:.8rem 1rem;text-align:center;color:#555;">{{ $player->gameSessions->count() }}</td>
                    <td style="padding:.8rem 1rem;text-align:center;font-weight:700;color:var(--orange);">
                        {{ $bestSession ? number_format($bestSession->score, 0, ',', ' ').' pts' : '—' }}
                    </td>
                    <td style="padding:.8rem 1rem;text-align:center;">
                        @if($counted)
                            <span style="background:#dcfce7;color:#15803d;padding:.2rem .6rem;border-radius:20px;font-size:.78rem;font-weight:700;">✓ Oui</span>
                        @else
                            <span style="background:#fee2e2;color:#dc2626;padding:.2rem .6rem;border-radius:20px;font-size:.78rem;font-weight:700;">✗ Non</span>
                        @endif
                    </td>
                    <td style="padding:.8rem 1rem;color:#666;font-size:.85rem;">
                        {{ $player->played_at ? $player->played_at->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td style="padding:.8rem 1rem;text-align:center;">
                        <form action="{{ route('admin.players.destroy', $player) }}" method="POST" onsubmit="return confirm('Supprimer {{ $player->full_name }} et toutes ses parties ?');" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:#fee2e2;color:#dc2626;border:none;padding:.3rem .7rem;border-radius:6px;cursor:pointer;font-size:.8rem;">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection

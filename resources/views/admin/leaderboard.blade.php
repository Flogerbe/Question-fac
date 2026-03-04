@extends('layouts.admin')
@section('title', 'Classement')
@section('page-title', '🏆 Classement des joueurs')

@section('content')
<div style="background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.06);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
        <thead style="background:#f9fafb;border-bottom:2px solid var(--orange);">
            <tr>
                <th style="padding:.8rem 1rem;text-align:center;color:#555;width:50px;">Rang</th>
                <th style="padding:.8rem 1rem;text-align:left;color:#555;">Joueur</th>
                <th style="padding:.8rem 1rem;text-align:right;color:#555;">Score</th>
                <th style="padding:.8rem 1rem;text-align:right;color:#555;">Temps</th>
                <th style="padding:.8rem 1rem;text-align:center;color:#555;">Jokers</th>
                <th style="padding:.8rem 1rem;text-align:center;color:#555;">Valide</th>
                <th style="padding:.8rem 1rem;text-align:center;color:#555;">Date</th>
                <th style="padding:.8rem 1rem;text-align:center;color:#555;">Action</th>
            </tr>
        </thead>
        <tbody>
            @php $rank = ($sessions->currentPage() - 1) * $sessions->perPage() + 1; @endphp
            @foreach($sessions as $session)
            <tr style="border-bottom:1px solid #f0f0f0;">
                <td style="padding:.8rem 1rem;text-align:center;font-weight:700;color:var(--orange);">{{ $rank++ }}</td>
                <td style="padding:.8rem 1rem;">
                    <div style="font-weight:600;">{{ $session->player->full_name }}</div>
                    <div style="font-size:.75rem;color:#aaa;">IP: {{ substr($session->player->ip_hash, 0, 8) }}...</div>
                </td>
                <td style="padding:.8rem 1rem;text-align:right;font-weight:700;color:var(--orange);">{{ number_format($session->score, 0, ',', ' ') }}</td>
                <td style="padding:.8rem 1rem;text-align:right;color:#666;">{{ floor($session->temps_total/60) }}m {{ $session->temps_total%60 }}s</td>
                <td style="padding:.8rem 1rem;text-align:center;font-size:1rem;">
                    {{ $session->joker_fifty ? '✂️' : '' }}
                    {{ $session->joker_public ? '👥' : '' }}
                    {{ $session->joker_coach ? '🎓' : '' }}
                    {{ (!$session->joker_fifty && !$session->joker_public && !$session->joker_coach) ? '—' : '' }}
                </td>
                <td style="padding:.8rem 1rem;text-align:center;">
                    <span style="padding:.2rem .6rem;border-radius:20px;font-size:.8rem;font-weight:600;background:{{ $session->counted ? '#dcfce7' : '#fee2e2' }};color:{{ $session->counted ? '#15803d' : '#dc2626' }};">
                        {{ $session->counted ? '✅ Oui' : '❌ Non' }}
                    </span>
                </td>
                <td style="padding:.8rem 1rem;text-align:center;color:#888;font-size:.85rem;">{{ $session->created_at->format('d/m H:i') }}</td>
                <td style="padding:.8rem 1rem;text-align:center;">
                    <form action="{{ route('admin.leaderboard.destroy', $session) }}" method="POST" onsubmit="return confirm('Supprimer cette entrée ?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:#fee2e2;color:#dc2626;border:none;padding:.3rem .6rem;border-radius:6px;cursor:pointer;font-size:.8rem;">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div style="margin-top:1.5rem;">
    {{ $sessions->links() }}
</div>
@endsection

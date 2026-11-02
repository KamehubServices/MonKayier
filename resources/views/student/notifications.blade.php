@extends('layouts.student')

@section('title', 'Notifications — Espace Élève MonKayier')

@section('content')
<style>
/* ==========================================================================
   NOTIFICATIONS — REBRANDING MONKAYIER
   CHARTE : SORA / INTER · ZÉRO EMOJI · ICÔNES VECTORIELLES SVG · NAVY & LIME
   ========================================================================== */

.notif-container {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
    box-sizing: border-box;
    padding-bottom: 50px;
}

/* En-tête de page */
.notif-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.notif-header-left {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.notif-page-title {
    font-family: 'Sora', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
    letter-spacing: -0.02em;
}
.notif-page-subtitle {
    font-size: 13.5px;
    color: #64748B;
    margin: 0;
}

/* Bouton marquer tout comme lu */
.notif-mark-read-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #FFFFFF;
    border: 1.5px solid #CBD5E1;
    border-radius: 8px;
    padding: 9px 16px;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #0E1022;
    cursor: pointer;
    transition: all 0.15s ease;
}
.notif-mark-read-btn:hover {
    background: #0E1022;
    border-color: #0E1022;
    color: #FFFFFF;
}
.notif-mark-read-btn svg {
    width: 15px;
    height: 15px;
    stroke: currentColor;
    stroke-width: 2.2;
    fill: none;
}

/* Filtres de notifications */
.notif-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.notif-filter-chip {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 12.5px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.notif-filter-chip:hover {
    border-color: #CBD5E1;
    color: #0E1022;
}
.notif-filter-chip.active {
    background: #0E1022;
    border-color: #0E1022;
    color: #FFFFFF;
}
.notif-filter-chip.active .chip-count {
    background: #D4F25C;
    color: #0E1022;
}
.chip-count {
    font-size: 11px;
    font-weight: 800;
    background: #F1F5F9;
    color: #64748B;
    padding: 1px 6px;
    border-radius: 5px;
}

/* Liste des cartes de notifications */
.notif-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.notif-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-sizing: border-box;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    transition: all 0.15s ease;
    position: relative;
}
.notif-card:hover {
    border-color: #0E1022;
    box-shadow: 0 4px 14px rgba(14, 16, 34, 0.05);
    transform: translateY(-1px);
}
.notif-card.is-unread {
    background: #FCFCFD;
    border-color: #CBD5E1;
    border-left: 4px solid #0E1022;
}

/* Contenu gauche */
.notif-card-main {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
    min-width: 0;
}
.notif-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: none;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    color: #0E1022;
}
.notif-icon-box svg {
    width: 20px;
    height: 20px;
    stroke: currentColor;
    stroke-width: 2.2;
    fill: none;
}
.notif-icon-assessment {
    background: rgba(212, 242, 92, 0.2);
    border-color: rgba(212, 242, 92, 0.5);
    color: #0E1022;
}
.notif-icon-devoir {
    background: #EFF6FF;
    border-color: #DBEAFE;
    color: #1D4ED8;
}
.notif-icon-seance {
    background: #F0FDF4;
    border-color: #DCFCE7;
    color: #15803D;
}
.notif-icon-examen {
    background: #FEF3C7;
    border-color: #FDE68A;
    color: #B45309;
}

.notif-text-wrap {
    display: flex;
    flex-direction: column;
    gap: 3px;
    min-width: 0;
}
.notif-card-title {
    font-family: 'Sora', sans-serif;
    font-size: 14.5px;
    font-weight: 800;
    color: #0E1022;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.notif-unread-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #D4F25C;
    border: 1.5px solid #0E1022;
    flex: none;
}
.notif-card-body {
    font-size: 13px;
    color: #64748B;
    line-height: 1.45;
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
@media (max-width: 768px) {
    .notif-card-body {
        white-space: normal;
    }
}
.notif-card-time {
    font-size: 11.5px;
    color: #94A3B8;
    margin-top: 2px;
}

/* Contenu droit */
.notif-card-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: none;
}
.notif-status-badge {
    font-size: 11px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 6px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}
.badge-new {
    background: #0E1022;
    color: #D4F25C;
}
.badge-read {
    background: #F1F5F9;
    color: #64748B;
}
.notif-arrow-icon {
    width: 14px;
    height: 14px;
    stroke: #94A3B8;
    stroke-width: 2.2;
    fill: none;
    transition: transform 0.15s ease;
}
.notif-card:hover .notif-arrow-icon {
    stroke: #0E1022;
    transform: translateX(3px);
}

/* État vide */
.notif-empty-state {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}
.notif-empty-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #F8FAFC;
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
}
.notif-empty-icon svg {
    width: 24px;
    height: 24px;
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
}
</style>

<div class="notif-container">

    {{-- EN-TÊTE --}}
    <div class="notif-header">
        <div class="notif-header-left">
            <h1 class="notif-page-title">Notifications</h1>
            <p class="notif-page-subtitle">Tes alertes et rappels de révision officiels</p>
        </div>
        <form method="POST" action="{{ route('student.notifications.markRead') }}" style="margin:0">
            @csrf
            <button type="submit" class="notif-mark-read-btn">
                <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Tout marquer comme lu</span>
            </button>
        </form>
    </div>

    {{-- BARRE DE NAVIGATION SOUS-PAGES --}}
    <div class="mk-subnav">
        <a href="{{ route('student.profile') }}" class="mk-subnav-link">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            <span>Mon Profil</span>
        </a>
        <a href="{{ route('student.history') }}" class="mk-subnav-link">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg>
            <span>Historique & Activités</span>
        </a>
        <a href="{{ route('student.notifications') }}" class="mk-subnav-link active">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 8 3 8H3s3-1 3-8"></path><path d="M10 20a2 2 0 0 0 4 0"></path></svg>
            <span>Notifications</span>
            @if(isset($currentStudent) && $currentStudent->unread_notifs > 0)
            <span class="badge-count">{{ $currentStudent->unread_notifs }}</span>
            @endif
        </a>
        <a href="{{ route('student.help') }}" class="mk-subnav-link">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <span>Aide & Contact</span>
        </a>
    </div>

    {{-- MESSAGE DE SUCCÈS --}}
    @if(session('success'))
    <div style="background:#ECFDF5;border:1.5px solid #A7F3D0;color:#065F46;padding:12px 18px;border-radius:10px;display:flex;align-items:center;gap:10px;font-size:13.5px;font-weight:600">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- FILTRES DE NOTIFICATIONS --}}
    <div class="notif-filters">
        <button type="button" class="notif-filter-chip active" onclick="filterNotifs('all', this)">
            <span>Toutes</span>
            <span class="chip-count">{{ $notifications->count() }}</span>
        </button>
        <button type="button" class="notif-filter-chip" onclick="filterNotifs('unread', this)">
            <span>Non lues</span>
            <span class="chip-count">{{ $notifications->where('is_read', 0)->count() }}</span>
        </button>
        <button type="button" class="notif-filter-chip" onclick="filterNotifs('important', this)">
            <span>Importantes</span>
        </button>
    </div>

    {{-- LISTE ÉPURÉE DES NOTIFICATIONS --}}
    <div class="notif-list">
        @forelse($notifications as $notif)
            @php
                $cat = strtolower($notif->category ?? '');
                $iconClass = 'notif-icon-box';
                if (str_contains($cat, 'assessment') || str_contains($cat, 'test')) {
                    $iconClass .= ' notif-icon-assessment';
                } elseif (str_contains($cat, 'devoir')) {
                    $iconClass .= ' notif-icon-devoir';
                } elseif (str_contains($cat, 'seance') || str_contains($cat, 'séance')) {
                    $iconClass .= ' notif-icon-seance';
                } elseif (str_contains($cat, 'examen') || str_contains($cat, 'calendrier')) {
                    $iconClass .= ' notif-icon-examen';
                }
            @endphp

            <div class="notif-card {{ $notif->is_read ? 'is-read' : 'is-unread' }}" 
                 onclick="window.location.href='{{ $notif->action_url ?? '#' }}'">
                <div class="notif-card-main">
                    <div class="{{ $iconClass }}">
                        @if(str_contains($cat, 'assessment') || str_contains($cat, 'test'))
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                        @elseif(str_contains($cat, 'devoir'))
                            <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        @elseif(str_contains($cat, 'seance') || str_contains($cat, 'séance'))
                            <svg viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        @else
                            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        @endif
                    </div>
                    <div class="notif-text-wrap">
                        <div class="notif-card-title">
                            @if(!$notif->is_read)
                                <span class="notif-unread-dot" title="Non lu"></span>
                            @endif
                            <span>{{ $notif->title }}</span>
                        </div>
                        <p class="notif-card-body">{{ $notif->body }}</p>
                        @if(isset($notif->created_at))
                        <div class="notif-card-time">
                            {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="notif-card-right">
                    @if(!$notif->is_read)
                        <span class="notif-status-badge badge-new">Nouveau</span>
                    @else
                        <span class="notif-status-badge badge-read">Lu</span>
                    @endif
                    <svg class="notif-arrow-icon" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </div>
        @empty
            <div class="notif-empty-state">
                <div class="notif-empty-icon">
                    <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div style="font-family:'Sora',sans-serif;font-size:16px;font-weight:700;color:#0E1022">Toutes tes notifications sont à jour</div>
                <div style="font-size:13px;color:#64748B">Aucun nouveau rappel ou alerte pour le moment.</div>
            </div>
        @endforelse
    </div>

</div>

<script>
function filterNotifs(type, btn) {
    document.querySelectorAll('.notif-filter-chip').forEach(el => el.classList.remove('active'));
    btn.classList.add('active');
    
    document.querySelectorAll('.notif-card').forEach(item => {
        if (type === 'all') {
            item.style.display = 'flex';
        } else if (type === 'unread') {
            item.style.display = item.classList.contains('is-unread') ? 'flex' : 'none';
        } else if (type === 'important') {
            item.style.display = 'flex';
        }
    });
}
</script>
@endsection

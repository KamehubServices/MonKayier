@extends('layouts.admin')

@section('title', 'Programmes Scolaires Officiels')
@section('header-title', 'Arborescence des Programmes Scolaires')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    @foreach($curricula as $curriculum)
        <div class="mk-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid var(--mk-border); padding-bottom: 16px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                        <span class="mk-badge mk-badge-published">{{ $curriculum->status }}</span>
                        <span class="mk-badge mk-badge-lavender">Version {{ $curriculum->version }}.0</span>
                        <span style="font-size: 13px; color: var(--mk-text-muted);">Année : {{ $curriculum->academicYear->label ?? '2026-2027' }}</span>
                    </div>
                    <h2 style="font-size: 20px; font-weight: 800; color: var(--mk-night);">{{ $curriculum->title }}</h2>
                    <div style="font-size: 13px; color: var(--mk-text-muted); margin-top: 4px;">
                        Source officielle : <strong>{{ $curriculum->source_title ?? 'MEPST Togo' }}</strong> · 
                        <a href="{{ $curriculum->source_url ?? '#' }}" target="_blank" style="color: var(--mk-indigo); text-decoration: underline;">Consulter l'acte ministériel</a>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="/api/v1/curricula/{{ $curriculum->id }}/tree" target="_blank" class="mk-btn mk-btn-outline mk-btn-sm">
                        Format JSON RAG
                    </a>
                </div>
            </div>

            <!-- Arborescence Thématique -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($curriculum->rootTopics as $domain)
                    <div style="border: 1px solid var(--mk-border); border-radius: 16px; overflow: hidden; background: #fafbff;">
                        <div style="padding: 14px 20px; background: var(--mk-lavender); display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 800; color: var(--mk-indigo); font-size: 15px;">{{ $domain->title }}</span>
                            <span class="mk-badge" style="background: #ffffff; color: var(--mk-night);">{{ $domain->code }}</span>
                        </div>

                        <div style="padding: 16px 20px; display: flex; flex-direction: column; gap: 14px;">
                            @foreach($domain->children as $chapter)
                                <div style="background: #ffffff; border: 1px solid var(--mk-border); border-radius: 12px; padding: 14px 18px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <div style="font-weight: 700; font-size: 15px; color: var(--mk-night);">
                                            {{ $chapter->title }}
                                        </div>
                                        <span class="mk-badge mk-badge-draft">{{ $chapter->code }}</span>
                                    </div>
                                    <p style="font-size: 13px; color: var(--mk-text-muted); margin-bottom: 12px;">{{ $chapter->description }}</p>

                                    <!-- Objectifs d'apprentissage -->
                                    <div style="background: #f8fafc; border-radius: 10px; padding: 12px 14px;">
                                        <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--mk-text-muted); margin-bottom: 6px;">
                                            Objectifs d'apprentissage observables :
                                        </div>
                                        <ul style="list-style: none; display: flex; flex-direction: column; gap: 6px; font-size: 13px;">
                                            @foreach($chapter->objectives as $objective)
                                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                                    <span style="color: var(--mk-indigo); font-weight: bold;">✓</span>
                                                    <span>{{ $objective->description }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

</div>
@endsection

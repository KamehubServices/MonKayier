@extends('layouts.parent')

@section('title', 'Échéances — Espace Parent MonKayier')

@section('content')
<div><h1 style="font-size:32px">Échéances</h1><div class="mu" style="font-size:14px;margin-top:4px">Les dates importantes pour Kodjo</div></div><div class="g2" style="grid-template-columns:1.5fr 1fr"><div class="c"><h3 style="font-size:18px">À venir</h3><div class="rw"><div><b>Devoir de maths</b><div class="mu">Mathématiques · Terminale D</div></div><span class="tg o">Date à saisir par l’établissement</span></div><div class="rw"><div><b>Composition du trimestre</b><div class="mu">Toutes matières</div></div><span class="tg o">Date à saisir par l’établissement</span></div><div class="rw"><div><b>BAC 2</b><div class="mu">Examen national</div></div><span class="tg o">Dans l’attente d’une date officielle</span></div></div><div class="c ind" style="gap:12px;justify-content:center"><span class="lb">Rappels</span><b class="hd" style="font-size:22px;line-height:1.25">Soyez prévenu dès qu’une date est publiée.</b><div class="mu">Les dates officielles s’affichent avec leur source dès qu’elles sont confirmées.</div><a class="bt l" href="/parent/notifications">Gérer mes rappels</a></div></div>
@endsection

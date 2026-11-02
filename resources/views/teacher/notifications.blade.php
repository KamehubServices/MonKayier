@extends('layouts.teacher')

@section('title', 'Notifications — Espace Enseignant MonKayier')

@section('content')
<div class="r"><div><h1 style="font-size:32px;margin:0">Notifications</h1><div class="mu" style="font-size:14px;margin-top:4px">Tes dernières alertes</div></div><a class="bt g" href="#" style="padding:10px 16px">Tout marquer comme lu</a></div><div style="display:flex;gap:8px;flex-wrap:wrap"><span class="ch on">Toutes</span><span class="ch">Non lues</span><span class="ch">Importantes</span></div><div class="c "><div class="rw"><div><b>9 copies attendent votre validation</b><div class="mu">Devoir 2 · Première C</div></div><span class="tg o">Nouveau</span></div><div class="rw"><div><b>Sujet généré par l’IA</b><div class="mu">Limites et dérivation · à valider</div></div><span class="tg o">Nouveau</span></div><div class="rw"><div><b>Un élève a besoin d’aide</b><div class="mu">Kofi M. · Analyse</div></div><span class="tg o">Important</span></div><div class="rw"><div><b>Nouvelle ressource partagée</b><div class="mu">Bibliothèque · Suites numériques</div></div><span class="tg ">Lu</span></div></div>
@endsection

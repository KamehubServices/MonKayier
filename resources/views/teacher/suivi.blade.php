@extends('layouts.teacher')

@section('title', 'Suivi des élèves — MonKayier')

@section('content')
<div class="r"><div><h1 style="font-size:32px">Suivi des élèves</h1><div class="mu" style="font-size:14px;margin-top:4px">Terminale D · 34 élèves</div></div><div style="display:flex;gap:8px;flex-wrap:wrap"><span class="ch on">Tous</span><span class="ch">À accompagner</span><span class="ch">Régularité faible</span></div></div>
<div class="g2" style="grid-template-columns:1.6fr 1fr"><div class="c"><div class="tr" style="border-top:0;padding-top:0"><span class="th">Élève</span><span class="th">Algèbre</span><span class="th">Analyse</span><span class="th">Régularité</span><span class="th">Statut</span></div>
<div class="tr"><div style="display:flex;align-items:center;gap:10px"><span class="av">KM</span><b>Kofi M.</b></div><b>72 %</b><b>48 %</b><span class="mu">6 jours</span><span class="tg o" style="justify-self:start">À accompagner</span></div>
<div class="tr"><div style="display:flex;align-items:center;gap:10px"><span class="av">AK</span><b>Ama K.</b></div><b>81 %</b><b>76 %</b><span class="mu">5 jours</span><span class="tg k" style="justify-self:start">Bon rythme</span></div>
<div class="tr"><div style="display:flex;align-items:center;gap:10px"><span class="av">EA</span><b>Esi A.</b></div><b>55 %</b><b>39 %</b><span class="mu">2 jours</span><span class="tg o" style="justify-self:start">À accompagner</span></div>
<div class="tr"><div style="display:flex;align-items:center;gap:10px"><span class="av">YT</span><b>Yao T.</b></div><b>68 %</b><b>64 %</b><span class="mu">4 jours</span><span class="tg" style="justify-self:start">Stable</span></div></div>
<div class="c dk" style="gap:14px"><span class="lb">Élève sélectionné</span><div style="display:flex;align-items:center;gap:12px"><span class="av" style="width:48px;height:48px;background:#D4F25C;color:#0E1022">KM</span><div><b class="hd" style="font-size:20px">Kofi M.</b><div class="mu">Terminale D</div></div></div><div class="mu" style="font-size:14px">Bloque sur les limites en l’infini : trois échecs sur les mêmes exercices cette semaine.</div><div class="r"><span>Analyse</span><b>48 %</b></div><div class="bar" style="background:#262A52"><i style="width:48%;background:#D4F25C"></i></div><a class="bt l" href="#" style="margin-top:6px">Proposer un parcours de remédiation</a><a class="bt" href="#" style="background:#262A52">Contacter les parents</a></div></div>
@endsection

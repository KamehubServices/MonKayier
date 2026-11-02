@extends('layouts.parent')

@section('title', 'Donner mon avis — Espace Parent MonKayier')

@section('content')
<div><h1 style="font-size:32px">Donner mon avis</h1><div class="mu" style="font-size:14px;margin-top:4px">Aidez-nous à améliorer le suivi pour les parents</div></div><div class="g2" style="grid-template-columns:1.3fr 1fr"><div class="c" style="gap:14px"><div class="lb">Votre impression générale</div><div style="display:flex;gap:8px;flex-wrap:wrap"><span class="ch">Très utile</span><span class="ch on">Utile</span><span class="ch">Peu utile</span><span class="ch">Pas utile</span></div><div class="lb">Que pouvons-nous améliorer ?</div><div style="border:1.5px solid #CBD6E6;border-radius:12px;padding:12px 14px;background:#fff;box-sizing:border-box;width:100%;color:#4B506A;min-height:120px">Écrivez votre message ici…</div><a class="bt" href="#" style="align-self:flex-start">Envoyer mon avis</a></div><div class="c dk" style="justify-content:center;gap:10px"><span class="lb">Merci</span><b class="hd" style="font-size:22px;line-height:1.25">Votre avis compte pour l’équipe MonKayier.</b><div class="mu">Il est lu par l’équipe et n’est jamais partagé avec votre enfant.</div></div></div>
@endsection

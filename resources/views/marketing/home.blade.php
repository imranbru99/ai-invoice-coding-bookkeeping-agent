@extends('layouts.marketing')
@section('title', 'AI Invoice Coding & Bookkeeping Agent | LedgerCode AI')
@section('content')
<section class="hero">
    <span class="badge">Accounting ops · Emerald #059669</span>
    <h1>AI Invoice Coding & Bookkeeping Agent</h1>
    <p>Classify expenses, post GL codes, reconcile vendors, and cut accounting close time dramatically — modular so finance teams only run what they need.</p>
    <div class="cta">
        <a class="btn" href="{{ route('demo') }}">Try the demo</a>
        <a class="btn ghost" href="{{ route('features') }}">Explore features</a>
    </div>
</section>
<section class="grid">
    @foreach($modules as $key => $module)
        <article class="card">
            <span class="badge">{{ ($module['enabled'] ?? false) ? 'Enabled' : 'Optional' }}</span>
            <h3>{{ $module['label'] }}</h3>
            <p>{{ $module['description'] }}</p>
        </article>
    @endforeach
</section>
@endsection

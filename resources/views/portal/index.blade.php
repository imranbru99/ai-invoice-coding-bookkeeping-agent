@extends('layouts.marketing')
@section('title', 'Vendor Portal | LedgerCode AI')
@section('content')
<h2 class="section-title">Your vendor profile</h2>
<section class="grid">
@forelse($vendors as $vendor)
<article class="card">
    <span class="badge">{{ $vendor->slug }}</span>
    <h3>{{ $vendor->name }}</h3>
    <p>Status: {{ $vendor->status }} · Invoices: {{ $vendor->invoices_count }} · Balance: {{ number_format($vendor->balance, 2) }}</p>
</article>
@empty
<article class="card"><p>No vendor profile linked to this account yet.</p></article>
@endforelse
</section>
<h2 class="section-title">Recent invoices</h2>
<section class="grid">
@foreach($invoices as $invoice)
<article class="card">
    <h3>{{ $invoice->invoice_number }}</h3>
    <p>Total: {{ number_format($invoice->total, 2) }} {{ $invoice->currency }} · Status: {{ $invoice->status }}</p>
</article>
@endforeach
</section>
<h2 class="section-title">Reconciliations</h2>
<section class="grid">
@foreach($reconciliations as $recon)
<article class="card">
    <h3>{{ $recon->period_start?->format('Y-m-d') }} → {{ $recon->period_end?->format('Y-m-d') }}</h3>
    <p>Variance: {{ number_format($recon->variance, 2) }} · Status: {{ $recon->status }}</p>
</article>
@endforeach
</section>
@endsection

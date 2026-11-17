@extends('layouts.marketing')
@section('title', 'Demo Access | LedgerCode AI')
@section('content')
<h2 class="section-title">Demo credentials</h2>
<section class="grid">
<article class="card">
    <h3>Admin / Demo user</h3>
    <p>Email: <strong>demo@ledgercode.test</strong></p>
    <p>Password: <strong>password</strong></p>
    <p style="margin-top:1rem;"><a class="btn" href="/admin">Open Filament admin</a></p>
</article>
<article class="card">
    <h3>Vendor portal user</h3>
    <p>Email: <strong>client@ledgercode.test</strong></p>
    <p>Password: <strong>password</strong></p>
    <p>Use the portal route after login to review invoices and reconciliations.</p>
</article>
</section>
@endsection

@extends('layouts.marketing')
@section('title', 'Features | AI Invoice Coding & Bookkeeping Agent')
@section('content')
<h2 class="section-title">Everything a modern AP desk needs</h2>
<p style="color:var(--muted); max-width:40rem;">From first invoice scan to month-end sign-off, LedgerCode AI compresses AP coding and close into guided workflows with controller oversight.</p>
<section class="grid">
    <article class="card"><h3>Invoice OCR</h3><p>Ingest PDFs and scans into structured headers, lines, tax, and vendor matches.</p></article>
    <article class="card"><h3>AI expense coding</h3><p>Suggest GL accounts with confidence scores and rationale for every line.</p></article>
    <article class="card"><h3>GL posting</h3><p>Draft balanced journals from coded invoices and post to the chart of accounts.</p></article>
    <article class="card"><h3>Vendor reconciliation</h3><p>Compare statement balances to ledger activity and flag variances.</p></article>
    <article class="card"><h3>Bank feed matching</h3><p>Import bank lines and suggest invoice or journal matches.</p></article>
    <article class="card"><h3>Close checklist</h3><p>Track month-end tasks, owners, and completion for a faster close.</p></article>
</section>
@endsection

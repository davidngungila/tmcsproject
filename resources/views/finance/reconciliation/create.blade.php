@extends('layouts.app')

@section('title', 'Perform Reconciliation - TmcsSmart')
@section('page-title', 'New Reconciliation')
@section('breadcrumb', 'TmcsSmart / Finance / Reconciliation / Create')

@section('content')
<div class="animate-in">
  <div class="card max-w-3xl mx-auto">
    <div class="card-header">
      <div class="card-title">Financial Reconciliation</div>
      <div class="card-subtitle">Match internal records with bank statements for a specific period</div>
    </div>
    <div class="card-body">
      <form action="{{ route('reconciliation.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-light rounded-lg">
          <div class="form-group">
            <label class="form-label">Period Start</label>
            <input type="date" name="period_start" class="form-control" value="{{ $start }}" readonly>
          </div>
          <div class="form-group">
            <label class="form-label">Period End</label>
            <input type="date" name="period_end" class="form-control" value="{{ $end }}" readonly>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-4">
            <h4 class="text-sm font-bold border-b border-light pb-2">System Records</h4>
            <div class="form-group">
              <label class="form-label">Total Income (TZS)</label>
              <input type="number" name="total_income" class="form-control bg-light" value="{{ $totalIncome }}" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">Total Expenses (TZS)</label>
              <input type="number" name="total_expenses" class="form-control bg-light" value="{{ $totalExpenses }}" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">Expected Net Balance</label>
              <div class="p-3 bg-green-50 text-green-700 font-bold rounded-lg border border-green-100">
                TZS {{ number_format($totalIncome - $totalExpenses, 2) }}
              </div>
            </div>
          </div>

          <div class="space-y-4">
            <h4 class="text-sm font-bold border-b border-light pb-2">Bank Statement Values</h4>
            <div class="form-group">
              <label class="form-label">Opening Balance *</label>
              <input type="number" name="opening_balance" class="form-control" value="{{ $openingBalance }}" step="0.01" required>
            </div>
            <div class="form-group">
              <label class="form-label">Closing Balance (Actual) *</label>
              <input type="number" name="closing_balance" class="form-control" value="{{ $closingBalance }}" step="0.01" required>
            </div>
            <div class="p-4 bg-amber-50 rounded-lg border border-amber-100">
              <p class="text-[10px] text-amber-800 uppercase font-bold mb-1">Audit Tip</p>
              <p class="text-xs text-amber-700">Ensure these values exactly match your bank statement for the selected period.</p>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Reconciliation Notes / Discrepancies</label>
          <textarea name="notes" class="form-control" rows="3" placeholder="Explain any differences or add internal audit notes..."></textarea>
        </div>

        <div class="flex gap-3 pt-4 border-t border-light">
          <a href="{{ route('reconciliation.index') }}" class="btn btn-secondary flex-1 text-center">Cancel</a>
          <button type="submit" class="btn btn-primary flex-1">Complete Reconciliation</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

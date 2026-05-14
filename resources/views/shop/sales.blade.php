@extends('layouts.app')

@section('title', 'Sales History - TmcsSmart')
@section('page-title', 'Sales History')
@section('breadcrumb', 'TmcsSmart / Shop / Sales')

@section('content')
<div class="animate-in">
  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Method</th>
            <th>Date</th>
            <th>Sold By</th>
          </tr>
        </thead>
        <tbody>
          @forelse($sales as $sale)
          <tr>
            <td>{{ $sale->product->name }}</td>
            <td>{{ $sale->quantity }}</td>
            <td>TZS {{ number_format($sale->total_price) }}</td>
            <td>{{ $sale->payment_method }}</td>
            <td>{{ $sale->sale_date->format('M d, Y H:i') }}</td>
            <td>{{ $sale->seller->name }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-8 text-muted">No sales recorded yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer px-4 py-3">
      {{ $sales->links() }}
    </div>
  </div>
</div>
@endsection

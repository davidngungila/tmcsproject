@extends('layouts.app')

@section('title', 'New Sale - TmcsSmart')
@section('page-title', 'Record Shop Sale')
@section('breadcrumb', 'TmcsSmart / Shop / New Sale')

@section('content')
<div class="animate-in">
  <form action="{{ route('shop.store-sale') }}" method="POST">
    @csrf

    <div class="card mb-4">
      <div class="card-header">
        <div class="card-title">Sale Details</div>
        <div class="card-subtitle">Enter product and payment information</div>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Product *</label>
            <select name="product_id" class="form-control" required>
              <option value="">Select Product</option>
              @foreach($products as $product)
              <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                {{ $product->name }} (In Stock: {{ $product->stock_quantity }}) - TZS {{ number_format($product->price) }}
              </option>
              @endforeach
            </select>
            @error('product_id') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Quantity *</label>
            <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity', 1) }}" required>
            @error('quantity') <div class="text-red text-xs mt-1">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Payment Method *</label>
            <select name="payment_method" class="form-control" required>
              <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
              <option value="Mobile Money" {{ old('payment_method') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
              <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
            </select>
          </div>
          <div class="form-group">
              <label class="form-label">Total Price Preview</label>
              <div class="form-control bg-light" id="totalPreview">TZS 0.00</div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('shop.sales') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        Complete Sale
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
const products = @json($products);
const productSelect = document.querySelector('select[name="product_id"]');
const quantityInput = document.querySelector('input[name="quantity"]');
const totalPreview = document.getElementById('totalPreview');

function updateTotal() {
  const productId = productSelect.value;
  const quantity = parseInt(quantityInput.value) || 0;
  const product = products.find(p => p.id == productId);
  
  if (product && quantity > 0) {
    const total = product.price * quantity;
    totalPreview.textContent = 'TZS ' + new Intl.NumberFormat().format(total);
  } else {
    totalPreview.textContent = 'TZS 0.00';
  }
}

productSelect.addEventListener('change', updateTotal);
quantityInput.addEventListener('input', updateTotal);
</script>
@endpush

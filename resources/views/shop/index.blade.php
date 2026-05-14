@extends('layouts.app')

@section('title', 'Shop - TmcsSmart')
@section('page-title', 'Shop Management')
@section('breadcrumb', 'TmcsSmart / Shop')

@section('content')
<div class="animate-in">
  <!-- SHOP STATISTICS -->
  <div class="stat-grid mb-6">
    <div class="stat-card green">
      <div class="stat-icon green">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      </div>
      <div class="stat-value">{{ $totalProducts }}</div>
      <div class="stat-label">Total Products</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        15 new this month
      </div>
    </div>

    <div class="stat-card gold">
      <div class="stat-icon gold">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">TZS {{ number_format($totalRevenue, 0) }}</div>
      <div class="stat-label">Total Revenue</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        22% from last month
      </div>
    </div>

    <div class="stat-card blue">
      <div class="stat-icon blue">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      </div>
      <div class="stat-value">{{ $totalSales }}</div>
      <div class="stat-label">Total Sales</div>
      <div class="stat-change up">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l9-9m0 0V5m0 12h-12"/></svg>
        18% from last month
      </div>
    </div>

    <div class="stat-card red">
      <div class="stat-icon red">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">{{ $lowStockProducts }}</div>
      <div class="stat-label">Low Stock Items</div>
      <div class="stat-change down">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 7l-9 9m0 0V4m0 12h12"/></svg>
        3 restocked today
      </div>
    </div>
  </div>

  <!-- PAGE ACTIONS -->
  <div class="flex items-center justify-between mb-4">
    <div>
      <h2 class="text-lg font-bold">Shop Management</h2>
      <p class="text-sm text-muted mt-1">Manage church shop inventory and sales</p>
    </div>
    <div class="flex gap-3">
      @if(auth()->user()->hasPermission('shop.reports'))
      <button class="btn btn-gold" onclick="showPOS()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        Point of Sale
      </button>
      @endif
      @if(auth()->user()->hasPermission('shop.reports'))
      <button class="btn btn-secondary" onclick="generateSalesReport()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Sales Report
      </button>
      @endif
      @if(auth()->user()->hasPermission('shop.export'))
      <button class="btn btn-secondary" onclick="exportProducts()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export
      </button>
      @endif
      @if(auth()->user()->hasPermission('shop.create'))
      <a href="{{ route('shop.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        Add Product
      </a>
      @endif
    </div>
  </div>

  <!-- FILTERS -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="flex gap-4 items-center flex-wrap">
        <div class="flex-1 min-w-64">
          <input type="text" class="form-control" placeholder="Search products..." id="searchInput">
        </div>
        <select class="form-control w-48" id="categoryFilter">
          <option value="">All Categories</option>
          <option value="books">Books</option>
          <option value="music">Music</option>
          <option value="clothing">Clothing</option>
          <option value="accessories">Accessories</option>
          <option value="other">Other</option>
        </select>
        <select class="form-control w-48" id="stockFilter">
          <option value="">All Stock Levels</option>
          <option value="in_stock">In Stock</option>
          <option value="low_stock">Low Stock</option>
          <option value="out_of_stock">Out of Stock</option>
        </select>
        <button class="btn btn-secondary" onclick="resetFilters()">Reset</button>
      </div>
    </div>
  </div>

  <!-- PRODUCTS GRID -->
  <div class="grid-3 mb-6">
    @forelse($products as $product)
    <div class="card hover:shadow-lg transition-shadow">
      @if($product->photo)
      <div class="h-48 bg-cover bg-center rounded-t-lg" style="background-image: url('{{ $product->photo }}');"></div>
      @else
      <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-300 rounded-t-lg flex items-center justify-center">
        <svg width="48" height="48" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      </div>
      @endif
      
      <div class="card-body">
        <div class="flex items-center justify-between mb-2">
          <span class="badge {{ getProductStockColor($product) }}">
            {{ getProductStockLabel($product) }}
          </span>
          <span class="badge blue">{{ ucfirst($product->category) }}</span>
        </div>
        
        <h3 class="font-bold text-lg mb-2">{{ $product->product_name }}</h3>
        <p class="text-sm text-muted mb-4">{{ Str::limit($product->description, 80) }}</p>
        
        <div class="space-y-2 mb-4">
          <div class="flex justify-between text-sm">
            <span class="text-muted">Price:</span>
            <span class="font-bold text-green">TZS {{ number_format($product->unit_price, 0) }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-muted">Stock:</span>
            <span>{{ $product->quantity_in_stock }} units</span>
          </div>
          @if($product->reorder_level)
          <div class="flex justify-between text-sm">
            <span class="text-muted">Reorder at:</span>
            <span>{{ $product->reorder_level }} units</span>
          </div>
          @endif
        </div>
        
        <div class="flex gap-2">
          <button class="btn btn-ghost btn-sm flex-1" onclick="viewProduct({{ $product->id }})" title="View">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View
          </button>
          @if(auth()->user()->hasPermission('shop.edit'))
          <a href="{{ route('shop.edit', $product->id) }}" class="btn btn-ghost btn-sm flex-1" title="Edit">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
          </a>
          @endif
          @if(auth()->user()->hasPermission('shop.sell') && $product->quantity_in_stock > 0)
          <button class="btn btn-primary btn-sm flex-1" onclick="quickSell({{ $product->id }})" title="Quick Sell">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Sell
          </button>
          @endif
        </div>
      </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-muted">
      <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;">
        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      <p>No products found</p>
      @if(auth()->user()->hasPermission('shop.create'))
      <a href="{{ route('shop.create') }}" class="btn btn-primary mt-4">Add First Product</a>
      @endif
    </div>
    @endforelse
  </div>

  <!-- PAGINATION -->
  {{ $products->links() }}
</div>

<!-- VIEW PRODUCT MODAL -->
<div class="modal-overlay" id="viewProductModal">
  <div class="modal" style="width: 700px;">
    <div class="modal-header">
      <div><div class="card-title">Product Details</div><div class="card-subtitle">Complete product information</div></div>
      <div class="modal-close" onclick="closeModal('viewProductModal')">✕</div>
    </div>
    <div class="modal-body" id="productDetails">
      <!-- Content will be loaded dynamically -->
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('viewProductModal')">Close</button>
    </div>
  </div>
</div>

<!-- QUICK SELL MODAL -->
<div class="modal-overlay" id="quickSellModal">
  <div class="modal" style="width: 500px;">
    <div class="modal-header">
      <div><div class="card-title">Quick Sale</div><div class="card-subtitle">Process quick sale for product</div></div>
      <div class="modal-close" onclick="closeModal('quickSellModal')">✕</div>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Product</label>
        <input type="text" class="form-control" id="quickSellProduct" readonly>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Quantity *</label>
          <input type="number" class="form-control" id="quickSellQuantity" min="1" value="1">
        </div>
        <div class="form-group">
          <label class="form-label">Unit Price</label>
          <input type="text" class="form-control" id="quickSellPrice" readonly>
        </div>
      </div>
      
      <div class="form-group">
        <label class="form-label">Member</label>
        <select class="form-control" id="quickSellMember">
          <option value="">Select Member (Optional)</option>
          @foreach($members as $member)
          <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->registration_number }}</option>
          @endforeach
        </select>
      </div>
      
      <div class="form-group">
        <label class="form-label">Payment Method *</label>
        <select class="form-control" id="quickSellPaymentMethod">
          <option value="">Select Method</option>
          <option value="cash">Cash</option>
          <option value="mobile_money">Mobile Money</option>
          <option value="card">Card</option>
        </select>
      </div>
      
      <div class="form-group">
        <label class="form-label">Total Amount</label>
        <input type="text" class="form-control" id="quickSellTotal" readonly>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('quickSellModal')">Cancel</button>
      <button class="btn btn-primary" onclick="processQuickSale()">Process Sale</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Helper functions
function getProductStockColor(product) {
  if (product.quantity_in_stock === 0) return 'red';
  if (product.quantity_in_stock <= product.reorder_level) return 'amber';
  return 'green';
}

function getProductStockLabel(product) {
  if (product.quantity_in_stock === 0) return 'Out of Stock';
  if (product.quantity_in_stock <= product.reorder_level) return 'Low Stock';
  return 'In Stock';
}

function viewProduct(productId) {
  fetch(`/shop/${productId}/show`)
    .then(response => response.text())
    .then(html => {
      document.getElementById('productDetails').innerHTML = html;
      document.getElementById('viewProductModal').classList.add('open');
    });
}

function quickSell(productId) {
  fetch(`/shop/${productId}/details`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('quickSellProduct').value = data.product.product_name;
        document.getElementById('quickSellPrice').value = 'TZS ' + data.product.unit_price;
        document.getElementById('quickSellModal').dataset.productId = productId;
        document.getElementById('quickSellModal').classList.add('open');
        updateQuickSellTotal();
      }
    });
}

function updateQuickSellTotal() {
  const quantity = parseInt(document.getElementById('quickSellQuantity').value) || 0;
  const price = parseFloat(document.getElementById('quickSellPrice').value.replace('TZS ', '').replace(/,/g, '')) || 0;
  const total = quantity * price;
  document.getElementById('quickSellTotal').value = 'TZS ' + total.toLocaleString();
}

function processQuickSale() {
  const productId = document.getElementById('quickSellModal').dataset.productId;
  const quantity = document.getElementById('quickSellQuantity').value;
  const memberId = document.getElementById('quickSellMember').value;
  const paymentMethod = document.getElementById('quickSellPaymentMethod').value;
  
  if (!quantity || !paymentMethod) {
    showToast('Please fill in all required fields', 'warning');
    return;
  }
  
  fetch(`/shop/sell`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      product_id: productId,
      quantity: quantity,
      member_id: memberId,
      payment_method: paymentMethod
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showToast('Sale processed successfully', 'success');
      closeModal('quickSellModal');
      location.reload();
    } else {
      showToast(data.message || 'Error processing sale', 'error');
    }
  });
}

function showPOS() {
  window.location.href = '/shop/pos';
}

function generateSalesReport() {
  window.open('/shop/sales-report', '_blank');
}

function exportProducts() {
  window.open('/shop/export', '_blank');
}

function resetFilters() {
  document.getElementById('searchInput').value = '';
  document.getElementById('categoryFilter').value = '';
  document.getElementById('stockFilter').value = '';
  location.href = '{{ route('shop.index') }}';
}

// Update total when quantity changes
document.getElementById('quickSellQuantity').addEventListener('input', updateQuickSellTotal);

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
  const search = e.target.value;
  const url = new URL(window.location);
  if (search) {
    url.searchParams.set('search', search);
  } else {
    url.searchParams.delete('search');
  }
  window.location = url.toString();
});

// Filter functionality
['categoryFilter', 'stockFilter'].forEach(id => {
  document.getElementById(id).addEventListener('change', function(e) {
    const value = e.target.value;
    const url = new URL(window.location);
    const param = id.replace('Filter', '');
    if (value) {
      url.searchParams.set(param, value);
    } else {
      url.searchParams.delete(param);
    }
    window.location = url.toString();
  });
});
</script>
@endpush

<?php
// Helper functions for the view
function getProductStockColor($product) {
    if ($product->quantity_in_stock === 0) return 'red';
    if ($product->quantity_in_stock <= $product->reorder_level) return 'amber';
    return 'green';
}

function getProductStockLabel($product) {
    if ($product->quantity_in_stock === 0) return 'Out of Stock';
    if ($product->quantity_in_stock <= $product->reorder_level) return 'Low Stock';
    return 'In Stock';
}
?>

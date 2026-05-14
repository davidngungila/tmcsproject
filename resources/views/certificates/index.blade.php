@extends('layouts.app')

@section('title', 'Certificates - TmcsSmart')
@section('page-title', 'Certificate Management')
@section('breadcrumb', 'TmcsSmart / Certificates')

@section('content')
<div class="animate-in">
  <!-- STATISTICS -->
  <div class="stat-grid mb-6">
    <div class="stat-card green">
      <div class="stat-icon green">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="stat-value">{{ $totalCertificates }}</div>
      <div class="stat-label">Total Certificates</div>
    </div>
    <div class="stat-card blue">
      <div class="stat-icon blue">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
      </div>
      <div class="stat-value">{{ $verifiedCertificates }}</div>
      <div class="stat-label">Verified Certificates</div>
    </div>
  </div>

  <!-- ACTIONS -->
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold">Certificates</h2>
    @if(auth()->user()->hasPermission('certificates.create'))
    <a href="{{ route('certificates.create') }}" class="btn btn-primary">Generate Certificate</a>
    @endif
  </div>

  <!-- TABLE -->
  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Member</th>
            <th>Type</th>
            <th>Issue Date</th>
            <th>Status</th>
            <th>Issued By</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($certificates as $cert)
          <tr>
            <td>{{ $cert->member_name }}</td>
            <td>{{ $cert->certificate_type }}</td>
            <td>{{ $cert->issue_date->format('M d, Y') }}</td>
            <td><span class="badge {{ $cert->status == 'Active' ? 'green' : 'red' }}">{{ $cert->status }}</span></td>
            <td>{{ $cert->issued_by }}</td>
            <td>
              <div class="flex gap-1">
                <button class="btn btn-ghost btn-sm">View</button>
                <button class="btn btn-ghost btn-sm">Download</button>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

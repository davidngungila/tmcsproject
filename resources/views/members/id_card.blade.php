@extends('layouts.app')

@section('title', 'Member ID Card - ' . $member->full_name)
@section('page-title', 'Identity Card Preview')
@section('breadcrumb', 'TmcsSmart / Members / ' . $member->registration_number . ' / ID Card')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap');

    :root {
        --card-width: 85.6mm;
        --card-height: 53.98mm;
        --primary: #059669; 
        --primary-dark: #064e3b;
        --primary-light: #d1fae5;
        --accent: #fbbf24;
        --text-main: #111827;
        --text-muted: #4b5563;
    }

    .id-card-container {
        font-family: 'Montserrat', sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 30px;
        padding: 20px;
    }

    .id-card {
        width: var(--card-width);
        height: var(--card-height);
        background: white;
        border-radius: 3.18mm;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        flex-shrink: 0;
        border: 1px solid #eee;
    }

    /* FRONT DESIGN */
    .card-front {
        background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);
    }

    .side-accent {
        position: absolute;
        left: 0;
        top: 0;
        width: 2mm;
        height: 100%;
        background: var(--primary);
        z-index: 2;
    }

    .header-section {
        height: 14mm;
        background: linear-gradient(to right, var(--primary-dark), var(--primary));
        display: flex;
        align-items: center;
        padding: 0 5mm;
        color: white;
        position: relative;
        z-index: 2;
    }

    .logo-box {
        width: 8mm;
        height: 8mm;
        background: white;
        border-radius: 1.5mm;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 3mm;
        color: var(--primary);
    }

    .church-info h1 {
        margin: 0;
        font-size: 2.5mm;
        font-weight: 900;
        letter-spacing: 0.1mm;
        text-transform: uppercase;
    }

    .church-info p {
        margin: 0;
        font-size: 1.4mm;
        font-weight: 600;
        opacity: 0.9;
        letter-spacing: 0.05mm;
    }

    .card-main {
        display: flex;
        padding: 4mm 5mm;
        gap: 5mm;
        position: relative;
        z-index: 2;
    }

    .photo-container {
        width: 24mm;
        height: 30mm;
        background: white;
        border-radius: 2mm;
        border: 0.5mm solid var(--primary-light);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .photo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .member-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .name-tag h2 {
        margin: 0;
        font-size: 3.5mm;
        font-weight: 900;
        color: var(--text-main);
    }

    .name-tag span {
        font-size: 2mm;
        font-weight: 800;
        color: var(--primary);
        text-transform: uppercase;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2mm;
        margin-top: 2mm;
    }

    .info-item label {
        display: block;
        font-size: 1.4mm;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .info-item p {
        margin: 0;
        font-size: 2mm;
        font-weight: 800;
        color: var(--text-main);
    }

    .valid-thru {
        margin-top: auto;
        font-size: 1.8mm;
        font-weight: 800;
        color: #ef4444;
        background: #fee2e2;
        padding: 0.5mm 1.5mm;
        border-radius: 1mm;
        display: inline-block;
    }

    /* BACK DESIGN */
    .card-back {
        background: #111827;
        color: white;
        padding: 5mm;
        display: flex;
        flex-direction: column;
    }

    .back-top {
        display: flex;
        justify-content: space-between;
        border-bottom: 0.1mm solid rgba(255,255,255,0.1);
        padding-bottom: 2mm;
        margin-bottom: 2mm;
    }

    .back-title-area h3 {
        margin: 0;
        font-size: 2.5mm;
        font-weight: 800;
        color: var(--accent);
        text-transform: uppercase;
    }

    .back-body {
        display: flex;
        gap: 4mm;
        flex: 1;
    }

    .qr-container {
        width: 20mm;
        height: 20mm;
        background: white;
        border-radius: 2mm;
        padding: 1.5mm;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .terms-box {
        flex: 1;
        font-size: 1.6mm;
        line-height: 1.4;
        color: #9ca3af;
    }

    .terms-box strong {
        color: white;
        display: block;
        margin-bottom: 1mm;
        font-size: 1.8mm;
    }

    .signature-area {
        margin-top: 3mm;
        display: flex;
        justify-content: space-between;
    }

    .sig-box {
        width: 28mm;
        text-align: center;
    }

    .sig-line {
        border-top: 0.1mm solid rgba(255,255,255,0.3);
        margin-bottom: 1mm;
    }

    .sig-label {
        font-size: 1.5mm;
        opacity: 0.7;
    }

    .back-footer-text {
        text-align: center;
        font-size: 1.6mm;
        color: var(--primary);
        margin-top: auto;
    }

    @media print {
        .no-print { display: none !important; }
        .main-content { padding: 0 !important; }
        .id-card { box-shadow: none; border: 1px solid #ddd; }
    }
</style>

<div class="animate-in">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold">Identity Card Preview</h2>
            <p class="text-sm text-muted">Review and download the member ID card</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('members.show', $member->id) }}" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Profile
            </a>
            <button onclick="window.print()" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print
            </button>
            <a href="{{ route('members.id-card', ['member' => $member->id, 'download' => 1]) }}" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="mr-2"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="id-card-container">
        <!-- FRONT -->
        <div class="id-card card-front">
            <div class="side-accent"></div>
            <div class="header-section">
                <div class="logo-box">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z"/></svg>
                </div>
                <div class="church-info">
                    <h1>ST. JOSEPH THE WORKER CHAPLAINCY</h1>
                    <p>CATHOLIC COMMUNITY OF MOSHI CO-OPERATIVE UNIVERSITY</p>
                </div>
            </div>
            
            <div class="card-main">
                <div class="photo-container">
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}" alt="">
                    @else
                        <div style="width:100%; height:100%; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary)">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    @endif
                </div>
                
                <div class="member-details">
                    <div class="name-tag">
                        <h2>{{ strtoupper($member->full_name) }}</h2>
                        <span>{{ strtoupper($member->member_type) }}</span>
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Member ID</label>
                            <p>{{ $member->registration_number }}</p>
                        </div>
                        <div class="info-item">
                            <label>Baptismal</label>
                            <p>{{ $member->baptismal_name ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Joined</label>
                            <p>{{ $member->registration_date->format('M Y') }}</p>
                        </div>
                        <div class="info-item">
                            <label>Category</label>
                            <p>{{ $member->category ? $member->category->name : 'Member' }}</p>
                        </div>
                    </div>
                    
                    <div class="valid-thru">
                        VALID THRU: {{ $member->registration_date->addYears(5)->format('m/Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- BACK -->
        <div class="id-card card-back">
            <div class="back-top">
                <div class="back-title-area">
                    <h3>Member Verification</h3>
                    <p style="font-size: 1.5mm; opacity: 0.6; margin: 0;">Authorized personnel only</p>
                </div>
                <div style="color: var(--primary); font-weight: 900; font-size: 2.2mm">TMCS MoCU</div>
            </div>
            
            <div class="back-body">
                <div class="qr-container">
                    @php
                        $qrContent = "ST. JOSEPH THE WORKER CHAPLAINCY\n";
                        $qrContent .= "CATHOLIC COMMUNITY OF MoCU\n";
                        $qrContent .= "NAME: " . $member->full_name . "\n";
                        $qrContent .= "REG NO: " . $member->registration_number;
                    @endphp
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->margin(0)->generate($qrContent) !!}
                </div>
                
                <div class="terms-box">
                    <strong>Terms & Conditions</strong>
                    1. This card is property of ST. JOSEPH THE WORKER CHAPLAINCY.<br>
                    2. Use subject to chaplaincy policies.<br>
                    3. If found, return to MoCU TMCS office.<br>
                    4. Lost cards must be reported.<br>
                    5. Digital identification document.
                </div>
            </div>
            
            <div class="signature-area">
                <div class="sig-box">
                    <div class="sig-line"></div>
                    <div class="sig-label">Member's Signature</div>
                </div>
                <div class="sig-box">
                    <div class="sig-line"></div>
                    <div class="sig-label">Church Secretary</div>
                </div>
            </div>

            <div class="back-footer-text">
                www.tmcssmart.org • info@tmcssmart.org
            </div>
        </div>
    </div>
</div>
@endsection

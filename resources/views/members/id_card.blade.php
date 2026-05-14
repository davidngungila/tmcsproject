<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Member ID Card - {{ $member->full_name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap');

        :root {
            --card-width: 85.6mm;
            --card-height: 53.98mm;
            --primary: #059669; /* emerald-600 */
            --primary-dark: #064e3b; /* emerald-900 */
            --primary-light: #d1fae5; /* emerald-100 */
            --accent: #fbbf24; /* amber-400 */
            --text-main: #111827;
            --text-muted: #4b5563;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #f0f2f5;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
            padding: 40px;
            -webkit-print-color-adjust: exact;
        }

        .no-print-zone {
            background: white;
            padding: 24px 48px;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
        }

        .btn-print {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2);
        }

        .btn-print:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px rgba(5, 150, 105, 0.3);
        }

        /* ID CARD CONTAINER */
        .id-card-wrapper {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .id-card {
            width: var(--card-width);
            height: var(--card-height);
            background: white;
            border-radius: 3.18mm;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            flex-shrink: 0;
        }

        /* WATERMARK PATTERN */
        .watermark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.03;
            pointer-events: none;
            z-index: 1;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23059669' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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
            font-size: 3.2mm;
            font-weight: 900;
            letter-spacing: 0.3mm;
            text-transform: uppercase;
        }

        .church-info p {
            margin: 0;
            font-size: 1.8mm;
            font-weight: 600;
            opacity: 0.9;
            letter-spacing: 0.1mm;
        }

        .card-main {
            display: flex;
            padding: 4mm 5mm;
            gap: 5mm;
            position: relative;
            z-index: 2;
        }

        .photo-container {
            width: 26mm;
            height: 32mm;
            background: white;
            border-radius: 2mm;
            border: 0.8mm solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hologram-badge {
            position: absolute;
            bottom: 1.5mm;
            right: 1.5mm;
            width: 6mm;
            height: 6mm;
            background: linear-gradient(135deg, #ddd 0%, #fff 50%, #ddd 100%);
            border-radius: 50%;
            opacity: 0.8;
            border: 0.2mm solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1mm;
            font-weight: 900;
            color: #999;
            box-shadow: inset 0 0 2px rgba(0,0,0,0.1);
        }

        .member-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .name-tag {
            margin-bottom: 2.5mm;
        }

        .name-tag h2 {
            margin: 0;
            font-size: 4.2mm;
            font-weight: 900;
            color: var(--primary-dark);
            line-height: 1;
            margin-bottom: 0.8mm;
        }

        .name-tag span {
            font-size: 2.2mm;
            font-weight: 800;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.5mm;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2mm;
        }

        .info-item label {
            display: block;
            font-size: 1.6mm;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 0.3mm;
        }

        .info-item p {
            margin: 0;
            font-size: 2.6mm;
            font-weight: 800;
            color: var(--text-main);
        }

        .valid-thru {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .expiry {
            font-size: 1.8mm;
            font-weight: 700;
            color: #ef4444;
            background: #fee2e2;
            padding: 0.4mm 1.5mm;
            border-radius: 1mm;
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
            border-bottom: 0.2mm solid rgba(255,255,255,0.1);
            padding-bottom: 3mm;
            margin-bottom: 3mm;
        }

        .back-title-area h3 {
            margin: 0;
            font-size: 2.8mm;
            font-weight: 800;
            color: var(--accent);
            text-transform: uppercase;
        }

        .back-title-area p {
            margin: 0;
            font-size: 1.6mm;
            opacity: 0.6;
        }

        .back-body {
            display: flex;
            gap: 5mm;
            flex: 1;
        }

        .qr-container {
            width: 26mm;
            height: 26mm;
            background: white;
            border-radius: 2.5mm;
            padding: 2mm;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        .qr-container svg {
            width: 100% !important;
            height: 100% !important;
        }

        .terms-box {
            flex: 1;
            font-size: 1.7mm;
            line-height: 1.5;
            color: #9ca3af;
        }

        .terms-box strong {
            color: white;
            display: block;
            margin-bottom: 1mm;
            font-size: 1.9mm;
        }

        .signature-area {
            margin-top: 4mm;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .sig-box {
            width: 30mm;
            text-align: center;
        }

        .sig-line {
            border-top: 0.15mm solid rgba(255,255,255,0.3);
            margin-bottom: 1mm;
        }

        .sig-label {
            font-size: 1.6mm;
            font-weight: 600;
            opacity: 0.7;
        }

        .back-footer-text {
            text-align: center;
            font-size: 1.8mm;
            font-weight: 600;
            color: var(--primary);
            margin-top: auto;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .no-print-zone {
                display: none;
            }
            .id-card {
                box-shadow: none;
                border: 0.1mm solid #eee;
                page-break-inside: avoid;
            }
            .id-card-wrapper {
                gap: 10mm;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-zone">
        <h2 style="margin-top:0; font-weight: 900; color: var(--primary-dark)">Advanced ID Card Preview</h2>
        <p style="color:var(--text-muted); font-size: 14px; margin-bottom: 24px">
            High-security professional CR80 standard card design.<br>
            Standard size: 85.6mm x 53.98mm.
        </p>
        <div style="display: flex; align-items: center; justify-content: center; gap: 12px">
            <button class="btn-print" onclick="window.print()">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Identity Card
            </button>
            <a href="{{ url()->previous() }}" style="color: var(--text-muted); text-decoration: none; font-size: 14px; font-weight: 700; border-bottom: 2px solid #ddd">Return Back</a>
        </div>
    </div>

    <div class="id-card-wrapper">
        <!-- FRONT OF CARD -->
        <div class="id-card card-front">
            <div class="watermark"></div>
            <div class="side-accent"></div>
            
            <div class="header-section">
                <div class="logo-box">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z"/></svg>
                </div>
                <div class="church-info">
                    <h1>TMCS Smart Church</h1>
                    <p>Digital Excellence in Ministry</p>
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
                    <div class="hologram-badge">TMCS</div>
                </div>
                
                <div class="member-details">
                    <div class="name-tag">
                        <h2>{{ strtoupper($member->full_name) }}</h2>
                        <span>{{ $member->member_type }}</span>
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
                            <p>{{ $member->category ?? 'Member' }}</p>
                        </div>
                    </div>

                    <div class="valid-thru">
                        <span class="expiry">VALID THRU: {{ $member->registration_date->addYears(5)->format('m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- BACK OF CARD -->
        <div class="id-card card-back">
            <div class="watermark" style="opacity: 0.05"></div>
            
            <div class="back-top">
                <div class="back-title-area">
                    <h3>Member Verification</h3>
                    <p>Authorized personnel only</p>
                </div>
                <div style="color: var(--primary); font-weight: 900; font-size: 2.5mm">TMCS-SMART</div>
            </div>
            
            <div class="back-body">
                <div class="qr-container">
                    @php
                        $qrContent = "TMCS SMART - MEMBER IDENTITY\n";
                        $qrContent .= "--------------------------\n";
                        $qrContent .= "NAME: " . $member->full_name . "\n";
                        $qrContent .= "REG NO: " . $member->registration_number . "\n";
                        $qrContent .= "TYPE: " . strtoupper($member->member_type) . "\n";
                        $qrContent .= "PHONE: " . ($member->phone ?? 'N/A') . "\n";
                        $qrContent .= "STATUS: ACTIVE MEMBER\n";
                        $qrContent .= "VERIFIED BY: TMCS SYSTEM";
                    @endphp
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->margin(0)->generate($qrContent) !!}
                </div>
                
                <div class="terms-box">
                    <strong>Terms & Conditions</strong>
                    1. This card is the property of TMCS Church Management.<br>
                    2. Use of this card is subject to church policies.<br>
                    3. If found, please return to the nearest branch office.<br>
                    4. Lost cards must be reported immediately.<br>
                    5. This is a digital identification document.
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

</body>
</html>

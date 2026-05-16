<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { 
            size: 85.6mm 53.98mm;
            margin: 0; 
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            width: 85.6mm;
            height: 53.98mm;
            line-height: 1;
        }
        .id-card {
            width: 85.6mm;
            height: 53.98mm;
            position: relative;
            overflow: hidden;
            display: block;
            page-break-after: always;
        }
        .id-card:last-child {
            page-break-after: avoid;
        }
        .header-section {
            height: 14mm;
            background: #064e3b;
            color: white;
            padding: 0 4mm;
            width: 100%;
            overflow: hidden;
        }
        .church-info {
            padding-top: 2.5mm;
        }
        .church-info h1 {
            margin: 0;
            font-size: 2.5mm;
            font-weight: bold;
            text-transform: uppercase;
            color: #ffffff;
        }
        .church-info p {
            margin: 0;
            font-size: 1.4mm;
            opacity: 0.9;
            color: #ffffff;
        }
        .card-main {
            padding: 3mm 4mm;
            width: 100%;
            height: 39.98mm;
        }
        .photo-container {
            width: 22mm;
            height: 28mm;
            float: left;
            margin-right: 3mm;
            border: 0.4mm solid #059669;
            background: #f3f4f6;
        }
        .photo-container img {
            width: 100%;
            height: 100%;
            display: block;
        }
        .member-details {
            float: left;
            width: 52mm;
        }
        .name-tag h2 {
            margin: 0;
            font-size: 3.2mm;
            color: #111827;
            font-weight: bold;
        }
        .name-tag span {
            font-size: 1.8mm;
            color: #059669;
            font-weight: bold;
            text-transform: uppercase;
            display: block;
            margin-top: 0.5mm;
        }
        .info-grid {
            margin-top: 1.5mm;
        }
        .info-item {
            margin-bottom: 1mm;
        }
        .info-item label {
            display: block;
            font-size: 1.3mm;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 0.2mm;
        }
        .info-item p {
            margin: 0;
            font-size: 1.9mm;
            font-weight: bold;
            color: #111827;
        }
        .valid-thru {
            margin-top: 2mm;
            font-size: 1.6mm;
            font-weight: bold;
            color: #ef4444;
            background: #fee2e2;
            padding: 0.4mm 1mm;
            display: inline-block;
        }

        /* BACK SIDE */
        .card-back {
            background: #111827;
            color: white;
            padding: 4mm 4mm;
        }
        .back-title h3 {
            margin: 0;
            font-size: 2.2mm;
            color: #fbbf24;
            text-transform: uppercase;
        }
        .qr-section {
            margin-top: 3mm;
            width: 100%;
            height: 22mm;
        }
        .qr-box {
            width: 20mm;
            height: 20mm;
            background: white;
            padding: 1mm;
            float: left;
        }
        .qr-box img {
            width: 18mm;
            height: 18mm;
            display: block;
        }
        .terms {
            float: left;
            width: 53mm;
            padding-left: 3mm;
            font-size: 1.4mm;
            color: #9ca3af;
            line-height: 1.3;
        }
        .terms strong { color: white; display: block; margin-bottom: 0.5mm; font-size: 1.6mm; }
        .signature-section {
            margin-top: 3mm;
            width: 100%;
            clear: both;
        }
        .sig-box {
            width: 35mm;
            float: left;
            text-align: center;
        }
        .sig-line {
            border-top: 0.1mm solid #fff;
            margin-bottom: 0.5mm;
        }
        .sig-label { font-size: 1.4mm; opacity: 0.7; color: #ffffff; }
        .footer-text {
            position: absolute;
            bottom: 2mm;
            width: 100%;
            text-align: center;
            font-size: 1.5mm;
            color: #059669;
        }
    </style>
</head>
<body>
    <!-- FRONT -->
    <div class="id-card">
        <div class="header-section">
            <div class="church-info">
                <h1>ST. JOSEPH THE WORKER CHAPLAINCY</h1>
                <p>CATHOLIC COMMUNITY OF MOSHI CO-OPERATIVE UNIVERSITY</p>
            </div>
        </div>
        <div class="card-main">
            <div class="photo-container">
                @if($member->photo)
                    <img src="{{ public_path('storage/' . $member->photo) }}" alt="">
                @else
                    <div style="background: #d1fae5; width: 100%; height: 100%;"></div>
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
        <div class="back-title">
            <h3>Member Verification</h3>
            <p style="font-size: 1.5mm; opacity: 0.6; margin: 0;">Authorized personnel only</p>
        </div>
        <div class="qr-section">
            <div class="qr-box">
                @php
                    $qrContent = "ST. JOSEPH THE WORKER CHAPLAINCY\n";
                    $qrContent .= "CATHOLIC COMMUNITY OF MoCU\n";
                    $qrContent .= "NAME: " . $member->full_name . "\n";
                    $qrContent .= "REG NO: " . $member->registration_number;
                @endphp
                <img src="data:image/png;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(80)->margin(0)->generate($qrContent)) !!} ">
            </div>
            <div class="terms">
                <strong>Terms & Conditions</strong>
                1. This card is property of ST. JOSEPH THE WORKER CHAPLAINCY.<br>
                2. Use subject to chaplaincy policies.<br>
                3. If found, return to MoCU TMCS office.<br>
                4. Lost cards must be reported.<br>
                5. Digital identification document.
            </div>
        </div>
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-line"></div>
                <div class="sig-label">Member's Signature</div>
            </div>
            <div class="sig-box" style="margin-left: 5mm;">
                <div class="sig-line"></div>
                <div class="sig-label">Church Secretary</div>
            </div>
        </div>
        <div class="footer-text">
            www.tmcssmart.org • info@tmcssmart.org
        </div>
    </div>
</body>
</html>

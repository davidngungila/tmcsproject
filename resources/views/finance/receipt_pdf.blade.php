    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 22px;
            font-weight: 900;
            color: #16a34a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sub-header {
            font-size: 11px;
            color: #16a34a;
            font-weight: bold;
            margin-top: 2px;
            text-transform: uppercase;
        }
        .receipt-title {
            font-size: 18px;
            margin-top: 8px;
            color: #111;
            font-weight: 900;
            background: #f3f4f6;
            padding: 5px;
            display: inline-block;
            border-radius: 4px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(22, 163, 74, 0.05);
            z-index: -1;
            font-weight: bold;
            white-space: nowrap;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table td {
            padding: 6px 0;
            vertical-align: top;
        }
        .label {
            font-weight: 800;
            color: #4b5563;
            width: 130px;
            text-transform: uppercase;
            font-size: 10px;
        }
        .value {
            font-weight: 700;
            color: #111;
            font-size: 12px;
        }
        .amount-section {
            background: linear-gradient(to right, #f0fdf4, #ffffff);
            border: 1px solid #bcf0da;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            position: relative;
        }
        .amount-label {
            font-size: 10px;
            color: #16a34a;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .amount-value {
            font-size: 28px;
            font-weight: 900;
            color: #15803d;
        }
        .amount-words {
            font-size: 11px;
            font-style: italic;
            color: #6b7280;
            margin-top: 5px;
            text-transform: capitalize;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            background: #fff;
        }
        .qr-code-box {
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .status-verified { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px dashed #e5e7eb;
            padding-top: 15px;
        }
        .signature-grid {
            margin-top: 50px;
            width: 100%;
        }
        .sig-line {
            border-top: 1px solid #374151;
            width: 160px;
            margin: 0 auto 5px;
        }
        .sig-text {
            font-size: 9px;
            font-weight: bold;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ $contribution->is_verified ? 'OFFICIAL' : 'PROVISIONAL' }}</div>
    
    <div class="container">
        <div class="header">
            <div class="logo">ST. JOSEPH THE WORKER CHAPLAINCY</div>
            <div class="sub-header">CATHOLIC COMMUNITY OF MOSHI CO-OPERATIVE UNIVERSITY</div>
            <div class="receipt-title">OFFICIAL PAYMENT RECEIPT</div>
        </div>

        <table style="width: 100%; margin-bottom: 15px;">
            <tr>
                <td>
                    <div class="label">Receipt No:</div>
                    <div class="value" style="font-size: 16px; color: #16a34a;">#{{ $safeReceiptNo ?? $contribution->receipt_number }}</div>
                </td>
                <td style="text-align: right;">
                    <div class="label">Date Issued:</div>
                    <div class="value">{{ $contribution->contribution_date->format('l, d F Y') }}</div>
                    <div class="value" style="font-size: 10px; color: #6b7280; font-weight: normal;">Time: {{ $contribution->created_at->format('H:i:s') }}</div>
                </td>
            </tr>
        </table>

        <div class="info-card">
            <table class="details-table">
                <tr>
                    <td class="label">Received From:</td>
                    <td class="value" style="font-size: 14px;">{{ strtoupper($contribution->member->full_name ?? 'Anonymous') }}</td>
                    <td rowspan="4" class="qr-code-box">
                        @php
                            $qrContent = "RECEIPT: " . $contribution->receipt_number . "\n";
                            $qrContent .= "MEMBER: " . ($contribution->member->full_name ?? 'N/A') . "\n";
                            $qrContent .= "AMOUNT: TZS " . number_format($contribution->amount) . "\n";
                            $qrContent .= "VERIFIED: " . ($contribution->is_verified ? 'YES' : 'NO');
                        @endphp
                        <img src="data:image/svg+xml;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(80)->margin(0)->generate($qrContent)) !!} " style="width: 25mm; height: 25mm;">
                    </td>
                </tr>
                <tr>
                    <td class="label">Member ID:</td>
                    <td class="value">{{ $contribution->member->registration_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Purpose:</td>
                    <td class="value">{{ strtoupper(str_replace('_', ' ', $contribution->contribution_type)) }}</td>
                </tr>
                <tr>
                    <td class="label">Payment Mode:</td>
                    <td class="value">
                        {{ strtoupper(str_replace('_', ' ', $contribution->payment_method)) }}
                        <span class="status-badge {{ $contribution->is_verified ? 'status-verified' : 'status-pending' }}">
                            • {{ $contribution->is_verified ? 'CONFIRMED' : 'AWAITING CLEARANCE' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="amount-section">
            <div class="amount-label">Authorized Amount</div>
            <div class="amount-value">TZS {{ number_format($contribution->amount, 2) }}</div>
            @php
                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                $words = $f->format($contribution->amount);
            @endphp
            <div class="amount-words">Amount in words: {{ $words }} Tanzanian Shillings Only</div>
        </div>

        <div style="font-size: 10px; color: #4b5563; padding: 10px; background: #f9fafb; border-radius: 6px; border-left: 3px solid #16a34a;">
            <strong>Transaction Reference:</strong> {{ $contribution->transaction_reference ?? 'INTERNAL-TRANS-'.$contribution->id }}<br>
            @if($contribution->notes)
                <strong>System Remarks:</strong> {{ $contribution->notes }}
            @endif
        </div>

        <table class="signature-grid">
            <tr>
                <td style="text-align: center; width: 50%;">
                    <div class="sig-line"></div>
                    <div class="sig-text">TREASURER / SECRETARY</div>
                    <div style="font-size: 8px; color: #9ca3af;">(Digital Seal Applied)</div>
                </td>
                <td style="text-align: center; width: 50%;">
                    <div class="sig-line"></div>
                    <div class="sig-text">MEMBER'S ACKNOWLEDGMENT</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <strong>ST. JOSEPH THE WORKER CHAPLAINCY - MoCU</strong><br>
            P.O. Box 474, Moshi, Kilimanjaro, Tanzania<br>
            <span style="color: #16a34a;">www.tmcssmart.org • info@tmcssmart.org</span><br>
            <div style="margin-top: 10px; font-size: 8px; color: #9ca3af;">
                This document is electronically generated and verified by TMCS SMART System.
            </div>
        </div>
    </div>
</body>
</html>

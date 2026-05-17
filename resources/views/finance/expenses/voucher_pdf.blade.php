<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expense Voucher - {{ $expense->voucher_number }}</title>
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
            border-bottom: 2px solid #dc2626;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 22px;
            font-weight: 900;
            color: #dc2626;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sub-header {
            font-size: 11px;
            color: #dc2626;
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
            color: rgba(220, 38, 38, 0.05);
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
            background: linear-gradient(to right, #fef2f2, #ffffff);
            border: 1px solid #fecaca;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            position: relative;
        }
        .amount-label {
            font-size: 10px;
            color: #dc2626;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .amount-value {
            font-size: 28px;
            font-weight: 900;
            color: #991b1b;
        }
        .amount-words {
            font-size: 11px;
            font-style: italic;
            color: #6b7280;
            margin-top: 5px;
            text-transform: capitalize;
        }
        .info-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            background: #fff;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
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
        .ledger-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        .ledger-table th {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
            font-weight: 900;
            text-transform: uppercase;
            color: #4b5563;
        }
        .ledger-table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ strtoupper($expense->status) }}</div>
    
    <div class="container">
        <div class="header">
            <div class="logo">ST. JOSEPH THE WORKER CHAPLAINCY</div>
            <div class="sub-header">CATHOLIC COMMUNITY OF MOSHI CO-OPERATIVE UNIVERSITY</div>
            <div class="receipt-title">OFFICIAL EXPENSE VOUCHER</div>
        </div>

        <table style="width: 100%; margin-bottom: 15px;">
            <tr>
                <td>
                    <div class="label">Voucher No:</div>
                    <div class="value" style="font-size: 16px; color: #dc2626;">#{{ $expense->voucher_number }}</div>
                </td>
                <td style="text-align: right;">
                    <div class="label">Date Issued:</div>
                    <div class="value">{{ $expense->expense_date->format('l, d F Y') }}</div>
                    <div class="value" style="font-size: 10px; color: #6b7280; font-weight: normal;">Recorded: {{ $expense->created_at->format('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>

        <div class="info-card">
            <table class="details-table">
                <tr>
                    <td class="label">Paid To / Purpose:</td>
                    <td class="value" style="font-size: 14px;">{{ strtoupper($expense->description) }}</td>
                    <td rowspan="4" style="text-align: right; vertical-align: middle;">
                        @php
                            $qrContent = "VOUCHER: " . $expense->voucher_number . "\n";
                            $qrContent .= "PURPOSE: " . $expense->description . "\n";
                            $qrContent .= "AMOUNT: TZS " . number_format($expense->amount) . "\n";
                            $qrContent .= "STATUS: " . $expense->status;
                        @endphp
                        <img src="data:image/svg+xml;base64, {!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(80)->margin(0)->generate($qrContent)) !!} " style="width: 25mm; height: 25mm;">
                    </td>
                </tr>
                <tr>
                    <td class="label">Category:</td>
                    <td class="value">{{ strtoupper($expense->category) }}</td>
                </tr>
                <tr>
                    <td class="label">Payment Mode:</td>
                    <td class="value">{{ strtoupper(str_replace('_', ' ', $expense->payment_method)) }}</td>
                </tr>
                <tr>
                    <td class="label">Reference:</td>
                    <td class="value">{{ $expense->reference_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Voucher Status:</td>
                    <td class="value">
                        <span class="status-badge {{ $expense->status == 'Approved' ? 'status-approved' : ($expense->status == 'Rejected' ? 'status-rejected' : 'status-pending') }}">
                            {{ strtoupper($expense->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="amount-section">
            <div class="amount-label">Authorized Expenditure</div>
            <div class="amount-value">TZS {{ number_format($expense->amount, 2) }}</div>
            @php
                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                $words = $f->format($expense->amount);
            @endphp
            <div class="amount-words">Amount in words: {{ $words }} Tanzanian Shillings Only</div>
        </div>

        @if($expense->status == 'Approved' && count($ledgerEntries) > 0)
        <div style="margin-top: 20px;">
            <div class="label" style="margin-bottom: 5px;">Accounting Entries (Double Entry)</div>
            <table class="ledger-table">
                <thead>
                    <tr>
                        <th>Account Code & Name</th>
                        <th style="text-align: right;">Debit (TZS)</th>
                        <th style="text-align: right;">Credit (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ledgerEntries as $entry)
                    <tr>
                        <td><strong>{{ $entry->account->code }}</strong> - {{ $entry->account->name }}</td>
                        <td style="text-align: right;">{{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}</td>
                        <td style="text-align: right;">{{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <table class="signature-grid">
            <tr>
                <td style="text-align: center; width: 33%;">
                    <div class="sig-line"></div>
                    <div class="sig-text">PREPARED BY</div>
                    <div style="font-size: 8px; color: #9ca3af;">{{ $expense->recorder->name ?? 'System' }}</div>
                </td>
                <td style="text-align: center; width: 33%;">
                    <div class="sig-line"></div>
                    <div class="sig-text">TREASURER</div>
                    <div style="font-size: 8px; color: #9ca3af;">(Authorized Official)</div>
                </td>
                <td style="text-align: center; width: 33%;">
                    <div class="sig-line"></div>
                    <div class="sig-text">RECEIVED BY</div>
                    <div style="font-size: 8px; color: #9ca3af;">(Beneficiary)</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <strong>ST. JOSEPH THE WORKER CHAPLAINCY - MoCU</strong><br>
            P.O. Box 474, Moshi, Kilimanjaro, Tanzania<br>
            <span style="color: #dc2626;">www.tmcssmart.org • info@tmcssmart.org</span><br>
            <div style="margin-top: 10px; font-size: 8px; color: #9ca3af;">
                This voucher is electronically generated and requires physical signature where indicated.
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $contribution->receipt_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #16a34a;
            text-transform: uppercase;
        }
        .receipt-title {
            font-size: 20px;
            margin-top: 10px;
            color: #666;
        }
        .details-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .details-grid td {
            padding: 8px 0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #666;
            width: 150px;
            text-transform: uppercase;
            font-size: 12px;
        }
        .value {
            font-weight: bold;
            color: #111;
            font-size: 14px;
        }
        .amount-box {
            background: #f0fdf4;
            border: 1px solid #bcf0da;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .amount-label {
            font-size: 12px;
            color: #16a34a;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .amount-value {
            font-size: 32px;
            font-weight: bold;
            color: #15803d;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .status-verified {
            color: #16a34a;
            font-weight: bold;
        }
        .status-pending {
            color: #d97706;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">ST. JOSEPH THE WORKER CHAPLAINCY</div>
            <div style="font-size: 14px; color: #16a34a; font-weight: bold; margin-top: 5px;">CATHOLIC COMMUNITY OF MOSHI CO-OPERATIVE UNIVERSITY</div>
            <div class="receipt-title">OFFICIAL PAYMENT RECEIPT</div>
        </div>

        <table class="details-grid">
            <tr>
                <td class="label">Receipt Number:</td>
                <td class="value">{{ $contribution->receipt_number }}</td>
                <td class="label" style="text-align: right;">Date:</td>
                <td class="value" style="text-align: right;">{{ $contribution->contribution_date->format('M d, Y') }}</td>
            </tr>
        </table>

        <div style="margin-bottom: 30px;">
            <table class="details-grid">
                <tr>
                    <td class="label">Received From:</td>
                    <td class="value">{{ $contribution->member->full_name }}</td>
                </tr>
                <tr>
                    <td class="label">Member ID:</td>
                    <td class="value">{{ $contribution->member->registration_number }}</td>
                </tr>
                <tr>
                    <td class="label">Giving Type:</td>
                    <td class="value">{{ ucfirst(str_replace('_', ' ', $contribution->contribution_type)) }}</td>
                </tr>
                <tr>
                    <td class="label">Payment Method:</td>
                    <td class="value">{{ ucfirst(str_replace('_', ' ', $contribution->payment_method)) }}</td>
                </tr>
                <tr>
                    <td class="label">Status:</td>
                    <td class="value">
                        <span class="{{ $contribution->is_verified ? 'status-verified' : 'status-pending' }}">
                            {{ $contribution->is_verified ? 'VERIFIED' : 'PENDING VERIFICATION' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="amount-box">
            <div class="amount-label">Total Amount Paid</div>
            <div class="amount-value">TZS {{ number_format($contribution->amount, 0) }}</div>
        </div>

        @if($contribution->notes)
        <div style="margin-bottom: 30px; font-size: 12px;">
            <div class="label" style="margin-bottom: 5px;">Notes:</div>
            <div style="padding: 10px; background: #f9fafb; border: 1px solid #eee; border-radius: 4px;">
                {{ $contribution->notes }}
            </div>
        </div>
        @endif

        <div style="margin-top: 60px; display: table; width: 100%;">
            <div style="display: table-cell; width: 50%; text-align: center;">
                <div style="border-top: 1px solid #333; width: 150px; margin: 0 auto;"></div>
                <div style="font-size: 10px; margin-top: 5px;">Receiver's Signature</div>
            </div>
            <div style="display: table-cell; width: 50%; text-align: center;">
                <div style="border-top: 1px solid #333; width: 150px; margin: 0 auto;"></div>
                <div style="font-size: 10px; margin-top: 5px;">Member's Signature</div>
            </div>
        </div>

        <div class="footer">
            Thank you for your contribution to TMCS Smart Church.<br>
            This is a computer-generated receipt. No signature is required for digital verification.<br>
            www.tmcssmart.org | info@tmcssmart.org
        </div>
    </div>
</body>
</html>

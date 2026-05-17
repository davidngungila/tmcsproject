<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Balance Sheet - {{ $asOfDate }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 0; }
        .container { width: 100%; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #1e293b; padding-bottom: 15px; margin-bottom: 30px; }
        .logo { font-size: 20px; font-weight: 900; color: #1e293b; text-transform: uppercase; }
        .sub-header { font-size: 10px; color: #475569; font-weight: bold; margin-top: 2px; text-transform: uppercase; }
        .report-title { font-size: 16px; margin-top: 10px; color: #111; font-weight: 900; background: #f1f5f9; padding: 8px 20px; display: inline-block; border-radius: 4px; }
        
        .section-title { background: #f8fafc; padding: 8px 12px; font-size: 11px; font-weight: 900; color: #1e293b; border-bottom: 1px solid #e2e8f0; margin-top: 25px; margin-bottom: 15px; text-transform: uppercase; }
        
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table td { padding: 8px 12px; font-size: 11px; border-bottom: 1px solid #f1f5f9; }
        .label { color: #475569; font-weight: 500; }
        .amount { text-align: right; font-family: 'Courier', monospace; font-weight: bold; color: #1e293b; }
        .total-row td { font-weight: 900; border-top: 2px solid #1e293b; border-bottom: 4px double #1e293b; padding-top: 10px; padding-bottom: 10px; background: #f8fafc; }
        
        .footer { margin-top: 60px; text-align: center; font-size: 9px; color: #64748b; border-top: 1px dashed #e2e8f0; padding-top: 20px; }
        .signature-section { margin-top: 50px; width: 100%; }
        .sig-box { text-align: center; width: 50%; }
        .sig-line { border-top: 1px solid #94a3b8; width: 180px; margin: 0 auto 8px; }
        .sig-text { font-size: 10px; font-weight: bold; color: #475569; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">ST. JOSEPH THE WORKER CHAPLAINCY</div>
            <div class="sub-header">CATHOLIC COMMUNITY OF MOSHI CO-OPERATIVE UNIVERSITY</div>
            <div class="report-title">STATEMENT OF FINANCIAL POSITION</div>
            <div style="font-size: 11px; margin-top: 10px; font-weight: bold; color: #64748b;">AS AT {{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}</div>
        </div>

        <div class="section-title">Assets</div>
        <table class="data-table">
            @foreach($assetAccounts as $account)
            <tr>
                <td class="label">{{ $account->name }} ({{ $account->code }})</td>
                <td class="amount">{{ number_format($account->current_balance, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td style="text-transform: uppercase;">Total Assets</td>
                <td class="amount">TZS {{ number_format($totalAssets, 2) }}</td>
            </tr>
        </table>

        <div class="section-title">Liabilities</div>
        <table class="data-table">
            @foreach($liabilityAccounts as $account)
            <tr>
                <td class="label">{{ $account->name }} ({{ $account->code }})</td>
                <td class="amount">{{ number_format($account->current_balance, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td style="text-transform: uppercase;">Total Liabilities</td>
                <td class="amount">TZS {{ number_format($totalLiabilities, 2) }}</td>
            </tr>
        </table>

        <div class="section-title">Equity & Retained Earnings</div>
        <table class="data-table">
            @foreach($equityAccounts as $account)
            <tr>
                <td class="label">{{ $account->name }} ({{ $account->code }})</td>
                <td class="amount">{{ number_format($account->current_balance, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td style="text-transform: uppercase;">Total Equity</td>
                <td class="amount">TZS {{ number_format($totalEquity, 2) }}</td>
            </tr>
        </table>

        <div style="margin-top: 30px; padding: 15px; background: #1e293b; color: white; border-radius: 6px;">
            <span style="font-size: 11px; font-weight: 900; text-transform: uppercase;">Total Liabilities & Equity</span>
            <span style="float: right; font-size: 14px; font-weight: 900; font-family: 'Courier', monospace;">TZS {{ number_format($totalLiabilities + $totalEquity, 2) }}</span>
            <div style="clear: both;"></div>
        </div>

        <table class="signature-section">
            <tr>
                <td class="sig-box">
                    <div class="sig-line"></div>
                    <div class="sig-text">Finance Secretary</div>
                </td>
                <td class="sig-box">
                    <div class="sig-line"></div>
                    <div class="sig-text">Church Treasurer</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <strong>TMCS SMART FINANCIAL MANAGEMENT SYSTEM</strong><br>
            Generated on {{ date('l, d F Y H:i:s') }}<br>
            <div style="margin-top: 8px;">This document balances assets against liabilities and equity using double-entry principles.</div>
        </div>
    </div>
</body>
</html>

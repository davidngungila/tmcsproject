<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Income Statement - {{ $year }}</title>
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
        .total-row td { font-weight: 900; border-top: 2px solid #1e293b; border-bottom: 4px double #1e293b; padding-top: 10px; padding-bottom: 10px; }
        
        .net-income-box { margin-top: 40px; padding: 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; }
        .net-income-label { font-size: 12px; font-weight: 900; color: #1e293b; }
        .net-income-value { float: right; font-size: 18px; font-weight: 900; }
        .surplus { color: #16a34a; }
        .deficit { color: #dc2626; }

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
            <div class="report-title">INCOME STATEMENT (PROFIT & LOSS)</div>
            <div style="font-size: 11px; margin-top: 10px; font-weight: bold; color: #64748b;">FOR THE FINANCIAL YEAR ENDED DECEMBER 31, {{ $year }}</div>
        </div>

        <div class="section-title">Revenue & Income</div>
        <table class="data-table">
            @foreach($revenueAccounts as $account)
            <tr>
                <td class="label">{{ $account->name }} ({{ $account->code }})</td>
                <td class="amount">{{ number_format($account->period_balance, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td style="text-transform: uppercase;">Total Operating Revenue</td>
                <td class="amount">TZS {{ number_format($totalRevenue, 2) }}</td>
            </tr>
        </table>

        <div class="section-title">Operating Expenses</div>
        <table class="data-table">
            @foreach($expenseAccounts as $account)
            <tr>
                <td class="label">{{ $account->name }} ({{ $account->code }})</td>
                <td class="amount">({{ number_format($account->period_balance, 2) }})</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td style="text-transform: uppercase;">Total Operating Expenses</td>
                <td class="amount" style="color: #dc2626;">(TZS {{ number_format($totalExpenses, 2) }})</td>
            </tr>
        </table>

        <div class="net-income-box">
            <span class="net-income-label">NET SURPLUS / (DEFICIT) FOR THE YEAR</span>
            <span class="net-income-value {{ $netIncome >= 0 ? 'surplus' : 'deficit' }}">
                {{ $netIncome < 0 ? '(' . number_format(abs($netIncome), 2) . ')' : number_format($netIncome, 2) }} TZS
            </span>
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
            <div style="margin-top: 8px;">This is an official financial document of St. Joseph the Worker Chaplaincy.</div>
        </div>
    </div>
</body>
</html>

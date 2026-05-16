<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; }
        .header { background-color: #1a56db; color: #ffffff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; background-color: #ffffff; }
        .footer { background-color: #f9fafb; color: #6b7280; padding: 20px; text-align: center; font-size: 12px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #1a56db; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .details { margin: 20px 0; padding: 20px; background-color: #f3f4f6; border-radius: 6px; }
        .details-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .details-label { font-weight: bold; color: #4b5563; }
        .details-value { color: #111827; }
        .amount { font-size: 20px; color: #059669; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Contribution Received</h1>
        </div>
        <div class="content">
            <p>Dear {{ $contribution->member->full_name }},</p>
            <p>Thank you for your generous contribution to the TMCS community. Your support helps us continue our mission and serve our members better.</p>
            
            <div class="details">
                <div class="details-row">
                    <span class="details-label">Receipt Number:</span>
                    <span class="details-value">{{ $contribution->receipt_number }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Date:</span>
                    <span class="details-value">{{ \Carbon\Carbon::parse($contribution->contribution_date)->format('M d, Y') }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Contribution Type:</span>
                    <span class="details-value">{{ ucfirst(str_replace('_', ' ', $contribution->contribution_type)) }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Amount Paid:</span>
                    <span class="details-value amount">TZS {{ number_format($contribution->amount, 0) }}</span>
                </div>
            </div>

            <p>We have attached a PDF copy of your official receipt for your records.</p>
            
            <p>If you have any questions regarding this contribution, please don't hesitate to contact our finance office.</p>
            
            <p>God bless you!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} TMCS SMART. All rights reserved.</p>
            <p>This is an automated message, please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>

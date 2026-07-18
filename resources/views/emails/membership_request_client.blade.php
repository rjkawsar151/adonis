<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>VIP Experience Invitation Received - Adonis</title>
    <style>
        body {
            background-color: #0B0B0B;
            color: #E2E8F0;
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #141414;
            border: 1px solid rgba(201, 168, 76, 0.35);
            padding: 40px;
            position: relative;
        }
        .header {
            text-align: center;
            border-bottom: 1px solid rgba(201, 168, 76, 0.1);
            padding-bottom: 25px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 0.2em;
            color: #ffffff;
            text-transform: uppercase;
        }
        .logo span {
            color: #C9A84C;
        }
        .crown-icon {
            text-align: center;
            color: #C9A84C;
            font-size: 32px;
            margin-bottom: 15px;
        }
        h2 {
            font-size: 18px;
            color: #ffffff;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 0.15em;
            margin-bottom: 25px;
        }
        .welcome-text {
            font-size: 14px;
            line-height: 1.8;
            color: #d1d5db;
            text-align: justify;
            margin-bottom: 30px;
        }
        .card-preview {
            background: linear-gradient(135deg, #0e1117 0%, #1f2937 100%);
            border: 1px solid rgba(201, 168, 76, 0.4);
            padding: 25px;
            margin: 30px 0;
            text-align: left;
            position: relative;
        }
        .card-preview .title {
            font-size: 10px;
            font-family: monospace;
            color: rgba(201, 168, 76, 0.6);
            letter-spacing: 0.2em;
            margin-bottom: 5px;
        }
        .card-preview .card-name {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.1em;
            color: #ffffff;
            text-transform: uppercase;
        }
        .card-preview .details {
            margin-top: 30px;
            font-size: 11px;
            color: #9ca3af;
            letter-spacing: 0.05em;
        }
        .card-preview .details span {
            color: #ffffff;
            font-weight: bold;
        }
        .card-preview .watermark {
            position: absolute;
            right: 20px;
            bottom: 15px;
            font-size: 40px;
            color: rgba(201, 168, 76, 0.05);
            font-weight: bold;
        }
        .next-steps {
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.6;
            margin-bottom: 30px;
            border-top: 1px solid rgba(255,255,255,0.05);
            padding-top: 20px;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #718096;
            letter-spacing: 0.1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Adonis <span>VIP CLUB</span></div>
        </div>
        
        <div class="crown-icon">👑</div>
        <h2>Invitation Pending Review</h2>
        
        <p class="welcome-text">
            Dear {{ $request->name }},<br><br>
            Thank you for your interest in joining the Adonis VIP Experience. We have received your invitation request and your priority credentials are currently pending verification by our concierge team.
        </p>
        
        <div class="card-preview">
            <div class="title">MEMBERSHIP CARD</div>
            <div class="card-name">ADONIS VIP GOLD</div>
            <div class="details">
                GUEST REGISTRY: <span>{{ $request->name }}</span><br>
                CONTACT PHONE: <span>{{ $request->phone }}</span><br>
                STATUS: <span style="color: #C9A84C;">PENDING VERIFICATION</span>
            </div>
            <div class="watermark">ADONIS</div>
        </div>
        
        <div class="next-steps">
            <strong>What happens next?</strong><br>
            Formal invitations are reviewed and issued within 48 business hours. If approved, you will receive your personal membership number along with scheduling credentials for priority bookings.
        </div>
        
        <div class="footer">
            ADONIS MEN'S GROOMING SALON • LUXURY SERVICES REDEFINED
        </div>
    </div>
</body>
</html>

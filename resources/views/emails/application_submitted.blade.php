<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Received - Adonis Men's Grooming</title>
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
            border: 1px solid rgba(50, 187, 237, 0.2);
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 1px solid rgba(50, 187, 237, 0.1);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 0.15em;
            color: #ffffff;
            text-transform: uppercase;
        }
        .logo span {
            color: #32BBED;
        }
        h2 {
            font-size: 18px;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 20px;
        }
        p {
            font-size: 13px;
            line-height: 1.6;
            color: #A0AEC0;
            margin-bottom: 20px;
        }
        .ref-box {
            background-color: #1C1C1C;
            border-left: 3px solid #32BBED;
            padding: 15px;
            margin: 25px 0;
            font-family: monospace;
            font-size: 14px;
            color: #ffffff;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Adonis <span>Grooming</span></div>
        </div>
        
        <h2>Gentleman Application Acknowledged</h2>
        
        <p>Dear {{ $application->full_name }},</p>
        
        <p>Thank you for submitting your credentials for the position of <strong>{{ $application->career->title }}</strong> at Adonis Men's Grooming Salon, Dhaka.</p>
        
        <p>Our talent acquisition deck has received your application. Your unique reference registry ID is:</p>
        
        <div class="ref-box">
            APPLICATION REGISTRY ID: {{ $application->reference_number }}
        </div>
        
        <p>We review portfolios and applications based on creative alignment, technical symmetry capabilities, and professional hospitality track record. Should your profile match our requirements, a member of our concierge operations will contact you directly to schedule an interview.</p>
        
        <p>Kind regards,</p>
        <p><strong>Concierge Operations</strong><br>Adonis Men's Grooming Salon</p>
        
        <div class="footer">
            &copy; 2026 Adonis Men's Grooming Salon. All rights reserved.<br>
            Gulshan Avenue, Dhaka, Bangladesh.
        </div>
    </div>
</body>
</html>

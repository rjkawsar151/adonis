<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Job Application Received - Adonis</title>
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
            font-size: 16px;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 20px;
            border-left: 3px solid #32BBED;
            padding-left: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        th {
            color: #718096;
            width: 30%;
        }
        td {
            color: #E2E8F0;
        }
        .button {
            display: inline-block;
            background-color: #32BBED;
            color: #000000;
            text-decoration: none;
            padding: 12px 25px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 20px;
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
            <div class="logo">Adonis <span>Concierge</span></div>
        </div>
        
        <h2>New Application Dispatch</h2>
        
        <p>A new applicant has registered interest for a job opening.</p>
        
        <table>
            <tr>
                <th>Job Title</th>
                <td><strong>{{ $application->career->title }}</strong></td>
            </tr>
            <tr>
                <th>Candidate Name</th>
                <td>{{ $application->full_name }}</td>
            </tr>
            <tr>
                <th>Email Address</th>
                <td>{{ $application->email }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{ $application->phone }}</td>
            </tr>
            <tr>
                <th>Reference ID</th>
                <td><code>{{ $application->reference_number }}</code></td>
            </tr>
            <tr>
                <th>Submission Time</th>
                <td>{{ $application->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>
        
        <p>Log in to the Adonis Administration Desk to review the complete profile details, custom question answers, and download their resume.</p>
        
        <a href="{{ url('/admin/careers/applications/' . $application->id) }}" class="button">Review Application</a>
        
        <div class="footer">
            Adonis Men's Grooming Lounge Admin Dispatcher System.
        </div>
    </div>
</body>
</html>

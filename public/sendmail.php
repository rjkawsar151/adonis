<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$isTest = $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['test']);

if ($isTest) {
    $methods = [];
    if (function_exists('mail')) $methods[] = 'mail';
    if (function_exists('stream_socket_client')) $methods[] = 'smtp';
    echo json_encode([
        'success' => true,
        'php' => phpversion(),
        'methods' => $methods,
        'sendmail_path' => ini_get('sendmail_path')
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Load configurations from .env if available
$env = [];
$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    $envPath = __DIR__ . '/.env';
}
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);
            // Strip quotes
            $val = trim($val, "\"'");
            $env[$key] = $val;
        }
    }
}

$smtpHost = $env['SMTP_HOST'] ?? 'mail.adonis.com.bd';
$smtpPort = isset($env['SMTP_PORT']) ? (int)$env['SMTP_PORT'] : 465;
$smtpUser = $env['SMTP_USER'] ?? 'book@adonis.com.bd';
$smtpPass = $env['SMTP_PASS'] ?? 'O[^1G=reL9EXLz[S';
$fromEmail = $env['SMTP_FROM_EMAIL'] ?? 'book@adonis.com.bd';
$fromName = 'Adonis Grooming';

$adminEmailsRaw = $env['SMTP_ADMIN_EMAILS'] ?? 'info@adonis.com.bd,booking@adonis.com.bd,itdepartmnet.adonis@gmail.com,kawsarhosen.dev@gmail.com';
$adminEmails = array_filter(array_map('trim', explode(',', $adminEmailsRaw)));


function smtpSend($to, $subject, $htmlBody, $fromEmail, $fromName, $smtpHost, $smtpPort, $smtpUser, $smtpPass) {
    $errno = 0;
    $errstr = '';

    $protocol = ($smtpPort == 465) ? 'ssl://' : '';
    $socket = @stream_socket_client(
        $protocol . $smtpHost . ':' . $smtpPort,
        $errno,
        $errstr,
        15
    );

    if (!$socket) {
        throw new Exception("SMTP connection failed: $errstr ($errno)");
    }

    $response = fgets($socket, 512);

    smtpCommand($socket, "EHLO adonis-smtp-relay");

    smtpCommand($socket, "AUTH LOGIN");
    smtpCommand($socket, base64_encode($smtpUser));
    smtpCommand($socket, base64_encode($smtpPass));

    smtpCommand($socket, "MAIL FROM:<$fromEmail>");

    $recipients = is_array($to) ? $to : [$to];
    foreach ($recipients as $recipient) {
        smtpCommand($socket, "RCPT TO:<$recipient>");
    }

    smtpCommand($socket, "DATA");

    $headers = "From: $fromName <$fromEmail>\r\n";
    $headers .= "Reply-To: $fromEmail\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: Adonis-PHP-Mailer\r\n";

    $message = $headers . "\r\n" . $htmlBody;

    fwrite($socket, $message . "\r\n.\r\n");
    $response = '';
    while ($line = fgets($socket, 512)) {
        $response .= $line;
        if (substr($line, 3, 1) !== '-') break;
    }

    smtpCommand($socket, "QUIT");
    fclose($socket);

    return true;
}

function smtpCommand($socket, $command) {
    fwrite($socket, $command . "\r\n");
    $response = '';
    while ($line = fgets($socket, 512)) {
        $response .= $line;
        if (substr($line, 3, 1) !== '-') break;
    }
    $code = (int)substr($response, 0, 3);

    if ($code >= 200 && $code < 400) {
        return $response;
    }

    throw new Exception("SMTP error: " . trim($response));
}

function mailSend($to, $subject, $htmlBody, $fromEmail, $fromName) {
    $headers = "From: $fromName <$fromEmail>\r\n";
    $headers .= "Reply-To: $fromEmail\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: Adonis-PHP-Mailer\r\n";

    $recipients = is_array($to) ? implode(', ', $to) : $to;

    $success = mail($recipients, $subject, $htmlBody, $headers, "-f $fromEmail");

    if (!$success) {
        throw new Exception("PHP mail() function returned false");
    }

    return true;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || empty($input['clientName']) || empty($input['clientPhone']) || empty($input['bookingCode'])) {
        echo json_encode(['success' => false, 'error' => 'Missing required booking fields.']);
        exit;
    }

    $branchName = $input['branchName'] ?? ($input['branchId'] ?? 'Adonis Grooming');
    $serviceName = $input['serviceName'] ?? 'General Appointment';
    $formattedDate = !empty($input['date']) ? date('l, F d, Y', strtotime($input['date'])) : 'Not specified';
    $notes = $input['notes'] ?? '';
    $formattedTime = date('h:i A', strtotime($input['time']));

    $adminSubject = '[New Booking] ' . $input['bookingCode'] . ' - ' . $input['clientName'];
    $esc = function($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); };

    $adminHtml = '
    <div style="background-color:#121212;color:#fff;font-family:Arial,sans-serif;padding:30px;border-left:4px solid #32BBED;max-width:600px;margin:0 auto">
        <h3 style="color:#32BBED;margin-top:0;margin-bottom:20px;font-weight:normal;letter-spacing:1px">NEW BOOKING SECURED</h3>
        <p style="font-size:13px;color:#ccc">A new appointment has been scheduled through the Adonis website.</p>
        <table style="width:100%;border-collapse:collapse;margin-top:15px;font-size:13px;color:#ccc">
            <tr style="border-bottom:1px solid #222">
                <td style="padding:8px 0;color:#888;width:40%">Client Name:</td>
                <td style="padding:8px 0;font-weight:bold;color:#fff">' . $esc($input['clientName']) . '</td>
            </tr>
            <tr style="border-bottom:1px solid #222">
                <td style="padding:8px 0;color:#888">Client Phone:</td>
                <td style="padding:8px 0;color:#fff">' . $esc($input['clientPhone']) . '</td>
            </tr>
            <tr style="border-bottom:1px solid #222">
                <td style="padding:8px 0;color:#888">Client Email:</td>
                <td style="padding:8px 0;color:#fff">' . $esc($input['clientEmail'] ?? 'Not provided') . '</td>
            </tr>
            <tr style="border-bottom:1px solid #222">
                <td style="padding:8px 0;color:#888">Booking Code:</td>
                <td style="padding:8px 0;font-weight:bold;color:#32BBED">' . $esc($input['bookingCode']) . '</td>
            </tr>
            <tr style="border-bottom:1px solid #222">
                <td style="padding:8px 0;color:#888">Lounge Branch:</td>
                <td style="padding:8px 0;color:#fff">' . $esc($branchName) . '</td>
            </tr>
            <tr style="border-bottom:1px solid #222">
                <td style="padding:8px 0;color:#888">Service:</td>
                <td style="padding:8px 0;color:#fff">' . $esc($serviceName) . '</td>
            </tr>
            <tr style="border-bottom:1px solid #222">
                <td style="padding:8px 0;color:#888">Date:</td>
                <td style="padding:8px 0;color:#fff">' . $formattedDate . '</td>
            </tr>
            <tr style="border-bottom:1px solid #222">
                <td style="padding:8px 0;color:#888">Time:</td>
                <td style="padding:8px 0;font-weight:bold;color:#32BBED">' . $formattedTime . '</td>
            </tr>';

    if (!empty($notes)) {
        $adminHtml .= '
            <tr style="border-bottom:1px solid #222">
                <td style="padding:8px 0;color:#888;vertical-align:top">Special Instructions:</td>
                <td style="padding:8px 0;font-style:italic;color:#999">"' . $esc($notes) . '"</td>
            </tr>';
    }

    $adminHtml .= '
        </table>
        <p style="font-size:11px;color:#666;margin-top:25px;margin-bottom:0">
            This booking was submitted via the Adonis website.
        </p>
    </div>';

    $method = 'none';
    $error = null;

    // 1. Try SMTP first if credentials are set
    if ($smtpHost && $smtpUser && $smtpPass) {
        try {
            smtpSend($adminEmails, $adminSubject, $adminHtml, $fromEmail, $fromName, $smtpHost, $smtpPort, $smtpUser, $smtpPass);
            $method = 'smtp';
        } catch (Exception $e) {
            $error = 'smtp: ' . $e->getMessage();
        }
    }

    // 2. Fall back to PHP mail() if SMTP was not used or failed
    if ($method === 'none' && function_exists('mail')) {
        try {
            mailSend($adminEmails, $adminSubject, $adminHtml, $fromEmail, $fromName);
            $method = 'mail';
        } catch (Exception $e) {
            $error = ($error ? "$error | " : '') . 'mail(): ' . $e->getMessage();
        }
    }

    if ($method === 'none') {
        throw new Exception("All sending methods failed: $error");
    }

    $clientSent = false;
    if (!empty($input['clientEmail'])) {
        $clientSubject = 'Grooming Session Confirmed: ' . $input['bookingCode'];
        $clientHtml = '
        <div style="background-color:#0b0b0b;color:#fff;font-family:Georgia,serif;padding:40px;border:1px solid #32BBED;max-width:600px;margin:0 auto">
            <div style="text-align:center;margin-bottom:30px">
                <h2 style="color:#32BBED;text-transform:uppercase;letter-spacing:4px;font-weight:normal;margin:0;font-size:24px">ADONIS</h2>
                <p style="color:#888;font-size:10px;text-transform:uppercase;letter-spacing:2px;margin-top:5px">Premium Grooming Lounge</p>
            </div>
            <div style="border-top:1px solid rgba(200,162,74,0.2);border-bottom:1px solid rgba(200,162,74,0.2);padding:25px 0;margin-bottom:25px">
                <p style="margin:0 0 15px 0;font-size:14px">Dear <strong>' . $esc($input['clientName']) . '</strong>,</p>
                <p style="color:#ccc;font-size:13px;line-height:1.6;margin:0 0 20px 0">Your premium styling bay has been reserved. Here are your booking details:</p>
                <table style="width:100%;font-size:13px;color:#ccc">
                    <tr>
                        <td style="padding:6px 0;color:#32BBED;width:40%">CONFIRMATION CODE:</td>
                        <td style="padding:6px 0;font-weight:bold;color:#fff">' . $esc($input['bookingCode']) . '</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#32BBED">LOUNGE LOCATION:</td>
                        <td style="padding:6px 0;color:#fff">' . $esc($branchName) . '</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#32BBED">SERVICE SELECT:</td>
                        <td style="padding:6px 0;color:#fff">' . $esc($serviceName) . '</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#32BBED">DATE SECURED:</td>
                        <td style="padding:6px 0;color:#fff">' . $formattedDate . '</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#32BBED">APPOINTMENT TIME:</td>
                        <td style="padding:6px 0;color:#fff;font-weight:bold">' . $formattedTime . '</td>
                    </tr>';

        if (!empty($notes)) {
            $clientHtml .= '
                    <tr>
                        <td style="padding:6px 0;color:#32BBED;vertical-align:top">SPECIAL NOTES:</td>
                        <td style="padding:6px 0;color:#999;font-style:italic">"' . $esc($notes) . '"</td>
                    </tr>';
        }

        $clientHtml .= '
                </table>
            </div>
            <p style="color:#888;font-size:11px;line-height:1.6;text-align:center;margin:0">
                Please present this email at the desk upon arrival.<br/>Thank you for choosing Adonis.
            </p>
        </div>';

        if ($method === 'mail') {
            try {
                mailSend($input['clientEmail'], $clientSubject, $clientHtml, $fromEmail, $fromName);
                $clientSent = true;
            } catch (Exception $e) {}
        } elseif ($method === 'smtp') {
            try {
                smtpSend($input['clientEmail'], $clientSubject, $clientHtml, $fromEmail, $fromName, $smtpHost, $smtpPort, $smtpUser, $smtpPass);
                $clientSent = true;
            } catch (Exception $e) {}
        }
    }

    echo json_encode([
        'success' => true,
        'method' => $method,
        'message' => 'Booking notification sent successfully.',
        'adminSent' => true,
        'clientSent' => $clientSent
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

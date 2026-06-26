<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdonisController extends Controller
{
    private array $defaultSettings = [
        'brandName' => 'ADONIS',
        'brandSubtitle' => 'Premium Grooming. Redefined Masculinity.',
        'heroTitle' => 'Craft Your Identity With Precision',
        'heroSubtitle' => "Experience elite barbering at Adonis Men's Grooming, where modern style meets timeless perfection in the heart of Dhaka.",
        'heroBg' => '/assets/images/adonis_executive_lounge_1779270704894.png',
        'aboutStory' => "Adonis Men's Grooming is a premium barbershop brand in Dhaka dedicated to redefining modern masculinity through precision grooming, luxury service, and personalized styling.",
        'aboutDescription' => "We believe that grooming is a curated ritual of premium transition. Adonis pairs classic barber heritage with high-end lounge accommodations.",
        'contactEmail' => 'info@adonis.com.bd',
        'openHoursDays' => 'Everyday (Sat - Fri)',
        'openHoursTime' => '10:00 AM - 10:00 PM',
        'phoneNumbers' => ['+880 1919-700800', '+880 1700-600333'],
        'facebookUrl' => 'https://facebook.com/adonis.bd',
        'instagramUrl' => 'https://instagram.com/adonis.grooming',
        'whatsappUrl' => 'https://wa.me/8801919700800',
    ];

    private array $defaultSmtp = [
        'host' => '',
        'port' => 587,
        'secure' => false,
        'user' => '',
        'pass' => '',
        'fromEmail' => 'noreply@adonis.com.bd',
        'adminEmails' => 'admin@adonis.com.bd',
    ];

    public function data()
    {
        if (!$this->schemaReady()) {
            return response()->json($this->fallbackData());
        }

        return response()->json([
            'services' => DB::table('services')->orderBy('id')->get()->map(fn ($row) => [
                'id' => $row->id,
                'name' => $row->name,
                'description' => $row->description,
                'durationMin' => (int) $row->durationMin,
                'priceBDT' => (int) $row->priceBDT,
                'category' => $row->category,
                'icon' => $row->icon,
            ])->values(),
            'barbers' => DB::table('barbers')->orderBy('id')->get()->map(fn ($row) => [
                'id' => $row->id,
                'name' => $row->name,
                'experienceYears' => (int) $row->experienceYears,
                'specialty' => $row->specialty,
                'portraitUrl' => $row->portraitUrl,
                'bio' => $row->bio,
                'rating' => (float) $row->rating,
            ])->values(),
            'settings' => $this->meta('settings', $this->defaultSettings),
            'smtp' => $this->publicSmtp(),
            'blogs' => DB::table('blogs')->orderByDesc('createdAt')->get()->map(fn ($row) => [
                'id' => $row->id,
                'slug' => $row->slug,
                'title' => $row->title,
                'excerpt' => $row->excerpt,
                'coverImage' => $row->coverImage,
                'contentHtml' => $row->contentHtml,
                'seoTitle' => $row->seoTitle,
                'seoDescription' => $row->seoDescription,
                'status' => $row->status,
                'createdAt' => $row->createdAt,
                'updatedAt' => $row->updatedAt,
            ])->values(),
            'bookings' => DB::table('bookings')->orderByDesc('createdAt')->limit(50)->get(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $settings = array_merge($this->defaultSettings, $request->all());
        $this->setMeta('settings', $settings);
        return response()->json(['success' => true, 'settings' => $settings]);
    }

    public function updateSmtp(Request $request)
    {
        $smtp = array_merge($this->defaultSmtp, $request->all());
        $this->setMeta('smtp', $smtp);
        return response()->json(['success' => true, 'smtp' => $this->withoutSecret($smtp)]);
    }

    public function storeService(Request $request)
    {
        $service = $request->all();
        $service['id'] = $service['id'] ?? Str::slug($service['name'] ?? 'service');
        DB::table('services')->updateOrInsert(['id' => $service['id']], [
            'name' => $service['name'] ?? 'Untitled Service',
            'description' => $service['description'] ?? '',
            'durationMin' => (int) ($service['durationMin'] ?? 45),
            'priceBDT' => (int) ($service['priceBDT'] ?? 0),
            'category' => $service['category'] ?? 'hair',
            'icon' => $service['icon'] ?? 'Scissors',
        ]);
        return response()->json(['success' => true, 'service' => $service]);
    }

    public function updateService(Request $request, string $id)
    {
        $service = DB::table('services')->where('id', $id)->first();
        if (!$service) return response()->json(['error' => 'Service not found'], 404);
        $data = array_merge((array) $service, $request->all());
        DB::table('services')->where('id', $id)->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'durationMin' => (int) $data['durationMin'],
            'priceBDT' => (int) $data['priceBDT'],
            'category' => $data['category'],
            'icon' => $data['icon'],
        ]);
        return response()->json(['success' => true, 'service' => $data]);
    }

    public function deleteService(string $id)
    {
        DB::table('services')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function storeBarber(Request $request)
    {
        $barber = $request->all();
        $barber['id'] = $barber['id'] ?? Str::slug($barber['name'] ?? 'barber');
        DB::table('barbers')->updateOrInsert(['id' => $barber['id']], [
            'name' => $barber['name'] ?? 'Untitled Barber',
            'experienceYears' => (int) ($barber['experienceYears'] ?? 0),
            'specialty' => $barber['specialty'] ?? '',
            'portraitUrl' => $barber['portraitUrl'] ?? '',
            'bio' => $barber['bio'] ?? '',
            'rating' => (float) ($barber['rating'] ?? 5),
        ]);
        return response()->json(['success' => true, 'barber' => $barber]);
    }

    public function updateBarber(Request $request, string $id)
    {
        $barber = DB::table('barbers')->where('id', $id)->first();
        if (!$barber) return response()->json(['error' => 'Barber not found'], 404);
        $data = array_merge((array) $barber, $request->all());
        DB::table('barbers')->where('id', $id)->update([
            'name' => $data['name'],
            'experienceYears' => (int) $data['experienceYears'],
            'specialty' => $data['specialty'] ?? '',
            'portraitUrl' => $data['portraitUrl'] ?? '',
            'bio' => $data['bio'] ?? '',
            'rating' => (float) $data['rating'],
        ]);
        return response()->json(['success' => true, 'barber' => $data]);
    }

    public function deleteBarber(string $id)
    {
        DB::table('barbers')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function blogs()
    {
        return response()->json(DB::table('blogs')->orderByDesc('createdAt')->get());
    }

    public function storeBlog(Request $request)
    {
        $blog = $this->normalizeBlog($request->all());
        DB::table('blogs')->insert($blog);
        return response()->json(['success' => true, 'blog' => $blog]);
    }

    public function updateBlog(Request $request, string $id)
    {
        $blog = DB::table('blogs')->where('id', $id)->first();
        if (!$blog) return response()->json(['error' => 'Blog not found'], 404);
        $data = $this->normalizeBlog(array_merge((array) $blog, $request->all()), false);
        DB::table('blogs')->where('id', $id)->update($data);
        return response()->json(['success' => true, 'blog' => $data]);
    }

    public function deleteBlog(string $id)
    {
        DB::table('blogs')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function upload(Request $request)
    {
        if (!$request->hasFile('portrait')) {
            return response()->json(['success' => false, 'error' => 'No file uploaded'], 400);
        }
        $path = $request->file('portrait')->store('uploads', 'public_uploads');
        return response()->json(['success' => true, 'url' => url('/' . str_replace('\\', '/', $path))]);
    }

    public function storeBooking(Request $request)
    {
        $booking = $request->all();
        foreach (['clientName', 'clientPhone', 'branchId', 'date', 'time', 'bookingCode'] as $field) {
            if (empty($booking[$field])) {
                return response()->json(['success' => false, 'error' => 'Missing required booking details.'], 400);
            }
        }
        DB::table('bookings')->insert([
            'id' => $booking['id'] ?? (string) Str::uuid(),
            'clientName' => $booking['clientName'],
            'clientPhone' => $booking['clientPhone'],
            'clientEmail' => $booking['clientEmail'] ?? '',
            'branchId' => $booking['branchId'],
            'barberId' => $booking['barberId'] ?? '',
            'serviceId' => $booking['serviceId'] ?? '',
            'date' => $booking['date'],
            'time' => $booking['time'],
            'notes' => $booking['notes'] ?? '',
            'bookingCode' => $booking['bookingCode'],
            'createdAt' => now(),
        ]);

        DB::table('appointments')->insert([
            'service_id' => $booking['serviceId'] ?? '',
            'name' => $booking['clientName'],
            'phone' => $booking['clientPhone'],
            'email' => $booking['clientEmail'] ?? '',
            'preferred_date' => $booking['date'],
            'preferred_time' => $booking['time'],
            'note' => $booking['notes'] ?? '',
            'branch_id' => $booking['branchId'],
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $emailSent = $this->sendBookingMail($booking);
        \Illuminate\Support\Facades\Log::info('Booking stored, email sent: ' . ($emailSent ? 'yes' : 'no'));
        return response()->json(['success' => true, 'booking' => $booking, 'emailSent' => $emailSent]);
    }

    public function bookings()
    {
        return response()->json(DB::table('bookings')->orderByDesc('createdAt')->get());
    }

    public function smtpStatus()
    {
        $smtp = $this->runtimeSmtp();
        return response()->json([
            'configured' => !empty($smtp['host']) && !empty($smtp['user']) && !empty($smtp['pass']),
            'host' => $smtp['host'],
            'port' => $smtp['port'],
            'user' => $smtp['user'],
            'fromEmail' => $smtp['fromEmail'],
            'adminEmails' => $smtp['adminEmails'],
        ]);
    }

    public function testSmtp(Request $request)
    {
        $smtp = $this->runtimeSmtp();
        $this->configureMailer($smtp);
        $targets = $request->input('to') ? [$request->input('to')] : array_filter(array_map('trim', explode(',', $smtp['adminEmails'])));
        if (!$targets) $targets = [$smtp['fromEmail']];
        try {
            foreach ($targets as $email) {
                Mail::raw('Adonis SMTP test email from Laravel.', fn ($message) => $message->to($email)->subject('Adonis SMTP Test'));
            }
            return response()->json(['success' => true, 'message' => 'Test email sent to ' . implode(', ', $targets) . '.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
    }

    private function schemaReady(): bool
    {
        try {
            return Schema::hasTable('services') && Schema::hasTable('barbers') && Schema::hasTable('cms_meta') && Schema::hasTable('blogs');
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }

    private function meta(string $key, array $default): array
    {
        $raw = DB::table('cms_meta')->where('meta_key', $key)->value('meta_value');
        return $raw ? array_merge($default, json_decode($raw, true) ?: []) : $default;
    }

    private function setMeta(string $key, array $value): void
    {
        DB::table('cms_meta')->updateOrInsert(['meta_key' => $key], ['meta_value' => json_encode($value)]);
    }

    private function publicSmtp(): array
    {
        return $this->withoutSecret($this->runtimeSmtp());
    }

    private function runtimeSmtp(): array
    {
        // Priority: env > cms_meta > website_settings
        $cms = $this->meta('smtp', $this->defaultSmtp);

        $ws = null;
        try {
            $ws = DB::table('website_settings')->where('id', 1)->first();
        } catch (\Throwable) {
        }

        return [
            'host' => env('SMTP_HOST',
                $cms['host'] ?: ($ws->smtp_host ?? '')),
            'port' => (int) env('SMTP_PORT',
                $cms['port'] ?: ($ws->smtp_port ?? 587)),
            'secure' => filter_var(
                env('SMTP_SECURE', $cms['secure'] ?? false)
                    ?: ($ws->smtp_encryption === 'ssl' ? true : false),
                FILTER_VALIDATE_BOOLEAN),
            'user' => env('SMTP_USER',
                $cms['user'] ?: ($ws->smtp_username ?? '')),
            'pass' => env('SMTP_PASS',
                $cms['pass'] ?: ($ws->smtp_password ?? '')),
            'fromEmail' => env('SMTP_FROM_EMAIL',
                $cms['fromEmail'] ?: ($ws->smtp_mail_to ?? '')),
            'adminEmails' => env('SMTP_ADMIN_EMAILS',
                $cms['adminEmails'] ?: ($ws->notification_emails ?? '')),
        ];
    }

    private function withoutSecret(array $smtp): array
    {
        $smtp['pass'] = $smtp['pass'] ? '********' : '';
        return $smtp;
    }

    private function normalizeBlog(array $blog, bool $new = true): array
    {
        $title = $blog['title'] ?? 'Untitled Blog';
        $slug = $blog['slug'] ?? Str::slug($title);
        $now = now()->toDateTimeString();
        return [
            'id' => $blog['id'] ?? $slug,
            'slug' => $slug,
            'title' => $title,
            'excerpt' => $blog['excerpt'] ?? '',
            'coverImage' => $blog['coverImage'] ?? '',
            'contentHtml' => $blog['contentHtml'] ?? '',
            'seoTitle' => $blog['seoTitle'] ?? $title,
            'seoDescription' => $blog['seoDescription'] ?? ($blog['excerpt'] ?? ''),
            'status' => $blog['status'] ?? 'draft',
            'createdAt' => $blog['createdAt'] ?? $now,
            'updatedAt' => $new ? ($blog['updatedAt'] ?? $now) : $now,
        ];
    }

    private function sendBookingMail(array $booking): bool
    {
        try {
            $smtp = $this->runtimeSmtp();
            if (!$smtp['host'] || !$smtp['user'] || !$smtp['pass']) {
                \Illuminate\Support\Facades\Log::warning('Booking email skipped: SMTP not configured', [
                    'host' => $smtp['host'] ? 'set' : 'empty',
                    'user' => $smtp['user'] ? 'set' : 'empty',
                    'pass' => $smtp['pass'] ? 'set' : 'empty',
                ]);
                return false;
            }

            $this->configureMailer($smtp);

            $subject = '[New Booking] ' . $booking['bookingCode'] . ' - ' . $booking['clientName'];
            $body = "New booking from Adonis website.\n\n"
                . "Booking code: {$booking['bookingCode']}\n"
                . "Name: {$booking['clientName']}\n"
                . "Phone: {$booking['clientPhone']}\n"
                . "Email: " . ($booking['clientEmail'] ?? 'Not provided') . "\n"
                . "Branch: {$booking['branchId']}\n"
                . "Service: " . ($booking['serviceId'] ?? 'General appointment') . "\n"
                . "Date: {$booking['date']}\n"
                . "Time: {$booking['time']}\n"
                . "Notes: " . ($booking['notes'] ?? '');

            $adminSent = false;
            foreach (array_filter(array_map('trim', explode(',', $smtp['adminEmails']))) as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Mail::raw($body, fn ($message) => $message->to($email)->subject($subject));
                    $adminSent = true;
                }
            }

            if (!empty($booking['clientEmail'])) {
                Mail::raw("Your Adonis booking request has been received.\n\n" . $body, fn ($message) => $message->to($booking['clientEmail'])->subject('Adonis Booking Confirmed: ' . $booking['bookingCode']));
            }

            \Illuminate\Support\Facades\Log::info('Booking email sent successfully', [
                'bookingCode' => $booking['bookingCode'],
                'adminSent' => $adminSent,
                'clientEmail' => $booking['clientEmail'] ?? 'none',
            ]);
            return true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Booking email failed: ' . $e->getMessage(), [
                'bookingCode' => $booking['bookingCode'],
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    private function fallbackData(): array
    {
        return [
            'services'  => [],
            'barbers'   => [],
            'settings'  => $this->defaultSettings,
            'smtp'      => $this->withoutSecret($this->defaultSmtp),
            'blogs'     => [],
            'bookings'  => [],
        ];
    }

    private function configureMailer(array $smtp): void
    {
        // Purge cached transport so new config is used immediately
        Mail::purge('smtp');

        config([
            'mail.default'                 => 'smtp',
            'mail.mailers.smtp.host'       => $smtp['host'],
            'mail.mailers.smtp.port'       => $smtp['port'],
            'mail.mailers.smtp.encryption' => $smtp['secure'] ? 'ssl' : 'tls',
            'mail.mailers.smtp.username'   => $smtp['user'],
            'mail.mailers.smtp.password'   => $smtp['pass'],
            'mail.from.address'            => $smtp['fromEmail'] ?: $smtp['user'],
            'mail.from.name'               => 'Adonis Booking',
        ]);
    }
}

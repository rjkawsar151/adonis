<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\ImageCompressor;

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

    private function clearFrontendCache()
    {
        Cache::forget('adonis_frontend_data');
        Cache::forget('adonis_barbers');
        Cache::forget('adonis_blogs');
        Cache::forget('adonis_offers');
        Cache::forget('adonis_price_list_all');
        Cache::forget('adonis_price_list_gulshan');
        Cache::forget('adonis_price_list_bashundhara');
        Cache::forget('adonis_services_all');
        Cache::forget('adonis_services_gulshan');
        Cache::forget('adonis_services_bashundhara');
    }

    public function data()
    {
        if (!$this->schemaReady()) {
            return response()->json($this->fallbackData());
        }

        $cachedData = Cache::remember('adonis_frontend_data', 3600, function () {
            return [
                'services' => DB::table('services')->orderBy('id')->get()->map(fn ($row) => [
                    'id' => $row->id,
                    'name' => $row->name,
                    'description' => $row->description,
                    'durationMin' => (int) $row->durationMin,
                    'priceBDT' => (int) $row->priceBDT,
                    'category' => $row->category,
                    'icon' => $row->icon,
                ])->values()->all(),
                'barbers' => DB::table('barbers')->orderBy('id')->get()->map(fn ($row) => [
                    'id' => $row->id,
                    'name' => $row->name,
                    'experienceYears' => (int) $row->experienceYears,
                    'specialty' => $row->specialty,
                    'portraitUrl' => $row->portraitUrl,
                    'bio' => $row->bio,
                    'rating' => (float) $row->rating,
                ])->values()->all(),
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
                ])->values()->all(),
                'bookings' => DB::table('bookings')->orderByDesc('createdAt')->limit(50)->get()->all(),
            ];
        });

        return response()->json($cachedData);
    }

    public function updateSettings(Request $request)
    {
        $settings = array_merge($this->defaultSettings, $request->all());
        $this->setMeta('settings', $settings);
        $this->clearFrontendCache();
        return response()->json(['success' => true, 'settings' => $settings]);
    }

    public function siteSettings()
    {
        $settings = $this->schemaReady()
            ? $this->meta('settings', $this->defaultSettings)
            : $this->defaultSettings;

        return response()->json($settings)->withHeaders([
            'Cache-Control' => 'public, max-age=300, stale-while-revalidate=3600',
        ]);
    }

    public function updateSmtp(Request $request)
    {
        $smtp = array_merge($this->defaultSmtp, $request->all());
        $this->setMeta('smtp', $smtp);
        $this->clearFrontendCache();
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
        $this->clearFrontendCache();
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
        $this->clearFrontendCache();
        return response()->json(['success' => true, 'service' => $data]);
    }

    public function deleteService(string $id)
    {
        DB::table('services')->where('id', $id)->delete();
        $this->clearFrontendCache();
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
        $this->clearFrontendCache();
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
        $this->clearFrontendCache();
        return response()->json(['success' => true, 'barber' => $data]);
    }

    public function deleteBarber(string $id)
    {
        DB::table('barbers')->where('id', $id)->delete();
        $this->clearFrontendCache();
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
        $this->clearFrontendCache();
        return response()->json(['success' => true, 'blog' => $blog]);
    }

    public function updateBlog(Request $request, string $id)
    {
        $blog = DB::table('blogs')->where('id', $id)->first();
        if (!$blog) return response()->json(['error' => 'Blog not found'], 404);
        $data = $this->normalizeBlog(array_merge((array) $blog, $request->all()), false);
        DB::table('blogs')->where('id', $id)->update($data);
        $this->clearFrontendCache();
        return response()->json(['success' => true, 'blog' => $data]);
    }

    public function deleteBlog(string $id)
    {
        DB::table('blogs')->where('id', $id)->delete();
        $this->clearFrontendCache();
        return response()->json(['success' => true]);
    }

    public function upload(Request $request)
    {
        $file = $request->file('portrait') ?? $request->file('image') ?? $request->file('photo') ?? $request->file('file');
        if (!$file) {
            return response()->json(['success' => false, 'error' => 'No file uploaded'], 400);
        }
        $path = ImageCompressor::compressAndSaveWebp($file, 'uploads/barbers', 70);
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
        $this->clearFrontendCache();
        return response()->json(['success' => true, 'booking' => $booking, 'emailSent' => $emailSent]);
    }

    public function bookings()
    {
        return response()->json(DB::table('bookings')->orderByDesc('createdAt')->get());
    }

    public function storeMembership(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
        ]);

        $membership = \App\Models\MembershipRequest::create([
            'name'   => $request->name,
            'phone'  => $request->phone,
            'email'  => $request->email,
            'status' => 'pending',
        ]);

        // Send emails
        try {
            $smtp = $this->runtimeSmtp();
            if ($smtp['host'] && $smtp['user'] && $smtp['pass']) {
                $this->configureMailer($smtp);

                // 1. Send admin notification
                $adminEmails = array_unique(array_filter(array_map('trim', explode(',', $smtp['adminEmails']))));
                foreach ($adminEmails as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        if (strtolower($email) === strtolower($request->email)) {
                            continue;
                        }
                        Mail::to($email)->send(new \App\Mail\MembershipRequestAdminMail($membership));
                    }
                }

                // 2. Send client confirmation
                Mail::to($request->email)->send(new \App\Mail\MembershipRequestClientMail($membership));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Membership email failed: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'membership' => $membership]);
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

            $barberName = 'Not specified';
            if (!empty($booking['barberId'])) {
                $barberName = DB::table('barbers')->where('id', $booking['barberId'])->value('name') ?? $booking['barberId'];
            }

            $subject = '[New Booking] ' . $booking['bookingCode'] . ' - ' . $booking['clientName'];
            $body = "New booking from Adonis website.\n\n"
                . "Booking code: {$booking['bookingCode']}\n"
                . "Name: {$booking['clientName']}\n"
                . "Phone: {$booking['clientPhone']}\n"
                . "Email: " . ($booking['clientEmail'] ?? 'Not provided') . "\n"
                . "Branch: {$booking['branchId']}\n"
                . "Barber: {$barberName}\n"
                . "Service: " . ($booking['serviceId'] ?? 'General appointment') . "\n"
                . "Date: {$booking['date']}\n"
                . "Time: {$booking['time']}\n"
                . "Notes: " . ($booking['notes'] ?? '');

            $adminSent = false;
            $adminEmails = array_unique(array_filter(array_map('trim', explode(',', $smtp['adminEmails']))));
            $clientEmail = !empty($booking['clientEmail']) ? trim($booking['clientEmail']) : '';

            foreach ($adminEmails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if ($clientEmail !== '' && strtolower($email) === strtolower($clientEmail)) {
                        continue;
                    }
                    Mail::raw($body, fn ($message) => $message->to($email)->subject($subject));
                    $adminSent = true;
                }
            }

            if (!empty($booking['clientEmail'])) {
                $html = '';
                $templatePath = base_path('adonis-booking-confirmation.html');
                if (file_exists($templatePath)) {
                    $html = file_get_contents($templatePath);
                    
                    // Determine branch information
                    $branchId = strtolower($booking['branchId'] ?? 'gulshan');
                    if ($branchId === 'bashundhara') {
                        $branchName = 'Bashundhara Premium Lounge';
                        $branchAddress = 'Rahman Tower (Lift-4), Ka-1/B, Jagannathpur, Beside Hardco International School, Bashundhara, Dhaka';
                        $directionsUrl = 'https://www.google.com/maps?sca_esv=c0a979c20fc01ddf&biw=1920&bih=911&sxsrf=ANbL-n6rBQHc6On-WNWo1_ntWaHqkg8ONQ:1779702997846&gs_lp=Egxnd3Mtd2l6LXNlcnAiCWFkb25pcyBiYSoCCAAyEBAuGK8BGMcBGIAEGIoFGCcyCxAAGIAEGIoFGJECMgoQABiABBgUGIcCMgsQABiABBiKBRiRAjIQEC4YFBivARjHARiHAhiABDILEAAYgAQYigUYkQIyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMh0QLhivARjHARiABBiKBRiXBRjcBBjeBBjgBNgBAUiWFVAAWMwNcAF4AZABAJgBsQGgAckHqgEDMC42uAEDyAEA-AEBmAIHoALlB8ICBBAjGCfCAgoQIxiABBiKBRgnwgIQEC4YgAQYigUYxwEYrwEYJ8ICEBAAGIAEGIoFGEMYsQMYyQPCAgoQABiABBiKBRhDwgIOEC4YrwEYxwEYkgMYgATCAgUQLhiABMICCxAuGK8BGMcBGIAEmAMAugYGCAEQARgUkgcDMS42oAeVZbIHAzAuNrgH4gfCBwUwLjMuNMgHFYAIAQ&um=1&ie=UTF-8&fb=1&gl=bd&sa=X&geocode=KXuG98vmx1U3MY1A3OZhVIwb&daddr=Ka-1/B,+4th+Floor,+Rahman+Tower,+Main+Road,+Dhaka+1229';
                        $supportPhone = '+880 1720-080091';
                    } else {
                        // Default to Gulshan
                        $branchName = 'Gulshan Premium Lounge';
                        $branchAddress = 'Rupayan Golden Age (2nd Floor), Plot 99, Road 37, Block CWN (C), Gulshan Avenue, Dhaka 1212';
                        $directionsUrl = 'https://www.google.com/maps/dir//%E0%A6%85%E0%A7%8D%E0%A6%AF%E0%A6%BE%E0%A6%A1%E0%A7%8B%E0%A6%A8%E0%A6%BF%E0%A6%B8+%E0%A6%AE%E0%A7%87%E0%A6%A8%E0%A6%B8+%E0%A6%97%E0%A7%8D%E0%A6%B0%E0%A7%8B%E0%A6%AE%E0%A6%BF%E0%A6%82+%E0%A6%B8%E0%A7%8D%E0%A6%AF%E0%A6%BE%E0%A6%B2%E0%A6%A8+%7C+%E0%A6%AC%E0%A7%87%E0%A6%B7%E0%A7%8D%E0%A6%9F+%E0%A6%B8%E0%A7%8D%E0%A6%AF%E0%A6%BE%E0%A6%B2%E0%A6%A8+%E0%A6%87%E0%A6%A8+%E0%A6%97%E0%A7%81%E0%A6%B2%E0%A6%B6%E0%A6%BE%E0%A6%A8,+%E0%A6%A2%E0%A6%BE%E0%A6%95%E0%A6%BE,+Holding+-+99,+%E0%A6%B0%E0%A7%81%E0%A6%AA%E0%A6%BE%E0%A7%9F%E0%A6%A8+%E0%A6%97%E0%A7%8B%E0%A6%B2%E0%A7%8D%E0%A6%A1%E0%A7%87%E0%A6%A8+%E0%A6%8F%E0%A6%9C,+Road+-+37+%E0%A6%97%E0%A7%81%E0%A6%B2%E0%A6%B6%E0%A6%BE%E0%A6%A8+%E0%A6%8F%E0%A6%AD%E0%A6%BF%E0%A6%A8%E0%A6%BF%E0%A6%89,+%E0%A6%A2%E0%A6%BE%E0%A6%95%E0%A6%BE+1212/@23.7928448,90.4200192,14z/data=!4m8!4m7!1m0!1m5!1m1!1s0x3755c7a8e8e640f3:0x853c8b802d259ab6!2m2!1d90.4163233!2d23.7884608?entry=ttu&g_ep=EgoyMDI2MDUyMC4wIKXMDSoASAFQAw%3D%3D';
                        $supportPhone = '+880 1919-700800';
                    }

                    // Retrieve service name
                    $serviceName = 'General Appointment';
                    if (!empty($booking['serviceId'])) {
                        $serviceName = DB::table('services')->where('id', $booking['serviceId'])->value('name') ?? $booking['serviceId'];
                    }

                    // Date formatting
                    $formattedDate = $booking['date'];
                    try {
                        $formattedDate = date('l, F j, Y', strtotime($booking['date']));
                    } catch (\Throwable $e) {}

                    // Retrieve the logo from database settings, converting SVG to PNG for email compatibility
                    $settings = \Illuminate\Support\Facades\DB::table('website_settings')->first();
                    $logoPath = $settings && !empty($settings->logo) ? $settings->logo : 'assets/images/optimized/adonis_logo_1779270678761.png';
                    
                    // Replace SVG extension with PNG for the email template logo since email clients don't support SVG
                    if (pathinfo($logoPath, PATHINFO_EXTENSION) === 'svg') {
                        $pngLogoPath = preg_replace('/\.svg$/i', '.png', $logoPath);
                        if (file_exists(public_path($pngLogoPath))) {
                            $logoPath = $pngLogoPath;
                        }
                    }
                    
                    $logoUrl = asset($logoPath);

                    // Replace placeholders
                    $replacements = [
                        '{{customer_name}}'      => $booking['clientName'] ?? '',
                        '{{booking_id}}'          => $booking['bookingCode'] ?? '',
                        '{{service_name}}'        => $serviceName,
                        '{{appointment_date}}'    => $formattedDate,
                        '{{appointment_time}}'    => $booking['time'] ?? '',
                        '{{branch_name}}'        => $branchName,
                        '{{professional_name}}'   => $barberName,
                        '{{directions_url}}'      => $directionsUrl,
                        '{{support_phone}}'       => $supportPhone,
                        '{{support_email}}'       => 'info@adonis.com.bd',
                        '{{branch_address}}'     => $branchAddress,
                        '{{manage_booking_url}}'  => 'https://www.adonis.com.bd/',
                        '{{logo_url}}'            => $logoUrl,
                    ];

                    foreach ($replacements as $placeholder => $value) {
                        $html = str_replace($placeholder, $value, $html);
                    }

                    Mail::html($html, function ($message) use ($booking) {
                        $message->to($booking['clientEmail'])->subject('Adonis Booking Confirmed: ' . $booking['bookingCode']);
                    });
                } else {
                    Mail::raw("Your Adonis booking request has been received.\n\n" . $body, fn ($message) => $message->to($booking['clientEmail'])->subject('Adonis Booking Confirmed: ' . $booking['bookingCode']));
                }
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
            'mail.from.name'               => "Adonis men's Grooming Salon",
        ]);
    }

    /* ─────────────────────────────────────────────────
     *  OFFERS & PACKAGES
     * ───────────────────────────────────────────────── */

    public function offers()
    {
        if (!Schema::hasTable('offers')) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            } catch (\Throwable $e) {
                if (!Schema::hasTable('offers')) {
                    return response()->json([]);
                }
            }
        }

        if (DB::table('offers')->count() === 0) {
            DB::table('offers')->insert([
                [
                    'title' => 'REGULAR PACKAGE',
                    'subtitle' => 'Essential Grooming Bundle',
                    'description' => 'Includes Hair Cut, Adonis Special Facial, Pedicure & Manicure, and Shave.',
                    'badge' => 'HOT DEAL',
                    'icon' => 'Crown',
                    'original_price' => 6800,
                    'discounted_price' => 5800,
                    'discount_percent' => 15,
                    'valid_until' => 'Limited Time',
                    'branch' => 'gulshan',
                    'is_active' => 1,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'CLASSIC PACKAGE',
                    'subtitle' => 'Complete Transformation',
                    'description' => 'Includes Hair Cut, Shave, Oil Massage, Pedicure & Manicure, and Janssen Whitening Facial.',
                    'badge' => 'POPULAR',
                    'icon' => 'Sparkles',
                    'original_price' => 12300,
                    'discounted_price' => 10500,
                    'discount_percent' => 15,
                    'valid_until' => 'Limited Time',
                    'branch' => 'gulshan',
                    'is_active' => 1,
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'BRIDEGROOM PACKAGE',
                    'subtitle' => 'Single Day Wedding Grooming',
                    'description' => 'Includes Hair Cut & Setting, Fair Polish, Hair Spa, Deluxe Pedicure & Manicure, Shave, Body Shop Vitamin C Facial, Make-Over Art, and Body Massage.',
                    'badge' => 'VIP GROOM',
                    'icon' => 'Award',
                    'original_price' => 24600,
                    'discounted_price' => 21000,
                    'discount_percent' => 15,
                    'valid_until' => 'Wedding Season',
                    'branch' => 'gulshan',
                    'is_active' => 1,
                    'sort_order' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'BUSINESS EXECUTIVE PACKAGE',
                    'subtitle' => 'Premium Professional Styling',
                    'description' => 'Includes Premium Stylish Hair Cut, Beard shaping/Shave, Bigen Ammonia Free Color Dye, Caring Hair Spa, Pedicure & Manicure, Body Shop Seaweed Facial, and Swedish Body Massage (60 Mins).',
                    'badge' => 'EXECUTIVE',
                    'icon' => 'Briefcase',
                    'original_price' => 20600,
                    'discounted_price' => 17500,
                    'discount_percent' => 15,
                    'valid_until' => 'Limited Time',
                    'branch' => 'gulshan',
                    'is_active' => 1,
                    'sort_order' => 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'ROYAL LUXURY PACKAGE',
                    'subtitle' => 'The Ultimate Pampering',
                    'description' => 'Includes Hair Cut, Shave, Deluxe Pedicure & Manicure, Ammonia Free Color (Inova), L’Oréal Hair Spa, Gold Facial, and Body Scrub with Steam.',
                    'badge' => 'ROYAL',
                    'icon' => 'Crown',
                    'original_price' => 22800,
                    'discounted_price' => 19500,
                    'discount_percent' => 14,
                    'valid_until' => 'Limited Time',
                    'branch' => 'bashundhara',
                    'is_active' => 1,
                    'sort_order' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'CLASSIC SPA PACKAGE',
                    'subtitle' => 'Deep Relaxation Ritual',
                    'description' => 'Includes Hair Cut, Shave, Oil Massage, Pedicure & Manicure, and Janssen Whitening Facial.',
                    'badge' => 'SPA SPECIAL',
                    'icon' => 'Flower',
                    'original_price' => 12000,
                    'discounted_price' => 10000,
                    'discount_percent' => 17,
                    'valid_until' => 'Limited Time',
                    'branch' => 'bashundhara',
                    'is_active' => 1,
                    'sort_order' => 6,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
            Cache::forget('adonis_offers');
        }

        $offers = Cache::remember('adonis_offers', 3600, function () {
            return DB::table('offers')
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->get()
                ->map(fn ($row) => [
                    'id'               => $row->id,
                    'title'            => $row->title,
                    'subtitle'         => $row->subtitle,
                    'description'      => $row->description,
                    'badge'            => $row->badge,
                    'icon'             => $row->icon,
                    'original_price'   => $row->original_price ? (float) $row->original_price : null,
                    'discounted_price' => $row->discounted_price ? (float) $row->discounted_price : null,
                    'discount_percent' => $row->discount_percent ? (int) $row->discount_percent : null,
                    'image'            => $row->image,
                    'valid_until'      => $row->valid_until,
                    'branch'           => $row->branch,
                    'is_active'        => (bool) $row->is_active,
                    'sort_order'       => (int) $row->sort_order,
                ])
                ->values()
                ->all();
        });

        return response()->json($offers);
    }

    public function storeOffer(Request $request)
    {
        $id = DB::table('offers')->insertGetId([
            'title'            => $request->input('title', 'New Offer'),
            'subtitle'         => $request->input('subtitle'),
            'description'      => $request->input('description'),
            'badge'            => $request->input('badge'),
            'icon'             => $request->input('icon', 'Tag'),
            'original_price'   => $request->input('original_price'),
            'discounted_price' => $request->input('discounted_price'),
            'discount_percent' => $request->input('discount_percent'),
            'image'            => $request->input('image'),
            'valid_until'      => $request->input('valid_until'),
            'branch'           => $request->input('branch', 'all'),
            'is_active'        => $request->boolean('is_active', true) ? 1 : 0,
            'sort_order'       => (int) $request->input('sort_order', 0),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
        $this->clearFrontendCache();
        return response()->json(['success' => true, 'id' => $id]);
    }

    public function updateOffer(Request $request, int $id)
    {
        $exists = DB::table('offers')->where('id', $id)->exists();
        if (!$exists) return response()->json(['error' => 'Offer not found'], 404);

        DB::table('offers')->where('id', $id)->update([
            'title'            => $request->input('title'),
            'subtitle'         => $request->input('subtitle'),
            'description'      => $request->input('description'),
            'badge'            => $request->input('badge'),
            'icon'             => $request->input('icon', 'Tag'),
            'original_price'   => $request->input('original_price'),
            'discounted_price' => $request->input('discounted_price'),
            'discount_percent' => $request->input('discount_percent'),
            'image'            => $request->input('image'),
            'valid_until'      => $request->input('valid_until'),
            'branch'           => $request->input('branch', 'all'),
            'is_active'        => $request->boolean('is_active', true) ? 1 : 0,
            'sort_order'       => (int) $request->input('sort_order', 0),
            'updated_at'       => now(),
        ]);
        $this->clearFrontendCache();
        return response()->json(['success' => true]);
    }

    public function deleteOffer(int $id)
    {
        DB::table('offers')->where('id', $id)->delete();
        $this->clearFrontendCache();
        return response()->json(['success' => true]);
    }
}

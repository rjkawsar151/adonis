<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use App\Models\FooterSetting;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $settings = WebsiteSetting::first();
        $footer = FooterSetting::first();
        return view('admin.settings', compact('settings', 'footer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'               => 'required|string|max:255',
            'appointment_button_text' => 'required|string|max:255',
            'appointment_button_url'  => 'required|string|max:255',
            'whatsapp_number'         => 'nullable|string|max:30',
            'smtp_mail_to'            => 'nullable|email',
            'smtp_host'               => 'nullable|string|max:255',
            'smtp_port'               => 'nullable|numeric',
            'smtp_username'           => 'nullable|string|max:255',
            'smtp_password'           => 'nullable|string|max:255',
            'smtp_encryption'         => 'nullable|string|max:50',
            'notification_emails'     => 'nullable|string',
            'hero_image'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'logo'                    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            // footer fields
            'description' => 'nullable|string',
            'address'     => 'nullable|string|max:500',
            'phone'       => 'nullable|string|max:30',
            'email'       => 'nullable|email',
            'facebook_url'  => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url'  => 'nullable|url',
            'copyright_text' => 'nullable|string|max:500',
        ]);

        if (!File::isDirectory(public_path('uploads/settings'))) {
            File::makeDirectory(public_path('uploads/settings'), 0755, true);
        }

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $isSvg = strtolower($file->getClientOriginalExtension()) === 'svg';
            $logoPath = ImageCompressor::compressAndSaveWebp($file, 'uploads/settings', 70);

            // If the uploaded logo is SVG, convert it to PNG for email client compatibility
            if ($isSvg) {
                $svgFile = public_path($logoPath);
                $pngFile = str_replace('.svg', '.png', $svgFile);
                $cmd = sprintf(
                    'node -e "import(\'sharp\').then(s => s.default(%s).resize(946).png().toFile(%s)).catch(err => console.error(err))"',
                    json_encode($svgFile),
                    json_encode($pngFile)
                );
                $output = shell_exec($cmd);
                \Illuminate\Support\Facades\Log::info('SVG logo upload conversion result', [
                    'command' => $cmd,
                    'output' => $output
                ]);
            }
        }

        // Handle hero image upload
        $heroImagePath = null;
        if ($request->hasFile('hero_image')) {
            $heroImagePath = ImageCompressor::compressAndSaveWebp($request->file('hero_image'), 'uploads/settings', 70);
        }

        // Update or create website settings
        $settingsData = $request->only([
            'site_name','appointment_button_text','appointment_button_url',
            'whatsapp_number','smtp_mail_to','smtp_host','smtp_port',
            'smtp_username','smtp_password','smtp_encryption','notification_emails',
        ]);
        if ($logoPath) $settingsData['logo'] = $logoPath;
        if ($heroImagePath) $settingsData['hero_image'] = $heroImagePath;
        WebsiteSetting::updateOrCreate(['id' => 1], $settingsData);

        // Update or create footer settings
        $footerData = $request->only(['description','address','phone','email','facebook_url','instagram_url','linkedin_url','pinterest_url','copyright_text']);
        FooterSetting::updateOrCreate(['id' => 1], $footerData);

        return back()->with('success', 'Settings saved successfully.');
    }
}

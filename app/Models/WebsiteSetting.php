<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'logo',
        'favicon',
        'hero_image',
        'primary_color',
        'secondary_color',
        'appointment_button_text',
        'appointment_button_url',
        'whatsapp_number',
        'smtp_mail_to',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'notification_emails',
    ];
}

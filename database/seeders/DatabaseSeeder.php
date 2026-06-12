<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $data = $this->loadJsonSeed();

        foreach ($data['services'] ?? [] as $service) {
            DB::table('services')->updateOrInsert(['id' => $service['id']], [
                'name' => $service['name'] ?? '',
                'description' => $service['description'] ?? '',
                'durationMin' => (int) ($service['durationMin'] ?? 45),
                'priceBDT' => (int) ($service['priceBDT'] ?? 0),
                'category' => $service['category'] ?? 'hair',
                'icon' => $service['icon'] ?? 'Scissors',
            ]);
        }

        foreach ($data['barbers'] ?? [] as $barber) {
            DB::table('barbers')->updateOrInsert(['id' => $barber['id']], [
                'name' => $barber['name'] ?? '',
                'experienceYears' => (int) ($barber['experienceYears'] ?? 0),
                'specialty' => $barber['specialty'] ?? '',
                'portraitUrl' => $barber['portraitUrl'] ?? '',
                'bio' => $barber['bio'] ?? '',
                'rating' => (float) ($barber['rating'] ?? 5),
            ]);
        }

        DB::table('cms_meta')->updateOrInsert(
            ['meta_key' => 'settings'],
            ['meta_value' => json_encode($data['settings'] ?? [])]
        );

        DB::table('cms_meta')->updateOrInsert(
            ['meta_key' => 'smtp'],
            ['meta_value' => json_encode($data['smtp'] ?? [
                'host' => '',
                'port' => 587,
                'secure' => false,
                'user' => '',
                'pass' => '',
                'fromEmail' => 'noreply@adonis.com.bd',
                'adminEmails' => 'admin@adonis.com.bd',
            ])]
        );

        foreach ($data['blogs'] ?? [] as $blog) {
            DB::table('blogs')->updateOrInsert(['id' => $blog['id']], [
                'slug' => $blog['slug'] ?? $blog['id'],
                'title' => $blog['title'] ?? 'Untitled Blog',
                'excerpt' => $blog['excerpt'] ?? '',
                'coverImage' => $blog['coverImage'] ?? '',
                'contentHtml' => $blog['contentHtml'] ?? '',
                'seoTitle' => $blog['seoTitle'] ?? ($blog['title'] ?? ''),
                'seoDescription' => $blog['seoDescription'] ?? ($blog['excerpt'] ?? ''),
                'status' => $blog['status'] ?? 'draft',
                'createdAt' => $blog['createdAt'] ?? now(),
                'updatedAt' => $blog['updatedAt'] ?? now(),
            ]);
        }
    }

    private function loadJsonSeed(): array
    {
        $path = base_path('db.json');
        if (is_file($path)) {
            $data = json_decode(file_get_contents($path), true);
            if (is_array($data)) return $data;
        }

        return [
            'services' => [],
            'barbers' => [],
            'settings' => [],
            'smtp' => [],
            'blogs' => [],
        ];
    }
}

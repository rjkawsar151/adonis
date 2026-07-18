<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedChairmanMessage();
        $this->seedMdMessage();
        $this->seedCompanyIntro();
        $this->seedMissionVision();
        $this->seedCoreValues();
        $this->seedWhyChooseUs();
        $this->seedStatistics();
        $this->seedTimeline();
        $this->seedTeamMembers();
        $this->seedCta();
    }

    protected function seedChairmanMessage(): void
    {
        DB::table('about_chairman_messages')->updateOrInsert(['id' => 1], [
            'name' => 'Babul Chandra Shil',
            'designation' => 'Founder & Chairman',
            'photo' => '/assets/images/babul_barbar.png',
            'title' => 'Redefining Men\'s Grooming Standards',
            'speech' => '<p>Welcome to <strong>ADONIS Men\'s Grooming Salon</strong>. Since our founding, we have committed ourselves to a singular goal: providing a sanctuary of premium transition and executive grooming for the modern gentleman.</p><p>We believe that grooming is not merely a transaction—it is an experience, a curated ritual of details. We pair classic European styling heritage with high-end, contemporary Dubai hotel-standard lounge accommodations to ensure every visit elevates your confidence and personal identity.</p><p>Thank you for choosing Adonis. We look forward to crafting your signature look.</p>',
            'signature_image' => null,
            'quotation' => 'True grooming is the art of sculpting confidence from details.',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function seedMdMessage(): void
    {
        DB::table('about_md_messages')->updateOrInsert(['id' => 1], [
            'name' => 'Antor Mondol',
            'designation' => 'Managing Director & Partner',
            'photo' => '/assets/images/master_barber_portrait_1779269169728.png',
            'title' => 'Uncompromising Pursuit of Perfection',
            'speech' => '<p>At Adonis, our operational focus centers entirely on standard training and absolute precision. We realize that the modern gentleman values time, consistency, and standard hospitality.</p><p>We continuously invest in custom barber training, sanitized blade systems, and active query slot management, ensuring your visit is seamless, relaxing, and delivers the exact look you desire.</p><p>Our dedication is to maintain these high benchmarks across all existing and upcoming branches.</p>',
            'signature_image' => null,
            'quotation' => 'Consistency is the blueprint of luxury and lasting trust.',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function seedCompanyIntro(): void
    {
        DB::table('about_company_introductions')->updateOrInsert(['id' => 1], [
            'title' => 'Who We Are & What We Do',
            'subtitle' => 'ADONIS is Dhaka\'s premium destination for high-end men\'s grooming and relaxation.',
            'description' => '<p>Established in the heart of Dhaka\'s premium neighborhoods (Gulshan Avenue & Bashundhara), <strong>ADONIS</strong> is more than a barbershop—it is an elite lifestyle lounge designed for gentlemen who expect the very best.</p><p>Our lounges feature soundproofed VIP suites, premium leather chesterfield seating, warm ambient gold lighting, and custom barista-style refreshments. We specialize in precision hair texturizing, master skin fades, symmetric beard mapping, therapeutic hot towel shaves, deep scalp treatments, body spas, and custom skincare facials.</p><p>Serving diplomats, corporate executives, elite athletes, and grooms, we combine specialized European training with standard organic grooming formulations to deliver excellence with every visit.</p>',
            'featured_image' => '/assets/images/adonis_styling_chairs_1779270725139.png',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function seedMissionVision(): void
    {
        $items = [
            [
                'id' => 1,
                'type' => 'mission',
                'title' => 'Our Core Mission',
                'short_description' => 'Redefining the grooming experience for gentlemen through unmatched craft and hospitality.',
                'content' => '<p>Our mission is to elevate standard men\'s grooming into a curated ritual of luxury, restoration, and self-confidence. We strive to combine traditional master barbering with state-of-the-art hospitality lounges, ensuring every client leaves feeling sharp, relaxed, and fully restored.</p>',
                'icon_or_image' => 'Compass',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'id' => 2,
                'type' => 'vision',
                'title' => 'Our Future Vision',
                'short_description' => 'Becoming the benchmark brand for premium men\'s grooming services across South Asia.',
                'content' => '<p>Our vision is to expand the footprint of ADONIS Lounges globally, setting a new benchmark for luxury men\'s wellness and grooming. We aim to nurture and train the finest master barber talent, continuously innovating in organic skin/hair therapies and custom VIP accommodations.</p>',
                'icon_or_image' => 'Sparkles',
                'is_active' => true,
                'sort_order' => 1,
            ],
        ];

        foreach ($items as $item) {
            DB::table('about_missions_visions')->updateOrInsert(['id' => $item['id']], array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    protected function seedCoreValues(): void
    {
        $values = [
            ['id' => 1, 'title' => 'Innovation', 'icon' => 'Zap', 'description' => 'Continuously adopting advanced styling techniques, modern hair therapies, and premium organic skin formulations.', 'sort_order' => 0],
            ['id' => 2, 'title' => 'Integrity', 'icon' => 'ShieldAlert', 'description' => 'Maintaining high professional standards, transparent pricing, and absolute consistency in service quality.', 'sort_order' => 1],
            ['id' => 3, 'title' => 'Customer Focus', 'icon' => 'Smile', 'description' => 'Centering all lounge designs, dynamic booking prioritizing, and custom hospitality options around client comfort.', 'sort_order' => 2],
            ['id' => 4, 'title' => 'Quality', 'icon' => 'Crown', 'description' => 'Utilizing only standard tools, sanitized setups, and premium organic grooming materials.', 'sort_order' => 3],
            ['id' => 5, 'title' => 'Teamwork', 'icon' => 'UserCheck', 'description' => 'Fostering collaboration between concierge coordinators and master barbers to deliver a seamless client transition.', 'sort_order' => 4],
            ['id' => 6, 'title' => 'Accountability', 'icon' => 'Compass', 'description' => 'Taking complete ownership of the client grooming results, sanitization protocols, and schedule punctuality.', 'sort_order' => 5],
            ['id' => 7, 'title' => 'Continuous Improvement', 'icon' => 'Flower', 'description' => 'Engaging in continuous master training, face structure profiling studies, and service audits.', 'sort_order' => 6],
        ];

        foreach ($values as $val) {
            DB::table('about_core_values')->updateOrInsert(['id' => $val['id']], array_merge($val, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    protected function seedWhyChooseUs(): void
    {
        $points = [
            ['id' => 1, 'title' => 'Experienced Team', 'icon' => 'UserCheck', 'description' => 'Stylists with 10+ years of training and face-bone contour profiling expertise.', 'sort_order' => 0],
            ['id' => 2, 'title' => 'Customized Solutions', 'icon' => 'Layers', 'description' => 'Haircuts, beard shapes, and scalp therapies customized strictly to your unique style.', 'sort_order' => 1],
            ['id' => 3, 'title' => 'Reliable Support', 'icon' => 'PhoneCall', 'description' => 'Dedicated concierge support team with quick phone, email, and WhatsApp booking priority.', 'sort_order' => 2],
            ['id' => 4, 'title' => 'Modern Technology', 'icon' => 'Zap', 'description' => 'Advanced ozone hair steamers, dynamic vacuum blackhead extractors, and micro-trim tools.', 'sort_order' => 3],
            ['id' => 5, 'title' => 'Transparent Communication', 'icon' => 'MessageSquare', 'description' => 'No hidden rates. Fully transparent digital checkouts and upfront consultations.', 'sort_order' => 4],
            ['id' => 6, 'title' => 'On-Time Delivery', 'icon' => 'Clock', 'description' => 'Zero peak-hour booking delays. Respecting your calendar priority with absolute precision.', 'sort_order' => 5],
            ['id' => 7, 'title' => 'Competitive Pricing', 'icon' => 'DollarSign', 'description' => 'Unmatched price-to-luxury ratio. Elite 5-star service experience at reasonable rates.', 'sort_order' => 6],
            ['id' => 8, 'title' => 'Long-Term Partnership', 'icon' => 'Crown', 'description' => 'VIP custom privileges, member-only rates, and client history profile preservation.', 'sort_order' => 7],
        ];

        foreach ($points as $p) {
            DB::table('about_why_choose_us')->updateOrInsert(['id' => $p['id']], array_merge($p, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    protected function seedStatistics(): void
    {
        $stats = [
            ['id' => 1, 'counter_number' => '12', 'suffix' => '+', 'label' => 'Years of Excellence', 'icon' => 'Crown', 'sort_order' => 0],
            ['id' => 2, 'counter_number' => '50000', 'suffix' => '+', 'label' => 'Grooming Sessions', 'icon' => 'Scissors', 'sort_order' => 1],
            ['id' => 3, 'counter_number' => '25000', 'suffix' => '+', 'label' => 'Happy Gentlemen', 'icon' => 'Smile', 'sort_order' => 2],
            ['id' => 4, 'counter_number' => '40', 'suffix' => '+', 'label' => 'Master Stylists', 'icon' => 'UserCheck', 'sort_order' => 3],
            ['id' => 5, 'counter_number' => '99', 'suffix' => '%', 'label' => 'Sanitization Standard', 'icon' => 'ShieldAlert', 'sort_order' => 4],
        ];

        foreach ($stats as $s) {
            DB::table('about_statistics')->updateOrInsert(['id' => $s['id']], array_merge($s, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    protected function seedTimeline(): void
    {
        $milestones = [
            ['id' => 1, 'year_or_date' => '2014', 'title' => 'The Genesis', 'description' => 'Opened our very first grooming station in Dhaka with three master styling chairs.', 'image' => '/assets/images/executive.png', 'sort_order' => 0],
            ['id' => 2, 'year_or_date' => '2018', 'title' => 'Gulshan Launch', 'description' => 'Unveiled the massive Gulshan Avenue Premium Lounge featuring dynamic VIP suites.', 'image' => '/assets/images/reception.png', 'sort_order' => 1],
            ['id' => 3, 'year_or_date' => '2021', 'title' => 'Expansion to Bashundhara', 'description' => 'Opened the Bashundhara Studio, offering dry heat saunas and therapeutic steam therapy.', 'image' => '/assets/images/vip.png', 'sort_order' => 2],
            ['id' => 4, 'year_or_date' => '2024', 'title' => 'Premium Revamp', 'description' => 'Complete digital upgrade with real-time slot checking, parallel API fetching, and WebP media optimization.', 'image' => '/assets/images/adonis_executive_lounge_1779270704894.png', 'sort_order' => 3],
        ];

        foreach ($milestones as $m) {
            DB::table('about_timelines')->updateOrInsert(['id' => $m['id']], array_merge($m, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    protected function seedTeamMembers(): void
    {
        $members = [
            [
                'id' => 1,
                'name' => 'Babul Chandra Shil',
                'designation' => 'Chairman & Master Barber',
                'photo' => '/assets/images/babul_barbar.png',
                'biography' => 'Founder with over a decade of scissor design experience. Specialized in executive silhouettes.',
                'facebook_url' => 'https://facebook.com',
                'linkedin_url' => 'https://linkedin.com',
                'email' => 'chairman@adonis.com.bd',
                'sort_order' => 0,
            ],
            [
                'id' => 2,
                'name' => 'Antor Mondol',
                'designation' => 'Co-Founder & Lead Groomer',
                'photo' => '/assets/images/master_barber_portrait_1779269169728.png',
                'biography' => 'Straight-razor specialist with a deep background in symmetric beard architecture and oil outlines.',
                'facebook_url' => 'https://facebook.com',
                'linkedin_url' => 'https://linkedin.com',
                'email' => 'antor@adonis.com.bd',
                'sort_order' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Rofiqul Islam',
                'designation' => 'Director of Styling',
                'photo' => '/assets/images/rofiq_barbar.png',
                'biography' => 'Coordinates custom training structures for junior barber profiles, maintaining standard styling guidelines.',
                'facebook_url' => 'https://facebook.com',
                'linkedin_url' => 'https://linkedin.com',
                'email' => 'rofiq@adonis.com.bd',
                'sort_order' => 2,
            ],
        ];

        foreach ($members as $m) {
            DB::table('about_team_members')->updateOrInsert(['id' => $m['id']], array_merge($m, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    protected function seedCta(): void
    {
        DB::table('about_ctas')->updateOrInsert(['id' => 1], [
            'title' => 'Ready for a Sharper Identity?',
            'description' => 'Reserve your custom precision grooming session at our luxury Gulshan or Bashundhara lounge terminals.',
            'primary_button_text' => 'Book Appointment',
            'primary_button_url' => '#booking-section',
            'secondary_button_text' => 'Explore Services',
            'secondary_button_url' => '/services',
            'background_image' => '/assets/images/executive.png',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\EmploymentType;
use App\Models\Career;
use App\Models\CareerQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Departments
        $depts = [
            ['name' => 'Hair Dressing & Styling', 'slug' => 'hair-dressing-styling', 'status' => 'active'],
            ['name' => 'Therapeutic Massage & Spa', 'slug' => 'therapeutic-massage-spa', 'status' => 'active'],
            ['name' => 'Customer Relations & Lobby', 'slug' => 'customer-relations-lobby', 'status' => 'active'],
            ['name' => 'Corporate Management', 'slug' => 'corporate-management', 'status' => 'active'],
        ];

        foreach ($depts as $dept) {
            Department::updateOrCreate(['slug' => $dept['slug']], $dept);
        }

        // 2. Seed Employment Types
        $types = [
            ['name' => 'Full-Time', 'slug' => 'full-time', 'status' => 'active'],
            ['name' => 'Part-Time', 'slug' => 'part-time', 'status' => 'active'],
            ['name' => 'Contract', 'slug' => 'contract', 'status' => 'active'],
            ['name' => 'Internship', 'slug' => 'internship', 'status' => 'active'],
        ];

        foreach ($types as $type) {
            EmploymentType::updateOrCreate(['slug' => $type['slug']], $type);
        }

        $hairDept = Department::where('slug', 'hair-dressing-styling')->first();
        $lobbyDept = Department::where('slug', 'customer-relations-lobby')->first();
        $ftType = EmploymentType::where('slug', 'full-time')->first();
        $ptType = EmploymentType::where('slug', 'part-time')->first();

        // 3. Seed Careers
        $careers = [
            [
                'title' => 'Senior Master Hair Barber',
                'slug' => 'senior-master-hair-barber',
                'short_description' => 'We are seeking an elite, highly experienced Master Barber to join our Gulshan and Bashundhara executive styling deck.',
                'description' => '<h2>Role Overview</h2><p>Adonis Men\'s Grooming is expanding its cohort of master stylists. We are looking for an experienced master barber capable of delivering traditional scissor cuts, modern fades, and professional beard contour mapping.</p><h3>Key Responsibilities</h3><ul><li>Provide premium consulting, precision haircuts, styling, and shaves.</li><li>Deliver outstanding guest services matching international luxury standards.</li><li>Advise guests on scalp treatments, hair spa, and personal care routines.</li></ul>',
                'responsibilities' => '<ul><li>Execute skin fades, executive cuts, and straight-razor clean shaves.</li><li>Maintain sanitization of stations and premium tools.</li><li>Provide detailed scalp diagnostics.</li></ul>',
                'educational_requirements' => '<p>Certification in styling, cosmetology, or equivalent professional trade credentials.</p>',
                'experience_requirements' => '<p>5+ years of craft experience in professional high-end salons or grooming lounges.</p>',
                'additional_requirements' => '<p>Polite communication, professional behavior, and punctual work ethics.</p>',
                'skills' => 'Razor Fades, Scissor Cutting, Beard Shaping, Hot Towel Therapy, Scalp Diagnostics',
                'benefits' => '<ul><li>Competitive basic salary + high-percentage service commissions.</li><li>Annual performance bonuses.</li><li>Complimentary master training cohorts.</li></ul>',
                'department_id' => $hairDept->id,
                'employment_type_id' => $ftType->id,
                'location' => 'Gulshan Avenue, Dhaka',
                'vacancy' => 2,
                'salary_min' => 40000.00,
                'salary_max' => 75000.00,
                'salary_type' => 'Monthly',
                'application_deadline' => now()->addDays(30)->toDateString(),
                'status' => 'active',
                'is_featured' => true,
                'seo_title' => 'Senior Master Hair Barber Job Opening Gulshan | Adonis',
                'seo_description' => 'Apply to become a Senior Master Barber at Adonis Men\'s Grooming Salon in Gulshan. Competitive salary plus commission structure.'
            ],
            [
                'title' => 'Lobby Host & Guest Relations Executive',
                'slug' => 'lobby-host-guest-relations',
                'short_description' => 'Manage candidate check-ins, guest welcomes, bookings coordination, and premium beverage offerings at our executive reception.',
                'description' => '<h2>Role Overview</h2><p>The Lobby Host represents the primary face of Adonis. You will greet incoming VIP guests, manage the digital booking schedule, direct patrons to their private styling cabins, and coordinate wait times.</p>',
                'responsibilities' => '<ul><li>Welcome guests and check appointments in the administrative dashboard.</li><li>Serve premium hot/cold beverages.</li><li>Resolve phone and digital inquiries.</li></ul>',
                'educational_requirements' => '<p>Bachelor\'s degree or diploma in Hospitality, Hotel Management, English, or related fields.</p>',
                'experience_requirements' => '<p>1-3 years in luxury hotel front decks, airline hospitality, or high-end lounge management.</p>',
                'additional_requirements' => '<p>Fluent in both English and Bengali with excellent interpersonal skills.</p>',
                'skills' => 'Hospitality, Schedule Coordination, MS Office, Fluent English, Guest Reception',
                'benefits' => '<ul><li>Attractive starting salary.</li><li>Two festival bonuses.</li><li>Professional growth tracks.</li></ul>',
                'department_id' => $lobbyDept->id,
                'employment_type_id' => $ptType->id,
                'location' => 'Gulshan & Bashundhara, Dhaka',
                'vacancy' => 1,
                'salary_min' => 20000.00,
                'salary_max' => 35000.00,
                'salary_type' => 'Monthly',
                'application_deadline' => now()->addDays(20)->toDateString(),
                'status' => 'active',
                'is_featured' => false,
                'seo_title' => 'Lobby Host Job Opening Dhaka | Adonis Men\'s Grooming',
                'seo_description' => 'Adonis Men\'s Grooming is hiring a hospitality-focused Lobby Host and Guest Relations Executive for Gulshan & Bashundhara lounges.'
            ]
        ];

        foreach ($careers as $car) {
            $careerModel = Career::updateOrCreate(['slug' => $car['slug']], $car);

            // Seed questions for the first career
            if ($car['slug'] === 'senior-master-hair-barber') {
                CareerQuestion::updateOrCreate(
                    ['career_id' => $careerModel->id, 'question' => 'Do you have experience with straight razors?'],
                    [
                        'career_id' => $careerModel->id,
                        'question' => 'Do you have experience with straight razors?',
                        'help_text' => 'Indicate if you are trained in hot-shaving techniques using straight-razor double pass.',
                        'question_type' => 'yes_no',
                        'options' => null,
                        'is_required' => true,
                        'sort_order' => 1
                    ]
                );

                CareerQuestion::updateOrCreate(
                    ['career_id' => $careerModel->id, 'question' => 'Provide a link to your styling portfolio'],
                    [
                        'career_id' => $careerModel->id,
                        'question' => 'Provide a link to your styling portfolio',
                        'help_text' => 'Add Instagram, website, or Google Drive folder showcasing your work.',
                        'question_type' => 'text',
                        'options' => null,
                        'is_required' => false,
                        'sort_order' => 2
                    ]
                );

                CareerQuestion::updateOrCreate(
                    ['career_id' => $careerModel->id, 'question' => 'Choose your preferred lounge assignment'],
                    [
                        'career_id' => $careerModel->id,
                        'question' => 'Choose your preferred lounge assignment',
                        'help_text' => 'Select which branch you prefer for your main shift.',
                        'question_type' => 'dropdown',
                        'options' => ['Gulshan Lounge', 'Bashundhara Lounge', 'Any Branch'],
                        'is_required' => true,
                        'sort_order' => 3
                    ]
                );
            }
        }
    }
}

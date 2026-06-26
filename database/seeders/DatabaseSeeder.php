<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedServices();
        $this->seedBarbers();
        $this->seedBlogs();
        $this->seedPriceList();
    }

    protected function seedServices(): void
    {
        $services = [
            ['id' => 'precision-haircut', 'name' => 'Precision Haircut', 'description' => 'Expert consultation, refreshing shampoo, premium haircut, sharp styling, and high-performance finish.', 'durationMin' => 45, 'priceBDT' => 700, 'category' => 'hair', 'icon' => 'Scissors', 'branch_id' => 'all'],
            ['id' => 'skin-fade', 'name' => 'Stylish Beard Trim', 'description' => 'Beard shaping and styling with premium trimming tools.', 'durationMin' => 60, 'priceBDT' => 600, 'category' => 'hair', 'icon' => 'Sparkles', 'branch_id' => 'all'],
            ['id' => 'royal-beard-shaping', 'name' => 'Beard Dye with Inova Color', 'description' => 'Beard trimming and styling with premium dye.', 'durationMin' => 30, 'priceBDT' => 1500, 'category' => 'beard', 'icon' => 'Smile', 'branch_id' => 'all'],
            ['id' => 'hot-towel-shave', 'name' => 'Hot Towel Shave', 'description' => 'Classic straight razor double pass, warm pre-shave lather, facial massage with signature mint cold towel.', 'durationMin' => 40, 'priceBDT' => 1500, 'category' => 'beard', 'icon' => 'Flame', 'branch_id' => 'all'],
            ['id' => 'hair-spa', 'name' => 'Hair Spa & Treatment', 'description' => 'Deep scalp detoxification, deep conditioning charcoal mask, steam activation, and shoulder stress-relief massage.', 'durationMin' => 60, 'priceBDT' => 3500, 'category' => 'spa', 'icon' => 'Flower', 'branch_id' => 'all'],
            ['id' => 'scalp-therapy', 'name' => 'Scalp Therapy', 'description' => 'Soothing tea-tree cooling treatment to cure itchiness, stimulate hair follicle circulation and strengthen roots.', 'durationMin' => 45, 'priceBDT' => 3000, 'category' => 'spa', 'icon' => 'ShieldAlert', 'branch_id' => 'all'],
            ['id' => 'facial-treatment', 'name' => 'Facial Treatment', 'description' => 'Adonis signature exfoliation, golden age anti-pollution face scrub, blackhead vacuum extraction, and hydrating ice therapy.', 'durationMin' => 50, 'priceBDT' => 4000, 'category' => 'spa', 'icon' => 'UserCheck', 'branch_id' => 'all'],
            ['id' => 'vip-grooming-package', 'name' => 'VIP Grooming Package', 'description' => 'The ultimate royal experience: Precision Cut + Hot Towel Shave + Golden-Age Scalp Spa + Signature Facial + Specialty Beverage.', 'durationMin' => 120, 'priceBDT' => 7500, 'category' => 'pack', 'icon' => 'Crown', 'branch_id' => 'all'],
        ];

        foreach ($services as $svc) {
            DB::table('services')->updateOrInsert(['id' => $svc['id']], $svc);
        }
    }

    protected function seedBarbers(): void
    {
        $barbers = [
            ['id' => 'tariq', 'name' => 'Babul Chandra Shil', 'experienceYears' => 12, 'specialty' => 'Skin Fade & Modern Texturizing', 'portraitUrl' => '/assets/images/babul_barbar.png', 'bio' => 'Renowned stylist with 12 years of craftsmanship. Expert in razor fades and sculpting bold executive silhouettes.', 'rating' => 4.9],
            ['id' => 'kamran', 'name' => 'Antor Mondol', 'experienceYears' => 9, 'specialty' => 'Royal Shaves & Beard Architecture', 'portraitUrl' => '/assets/images/master_barber_portrait_1779269169728.png', 'bio' => 'A true maestro of the straight razor, specialized in symmetrical beard mapping and hot towel restoration.', 'rating' => 5.0],
            ['id' => 'arif', 'name' => 'Rofiqul Islam', 'experienceYears' => 7, 'specialty' => 'Classic Scissor Cuts & Scalp Health', 'portraitUrl' => '/assets/images/rofiq_barbar.png', 'bio' => 'Dedicated to traditional high-end scissor sculpting and therapeutic scalp treatments to promote long-term volume.', 'rating' => 4.8],
            ['id' => 'javed', 'name' => 'Chandra', 'experienceYears' => 10, 'specialty' => 'Hair Color & Keratin Treatments', 'portraitUrl' => '/assets/images/chandra.png', 'bio' => 'Color specialist with a decade of expertise in ammonia-free dye systems, keratin bonding, and high-fashion tone transformations.', 'rating' => 4.9],
            ['id' => 'rohit', 'name' => 'Kalu Rupa Shil', 'experienceYears' => 6, 'specialty' => 'Facial Treatments & Spa Services', 'portraitUrl' => '/assets/images/kalu_rupa.png', 'bio' => 'Certified skin-care professional with advanced training in premium facials, body scrubs, and therapeutic massage techniques.', 'rating' => 4.7],
            ['id' => 'salim', 'name' => 'Saimon', 'experienceYears' => 8, 'specialty' => 'Massage Therapy & Body Spa', 'portraitUrl' => '/assets/images/saimon.png', 'bio' => 'Licensed massage therapist trained in Swedish, Deep Tissue, Thai and Aroma techniques. Known for his precision pressure-point therapy.', 'rating' => 4.8],
            ['id' => 'rakib', 'name' => 'Dulal Chandra', 'experienceYears' => 5, 'specialty' => 'Modern Beard Styling & Waxing', 'portraitUrl' => '/assets/images/dulal_chandra.png', 'bio' => 'Young and passionate groomer specializing in contemporary beard shaping, wax-based styling, and precise razor-line finishes.', 'rating' => 4.6],
        ];

        foreach ($barbers as $b) {
            DB::table('barbers')->updateOrInsert(['id' => $b['id']], $b);
        }
    }

    protected function seedBlogs(): void
    {
        $blogs = [
            [
                'id' => 'premium-mens-grooming-dhaka',
                'slug' => 'premium-mens-grooming-dhaka',
                'title' => 'Premium Men\'s Grooming in Dhaka: The Adonis Guide to a Sharper Look',
                'excerpt' => 'Discover how professional haircuts, beard shaping, facials, massage, and premium salon care help modern men in Dhaka look sharper and feel more confident.',
                'coverImage' => '/assets/images/adonis_styling_chairs_1779270725139.png',
                'contentHtml' => '<h2>Why Men\'s Grooming Matters More Than Ever</h2><p>Modern grooming is not only about looking polished. It is about confidence, hygiene, presentation, and personal identity. At <strong>Adonis Men\'s Grooming in Dhaka</strong>, every service is designed to help men look sharp while enjoying a calm, premium salon experience.</p><h3>Precision Haircuts for Every Lifestyle</h3><p>A professional haircut should match your face shape, profession, hair texture, and daily styling routine. Whether you prefer a classic scissor cut, a modern skin fade, or a refined executive style, the right haircut can instantly improve your overall appearance.</p><ul><li>Clean fades and sharp blending</li><li>Face-shape-based consultation</li><li>Professional styling and finishing</li></ul><h3>Beard Shaping and Hot Towel Shaves</h3><p>For men with facial hair, beard maintenance is essential. A balanced beard line, clean cheek contour, and sharp neckline can define the jaw and create a more structured look. Hot towel shaving also refreshes the skin and delivers a traditional barbering experience.</p><h4>Facials, Spa Care, and Relaxation</h4><p>Dhaka\'s weather, dust, and stress can affect skin and scalp health. Premium facials, hair spa treatments, massage, and body care help restore freshness and reduce everyday fatigue.</p><h5>Why Choose Adonis?</h5><p><strong>Adonis Men\'s Grooming</strong> combines expert stylists, premium products, luxury interiors, and personalized service for men who expect more than a basic haircut.</p><h6>Book Your Premium Grooming Session</h6><p>If you are searching for a premium men\'s salon in Dhaka, visit Adonis in Gulshan or Bashundhara and experience grooming designed around confidence, comfort, and detail.</p>',
                'seoTitle' => 'Premium Men\'s Grooming in Dhaka | Adonis Men\'s Grooming',
                'seoDescription' => 'Looking for the best men\'s grooming salon in Dhaka? Learn how Adonis combines precision haircuts, beard styling, facials, spa care, and premium salon service.',
                'status' => 'published',
                'createdAt' => '2026-06-04 00:00:00',
                'updatedAt' => '2026-06-04 00:00:00',
            ],
        ];

        foreach ($blogs as $b) {
            DB::table('blogs')->updateOrInsert(['id' => $b['id']], $b);
        }
    }

    protected function seedPriceList(): void
    {
        DB::table('price_list_items')->truncate();

        $allItems = [
            // GULSHAN BRANCH
            // HAIR CUTTING
            ['category' => 'HAIR CUTTING', 'name' => 'Hair Cut with Shampoo', 'price' => '700/-', 'description' => 'Classic precise cut with professional wash styling finish', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'HAIR CUTTING', 'name' => 'Hair Cut Stylish / Fashion / Catalogue', 'price' => '1,000/- & Above', 'description' => 'Trending, aesthetic styles suited for elite bone-structures', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'HAIR CUTTING', 'name' => 'Normal Beard Trim', 'price' => '600/-', 'description' => 'Symmetrical trim with razor lines finish', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'HAIR CUTTING', 'name' => 'Stylish Beard Trim', 'price' => '600/- & Above', 'description' => 'Beard mapping, precise styling and defining', 'branch_id' => 'gulshan', 'sort_order' => 3],
            ['category' => 'HAIR CUTTING', 'name' => 'Shave', 'price' => '600/-', 'description' => 'Smooth face, premium shaving foam and moisturization', 'branch_id' => 'gulshan', 'sort_order' => 4],
            ['category' => 'HAIR CUTTING', 'name' => 'Bald Adult', 'price' => '700/-', 'description' => 'Clean skull head shave with soothing balm treatment', 'branch_id' => 'gulshan', 'sort_order' => 5],
            ['category' => 'HAIR CUTTING', 'name' => 'Bald Infant / Children', 'price' => '700/-', 'description' => 'Careful children or infant skull shave with extra sensitivity care', 'branch_id' => 'gulshan', 'sort_order' => 6],
            // HAIR SETTING
            ['category' => 'HAIR SETTING', 'name' => 'Hair Setting Mousse / Cream / Gel / Spray / Wax', 'price' => '500/-', 'description' => 'Professional holding finish', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'HAIR SETTING', 'name' => 'Hair Shampoo with Conditioner', 'price' => '500/-', 'description' => 'Deep cleanse with premium hydration', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'HAIR SETTING', 'name' => 'Blow Dry', 'price' => '500/-', 'description' => 'Volume building blow action styling', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'HAIR SETTING', 'name' => 'Hair Iron', 'price' => '2,000/- & Above', 'description' => 'Flat iron straight styling texture', 'branch_id' => 'gulshan', 'sort_order' => 3],
            ['category' => 'HAIR SETTING', 'name' => 'Hair Rebonding', 'price' => '10,000/- & Above', 'description' => 'Permanent elite hair straight restructuring', 'branch_id' => 'gulshan', 'sort_order' => 4],
            ['category' => 'HAIR SETTING', 'name' => 'Super Shine Keratin', 'price' => '16,000/- & Above', 'description' => 'Ultimate premium silk infusion keratin shield', 'branch_id' => 'gulshan', 'sort_order' => 5],
            // SPECIAL CHARGE LIST
            ['category' => 'SPECIAL CHARGE LIST', 'name' => 'Shower & Steam', 'price' => '1,000/-', 'description' => 'Relieving warm shower and professional steam session', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'SPECIAL CHARGE LIST', 'name' => 'Personal Room Charge', 'price' => '1,000/-', 'description' => 'Private suite reservation with completely exclusive styling', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'SPECIAL CHARGE LIST', 'name' => 'Sauna', 'price' => '1,000/-', 'description' => 'Elite dry heat detoxification chamber access', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'SPECIAL CHARGE LIST', 'name' => 'Home Service', 'price' => '2,000/-', 'description' => 'Premium door-to-door styling with master barber sent to your residence', 'branch_id' => 'gulshan', 'sort_order' => 3],
            // HAIR DYE & OTHERS
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Moustache Dye', 'price' => '600/-', 'description' => 'Quick moustache grey coverage', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Beard Dye Ammonia Free Bigen', 'price' => '1,000/-', 'description' => 'Safe ammonia-free beard contour dye', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Beard Dye with Inova Color', 'price' => '1,500/-', 'description' => 'Rich deep color tone alignment', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Dye Color Apply Only Service Charge + Shampoo', 'price' => '1,000/- & Above', 'description' => 'Using client\'s custom self-purchased dye', 'branch_id' => 'gulshan', 'sort_order' => 3],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Color Apply Charge', 'price' => '1,000/- & Above', 'description' => 'Professional expert dye application service', 'branch_id' => 'gulshan', 'sort_order' => 4],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Color Sticks Each Color', 'price' => '1,000/- & Above', 'description' => 'Bespoke design highlight streaks', 'branch_id' => 'gulshan', 'sort_order' => 5],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Bigen', 'price' => '1,300/-', 'description' => 'Traditional coverage formula', 'branch_id' => 'gulshan', 'sort_order' => 6],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Bigen Ammonia Free', 'price' => '1,800/-', 'description' => 'Non-irritant natural formula', 'branch_id' => 'gulshan', 'sort_order' => 7],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Mehedi Color', 'price' => '2,000/- & Above', 'description' => 'Traditional organic natural henna styling', 'branch_id' => 'gulshan', 'sort_order' => 8],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'L\'Oréal Majirel Color', 'price' => '2,500/-', 'description' => 'Premium premium dye with radiant shine', 'branch_id' => 'gulshan', 'sort_order' => 9],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'L\'Oréal INOA Ammonia Free', 'price' => '3,000/-', 'description' => 'Elite zero ammonia protective hair color', 'branch_id' => 'gulshan', 'sort_order' => 10],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Keune Color Ammonia Free', 'price' => '3,000/- & Above', 'description' => 'Top professional hair care coloring', 'branch_id' => 'gulshan', 'sort_order' => 11],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Fashion Color with Pre-lightening', 'price' => '9,000/- & Above', 'description' => 'Extreme ash, grey or blonde high-fashion tone transformations', 'branch_id' => 'gulshan', 'sort_order' => 12],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Godrej Professional Hair Color', 'price' => '3,500/- & Above', 'description' => 'Professional color system', 'branch_id' => 'gulshan', 'sort_order' => 13],
            // HAIR TREATMENT
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Massage with Steam Coconut Oil', 'price' => '1,000/- & Above', 'description' => 'Relieving warm oil therapy with intense steam', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Massage with Steam Castor / Almond / Shahanaj / Garlic Oil', 'price' => '1,500/-', 'description' => 'Specific selected essential oils for target remedies', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Massage with Steam Godrej Argan or Acai Oil', 'price' => '1,500/-', 'description' => 'Ultra premium hydration oils', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Massage with Steam Body Shop Coco Oil', 'price' => '1,800/-', 'description' => 'Imported Body Shop Coco formula treatment', 'branch_id' => 'gulshan', 'sort_order' => 3],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Therapy with Aloe Vera Pack', 'price' => '2,000/- & Above', 'description' => 'Anti-allergy organic hair cooling pack', 'branch_id' => 'gulshan', 'sort_order' => 4],
            ['category' => 'HAIR TREATMENT', 'name' => 'L\'Oréal Hair Spa', 'price' => '2,800/-', 'description' => 'Deep L\'Oréal hair nourishment treatment', 'branch_id' => 'gulshan', 'sort_order' => 5],
            ['category' => 'HAIR TREATMENT', 'name' => 'Godrej Hair Spa', 'price' => '3,000/-', 'description' => 'Professional hair mask with steam', 'branch_id' => 'gulshan', 'sort_order' => 6],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hair Shine', 'price' => '3,000/-', 'description' => 'Intense glass reflecting hair gloss finish', 'branch_id' => 'gulshan', 'sort_order' => 7],
            ['category' => 'HAIR TREATMENT', 'name' => 'Treatment for Rebonding Hair', 'price' => '3,500/-', 'description' => 'Reconstructive conditioning for chemically straightened hair', 'branch_id' => 'gulshan', 'sort_order' => 8],
            ['category' => 'HAIR TREATMENT', 'name' => 'Godrej Hair Treatment Dry & Damaged Hair', 'price' => '4,000/-', 'description' => 'Target repair for splitting or over-processed hair', 'branch_id' => 'gulshan', 'sort_order' => 9],
            ['category' => 'HAIR TREATMENT', 'name' => 'Godrej Hair Treatment Regular & Dry Hair', 'price' => '4,000/-', 'description' => 'Normalizing hydration booster', 'branch_id' => 'gulshan', 'sort_order' => 10],
            ['category' => 'HAIR TREATMENT', 'name' => 'Godrej Hair Treatment Frizzy & Straightened Hair', 'price' => '4,000/-', 'description' => 'Sleek smoothing therapy for high-frizz cases', 'branch_id' => 'gulshan', 'sort_order' => 11],
            ['category' => 'HAIR TREATMENT', 'name' => 'L\'Oréal Elvive Dandruff Control / Hair Fall / Damage', 'price' => '4,500/-', 'description' => 'Intensive scalp relief remedies', 'branch_id' => 'gulshan', 'sort_order' => 12],
            ['category' => 'HAIR TREATMENT', 'name' => 'Godrej Kera Care Keratin Treatment', 'price' => '5,000/-', 'description' => 'Premium keratin structure builder', 'branch_id' => 'gulshan', 'sort_order' => 13],
            ['category' => 'HAIR TREATMENT', 'name' => 'Garnier Treatment', 'price' => '5,000/-', 'description' => 'Natural extract conditioning', 'branch_id' => 'gulshan', 'sort_order' => 14],
            ['category' => 'HAIR TREATMENT', 'name' => 'Body Shop Hair Treatment', 'price' => '7,000/-', 'description' => 'Ultra-luxurious premium imported therapy', 'branch_id' => 'gulshan', 'sort_order' => 15],
            ['category' => 'HAIR TREATMENT', 'name' => 'Ginger Hair Treatment', 'price' => '8,000/-', 'description' => 'Ultimate deep-cleansing organic anti-dandruff ginger therapy', 'branch_id' => 'gulshan', 'sort_order' => 16],
            // FACE MASSAGE & SCRUB
            ['category' => 'FACE MASSAGE & SCRUB', 'name' => 'Face Massage', 'price' => '1,500/-', 'description' => 'Relieving traditional facial muscles relaxation', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'FACE MASSAGE & SCRUB', 'name' => 'Body Shop Face Massage Vitamin C', 'price' => '3,000/-', 'description' => 'Glow-boosting skin brightening workout', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'FACE MASSAGE & SCRUB', 'name' => 'Special Face Massage Body Shop Vitamin E', 'price' => '4,000/-', 'description' => 'Anti-aging cells repair & deep moisture nutrition', 'branch_id' => 'gulshan', 'sort_order' => 2],
            // FAIR POLISH
            ['category' => 'FAIR POLISH', 'name' => 'Face Polish', 'price' => '1,200/-', 'description' => 'Exfoliating glow application', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'FAIR POLISH', 'name' => 'Neck Polish', 'price' => '800/-', 'description' => 'Clean color adjustment', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'FAIR POLISH', 'name' => 'Face + Neck Polish', 'price' => '1,800/-', 'description' => 'Complete head-neck brightness polish', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'FAIR POLISH', 'name' => 'Hands Polish', 'price' => '2,000/-', 'description' => 'Dual arm exfoliation and tone matching', 'branch_id' => 'gulshan', 'sort_order' => 3],
            ['category' => 'FAIR POLISH', 'name' => 'Half Body Front Polish', 'price' => '2,500/-', 'description' => 'Torso skin restoration', 'branch_id' => 'gulshan', 'sort_order' => 4],
            ['category' => 'FAIR POLISH', 'name' => 'Half Body Back Polish', 'price' => '3,000/-', 'description' => 'Spine skin polish with steam', 'branch_id' => 'gulshan', 'sort_order' => 5],
            ['category' => 'FAIR POLISH', 'name' => 'Face + Neck + Hands Polish', 'price' => '3,000/-', 'description' => 'Highly popular complete visual grooming combo', 'branch_id' => 'gulshan', 'sort_order' => 6],
            ['category' => 'FAIR POLISH', 'name' => 'Upper Body Polish', 'price' => '4,500/-', 'description' => 'Complete waist-up whitening polish', 'branch_id' => 'gulshan', 'sort_order' => 7],
            ['category' => 'FAIR POLISH', 'name' => 'Full Body Polish', 'price' => '6,000/-', 'description' => 'The absolute full body skin renewal experience', 'branch_id' => 'gulshan', 'sort_order' => 8],
            // MILK FAIR POLISH
            ['category' => 'MILK FAIR POLISH', 'name' => 'Face + Neck (Milk)', 'price' => '3,000/-', 'description' => 'Lactic acid whitening and hydration for face and neck', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'MILK FAIR POLISH', 'name' => 'Front & Back (Milk)', 'price' => '12,000/-', 'description' => 'Torso-wide deluxe milk polish', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'MILK FAIR POLISH', 'name' => 'Full Body (Milk)', 'price' => '15,000/-', 'description' => 'Ultimate royal skin hydration and tone leveling', 'branch_id' => 'gulshan', 'sort_order' => 2],
            // FACIAL
            ['category' => 'FACIAL', 'name' => 'Adonis Special Facial', 'price' => '3,500/-', 'description' => 'Our signature charcoal blend for active city lifestyles', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'FACIAL', 'name' => 'Aloe Vera Facial', 'price' => '3,500/-', 'description' => 'Soothing hydration for highly sensitive or sun-irritated skins', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'FACIAL', 'name' => 'Pearl Facial', 'price' => '4,500/-', 'description' => 'Deluxe mineral-based face glow facial', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'FACIAL', 'name' => 'Diamond Facial', 'price' => '5,000/-', 'description' => 'Deep micro-exfoliation and light reflecting brightness limit', 'branch_id' => 'gulshan', 'sort_order' => 3],
            ['category' => 'FACIAL', 'name' => 'Gold Facial', 'price' => '6,000/-', 'description' => 'Premium 24k gold leaf skin recovery and glow action', 'branch_id' => 'gulshan', 'sort_order' => 4],
            ['category' => 'FACIAL', 'name' => 'Janssen Whitening Facial', 'price' => '8,000/-', 'description' => 'Aesthetic-level German Janssen formula', 'branch_id' => 'gulshan', 'sort_order' => 5],
            ['category' => 'FACIAL', 'name' => 'Lotus Gold Facial', 'price' => '8,000/-', 'description' => 'Ultra premium luxury natural extracts gold treatment', 'branch_id' => 'gulshan', 'sort_order' => 6],
            ['category' => 'FACIAL', 'name' => 'Body Shop Vitamin C Facial', 'price' => '8,000/-', 'description' => 'Rich vitamin C active glow remedy', 'branch_id' => 'gulshan', 'sort_order' => 7],
            ['category' => 'FACIAL', 'name' => 'Janssen Whitening Facial Orange', 'price' => '9,000/-', 'description' => 'Premium citric Janssen formula', 'branch_id' => 'gulshan', 'sort_order' => 8],
            ['category' => 'FACIAL', 'name' => 'S K 5 Diamond Facial', 'price' => '10,000/-', 'description' => 'Super premium cell replacement and luxury glow', 'branch_id' => 'gulshan', 'sort_order' => 9],
            ['category' => 'FACIAL', 'name' => 'Body Shop Seaweed Facial', 'price' => '10,000/-', 'description' => 'Intense minerals oil control marine treatment', 'branch_id' => 'gulshan', 'sort_order' => 10],
            ['category' => 'FACIAL', 'name' => 'Body Shop Drops of Light Facial', 'price' => '12,000/-', 'description' => 'The ultimate luxury imported brightening program', 'branch_id' => 'gulshan', 'sort_order' => 11],
            // BODY FASHION & WAX
            ['category' => 'BODY FASHION & WAX', 'name' => 'Eye Brows Shaping', 'price' => '500/-', 'description' => 'Elite thread layout shaping', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Face Threading', 'price' => '1,000/- & Above', 'description' => 'Clean smooth facial threading', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Ears Candling', 'price' => '2,500/-', 'description' => 'Traditional thermal wax ear canal detox and pressure relief', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing Face', 'price' => '1,000/-', 'description' => 'Smooth face waxing', 'branch_id' => 'gulshan', 'sort_order' => 3],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing Neck', 'price' => '1,000/-', 'description' => 'Hygienic neck skin wax', 'branch_id' => 'gulshan', 'sort_order' => 4],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing Nose / Ear', 'price' => '1,000/-', 'description' => 'Pain-free premium interior wax', 'branch_id' => 'gulshan', 'sort_order' => 5],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing Hands', 'price' => '2,000/-', 'description' => 'Full arm waxing', 'branch_id' => 'gulshan', 'sort_order' => 6],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing Back Upper Body', 'price' => '3,000/-', 'description' => 'Upper back wax', 'branch_id' => 'gulshan', 'sort_order' => 7],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing Half Legs / Full Legs', 'price' => '3,000/- & Above', 'description' => 'Leg area hair removal', 'branch_id' => 'gulshan', 'sort_order' => 8],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing Front Upper Body', 'price' => '3,500/-', 'description' => 'Chest and stomach area wax', 'branch_id' => 'gulshan', 'sort_order' => 9],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing Half Body (Neck+Hands+Front+Back)', 'price' => '6,000/- & Above', 'description' => 'Comprehensive upper torso wax package', 'branch_id' => 'gulshan', 'sort_order' => 10],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing Full Body', 'price' => '10,000/-', 'description' => 'Complete full body premium hair removal wax', 'branch_id' => 'gulshan', 'sort_order' => 11],
            // SPA & BODY SCRUB
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Aromatic Bath (15 Minutes)', 'price' => '10,000/-', 'description' => 'Relaxing jacuzzi-level warm bath infused with premium essential oils', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Full Body Scrub with Steam', 'price' => '5,500/- & Above', 'description' => 'Organic dermabrasion scrub followed by complete hot steam', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Back Scrub', 'price' => '2,400/- & Above', 'description' => 'Shedding dead cells, deep cleansing', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Leg Scrub', 'price' => '1,200/-', 'description' => 'Exfoliation for legs', 'branch_id' => 'gulshan', 'sort_order' => 3],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Hand Scrub', 'price' => '1,000/-', 'description' => 'Smooth, hydrating hand scrub', 'branch_id' => 'gulshan', 'sort_order' => 4],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Shop Full Body Scrub with Steam', 'price' => '11,000/-', 'description' => 'Super premium imported Body Shop exfoliation', 'branch_id' => 'gulshan', 'sort_order' => 5],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Smoothing Scrub', 'price' => '8,000/-', 'description' => 'Cell renewal visual smoothing scrub', 'branch_id' => 'gulshan', 'sort_order' => 6],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Unilever Scrub Full Body Scrub', 'price' => '6,500/-', 'description' => 'Clean, hydrating scrub combo', 'branch_id' => 'gulshan', 'sort_order' => 7],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Shop Hand Scrub', 'price' => '2,000/-', 'description' => 'Premium hydration and shine', 'branch_id' => 'gulshan', 'sort_order' => 8],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Shop Back Scrub', 'price' => '6,000/-', 'description' => 'Advanced spine smoothing and steam', 'branch_id' => 'gulshan', 'sort_order' => 9],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Shop Foot Scrub', 'price' => '2,000/-', 'description' => 'Dead skin removal with deep moisturizing', 'branch_id' => 'gulshan', 'sort_order' => 10],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Aloe Vera Body Scrub Full Body', 'price' => '5,000/-', 'description' => 'Sensitive skin safe cooling full body scrub', 'branch_id' => 'gulshan', 'sort_order' => 11],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Royal Body Smoothing Scrub', 'price' => '16,000/-', 'description' => 'The absolute pinnacle of luxury full body pampering', 'branch_id' => 'gulshan', 'sort_order' => 12],
            // HAND & FOOT CARE
            ['category' => 'HAND & FOOT CARE', 'name' => 'Manicure', 'price' => '800/-', 'description' => 'Clean cuticle trim, buffing, and hand cream', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'HAND & FOOT CARE', 'name' => 'Pedicure', 'price' => '1,200/-', 'description' => 'Deep foot soak, heel scrubbing, and massage', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'HAND & FOOT CARE', 'name' => 'Manicure Deluxe', 'price' => '1,200/-', 'description' => 'Warm oils massage, paraffin mask hydration', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'HAND & FOOT CARE', 'name' => 'Pedicure Deluxe', 'price' => '2,300/-', 'description' => 'Signature premium mud mask, hot stone massage', 'branch_id' => 'gulshan', 'sort_order' => 3],
            // MASSAGE LAB
            ['category' => 'MASSAGE LAB', 'name' => 'Neck, Head & Foot Massage (60 Mins)', 'price' => '2,200/-', 'description' => 'Full tension release combo', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'MASSAGE LAB', 'name' => 'Neck, Head & Foot Massage (90 Mins)', 'price' => '3,000/-', 'description' => 'Extended relieving treatment', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'MASSAGE LAB', 'name' => 'Back, Neck & Shoulder Massage (30 Mins)', 'price' => '1,500/-', 'description' => 'Quick trigger points de-stress', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'MASSAGE LAB', 'name' => 'Thai Foot Reflexology (45 Mins)', 'price' => '1,200/-', 'description' => 'Pressure points mapping for overall body energy', 'branch_id' => 'gulshan', 'sort_order' => 3],
            ['category' => 'MASSAGE LAB', 'name' => 'Thai Foot Reflexology (60 Mins)', 'price' => '1,600/-', 'description' => 'Standard reflex massage', 'branch_id' => 'gulshan', 'sort_order' => 4],
            ['category' => 'MASSAGE LAB', 'name' => 'Thai Foot Reflexology (90 Mins)', 'price' => '2,200/-', 'description' => 'Ultimate leg and nervous recovery', 'branch_id' => 'gulshan', 'sort_order' => 5],
            ['category' => 'MASSAGE LAB', 'name' => 'Head Massage with Hair Tonic (15 Mins)', 'price' => '1,000/-', 'description' => 'Deep cerebral massage with nutrient-rich liquid', 'branch_id' => 'gulshan', 'sort_order' => 6],
            ['category' => 'MASSAGE LAB', 'name' => 'Head Massage without Hair Tonic (15 Mins)', 'price' => '600/-', 'description' => 'Standard dry scalp soothing massage', 'branch_id' => 'gulshan', 'sort_order' => 7],
            ['category' => 'MASSAGE LAB', 'name' => 'Chair Massage (20 Mins)', 'price' => '1,200/-', 'description' => 'Ergonomic neck-to-hip quick massage', 'branch_id' => 'gulshan', 'sort_order' => 8],
            ['category' => 'MASSAGE LAB', 'name' => 'Bamboo Massage (60 Mins)', 'price' => '3,500/-', 'description' => 'Warm bamboo rods rolling to break deep muscle fibers', 'branch_id' => 'gulshan', 'sort_order' => 9],
            ['category' => 'MASSAGE LAB', 'name' => 'Bamboo Massage (90 Mins)', 'price' => '4,800/-', 'description' => 'Extended deep tension bamboo therapy', 'branch_id' => 'gulshan', 'sort_order' => 10],
            ['category' => 'MASSAGE LAB', 'name' => 'Hot Stone Massage (60 Mins)', 'price' => '3,800/-', 'description' => 'Therapeutic warm volcanic basalt stones layout', 'branch_id' => 'gulshan', 'sort_order' => 11],
            ['category' => 'MASSAGE LAB', 'name' => 'Hot Stone Massage (90 Mins)', 'price' => '5,000/-', 'description' => 'Deep heat thermal stone recovery', 'branch_id' => 'gulshan', 'sort_order' => 12],
            ['category' => 'MASSAGE LAB', 'name' => 'Swedish Massage (60 Mins)', 'price' => '3,200/-', 'description' => 'Classic long-stroke circulatory massage', 'branch_id' => 'gulshan', 'sort_order' => 13],
            ['category' => 'MASSAGE LAB', 'name' => 'Swedish Massage (90 Mins)', 'price' => '4,200/-', 'description' => 'Ultimate light-to-medium relax', 'branch_id' => 'gulshan', 'sort_order' => 14],
            ['category' => 'MASSAGE LAB', 'name' => 'Aroma Massage (60 Mins)', 'price' => '2,800/-', 'description' => 'Lavendar/Eucalyptus therapeutic essential oil massage', 'branch_id' => 'gulshan', 'sort_order' => 15],
            ['category' => 'MASSAGE LAB', 'name' => 'Aroma Massage (90 Mins)', 'price' => '3,700/-', 'description' => 'Total aromatherapy recharge', 'branch_id' => 'gulshan', 'sort_order' => 16],
            ['category' => 'MASSAGE LAB', 'name' => 'Indian / Traditional Massage (60 Mins)', 'price' => '2,500/-', 'description' => 'Deep kneading physical muscle reset', 'branch_id' => 'gulshan', 'sort_order' => 17],
            ['category' => 'MASSAGE LAB', 'name' => 'Indian / Traditional Massage (90 Mins)', 'price' => '3,500/-', 'description' => 'Full body restorative massage', 'branch_id' => 'gulshan', 'sort_order' => 18],
            ['category' => 'MASSAGE LAB', 'name' => 'Deep Tissue Body Massage (60 Mins)', 'price' => '3,200/-', 'description' => 'High-pressure physical knot removal', 'branch_id' => 'gulshan', 'sort_order' => 19],
            ['category' => 'MASSAGE LAB', 'name' => 'Deep Tissue Body Massage (90 Mins)', 'price' => '4,200/-', 'description' => 'Maestra muscle restoration', 'branch_id' => 'gulshan', 'sort_order' => 20],
            ['category' => 'MASSAGE LAB', 'name' => 'Thai Body Massage (60 Mins)', 'price' => '3,200/-', 'description' => 'Passive yoga stretches, compressions', 'branch_id' => 'gulshan', 'sort_order' => 21],
            ['category' => 'MASSAGE LAB', 'name' => 'Thai Body Massage (90 Mins)', 'price' => '4,200/-', 'description' => 'Extended physical stretch alignment', 'branch_id' => 'gulshan', 'sort_order' => 22],
            // SPORTS & CUPPING
            ['category' => 'SPORTS & CUPPING', 'name' => 'Back Cupping with 30 Min Massage', 'price' => '4,000/-', 'description' => 'Detoxifying vacuum cupping of the back', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'SPORTS & CUPPING', 'name' => 'Full Body Cupping with 30 Min Massage', 'price' => '7,000/-', 'description' => 'Full-body muscle blood flow cupping activation', 'branch_id' => 'gulshan', 'sort_order' => 1],
            ['category' => 'SPORTS & CUPPING', 'name' => 'Sports Massage (90 Mins)', 'price' => '5,500/-', 'description' => 'High-intensity athletic relief and joint flexibility', 'branch_id' => 'gulshan', 'sort_order' => 2],
            ['category' => 'SPORTS & CUPPING', 'name' => 'Back Fire Cupping with 30 Min Massage', 'price' => '7,000/-', 'description' => 'Traditional dynamic heat vacuum cupping therapy', 'branch_id' => 'gulshan', 'sort_order' => 3],
            ['category' => 'SPORTS & CUPPING', 'name' => 'Full Body Fire Cupping with 30 Min Massage', 'price' => '10,000/-', 'description' => 'The peak physical recovery standard for high fatigue', 'branch_id' => 'gulshan', 'sort_order' => 4],
            // MAKE-OVER ART
            ['category' => 'MAKE-OVER ART', 'name' => 'Groom Make-Up', 'price' => '4,000/-', 'description' => 'Blemish-free high definition camera matching groom facial setting', 'branch_id' => 'gulshan', 'sort_order' => 0],
            ['category' => 'MAKE-OVER ART', 'name' => 'Studio / Model Make-Up', 'price' => '6,000/-', 'description' => 'Studio flash-safe professional modeling look design', 'branch_id' => 'gulshan', 'sort_order' => 1],

            // BASHUNDHARA BRANCH
            // HAIR CUTTING
            ['category' => 'HAIR CUTTING', 'name' => 'Hair Cut with Shampoo', 'price' => '700/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'HAIR CUTTING', 'name' => 'Hair Cut (Stylish/Fashion/Catalogue)', 'price' => '1,000/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'HAIR CUTTING', 'name' => 'Normal Beard Trim', 'price' => '500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'HAIR CUTTING', 'name' => 'Stylish Beard Trim', 'price' => '600/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            ['category' => 'HAIR CUTTING', 'name' => 'Shave', 'price' => '500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 4],
            ['category' => 'HAIR CUTTING', 'name' => 'Bald-Adult', 'price' => '700/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 5],
            ['category' => 'HAIR CUTTING', 'name' => 'Bald-Infant/Children', 'price' => '700/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 6],
            // HAIR SETTING
            ['category' => 'HAIR SETTING', 'name' => 'Hair Setting (Mousse/Cream/Gel/Spray/Wax)', 'price' => '500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'HAIR SETTING', 'name' => 'Hair Shampoo With Conditioner', 'price' => '500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'HAIR SETTING', 'name' => 'Blow Dry', 'price' => '500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'HAIR SETTING', 'name' => 'Hair Iron', 'price' => '2,000/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            ['category' => 'HAIR SETTING', 'name' => 'Hair Rebonding', 'price' => '10,000/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 4],
            ['category' => 'HAIR SETTING', 'name' => 'Super Shine Karateen', 'price' => '16,000/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 5],
            // SPECIAL CHARGE LIST
            ['category' => 'SPECIAL CHARGE LIST', 'name' => 'Shower & Steam (30 Min)', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'SPECIAL CHARGE LIST', 'name' => 'Shower without Steam', 'price' => '500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'SPECIAL CHARGE LIST', 'name' => 'Personal Room Charge', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'SPECIAL CHARGE LIST', 'name' => 'Sauna', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            ['category' => 'SPECIAL CHARGE LIST', 'name' => 'Home Service', 'price' => '2,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 4],
            // HAIR TREATMENT
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Massage with steam (Harbal Oil)', 'price' => '1,000/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Massage with steam (Castor Oil)', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Massage with steam (Almond Oil)', 'price' => '1,200/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Massage with steam (Argan Shahanaj Oil)', 'price' => '1,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Massage with steam (Garlic Oil)', 'price' => '1,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 4],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Massage with steam (Body Shop Coco Oil)', 'price' => '1,800/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 5],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hot Oil Therapy with Alovera Pack', 'price' => '2,000/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 6],
            ['category' => 'HAIR TREATMENT', 'name' => 'Caring Hair SPA', 'price' => '2,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 7],
            ['category' => 'HAIR TREATMENT', 'name' => 'L’OREAL Hair SPA', 'price' => '2,200/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 8],
            ['category' => 'HAIR TREATMENT', 'name' => 'Hair Shine (Temporary shine enhancement before any special events or party)', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 9],
            ['category' => 'HAIR TREATMENT', 'name' => 'Treatment for Rebonding Hair', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 10],
            ['category' => 'HAIR TREATMENT', 'name' => 'Zem Treatment', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 11],
            ['category' => 'HAIR TREATMENT', 'name' => 'Keratin Treatment', 'price' => '3,200/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 12],
            ['category' => 'HAIR TREATMENT', 'name' => 'L’OREAL (Treatment for hair Loss, dry & Sensetive scalp)', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 13],
            ['category' => 'HAIR TREATMENT', 'name' => 'L’OREAL (Treatment for dandruff Control)', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 14],
            ['category' => 'HAIR TREATMENT', 'name' => 'European Treatment (Dandruff)', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 15],
            ['category' => 'HAIR TREATMENT', 'name' => 'European Treatment (Hair Fall)', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 16],
            ['category' => 'HAIR TREATMENT', 'name' => 'European Treatment (Damage)', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 17],
            ['category' => 'HAIR TREATMENT', 'name' => 'Organ Treatment', 'price' => '4,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 18],
            ['category' => 'HAIR TREATMENT', 'name' => 'Garnear Treatment', 'price' => '5,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 19],
            ['category' => 'HAIR TREATMENT', 'name' => 'VLCC Hair Treatment Dandruff', 'price' => '6,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 20],
            ['category' => 'HAIR TREATMENT', 'name' => 'VLCC Hair Treatment Hair Fall', 'price' => '6,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 21],
            ['category' => 'HAIR TREATMENT', 'name' => 'Body Shop Hair Treatment', 'price' => '7,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 22],
            ['category' => 'HAIR TREATMENT', 'name' => 'Ginger Hair Treatment', 'price' => '8,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 23],
            ['category' => 'HAIR TREATMENT', 'name' => 'Olafex Hair Treatment', 'price' => '9,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 24],
            // FACE MASSAGE & SCRUB
            ['category' => 'FACE MASSAGE & SCRUB', 'name' => 'Stacy Face Massage', 'price' => '1,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'FACE MASSAGE & SCRUB', 'name' => 'Body Shop Face Massage Vitamin-C', 'price' => '2,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'FACE MASSAGE & SCRUB', 'name' => 'Special Face Massage (Body Shop) Vitamin-E', 'price' => '3,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            // FAIR POLISH
            ['category' => 'FAIR POLISH', 'name' => 'Face', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'FAIR POLISH', 'name' => 'Neck', 'price' => '600/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'FAIR POLISH', 'name' => 'Face + Neck', 'price' => '1,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'FAIR POLISH', 'name' => 'Hands', 'price' => '1,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            ['category' => 'FAIR POLISH', 'name' => 'Half body - Front', 'price' => '2,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 4],
            ['category' => 'FAIR POLISH', 'name' => 'Half body - back', 'price' => '2,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 5],
            ['category' => 'FAIR POLISH', 'name' => 'Face + Neck + Hands', 'price' => '2,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 6],
            ['category' => 'FAIR POLISH', 'name' => 'Upper Body', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 7],
            ['category' => 'FAIR POLISH', 'name' => 'Full body', 'price' => '5,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 8],
            // MILK FAIR POLISH
            ['category' => 'MILK FAIR POLISH', 'name' => 'Face + Neck', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'MILK FAIR POLISH', 'name' => 'Front & Back', 'price' => '12,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'MILK FAIR POLISH', 'name' => 'Full Body', 'price' => '15,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            // HAIR DYE & OTHERS
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Moustache Dye', 'price' => '500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Beard Dye (Ammonia Free) Bigen', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Beard Dye with Inova Color', 'price' => '1,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Dye Color apply only (Service charge)+Shampoo', 'price' => '800/- & above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Color Apply Charge', 'price' => '1,000/- & above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 4],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Hi-Speed', 'price' => '1,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 5],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Bigen', 'price' => '1,200/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 6],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Bigen (Ammonia Free)', 'price' => '1,600/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 7],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Mehedi Color', 'price' => '2,000/- & above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 8],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Just for Man Color', 'price' => '2,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 9],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'L’Oréal Majnel Color', 'price' => '2,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 10],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'L’Oréal INOA (Ammonia Free)', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 11],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Keune Color (Ammonia Free)', 'price' => '3,000/- & above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 12],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Color sticks (Each colors)', 'price' => '1,000/- & above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 13],
            ['category' => 'HAIR DYE & OTHERS', 'name' => 'Fashion Color with Pre-lightening', 'price' => '9,000/- & above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 14],
            // BODY FASHION & WAX
            ['category' => 'BODY FASHION & WAX', 'name' => 'Eye Brows', 'price' => '300/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Face Threading', 'price' => '600/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Ears Candeling', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing-Face', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing-Neck', 'price' => '600/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 4],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing-Hands', 'price' => '2,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 5],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing-Back-Upper body', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 6],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing-Front-Upper body', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 7],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing-(Half Legs-Full Legs)', 'price' => '2,000/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 8],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing-Half Body (Neck+Hands+Front & Back)', 'price' => '6,000/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 9],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing-Full Body', 'price' => '8,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 10],
            ['category' => 'BODY FASHION & WAX', 'name' => 'Waxing-Nose, Ear', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 11],
            // SPA & BODY SCRUB
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Spa', 'price' => '9,000/-', 'description' => 'Basic Pedicure & Manicure (45 Minutes), Aroma Relaxing massage (45 Minutes), Herbal Body pack (25 Minutes), Aromatic Bath (15 Minutes)', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Full body scrub with steam', 'price' => '4,000/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Back scrub', 'price' => '2,000/- & Above', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Leg Scrub', 'price' => '1,200/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Hand scrub', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 4],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Shop Full Body scrub with steam', 'price' => '10,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 5],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Smoothing Scrab', 'price' => '8,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 6],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Uniliver Scrab (Full Body Scrab)', 'price' => '6,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 7],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Shop Back Scrab', 'price' => '5,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 8],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Shop Hand Scrab', 'price' => '1,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 9],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Body Shop Foot Scrab', 'price' => '2,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 10],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Alovera Body Scrab (Full Body)', 'price' => '5,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 11],
            ['category' => 'SPA & BODY SCRUB', 'name' => 'Royal Body Smoothing Scrab', 'price' => '16,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 12],
            // HAND & FOOT CARE
            ['category' => 'HAND & FOOT CARE', 'name' => 'Manicure', 'price' => '700/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'HAND & FOOT CARE', 'name' => 'Pedicure', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'HAND & FOOT CARE', 'name' => 'Manicure Deluxe', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'HAND & FOOT CARE', 'name' => 'Pedicure Deluxe', 'price' => '2,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            // FACIAL
            ['category' => 'FACIAL', 'name' => 'Adonis Special', 'price' => '3,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'FACIAL', 'name' => 'Aloe Vera facial for all types of skin', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'FACIAL', 'name' => 'Pearl Facial', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'FACIAL', 'name' => 'Gold Facial', 'price' => '5,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            ['category' => 'FACIAL', 'name' => 'Diamond Facial', 'price' => '4,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 4],
            ['category' => 'FACIAL', 'name' => 'Janssen Man Facial', 'price' => '7,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 5],
            ['category' => 'FACIAL', 'name' => 'Janssen Whitening facial for all types of skin', 'price' => '7,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 6],
            ['category' => 'FACIAL', 'name' => 'Lotus Gold', 'price' => '8,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 7],
            ['category' => 'FACIAL', 'name' => 'Lotus Platinum', 'price' => '10,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 8],
            ['category' => 'FACIAL', 'name' => 'Body Shop Vitamin-C Facial', 'price' => '7,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 9],
            ['category' => 'FACIAL', 'name' => 'Body Shop Seedwed', 'price' => '9,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 10],
            ['category' => 'FACIAL', 'name' => 'Body Shop Drops of Light', 'price' => '12,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 11],
            ['category' => 'FACIAL', 'name' => 'Janssen Whitening Facial (Orange)', 'price' => '8,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 12],
            // MASSAGE LAB
            ['category' => 'MASSAGE LAB', 'name' => 'Neck, Head & Foot Massage (60 Minutes)', 'price' => '2,200/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 0],
            ['category' => 'MASSAGE LAB', 'name' => 'Neck, Head & Foot Massage (90 Minutes)', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 1],
            ['category' => 'MASSAGE LAB', 'name' => 'Back, Neck & Shoulder Massage (30 Minutes)', 'price' => '1,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 2],
            ['category' => 'MASSAGE LAB', 'name' => 'Thai Foot Reflexology (45 Minutes)', 'price' => '1,200/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 3],
            ['category' => 'MASSAGE LAB', 'name' => 'Thai Foot Reflexology (60 Minutes)', 'price' => '1,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 4],
            ['category' => 'MASSAGE LAB', 'name' => 'Thai Foot Reflexology (90 Minutes)', 'price' => '2,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 5],
            ['category' => 'MASSAGE LAB', 'name' => 'Head Massage with Hair Tonic (15 Minutes)', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 6],
            ['category' => 'MASSAGE LAB', 'name' => 'Head Massage without Hair Tonic (15 Minutes)', 'price' => '600/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 7],
            ['category' => 'MASSAGE LAB', 'name' => 'Chair Massage (20 Minutes)', 'price' => '1,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 8],
            ['category' => 'MASSAGE LAB', 'name' => 'Indian / Traditional Massage (60 Minutes)', 'price' => '2,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 9],
            ['category' => 'MASSAGE LAB', 'name' => 'Indian / Traditional Massage (90 Minutes)', 'price' => '3,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 10],
            ['category' => 'MASSAGE LAB', 'name' => 'Deep Tissue Body Massage (60 Minutes)', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 11],
            ['category' => 'MASSAGE LAB', 'name' => 'Deep Tissue Body Massage (90 Minutes)', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 12],
            ['category' => 'MASSAGE LAB', 'name' => 'Thai Body Massage (60 Minutes)', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 13],
            ['category' => 'MASSAGE LAB', 'name' => 'Thai Body Massage (90 Minutes)', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 14],
            ['category' => 'MASSAGE LAB', 'name' => 'Bamboo Massage (30 Minutes)', 'price' => '3,200/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 15],
            ['category' => 'MASSAGE LAB', 'name' => 'Bamboo Massage (90 Minutes)', 'price' => '4,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 16],
            ['category' => 'MASSAGE LAB', 'name' => 'Hot Stone Massage (30 Minutes)', 'price' => '3,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 17],
            ['category' => 'MASSAGE LAB', 'name' => 'Hot Stone Massage (90 Minutes)', 'price' => '4,600/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 18],
            ['category' => 'MASSAGE LAB', 'name' => 'Swedish Massage (60 Minutes)', 'price' => '3,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 19],
            ['category' => 'MASSAGE LAB', 'name' => 'Swedish Massage (90 Minutes)', 'price' => '4,000/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 20],
            ['category' => 'MASSAGE LAB', 'name' => 'Aroma Massage (60 Minutes)', 'price' => '2,600/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 21],
            ['category' => 'MASSAGE LAB', 'name' => 'Aroma Massage (90 Minutes)', 'price' => '3,500/-', 'description' => '', 'branch_id' => 'bashundhara', 'sort_order' => 22],
        ];

        foreach ($allItems as $item) {
            DB::table('price_list_items')->insert($item);
        }
    }
}

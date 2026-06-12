-- Adonis CMS MySQL schema
-- Import this into the MySQL database you created in cPanel/phpMyAdmin.
-- The Node app expects these tables in MYSQL_DATABASE.

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS services (
  id VARCHAR(120) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  durationMin INT NOT NULL DEFAULT 45,
  priceBDT INT NOT NULL DEFAULT 0,
  category VARCHAR(40) NOT NULL DEFAULT 'hair',
  icon VARCHAR(80) NOT NULL DEFAULT 'Scissors'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS barbers (
  id VARCHAR(120) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  experienceYears INT NOT NULL DEFAULT 0,
  specialty VARCHAR(255),
  portraitUrl TEXT,
  bio TEXT,
  rating DECIMAL(3,1) NOT NULL DEFAULT 5.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cms_meta (
  meta_key VARCHAR(80) PRIMARY KEY,
  meta_value LONGTEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS blogs (
  id VARCHAR(120) PRIMARY KEY,
  slug VARCHAR(160) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  excerpt TEXT,
  coverImage TEXT,
  contentHtml LONGTEXT,
  seoTitle VARCHAR(255),
  seoDescription TEXT,
  status VARCHAR(30) NOT NULL DEFAULT 'draft',
  createdAt DATETIME NOT NULL,
  updatedAt DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO services (id, name, description, durationMin, priceBDT, category, icon) VALUES
('precision-haircut', 'Precision Haircut', 'Expert consultation, refreshing shampoo, premium haircut, sharp styling, and high-performance finish.', 45, 700, 'hair', 'Scissors'),
('skin-fade', 'Skin Fade & Modern Styling', 'Expert low/mid/high zero fade blend, hair texturizing, and high-hold pomade styling.', 60, 2500, 'hair', 'Sparkles'),
('royal-beard-shaping', 'Royal Beard Shaping', 'Beard trimming, symmetrical oil outlining, free-hand clippers sculpting, and moisturizing balm.', 30, 1200, 'beard', 'Smile'),
('hot-towel-shave', 'Hot Towel Shave', 'Classic straight razor double pass, warm pre-shave lather, facial massage with signature mint cold towel.', 40, 1500, 'beard', 'Flame'),
('hair-spa', 'Hair Spa & Treatment', 'Deep scalp detoxification, deep conditioning charcoal mask, steam activation, and shoulder stress-relief massage.', 60, 3500, 'spa', 'Flower'),
('scalp-therapy', 'Scalp Therapy', 'Soothing tea-tree cooling treatment to cure itchiness, stimulate hair follicle circulation and strengthen roots.', 45, 3000, 'spa', 'ShieldAlert'),
('facial-treatment', 'Facial Treatment', 'Adonis signature exfoliation, golden age anti-pollution face scrub, blackhead vacuum extraction, and hydrating ice therapy.', 50, 4000, 'spa', 'UserCheck'),
('vip-grooming-package', 'VIP Grooming Package', 'The ultimate royal experience: Precision Cut + Hot Towel Shave + Golden-Age Scalp Spa + Signature Facial + Specialty Beverage.', 120, 7500, 'pack', 'Crown')
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  description = VALUES(description),
  durationMin = VALUES(durationMin),
  priceBDT = VALUES(priceBDT),
  category = VALUES(category),
  icon = VALUES(icon);

INSERT INTO barbers (id, name, experienceYears, specialty, portraitUrl, bio, rating) VALUES
('tariq', 'Babul Chandra Shil', 12, 'Skin Fade & Modern Texturizing', '/assets/images/babul_barbar.png', 'Renowned stylist with 12 years of craftsmanship. Expert in razor fades and sculpting bold executive silhouettes.', 4.9),
('kamran', 'Antor Mondol', 9, 'Royal Shaves & Beard Architecture', '/assets/images/master_barber_portrait_1779269169728.png', 'A true maestro of the straight razor, specialized in symmetrical beard mapping and hot towel restoration.', 5.0),
('arif', 'Rofiqul Islam', 7, 'Classic Scissor Cuts & Scalp Health', '/assets/images/rofiq_barbar.png', 'Dedicated to traditional high-end scissor sculpting and therapeutic scalp treatments to promote long-term volume.', 4.8),
('javed', 'Chandra', 10, 'Hair Color & Keratin Treatments', '/assets/images/chandra.png', 'Color specialist with a decade of expertise in ammonia-free dye systems, keratin bonding, and high-fashion tone transformations.', 4.9),
('rohit', 'Kalu Rupa Shil', 6, 'Facial Treatments & Spa Services', '/assets/images/kalu_rupa.png', 'Certified skin-care professional with advanced training in premium facials, body scrubs, and therapeutic massage techniques.', 4.7),
('salim', 'Saimon', 8, 'Massage Therapy & Body Spa', '/assets/images/saimon.png', 'Licensed massage therapist trained in Swedish, Deep Tissue, Thai and Aroma techniques. Known for his precision pressure-point therapy.', 4.8),
('rakib', 'Dulal Chandra', 5, 'Modern Beard Styling & Waxing', '/assets/images/dulal_chandra.png', 'Young and passionate groomer specializing in contemporary beard shaping, wax-based styling, and precise razor-line finishes.', 4.6)
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  experienceYears = VALUES(experienceYears),
  specialty = VALUES(specialty),
  portraitUrl = VALUES(portraitUrl),
  bio = VALUES(bio),
  rating = VALUES(rating);

INSERT INTO cms_meta (meta_key, meta_value) VALUES
('settings', JSON_OBJECT(
  'brandName', 'ADONIS',
  'brandSubtitle', 'Premium Grooming. Redefined Masculinity.',
  'heroTitle', 'Craft Your Identity With Precision',
  'heroSubtitle', 'Experience elite barbering at Adonis Men’s Grooming, where modern style meets timeless perfection in the heart of Dhaka.',
  'heroBg', '/assets/images/adonis_executive_lounge_1779270704894.png',
  'aboutStory', 'Adonis Men’s Grooming is a premium barbershop brand in Dhaka dedicated to redefining modern masculinity through precision grooming, luxury service, and personalized styling. Every cut, shave, and treatment is designed to elevate confidence and identity.',
  'aboutDescription', 'We believe that grooming is not merely a transaction—it represents a curated ritual of premium transition. Nestled in Dhaka’s premier neighborhoods, Adonis pairs classic European barber heritage with high-end, contemporary Dubai hotel-standard lounge accommodations. From the selection of our premium organic grooming balms to the exact temperature parameters of our steaming mint towels, every detail is meticulously orchestrated to deliver perfection.',
  'contactEmail', 'info@adonis.com.bd',
  'openHoursDays', 'Everyday (Sat - Fri)',
  'openHoursTime', '10:00 AM – 10:00 PM',
  'phoneNumbers', JSON_ARRAY('+880 1919-700800', '+880 1700-600333'),
  'facebookUrl', 'https://facebook.com/adonis.bd',
  'instagramUrl', 'https://instagram.com/adonis.grooming',
  'whatsappUrl', 'https://wa.me/8801919700800'
)),
('smtp', JSON_OBJECT(
  'host', '',
  'port', 587,
  'secure', false,
  'user', '',
  'pass', '',
  'fromEmail', 'noreply@adonis.com.bd',
  'adminEmails', 'admin@adonis.com.bd'
))
ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value);

INSERT INTO blogs (id, slug, title, excerpt, coverImage, contentHtml, seoTitle, seoDescription, status, createdAt, updatedAt) VALUES
(
  'premium-mens-grooming-dhaka',
  'premium-mens-grooming-dhaka',
  'Premium Men’s Grooming in Dhaka: The Adonis Guide to a Sharper Look',
  'Discover how professional haircuts, beard shaping, facials, massage, and premium salon care help modern men in Dhaka look sharper and feel more confident.',
  '/assets/images/adonis_styling_chairs_1779270725139.png',
  '<h2>Why Men’s Grooming Matters More Than Ever</h2><p>Modern grooming is not only about looking polished. It is about confidence, hygiene, presentation, and personal identity. At <strong>Adonis Men’s Grooming in Dhaka</strong>, every service is designed to help men look sharp while enjoying a calm, premium salon experience.</p><h3>Precision Haircuts for Every Lifestyle</h3><p>A professional haircut should match your face shape, profession, hair texture, and daily styling routine. Whether you prefer a classic scissor cut, a modern skin fade, or a refined executive style, the right haircut can instantly improve your overall appearance.</p><ul><li>Clean fades and sharp blending</li><li>Face-shape-based consultation</li><li>Professional styling and finishing</li></ul><h3>Beard Shaping and Hot Towel Shaves</h3><p>For men with facial hair, beard maintenance is essential. A balanced beard line, clean cheek contour, and sharp neckline can define the jaw and create a more structured look. Hot towel shaving also refreshes the skin and delivers a traditional barbering experience.</p><h4>Facials, Spa Care, and Relaxation</h4><p>Dhaka’s weather, dust, and stress can affect skin and scalp health. Premium facials, hair spa treatments, massage, and body care help restore freshness and reduce everyday fatigue.</p><h5>Why Choose Adonis?</h5><p><strong>Adonis Men’s Grooming</strong> combines expert stylists, premium products, luxury interiors, and personalized service for men who expect more than a basic haircut.</p><h6>Book Your Premium Grooming Session</h6><p>If you are searching for a premium men’s salon in Dhaka, visit Adonis in Gulshan or Bashundhara and experience grooming designed around confidence, comfort, and detail.</p>',
  'Premium Men’s Grooming in Dhaka | Adonis Men’s Grooming',
  'Looking for the best men’s grooming salon in Dhaka? Learn how Adonis combines precision haircuts, beard styling, facials, spa care, and premium salon service.',
  'published',
  '2026-06-04 00:00:00',
  '2026-06-04 00:00:00'
)
ON DUPLICATE KEY UPDATE
  slug = VALUES(slug),
  title = VALUES(title),
  excerpt = VALUES(excerpt),
  coverImage = VALUES(coverImage),
  contentHtml = VALUES(contentHtml),
  seoTitle = VALUES(seoTitle),
  seoDescription = VALUES(seoDescription),
  status = VALUES(status),
  updatedAt = VALUES(updatedAt);

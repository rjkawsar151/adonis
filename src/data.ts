import { Service, Branch, Barber, Testimonial, BlogPost } from './types';
import { assetUrl } from './assetUrl';

// Images are served from public/assets/images
export const IMAGES = {
  logo: assetUrl('/assets/images/adonis_logo_1779270678761.png'),
  heroBg: assetUrl('/assets/images/adonis_executive_lounge_1779270704894.png'),
  barberPortrait: assetUrl('/assets/images/master_barber_portrait_1779269169728.png'),
  shaveAction: assetUrl('/assets/images/hot_towel_shave_1779269193622.png'),
  shaveBg: assetUrl('/assets/images/hot_towel_shave_1779269193622.png'),
  loungeChairs: assetUrl('/assets/images/adonis_styling_chairs_1779270725139.png'),

  // High quality Unsplash placeholder images matching luxury barbershops
  gallery: [
    {
      url: assetUrl('/assets/images/adonis_executive_lounge_1779270704894.png'),
      title: 'Adonis Executive Lounge',
      subtitle: 'Dhaka\'s premium space & Master Barbers'
    },
    {
      url: assetUrl('/assets/images/adonis_styling_chairs_1779270725139.png'),
      title: 'Elite Styling Suite',
      subtitle: 'Bespoke design, gold frames & custom details'
    },
    {
      url: assetUrl('/assets/images/master_barber_portrait_1779269169728.png'),
      title: 'Precision Styling & Cuts',
      subtitle: 'Elite Men Haircuts and Texturizing'
    },
    {
      url: assetUrl('/assets/images/hot_towel_shave_1779269193622.png'),
      title: 'Royal Straight Razor Shave',
      subtitle: 'Traditional lathering and steaming'
    },
    {
      url: assetUrl('/assets/images/luxury_barbershop_interior_1779269147619.png'),
      title: 'Luxury Barbershop Interior',
      subtitle: 'Premium styling bays and ambient lighting'
    },
    {
      url: assetUrl('/assets/images/executive.png'),
      title: 'Executive Lounge Reception',
      subtitle: 'Leather chesterfield sofas and barista coffee'
    },
    {
      url: assetUrl('/assets/images/reception.png'),
      title: 'VIP Reception Area',
      subtitle: 'Warm lighting and elegant premium reception'
    },
    {
      url: assetUrl('/assets/images/vip.png'),
      title: 'VIP Private Suite',
      subtitle: 'Soundproofed suite with personal butler service'
    },
    {
      url: assetUrl('/assets/images/steam.png'),
      title: 'Steam & Sauna Chamber',
      subtitle: 'Turkish-style steam chambers for deep detox'
    },
    {
      url: assetUrl('/assets/images/sauana.png'),
      title: 'Jacuzzi Hydrotherapy Bath',
      subtitle: 'Chromotherapy lighting and essential oil infusion'
    }
  ]
};

export const SERVICES: Service[] = [
  {
    id: 'precision-haircut',
    name: 'Precision Haircut',
    description: 'Expert consultation, refreshing shampoo, premium haircut, sharp styling, and high-performance finish.',
    durationMin: 45,
    priceBDT: 700,
    category: 'hair',
    icon: 'Scissors'
  },
  {
    id: 'skin-fade',
    name: 'Stylish Beard Trim',
    description: 'Beard shaping and styling with premium trimming tools.',
    durationMin: 60,
    priceBDT: 600,
    category: 'hair',
    icon: 'Sparkles'
  },
  {
    id: 'royal-beard-shaping',
    name: 'Beard Dye with Inova Color',
    description: 'Beard trimming and styling with premium dye.',
    durationMin: 30,
    priceBDT: 1500,
    category: 'beard',
    icon: 'Smile'
  },
  {
    id: 'hot-towel-shave',
    name: 'Hot Towel Shave',
    description: 'Classic straight razor double pass, warm pre-shave lather, facial massage with signature mint cold towel.',
    durationMin: 40,
    priceBDT: 1500,
    category: 'beard',
    icon: 'Flame'
  },
  {
    id: 'hair-spa',
    name: 'Hair Spa & Treatment',
    description: 'Deep scalp detoxification, deep conditioning charcoal mask, steam activation, and shoulder stress-relief massage.',
    durationMin: 60,
    priceBDT: 3500,
    category: 'spa',
    icon: 'Flower'
  },
  {
    id: 'scalp-therapy',
    name: 'Scalp Therapy',
    description: 'Soothing tea-tree cooling treatment to cure itchiness, stimulate hair follicle circulation and strengthen roots.',
    durationMin: 45,
    priceBDT: 3000,
    category: 'spa',
    icon: 'ShieldAlert'
  },
  {
    id: 'facial-treatment',
    name: 'Facial Treatment',
    description: 'Adonis signature exfoliation, golden age anti-pollution face scrub, blackhead vacuum extraction, and hydrating ice therapy.',
    durationMin: 50,
    priceBDT: 4000,
    category: 'spa',
    icon: 'UserCheck'
  },
  {
    id: 'vip-grooming-package',
    name: 'VIP Grooming Package',
    description: 'The ultimate royal experience: Precision Cut + Hot Towel Shave + Golden-Age Scalp Spa + Signature Facial + Specialty Beverage.',
    durationMin: 120,
    priceBDT: 7500,
    category: 'pack',
    icon: 'Crown'
  }
];

export const BRANCHES: Branch[] = [
  {
    id: 'gulshan',
    name: 'Gulshan Premium Lounge',
    fullName: 'Adonis Gulshan Lounge',
    address: 'Rupayan Golden Age (2nd Floor), Plot 99, Road 37, Block CWN (C), Gulshan Avenue, Dhaka 1212',
    googleMapEmbed: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.1561658428275!2d90.4109405!3d23.7846175!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c7a07bfdcb99%3A0xe9795f5ac4dbd5!2sRupayan%20Golden%20Age!5e0!3m2!1sen!2sbd!4v1716279147619!5m2!1sen!2sbd',
    directionsUrl: 'https://www.google.com/maps/dir//%E0%A6%85%E0%A7%8D%E0%A6%AF%E0%A6%BE%E0%A6%A1%E0%A7%8B%E0%A6%A8%E0%A6%BF%E0%A6%B8+%E0%A6%AE%E0%A7%87%E0%A6%A8%E0%A6%B8+%E0%A6%97%E0%A7%8D%E0%A6%B0%E0%A7%8B%E0%A6%AE%E0%A6%BF%E0%A6%82+%E0%A6%B8%E0%A7%8D%E0%A6%AF%E0%A6%BE%E0%A6%B2%E0%A6%A8+%7C+%E0%A6%AC%E0%A7%87%E0%A6%B7%E0%A7%8D%E0%A6%9F+%E0%A6%B8%E0%A7%8D%E0%A6%AF%E0%A6%BE%E0%A6%B2%E0%A6%A8+%E0%A6%87%E0%A6%A8+%E0%A6%97%E0%A7%81%E0%A6%B2%E0%A6%B6%E0%A6%BE%E0%A6%A8,+%E0%A6%A2%E0%A6%BE%E0%A6%95%E0%A6%BE,+Holding+-+99,+%E0%A6%B0%E0%A7%81%E0%A6%AA%E0%A6%BE%E0%A7%9F%E0%A6%A8+%E0%A6%97%E0%A7%8B%E0%A6%B2%E0%A7%8D%E0%A6%A1%E0%A7%87%E0%A6%A8+%E0%A6%8F%E0%A6%9C,+Road+-+37+%E0%A6%97%E0%A7%81%E0%A6%B2%E0%A6%B6%E0%A6%BE%E0%A6%A8+%E0%A6%8F%E0%A6%AD%E0%A6%BF%E0%A6%A8%E0%A6%BF%E0%A6%89,+%E0%A6%A2%E0%A6%BE%E0%A6%95%E0%A6%BE+1212/@23.7928448,90.4200192,14z/data=!4m8!4m7!1m0!1m5!1m1!1s0x3755c7a8e8e640f3:0x853c8b802d259ab6!2m2!1d90.4163233!2d23.7884608?entry=ttu&g_ep=EgoyMDI2MDUyMC4wIKXMDSoASAFQAw%3D%3D',
    phoneNumbers: ['+880 1919-700800', '+880 1700-600333'],
    hours: '10AM to 7PM'
  },
  {
    id: 'bashundhara',
    name: 'Bashundhara Premium Lounge',
    fullName: 'Adonis Bashundhara Studio',
    address: 'Rahman Tower (Lift-4), Ka-1/B, Jagannathpur, Beside Hardco International School, Bashundhara, Dhaka',
    googleMapEmbed: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.180652345558!2d90.42233377589841!3d23.812174286400836!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c7e6cbf7867b%3A0x1b8c5461e6dc408d!2sAdonis%20%7C%20Best%20Grooming%20Salon%20in%20Bashundhara!5e0!3m2!1sen!2sbd!4v1780478624101!5m2!1sen!2sbd',
    directionsUrl: 'https://www.google.com/maps?sca_esv=c0a979c20fc01ddf&biw=1920&bih=911&sxsrf=ANbL-n6rBQHc6On-WNWo1_ntWaHqkg8ONQ:1779702997846&gs_lp=Egxnd3Mtd2l6LXNlcnAiCWFkb25pcyBiYSoCCAAyEBAuGK8BGMcBGIAEGIoFGCcyCxAAGIAEGIoFGJECMgoQABiABBgUGIcCMgsQABiABBiKBRiRAjIQEC4YFBivARjHARiHAhiABDILEAAYgAQYigUYkQIyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMh0QLhivARjHARiABBiKBRiXBRjcBBjeBBjgBNgBAUiWFVAAWMwNcAF4AZABAJgBsQGgAckHqgEDMC42uAEDyAEA-AEBmAIHoALlB8ICBBAjGCfCAgoQIxiABBiKBRgnwgIQEC4YgAQYigUYxwEYrwEYJ8ICEBAAGIAEGIoFGEMYsQMYyQPCAgoQABiABBiKBRhDwgIOEC4YrwEYxwEYkgMYgATCAgUQLhiABMICCxAuGK8BGMcBGIAEmAMAugYGCAEQARgUkgcDMS42oAeVZbIHAzAuNrgH4gfCBwUwLjMuNMgHFYAIAQ&um=1&ie=UTF-8&fb=1&gl=bd&sa=X&geocode=KXuG98vmx1U3MY1A3OZhVIwb&daddr=Ka-1/B,+4th+Floor,+Rahman+Tower,+Main+Road,+Dhaka+1229',
    phoneNumbers: ['+880 1720-080091'],
    hours: '10:00 AM to 10:00 PM'
  }
];

export const BARBERS: Barber[] = [
  {
    id: 'tariq',
    name: 'Babul Chandra Shil',
    experienceYears: 12,
    specialty: 'Skin Fade & Modern Texturizing',
    portraitUrl: assetUrl('/assets/images/babul_barbar.png'),
    bio: 'Renowned stylist with 12 years of craftsmanship. Expert in razor fades and sculpting bold executive silhouettes.',
    rating: 4.9
  },
  {
    id: 'kamran',
    name: 'Antor Mondol',
    experienceYears: 9,
    specialty: 'Royal Shaves & Beard Architecture',
    portraitUrl: assetUrl('/assets/images/master_barber_portrait_1779269169728.png'),
    bio: 'A true maestro of the straight razor, specialized in symmetrical beard mapping and hot towel restoration.',
    rating: 5.0
  },
  {
    id: 'arif',
    name: 'Rofiqul Islam',
    experienceYears: 7,
    specialty: 'Classic Scissor Cuts & Scalp Health',
    portraitUrl: assetUrl('/assets/images/rofiq_barbar.png'),
    bio: 'Dedicated to traditional high-end scissor sculpting and therapeutic scalp treatments to promote long-term volume.',
    rating: 4.8
  },
  {
    id: 'javed',
    name: 'Chandra',
    experienceYears: 10,
    specialty: 'Hair Color & Keratin Treatments',
    portraitUrl: assetUrl('/assets/images/chandra.png'),
    bio: 'Color specialist with a decade of expertise in ammonia-free dye systems, keratin bonding, and high-fashion tone transformations.',
    rating: 4.9
  },
  {
    id: 'rohit',
    name: 'Kalu Rupa Shil',
    experienceYears: 6,
    specialty: 'Facial Treatments & Spa Services',
    portraitUrl: assetUrl('/assets/images/kalu_rupa.png'),
    bio: 'Certified skin-care professional with advanced training in premium facials, body scrubs, and therapeutic massage techniques.',
    rating: 4.7
  },
  {
    id: 'salim',
    name: 'Saimon',
    experienceYears: 8,
    specialty: 'Massage Therapy & Body Spa',
    portraitUrl: assetUrl('/assets/images/saimon.png'),
    bio: 'Licensed massage therapist trained in Swedish, Deep Tissue, Thai and Aroma techniques. Known for his precision pressure-point therapy.',
    rating: 4.8
  },
  {
    id: 'rakib',
    name: 'Dulal Chandra',
    experienceYears: 5,
    specialty: 'Modern Beard Styling & Waxing',
    portraitUrl: assetUrl('/assets/images/dulal_chandra.png'),
    bio: 'Young and passionate groomer specializing in contemporary beard shaping, wax-based styling, and precise razor-line finishes.',
    rating: 4.6
  }
];

export const TESTIMONIALS: Testimonial[] = [
  {
    id: 'r1',
    author: 'Asif Chowdhury',
    rating: 5,
    comment: 'Best barber experience in Dhaka. The Gulshan lounge feels like a 5-star hotel. Tariq perfectly executed my low skin fade with extreme attention to detail and precision!',
    source: 'Gulshan Member',
    avatarLetter: 'A'
  },
  {
    id: 'r2',
    author: 'Zaynul Abedin',
    rating: 5,
    comment: 'Absolute masterpiece. The hot towel shave with cold signature towels feels incredibly refreshing. Highly skilled barbers, polite staff, and beautiful masculine design.',
    source: 'Bashundhara regular',
    avatarLetter: 'Z'
  },
  {
    id: 'r3',
    author: 'Farhan Azim',
    rating: 5,
    comment: 'Finally, a premium barbershop that delivers Dubai-level and London-level grooming in Dhaka. The VIP package is worth every single Taka. Outstanding service.',
    source: 'Elite Club Member',
    avatarLetter: 'F'
  }
];

export const BLOG_POSTS: BlogPost[] = [
  {
    id: 'premium-mens-grooming-dhaka',
    slug: 'premium-mens-grooming-dhaka',
    title: 'Premium Men’s Grooming in Dhaka: The Adonis Guide to a Sharper Look',
    excerpt: 'Discover how professional haircuts, beard shaping, facials, massage, and premium salon care help modern men in Dhaka look sharper and feel more confident.',
    coverImage: assetUrl('/assets/images/adonis_styling_chairs_1779270725139.png'),
    contentHtml: `
      <h2>Why Men’s Grooming Matters More Than Ever</h2>
      <p>Modern grooming is not only about looking polished. It is about confidence, hygiene, presentation, and personal identity. At <strong>Adonis Men’s Grooming in Dhaka</strong>, every service is designed to help men look sharp while enjoying a calm, premium salon experience.</p>
      <h3>Precision Haircuts for Every Lifestyle</h3>
      <p>A professional haircut should match your face shape, profession, hair texture, and daily styling routine. Whether you prefer a classic scissor cut, a modern skin fade, or a refined executive style, the right haircut can instantly improve your overall appearance.</p>
      <ul>
        <li>Clean fades and sharp blending</li>
        <li>Face-shape-based consultation</li>
        <li>Professional styling and finishing</li>
      </ul>
      <h3>Beard Shaping and Hot Towel Shaves</h3>
      <p>For men with facial hair, beard maintenance is essential. A balanced beard line, clean cheek contour, and sharp neckline can define the jaw and create a more structured look. Hot towel shaving also refreshes the skin and delivers a traditional barbering experience.</p>
      <h4>Facials, Spa Care, and Relaxation</h4>
      <p>Dhaka’s weather, dust, and stress can affect skin and scalp health. Premium facials, hair spa treatments, massage, and body care help restore freshness and reduce everyday fatigue.</p>
      <h5>Why Choose Adonis?</h5>
      <p><strong>Adonis Men’s Grooming</strong> combines expert stylists, premium products, luxury interiors, and personalized service for men who expect more than a basic haircut.</p>
      <h6>Book Your Premium Grooming Session</h6>
      <p>If you are searching for a premium men’s salon in Dhaka, visit Adonis in Gulshan or Bashundhara and experience grooming designed around confidence, comfort, and detail.</p>
    `,
    seoTitle: 'Premium Men’s Grooming in Dhaka | Adonis Men’s Grooming',
    seoDescription: 'Looking for the best men’s grooming salon in Dhaka? Learn how Adonis combines precision haircuts, beard styling, facials, spa care, and premium salon service.',
    status: 'published',
    createdAt: '2026-06-04T00:00:00.000Z',
    updatedAt: '2026-06-04T00:00:00.000Z'
  }
];

export const VIP_PRIVILEGES = [
  { title: 'Priority Booking Override', desc: 'Secure peak-hour slots instantly with absolute calendar precedence.' },
  { title: 'Personalized Grooming Profile', desc: 'Saves your exact haircut measurements, beard angles, and styling preference records.' },
  { title: 'Complimentary Styling Consultation', desc: 'Exclusive facial bone-structure profiling with our Master Barber once a month.' },
  { title: 'VIP Suite Custom Refreshments', desc: 'Gourmet bar selection including single-origin black drip coffee or fresh mint coolers.' },
  { title: 'Members-Only Special Rate', desc: '15% savings across all therapeutic scalp spas, signature charcoal treatments, and custom products.' }
];

export const OPEN_HOURS = {
  days: 'Everyday (Sat - Fri)',
  hours: '10:00 AM – 10:00 PM'
};

export const CONTACT_INFO = {
  email: 'info@adonis.com.bd',
  website: 'http://adonis.com.bd',
  phoneNumbers: ['+880 1919-700800', '+880 1700-600333'],
  social: {
    facebook: 'https://facebook.com/adonis.bd',
    instagram: 'https://instagram.com/adonis.grooming',
    whatsapp: 'https://wa.me/8801919700800'
  }
};

export interface PriceListItem {
  name: string;
  price: string;
  description?: string;
}

export interface PriceGroup {
  category: string;
  items: PriceListItem[];
}

export const COMPLETE_PRICE_LIST: PriceGroup[] = [
  {
    category: 'HAIR CUTTING',
    items: [
      { name: 'Hair Cut with Shampoo', price: '700/-', description: 'Classic precise cut with professional wash styling finish' },
      { name: 'Hair Cut Stylish / Fashion / Catalogue', price: '1,000/- & Above', description: 'Trending, aesthetic styles suited for elite bone-structures' },
      { name: 'Normal Beard Trim', price: '600/-', description: 'Symmetrical trim with razor lines finish' },
      { name: 'Stylish Beard Trim', price: '600/- & Above', description: 'Beard mapping, precise styling and defining' },
      { name: 'Shave', price: '600/-', description: 'Smooth face, premium shaving foam and moisturization' },
      { name: 'Bald Adult', price: '700/-', description: 'Clean skull head shave with soothing balm treatment' },
      { name: 'Bald Infant / Children', price: '700/-', description: 'Careful children or infant skull shave with extra sensitivity care' }
    ]
  },
  {
    category: 'HAIR SETTING',
    items: [
      { name: 'Hair Setting Mousse / Cream / Gel / Spray / Wax', price: '500/-', description: 'Professional holding finish' },
      { name: 'Hair Shampoo with Conditioner', price: '500/-', description: 'Deep cleanse with premium hydration' },
      { name: 'Blow Dry', price: '500/-', description: 'Volume building blow action styling' },
      { name: 'Hair Iron', price: '2,000/- & Above', description: 'Flat iron straight styling texture' },
      { name: 'Hair Rebonding', price: '10,000/- & Above', description: 'Permanent elite hair straight restructuring' },
      { name: 'Super Shine Keratin', price: '16,000/- & Above', description: 'Ultimate premium silk infusion keratin shield' }
    ]
  },
  {
    category: 'SPECIAL CHARGE LIST',
    items: [
      { name: 'Shower & Steam', price: '1,000/-', description: 'Relieving warm shower and professional steam session' },
      { name: 'Personal Room Charge', price: '1,000/-', description: 'Private suite reservation with completely exclusive styling' },
      { name: 'Sauna', price: '1,000/-', description: 'Elite dry heat detoxification chamber access' },
      { name: 'Home Service', price: '2,000/-', description: 'Premium door-to-door styling with master barber sent to your residence' }
    ]
  },
  {
    category: 'HAIR DYE & OTHERS',
    items: [
      { name: 'Moustache Dye', price: '600/-', description: 'Quick moustache grey coverage' },
      { name: 'Beard Dye Ammonia Free Bigen', price: '1,000/-', description: 'Safe ammonia-free beard contour dye' },
      { name: 'Beard Dye with Inova Color', price: '1,500/-', description: 'Rich deep color tone alignment' },
      { name: 'Dye Color Apply Only Service Charge + Shampoo', price: '1,000/- & Above', description: 'Using client\'s custom self-purchased dye' },
      { name: 'Color Apply Charge', price: '1,000/- & Above', description: 'Professional expert dye application service' },
      { name: 'Color Sticks Each Color', price: '1,000/- & Above', description: 'Bespoke design highlight streaks' },
      { name: 'Bigen', price: '1,300/-', description: 'Traditional coverage formula' },
      { name: 'Bigen Ammonia Free', price: '1,800/-', description: 'Non-irritant natural formula' },
      { name: 'Mehedi Color', price: '2,000/- & Above', description: 'Traditional organic natural henna styling' },
      { name: 'L’Oréal Majirel Color', price: '2,500/-', description: 'Premium premium dye with radiant shine' },
      { name: 'L’Oréal INOA Ammonia Free', price: '3,000/-', description: 'Elite zero ammonia protective hair color' },
      { name: 'Keune Color Ammonia Free', price: '3,000/- & Above', description: 'Top professional hair care coloring' },
      { name: 'Fashion Color with Pre-lightening', price: '9,000/- & Above', description: 'Extreme ash, grey or blonde high-fashion tone transformations' },
      { name: 'Godrej Professional Hair Color', price: '3,500/- & Above', description: 'Professional color system' }
    ]
  },
  {
    category: 'HAIR TREATMENT',
    items: [
      { name: 'Hot Oil Massage with Steam Coconut Oil', price: '1,000/- & Above', description: 'Relieving warm oil therapy with intense steam' },
      { name: 'Hot Oil Massage with Steam Castor / Almond / Shahanaj / Garlic Oil', price: '1,500/-', description: 'Specific selected essential oils for target remedies' },
      { name: 'Hot Oil Massage with Steam Godrej Argan or Acai Oil', price: '1,500/-', description: 'Ultra premium hydration oils' },
      { name: 'Hot Oil Massage with Steam Body Shop Coco Oil', price: '1,800/-', description: 'Imported Body Shop Coco formula treatment' },
      { name: 'Hot Oil Therapy with Aloe Vera Pack', price: '2,000/- & Above', description: 'Anti-allergy organic hair cooling pack' },
      { name: 'L’Oréal Hair Spa', price: '2,800/-', description: 'Deep L’Oréal hair nourishment treatment' },
      { name: 'Godrej Hair Spa', price: '3,000/-', description: 'Professional hair mask with steam' },
      { name: 'Hair Shine', price: '3,000/-', description: 'Intense glass reflecting hair gloss finish' },
      { name: 'Treatment for Rebonding Hair', price: '3,500/-', description: 'Reconstructive conditioning for chemically straightened hair' },
      { name: 'Godrej Hair Treatment Dry & Damaged Hair', price: '4,000/-', description: 'Target repair for splitting or over-processed hair' },
      { name: 'Godrej Hair Treatment Regular & Dry Hair', price: '4,000/-', description: 'Normalizing hydration booster' },
      { name: 'Godrej Hair Treatment Frizzy & Straightened Hair', price: '4,000/-', description: 'Sleek smoothing therapy for high-frizz cases' },
      { name: 'L’Oréal Elvive Dandruff Control / Hair Fall / Damage', price: '4,500/-', description: 'Intensive scalp relief remedies' },
      { name: 'Godrej Kera Care Keratin Treatment', price: '5,000/-', description: 'Premium keratin structure builder' },
      { name: 'Garnier Treatment', price: '5,000/-', description: 'Natural extract conditioning' },
      { name: 'Body Shop Hair Treatment', price: '7,000/-', description: 'Ultra-luxurious premium imported therapy' },
      { name: 'Ginger Hair Treatment', price: '8,000/-', description: 'Ultimate deep-cleansing organic anti-dandruff ginger therapy' }
    ]
  },
  {
    category: 'FACE MASSAGE & SCRUB',
    items: [
      { name: 'Face Massage', price: '1,500/-', description: 'Relieving traditional facial muscles relaxation' },
      { name: 'Body Shop Face Massage Vitamin C', price: '3,000/-', description: 'Glow-boosting skin brightening workout' },
      { name: 'Special Face Massage Body Shop Vitamin E', price: '4,000/-', description: 'Anti-aging cells repair & deep moisture nutrition' }
    ]
  },
  {
    category: 'FAIR POLISH',
    items: [
      { name: 'Face Polish', price: '1,200/-', description: 'Exfoliating glow application' },
      { name: 'Neck Polish', price: '800/-', description: 'Clean color adjustment' },
      { name: 'Face + Neck Polish', price: '1,800/-', description: 'Complete head-neck brightness polish' },
      { name: 'Hands Polish', price: '2,000/-', description: 'Dual arm exfoliation and tone matching' },
      { name: 'Half Body Front Polish', price: '2,500/-', description: 'Torso skin restoration' },
      { name: 'Half Body Back Polish', price: '3,000/-', description: 'Spine skin polish with steam' },
      { name: 'Face + Neck + Hands Polish', price: '3,000/-', description: 'Highly popular complete visual grooming combo' },
      { name: 'Upper Body Polish', price: '4,500/-', description: 'Complete waist-up whitening polish' },
      { name: 'Full Body Polish', price: '6,000/-', description: 'The absolute full body skin renewal experience' }
    ]
  },
  {
    category: 'MILK FAIR POLISH',
    items: [
      { name: 'Face + Neck (Milk)', price: '3,000/-', description: 'Lactic acid whitening and hydration for face and neck' },
      { name: 'Front & Back (Milk)', price: '12,000/-', description: 'Torso-wide deluxe milk polish' },
      { name: 'Full Body (Milk)', price: '15,000/-', description: 'Ultimate royal skin hydration and tone leveling' }
    ]
  },
  {
    category: 'FACIAL',
    items: [
      { name: 'Adonis Special Facial', price: '3,500/-', description: 'Our signature charcoal blend for active city lifestyles' },
      { name: 'Aloe Vera Facial', price: '3,500/-', description: 'Soothing hydration for highly sensitive or sun-irritated skins' },
      { name: 'Pearl Facial', price: '4,500/-', description: 'Deluxe mineral-based face glow facial' },
      { name: 'Diamond Facial', price: '5,000/-', description: 'Deep micro-exfoliation and light reflecting brightness limit' },
      { name: 'Gold Facial', price: '6,000/-', description: 'Premium 24k gold leaf skin recovery and glow action' },
      { name: 'Janssen Whitening Facial', price: '8,000/-', description: 'Aesthetic-level German Janssen formula' },
      { name: 'Lotus Gold Facial', price: '8,000/-', description: 'Ultra premium luxury natural extracts gold treatment' },
      { name: 'Body Shop Vitamin C Facial', price: '8,000/-', description: 'Rich vitamin C active glow remedy' },
      { name: 'Janssen Whitening Facial Orange', price: '9,000/-', description: 'Premium citric Janssen formula' },
      { name: 'S K 5 Diamond Facial', price: '10,000/-', description: 'Super premium cell replacement and luxury glow' },
      { name: 'Body Shop Seaweed Facial', price: '10,000/-', description: 'Intense minerals oil control marine treatment' },
      { name: 'Body Shop Drops of Light Facial', price: '12,000/-', description: 'The ultimate luxury imported brightening program' }
    ]
  },
  {
    category: 'BODY FASHION & WAX',
    items: [
      { name: 'Eye Brows Shaping', price: '500/-', description: 'Elite thread layout shaping' },
      { name: 'Face Threading', price: '1,000/- & Above', description: 'Clean smooth facial threading' },
      { name: 'Ears Candling', price: '2,500/-', description: 'Traditional thermal wax ear canal detox and pressure relief' },
      { name: 'Waxing Face', price: '1,000/-', description: 'Smooth face waxing' },
      { name: 'Waxing Neck', price: '1,000/-', description: 'Hygienic neck skin wax' },
      { name: 'Waxing Nose / Ear', price: '1,000/-', description: 'Pain-free premium interior wax' },
      { name: 'Waxing Hands', price: '2,000/-', description: 'Full arm waxing' },
      { name: 'Waxing Back Upper Body', price: '3,000/-', description: 'Upper back wax' },
      { name: 'Waxing Half Legs / Full Legs', price: '3,000/- & Above', description: 'Leg area hair removal' },
      { name: 'Waxing Front Upper Body', price: '3,500/-', description: 'Chest and stomach area wax' },
      { name: 'Waxing Half Body (Neck+Hands+Front+Back)', price: '6,000/- & Above', description: 'Comprehensive upper torso wax package' },
      { name: 'Waxing Full Body', price: '10,000/-', description: 'Complete full body premium hair removal wax' }
    ]
  },
  {
    category: 'SPA & BODY SCRUB',
    items: [
      { name: 'Aromatic Bath (15 Minutes)', price: '10,000/-', description: 'Relaxing jacuzzi-level warm bath infused with premium essential oils' },
      { name: 'Full Body Scrub with Steam', price: '5,500/- & Above', description: 'Organic dermabrasion scrub followed by complete hot steam' },
      { name: 'Back Scrub', price: '2,400/- & Above', description: 'Shedding dead cells, deep cleansing' },
      { name: 'Leg Scrub', price: '1,200/-', description: 'Exfoliation for legs' },
      { name: 'Hand Scrub', price: '1,000/-', description: 'Smooth, hydrating hand scrub' },
      { name: 'Body Shop Full Body Scrub with Steam', price: '11,000/-', description: 'Super premium imported Body Shop exfoliation' },
      { name: 'Body Smoothing Scrub', price: '8,000/-', description: 'Cell renewal visual smoothing scrub' },
      { name: 'Unilever Scrub Full Body Scrub', price: '6,500/-', description: 'Clean, hydrating scrub combo' },
      { name: 'Body Shop Hand Scrub', price: '2,000/-', description: 'Premium hydration and shine' },
      { name: 'Body Shop Back Scrub', price: '6,000/-', description: 'Advanced spine smoothing and steam' },
      { name: 'Body Shop Foot Scrub', price: '2,000/-', description: 'Dead skin removal with deep moisturizing' },
      { name: 'Aloe Vera Body Scrub Full Body', price: '5,000/-', description: 'Sensitive skin safe cooling full body scrub' },
      { name: 'Royal Body Smoothing Scrub', price: '16,000/-', description: 'The absolute pinnacle of luxury full body pampering' }
    ]
  },
  {
    category: 'HAND & FOOT CARE',
    items: [
      { name: 'Manicure', price: '800/-', description: 'Clean cuticle trim, buffing, and hand cream' },
      { name: 'Pedicure', price: '1,200/-', description: 'Deep foot soak, heel scrubbing, and massage' },
      { name: 'Manicure Deluxe', price: '1,200/-', description: 'Warm oils massage, paraffin mask hydration' },
      { name: 'Pedicure Deluxe', price: '2,300/-', description: 'Signature premium mud mask, hot stone massage' }
    ]
  },
  {
    category: 'MASSAGE LAB',
    items: [
      { name: 'Neck, Head & Foot Massage (60 Mins)', price: '2,200/-', description: 'Full tension release combo' },
      { name: 'Neck, Head & Foot Massage (90 Mins)', price: '3,000/-', description: 'Extended relieving treatment' },
      { name: 'Back, Neck & Shoulder Massage (30 Mins)', price: '1,500/-', description: 'Quick trigger points de-stress' },
      { name: 'Thai Foot Reflexology (45 Mins)', price: '1,200/-', description: 'Pressure points mapping for overall body energy' },
      { name: 'Thai Foot Reflexology (60 Mins)', price: '1,600/-', description: 'Standard reflex massage' },
      { name: 'Thai Foot Reflexology (90 Mins)', price: '2,200/-', description: 'Ultimate leg and nervous recovery' },
      { name: 'Head Massage with Hair Tonic (15 Mins)', price: '1,000/-', description: 'Deep cerebral massage with nutrient-rich liquid' },
      { name: 'Head Massage without Hair Tonic (15 Mins)', price: '600/-', description: 'Standard dry scalp soothing massage' },
      { name: 'Chair Massage (20 Mins)', price: '1,200/-', description: 'Ergonomic neck-to-hip quick massage' },
      { name: 'Bamboo Massage (60 Mins)', price: '3,500/-', description: 'Warm bamboo rods rolling to break deep muscle fibers' },
      { name: 'Bamboo Massage (90 Mins)', price: '4,800/-', description: 'Extended deep tension bamboo therapy' },
      { name: 'Hot Stone Massage (60 Mins)', price: '3,800/-', description: 'Therapeutic warm volcanic basalt stones layout' },
      { name: 'Hot Stone Massage (90 Mins)', price: '5,000/-', description: 'Deep heat thermal stone recovery' },
      { name: 'Swedish Massage (60 Mins)', price: '3,200/-', description: 'Classic long-stroke circulatory massage' },
      { name: 'Swedish Massage (90 Mins)', price: '4,200/-', description: 'Ultimate light-to-medium relax' },
      { name: 'Aroma Massage (60 Mins)', price: '2,800/-', description: 'Lavendar/Eucalyptus therapeutic essential oil massage' },
      { name: 'Aroma Massage (90 Mins)', price: '3,700/-', description: 'Total aromatherapy recharge' },
      { name: 'Indian / Traditional Massage (60 Mins)', price: '2,500/-', description: 'Deep kneading physical muscle reset' },
      { name: 'Indian / Traditional Massage (90 Mins)', price: '3,500/-', description: 'Full body restorative massage' },
      { name: 'Deep Tissue Body Massage (60 Mins)', price: '3,200/-', description: 'High-pressure physical knot removal' },
      { name: 'Deep Tissue Body Massage (90 Mins)', price: '4,200/-', description: 'Maestra muscle restoration' },
      { name: 'Thai Body Massage (60 Mins)', price: '3,200/-', description: 'Passive yoga stretches, compressions' },
      { name: 'Thai Body Massage (90 Mins)', price: '4,200/-', description: 'Extended physical stretch alignment' }
    ]
  },
  {
    category: 'SPORTS & CUPPING',
    items: [
      { name: 'Back Cupping with 30 Min Massage', price: '4,000/-', description: 'Detoxifying vacuum cupping of the back' },
      { name: 'Full Body Cupping with 30 Min Massage', price: '7,000/-', description: 'Full-body muscle blood flow cupping activation' },
      { name: 'Sports Massage (90 Mins)', price: '5,500/-', description: 'High-intensity athletic relief and joint flexibility' },
      { name: 'Back Fire Cupping with 30 Min Massage', price: '7,000/-', description: 'Traditional dynamic heat vacuum cupping therapy' },
      { name: 'Full Body Fire Cupping with 30 Min Massage', price: '10,000/-', description: 'The peak physical recovery standard for high fatigue' }
    ]
  },
  {
    category: 'MAKE-OVER ART',
    items: [
      { name: 'Groom Make-Up', price: '4,000/-', description: 'Blemish-free high definition camera matching groom facial setting' },
      { name: 'Studio / Model Make-Up', price: '6,000/-', description: 'Studio flash-safe professional modeling look design' }
    ]
  }
];

export interface PromoPackage {
  name: string;
  price: string;
  originalPrice: string;
  discount: string;
  includes: string[];
  description?: string;
  multiday?: boolean;
}

export const GULSHAN_PACKAGES: PromoPackage[] = [
  {
    name: 'REGULAR PACKAGE',
    price: '5,800/-',
    originalPrice: '6,800/-',
    discount: '1,000/-',
    includes: ['Hair Cut', 'Adonis Special Facial', 'Pedicure & Manicure', 'Shave']
  },
  {
    name: 'CLASSIC PACKAGE',
    price: '10,500/-',
    originalPrice: '12,300/-',
    discount: '1,800/-',
    includes: ['Hair Cut', 'Shave', 'Oil Massage', 'Pedicure & Manicure', 'Janssen Whitening Facial']
  },
  {
    name: 'BRIDEGROOM PACKAGE (Single Day)',
    price: '21,000/-',
    originalPrice: '24,600/-',
    discount: '3,600/-',
    includes: ['Hair Cutting & Setting', 'Fair Polish (Face+Neck+Hands)', 'Hair Spa', 'Deluxe Pedicure & Manicure', 'Shave', 'Body Shop Vitamin C Facial', 'Personal Room Service', 'Make-Over Art', 'Body Massage']
  },
  {
    name: 'BRIDEGROOM PACKAGE (2 Days)',
    price: '23,500/-',
    originalPrice: '26,800/-',
    discount: '3,300/-',
    multiday: true,
    includes: [
      'Day 1: Hair Cut + Shampoo + Conditioner Wash, Fair Polish Face + Neck + Hands, Hair Spa, Pedicure & Manicure, Shave',
      'Day 2: Body Massage with Steam Shower, Hair Shining, Body Shop Vitamin E Facial, Make-Over Art, Hair Setting'
    ]
  },
  {
    name: 'BUSINESS PACKAGE',
    price: '17,500/-',
    originalPrice: '20,600/-',
    discount: '3,100/-',
    includes: ['Premium Stylish Hair Cut', 'Stylish Beard shaping/Shave', 'Bigen Ammonia Free Color Dye', 'Caring Hair Spa treatment', 'Pedicure & Manicure', 'Body Shop Seaweed Facial', 'Swedish Body Massage (60 Mins)']
  },
  {
    name: 'ROYAL PACKAGE',
    price: '19,500/-',
    originalPrice: '22,800/-',
    discount: '3,300/-',
    includes: ['Hair Cut', 'Shave', 'Deluxe Pedicure & Manicure', 'Ammonia Free Color (Inova)', 'L’Oréal Hair Spa', 'Gold Facial', 'Body Scrub with Steam']
  },
  {
    name: 'CLASSIC SPA PACKAGE',
    price: '10,000/-',
    originalPrice: '11,500/-',
    discount: '1,500/-',
    includes: ['Pedicure & Manicure Combo', 'Indian Traditional Massage', 'Steam with Shower', 'Body Polish Scrub']
  },
  {
    name: 'BUSINESS SPA PACKAGE',
    price: '14,000/-',
    originalPrice: '15,500/-',
    discount: '1,500/-',
    includes: ['Pedicure Deluxe', 'Manicure Deluxe', 'Deep Tissue Body Massage', 'Body Smoothing Scrub', 'Sauna with Shower']
  },
  {
    name: 'PREMIUM SPA PACKAGE',
    price: '21,000/-',
    originalPrice: '23,500/-',
    discount: '2,500/-',
    includes: ['Pedicure Deluxe', 'Manicure Deluxe', 'Swedish Massage (60 Mins)', 'Royal Body Smoothing Scrub', 'Sauna / Steam with Shower']
  }
];

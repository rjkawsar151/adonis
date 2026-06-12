import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const DB_FILE = path.join(__dirname, 'db.json');

// Read existing db.json or fallback
let db = {
  services: [],
  barbers: [],
  settings: {},
  smtp: {},
  bookings: []
};

if (fs.existsSync(DB_FILE)) {
  try {
    db = JSON.parse(fs.readFileSync(DB_FILE, 'utf-8'));
  } catch (err) {
    console.error("Error reading db.json:", err);
  }
}

// Define updated services array from the user's price list
const newServices = [
  // HAIR CUTTING
  { id: 'haircut-shampoo', name: 'Hair Cut with Shampoo', description: 'Classic precise cut with professional hair wash and custom styling finish.', durationMin: 45, priceBDT: 700, category: 'hair', icon: 'Scissors' },
  { id: 'haircut-stylish', name: 'Hair Cut Stylish / Fashion / Catalogue', description: 'Trending, aesthetic custom hairstyles suited to your face structure.', durationMin: 60, priceBDT: 1000, category: 'hair', icon: 'Sparkles' },
  { id: 'beard-trim-normal', name: 'Normal Beard Trim', description: 'Symmetrical trim with clean razor outlining.', durationMin: 20, priceBDT: 600, category: 'beard', icon: 'Smile' },
  { id: 'beard-trim-stylish', name: 'Stylish Beard Trim & Outline', description: 'Bespoke beard mapping, precise scissor sculpting, and line defining.', durationMin: 30, priceBDT: 600, category: 'beard', icon: 'Smile' },
  { id: 'classic-shave', name: 'Shave', description: 'Smooth traditional hot shave with premium foam and deep hydration.', durationMin: 30, priceBDT: 600, category: 'beard', icon: 'Flame' },
  { id: 'bald-adult', name: 'Bald Adult Head Shave', description: 'Clean scalp razor shave with soothing after-shave oil treatment.', durationMin: 35, priceBDT: 700, category: 'hair', icon: 'Scissors' },
  { id: 'bald-infant', name: 'Bald Infant / Children Shave', description: 'Extremely gentle children or infant head shave with extra sensitive skin care.', durationMin: 40, priceBDT: 700, category: 'hair', icon: 'Scissors' },

  // HAIR SETTING
  { id: 'hair-setting-product', name: 'Hair Setting with Mousse / Cream / Gel / Spray / Wax', description: 'Professional hold styling and shape setting with elite hold products.', durationMin: 15, priceBDT: 500, category: 'hair', icon: 'Sparkles' },
  { id: 'shampoo-conditioner', name: 'Hair Shampoo with Conditioner', description: 'Deep scalp cleansing shampoo followed by rich nourishing conditioner.', durationMin: 20, priceBDT: 500, category: 'hair', icon: 'Flower' },
  { id: 'blow-dry', name: 'Blow Dry Styling', description: 'Volume building blow dry styling with professional texturizers.', durationMin: 20, priceBDT: 500, category: 'hair', icon: 'Sparkles' },
  { id: 'hair-iron', name: 'Hair Iron Straightening', description: 'Flat iron straight styling for polished, sleek textures.', durationMin: 35, priceBDT: 2000, category: 'hair', icon: 'Sparkles' },
  { id: 'hair-rebonding', name: 'Hair Rebonding Treatment', description: 'Permanent hair straight restructuring using advanced smoothing systems.', durationMin: 180, priceBDT: 10000, category: 'hair', icon: 'Sparkles' },
  { id: 'super-shine-keratin', name: 'Super Shine Keratin Shield', description: 'Ultimate premium silk infusion keratin shield for maximum shine and frizz control.', durationMin: 150, priceBDT: 16000, category: 'hair', icon: 'Sparkles' },

  // SPECIAL CHARGE LIST
  { id: 'shower-steam', name: 'Shower & Steam Session', description: 'Relieving warm shower and professional steam capsule session.', durationMin: 30, priceBDT: 1000, category: 'spa', icon: 'Flower' },
  { id: 'personal-room', name: 'Personal Suite Room Charge', description: 'Private suite reservation with completely exclusive styling environment.', durationMin: 60, priceBDT: 1000, category: 'spa', icon: 'Crown' },
  { id: 'sauna-detox', name: 'Sauna Cabin Access', description: 'Elite dry heat detoxification chamber access with hydrating cooling water.', durationMin: 40, priceBDT: 1000, category: 'spa', icon: 'Flame' },
  { id: 'home-service', name: 'Home Service Styling', description: 'Premium door-to-door styling with a master barber sent to your residence.', durationMin: 90, priceBDT: 2000, category: 'hair', icon: 'Scissors' },

  // HAIR DYE & OTHERS
  { id: 'moustache-dye', name: 'Moustache Dye', description: 'Quick moustache grey coverage with safe premium hair dye.', durationMin: 20, priceBDT: 600, category: 'beard', icon: 'Flame' },
  { id: 'beard-dye-bigen', name: 'Beard Dye Ammonia Free Bigen', description: 'Safe ammonia-free beard contour dye for natural black outlines.', durationMin: 30, priceBDT: 1000, category: 'beard', icon: 'Flame' },
  { id: 'beard-dye-inova', name: 'Beard Dye with Inova Color', description: 'Rich deep color tone alignment using professional Inova coloring.', durationMin: 35, priceBDT: 1500, category: 'beard', icon: 'Flame' },
  { id: 'dye-apply-only', name: 'Dye Color Apply Only Service Charge + Shampoo', description: 'Professional expert dye application service using client-provided dyes.', durationMin: 45, priceBDT: 1000, category: 'hair', icon: 'Sparkles' },
  { id: 'color-apply-charge', name: 'Color Apply Charge', description: 'Professional hair color application by master colorist.', durationMin: 45, priceBDT: 1000, category: 'hair', icon: 'Sparkles' },
  { id: 'color-sticks', name: 'Color Sticks Each Color', description: 'Bespoke design highlight streaks for selected hair sections.', durationMin: 40, priceBDT: 1000, category: 'hair', icon: 'Sparkles' },
  { id: 'bigen-dye', name: 'Bigen Hair Color', description: 'Traditional deep coverage formula for full grey camouflage.', durationMin: 45, priceBDT: 1300, category: 'hair', icon: 'Sparkles' },
  { id: 'bigen-ammonia-free', name: 'Bigen Ammonia Free', description: 'Non-irritant natural formula for sensitive scalps.', durationMin: 50, priceBDT: 1800, category: 'hair', icon: 'Sparkles' },
  { id: 'mehedi-color', name: 'Mehedi Color Treatment', description: 'Traditional organic natural henna styling for hair and roots.', durationMin: 60, priceBDT: 2000, category: 'hair', icon: 'Flower' },
  { id: 'loreal-majirel', name: 'L’Oréal Majirel Color', description: 'Premium permanent dye with rich conditioning and radiant shine.', durationMin: 60, priceBDT: 2500, category: 'hair', icon: 'Sparkles' },
  { id: 'loreal-inoa', name: 'L’Oréal INOA Ammonia Free', description: 'Elite zero ammonia protective hair color for a natural finish.', durationMin: 60, priceBDT: 3000, category: 'hair', icon: 'Sparkles' },
  { id: 'keune-color', name: 'Keune Color Ammonia Free', description: 'Top professional hair care coloring with rich scalp protection.', durationMin: 60, priceBDT: 3000, category: 'hair', icon: 'Sparkles' },
  { id: 'fashion-color', name: 'Fashion Color with Pre-lightening', description: 'Extreme ash, grey, blonde, or high-fashion tone transformations.', durationMin: 120, priceBDT: 9000, category: 'hair', icon: 'Sparkles' },
  { id: 'godrej-color', name: 'Godrej Professional Hair Color', description: 'Professional coloring system with deep gloss conditioning.', durationMin: 55, priceBDT: 3500, category: 'hair', icon: 'Sparkles' },

  // HAIR TREATMENT
  { id: 'oil-steam-coconut', name: 'Hot Oil Massage with Steam Coconut Oil', description: 'Traditional head massage with warm coconut oil followed by steam.', durationMin: 30, priceBDT: 1000, category: 'spa', icon: 'Flower' },
  { id: 'oil-steam-essential', name: 'Hot Oil Massage with Steam Castor / Almond / Shahanaj / Garlic Oil', description: 'Targeted head massage with custom therapeutic oils to prevent hair fall.', durationMin: 30, priceBDT: 1500, category: 'spa', icon: 'Flower' },
  { id: 'oil-steam-argan', name: 'Hot Oil Massage with Steam Godrej Argan or Acai Oil', description: 'Deep nourishing argan oil massage and steam therapy for soft hair.', durationMin: 35, priceBDT: 1500, category: 'spa', icon: 'Flower' },
  { id: 'oil-steam-bodyshop', name: 'Hot Oil Massage with Steam Body Shop Coco Oil', description: 'Imported Body Shop coco formula for dry scalp restoration.', durationMin: 35, priceBDT: 1800, category: 'spa', icon: 'Flower' },
  { id: 'oil-aloe-pack', name: 'Hot Oil Therapy with Aloe Vera Pack', description: 'Anti-allergy organic hair cooling pack with scalp massage.', durationMin: 45, priceBDT: 2000, category: 'spa', icon: 'Flower' },
  { id: 'loreal-hair-spa', name: 'L’Oréal Hair Spa & Treatment', description: 'Deep L’Oréal hair nourishment treatment with steam.', durationMin: 50, priceBDT: 2800, category: 'spa', icon: 'Flower' },
  { id: 'godrej-hair-spa', name: 'Godrej Hair Spa', description: 'Professional hair repair mask with scalp massage and steam activation.', durationMin: 50, priceBDT: 3000, category: 'spa', icon: 'Flower' },
  { id: 'hair-shine-gloss', name: 'Hair Shine & Gloss', description: 'Intense glass reflecting hair gloss finish and structural coat.', durationMin: 40, priceBDT: 3000, category: 'spa', icon: 'Sparkles' },
  { id: 'rebonding-treatment', name: 'Treatment for Rebonding Hair', description: 'Reconstructive conditioning for chemically straightened hair.', durationMin: 60, priceBDT: 3500, category: 'spa', icon: 'Flower' },
  { id: 'godrej-damaged-hair', name: 'Godrej Hair Treatment Dry & Damaged Hair', description: 'Targeted damage repair mask for split-ends and over-processed hair.', durationMin: 60, priceBDT: 4000, category: 'spa', icon: 'Flower' },
  { id: 'godrej-regular-dry', name: 'Godrej Hair Treatment Regular & Dry Hair', description: 'Normalizing hydration booster for dull or dry textures.', durationMin: 60, priceBDT: 4000, category: 'spa', icon: 'Flower' },
  { id: 'godrej-frizzy-straight', name: 'Godrej Hair Treatment Frizzy & Straightened', description: 'Sleek smoothing therapy for chemically treated hair with high frizz.', durationMin: 60, priceBDT: 4000, category: 'spa', icon: 'Flower' },
  { id: 'loreal-elvive-treatment', name: 'L’Oréal Elvive Dandruff Control / Hair Fall / Damage', description: 'Intensive scalp relief remedies for dandruff control and root health.', durationMin: 60, priceBDT: 4500, category: 'spa', icon: 'ShieldAlert' },
  { id: 'godrej-keracare', name: 'Godrej Kera Care Keratin Treatment', description: 'Premium keratin structure building and deep hair reconstruction.', durationMin: 90, priceBDT: 5000, category: 'spa', icon: 'Flower' },
  { id: 'garnier-treatment', name: 'Garnier Natural Extract Treatment', description: 'Nourishing organic botanical fruit extract conditioning.', durationMin: 50, priceBDT: 5000, category: 'spa', icon: 'Flower' },
  { id: 'bodyshop-hair-luxe', name: 'Body Shop Hair Treatment', description: 'Ultra-luxurious premium imported Body Shop hair therapy.', durationMin: 70, priceBDT: 7000, category: 'spa', icon: 'Flower' },
  { id: 'ginger-hair-spa', name: 'Ginger Hair Treatment', description: 'Ultimate deep-cleansing organic anti-dandruff ginger tonic therapy.', durationMin: 60, priceBDT: 8000, category: 'spa', icon: 'Flower' },

  // FACE MASSAGE & SCRUB
  { id: 'face-massage-classic', name: 'Face Massage', description: 'Relieving traditional facial muscles relaxation and acupressure.', durationMin: 25, priceBDT: 1500, category: 'spa', icon: 'Flower' },
  { id: 'facemassage-vitc', name: 'Body Shop Face Massage Vitamin C', description: 'Glow-boosting skin brightening workout with active Vitamin C.', durationMin: 35, priceBDT: 3000, category: 'spa', icon: 'Flower' },
  { id: 'facemassage-vite', name: 'Special Face Massage Body Shop Vitamin E', description: 'Anti-aging cells repair and deep moisture nutrition.', durationMin: 40, priceBDT: 4000, category: 'spa', icon: 'Flower' },

  // FAIR POLISH
  { id: 'fair-polish-face', name: 'Face Polish', description: 'Exfoliating glow application and dead cells removal for the face.', durationMin: 20, priceBDT: 1200, category: 'spa', icon: 'Sparkles' },
  { id: 'fair-polish-neck', name: 'Neck Polish', description: 'Clean color adjustment and skin polishing for the neck.', durationMin: 15, priceBDT: 800, category: 'spa', icon: 'Sparkles' },
  { id: 'fair-polish-face-neck', name: 'Face + Neck Polish', description: 'Complete head and neck brightness polishing.', durationMin: 30, priceBDT: 1800, category: 'spa', icon: 'Sparkles' },
  { id: 'fair-polish-hands', name: 'Hands Polish', description: 'Dual arm exfoliation, skin renewal, and tone matching.', durationMin: 35, priceBDT: 2000, category: 'spa', icon: 'Sparkles' },
  { id: 'fair-polish-front', name: 'Half Body Front Polish', description: 'Chest and torso skin polishing with premium exfoliating creams.', durationMin: 40, priceBDT: 2500, category: 'spa', icon: 'Sparkles' },
  { id: 'fair-polish-back', name: 'Half Body Back Polish', description: 'Spine and back skin polishing with hot steam extraction.', durationMin: 45, priceBDT: 3000, category: 'spa', icon: 'Sparkles' },
  { id: 'fair-polish-combo', name: 'Face + Neck + Hands Polish', description: 'Highly popular complete visual grooming combo package.', durationMin: 50, priceBDT: 3000, category: 'spa', icon: 'Sparkles' },
  { id: 'fair-polish-upper', name: 'Upper Body Polish', description: 'Complete waist-up skin whitening and polish treatment.', durationMin: 60, priceBDT: 4500, category: 'spa', icon: 'Sparkles' },
  { id: 'fair-polish-full', name: 'Full Body Polish', description: 'The absolute full body skin renewal and whitening polish.', durationMin: 90, priceBDT: 6000, category: 'spa', icon: 'Sparkles' },

  // MILK FAIR POLISH
  { id: 'milk-polish-face-neck', name: 'Face + Neck (Milk)', description: 'Lactic acid whitening and hydration for face and neck.', durationMin: 30, priceBDT: 3000, category: 'spa', icon: 'Flower' },
  { id: 'milk-polish-torso', name: 'Front & Back (Milk)', description: 'Torso-wide deluxe milk polish for deep moisturization.', durationMin: 60, priceBDT: 12000, category: 'spa', icon: 'Flower' },
  { id: 'milk-polish-full', name: 'Full Body (Milk)', description: 'Ultimate royal skin hydration and tone leveling with raw milk extract.', durationMin: 100, priceBDT: 15000, category: 'spa', icon: 'Flower' },

  // FACIAL
  { id: 'facial-adonis-special', name: 'Adonis Special Facial', description: 'Our signature charcoal blend for active city lifestyles.', durationMin: 45, priceBDT: 3500, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-aloe', name: 'Aloe Vera Facial for All Types of Skin', description: 'Soothing hydration and redness relief for sensitive skin.', durationMin: 45, priceBDT: 3500, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-pearl', name: 'Pearl Facial', description: 'Deluxe mineral-based facial for a pearl-like glowing finish.', durationMin: 50, priceBDT: 4500, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-diamond', name: 'Diamond Facial', description: 'Deep micro-exfoliation and light reflecting skin brightening.', durationMin: 50, priceBDT: 5000, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-gold', name: 'Gold Facial', description: 'Premium 24k gold leaf skin recovery and anti-aging glow.', durationMin: 60, priceBDT: 6000, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-janssen', name: 'Janssen Whitening Facial for All Types of Skin', description: 'Aesthetic-level German Janssen formula for clinical whitening.', durationMin: 60, priceBDT: 8000, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-lotus-gold', name: 'Lotus Gold Facial', description: 'Ultra premium luxury natural extracts gold treatment.', durationMin: 65, priceBDT: 8000, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-bodyshop-vitc', name: 'Body Shop Vitamin C Facial', description: 'Rich Vitamin C active glow skin cell revival.', durationMin: 60, priceBDT: 8000, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-janssen-orange', name: 'Janssen Whitening Facial Orange', description: 'Premium citric Janssen formula for blemish-free skin.', durationMin: 60, priceBDT: 9000, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-sk5', name: 'S K 5 Diamond Facial', description: 'Super premium cell replacement and luxury glow.', durationMin: 70, priceBDT: 10000, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-bodyshop-seaweed', name: 'Body Shop Seaweed Facial', description: 'Intense minerals oil control marine treatment for oily skins.', durationMin: 60, priceBDT: 10000, category: 'spa', icon: 'UserCheck' },
  { id: 'facial-drops-of-light', name: 'Body Shop Drops of Light Facial', description: 'The ultimate luxury imported brightening program.', durationMin: 75, priceBDT: 12000, category: 'spa', icon: 'UserCheck' },

  // BODY FASHION & WAXING
  { id: 'eyebrows-shaping', name: 'Eye Brows Shaping', description: 'Elite thread layout eyebrow defining.', durationMin: 15, priceBDT: 500, category: 'hair', icon: 'Scissors' },
  { id: 'face-threading', name: 'Face Threading', description: 'Clean smooth facial threading for hair removal.', durationMin: 25, priceBDT: 1000, category: 'hair', icon: 'Scissors' },
  { id: 'ears-candling', name: 'Ears Candling Detox', description: 'Traditional thermal wax ear canal detox and pressure relief.', durationMin: 30, priceBDT: 2500, category: 'spa', icon: 'Flame' },
  { id: 'waxing-face', name: 'Waxing Face', description: 'Smooth facial hair removal waxing.', durationMin: 25, priceBDT: 1000, category: 'spa', icon: 'Flower' },
  { id: 'waxing-neck', name: 'Waxing Neck', description: 'Hygienic neck area waxing.', durationMin: 15, priceBDT: 1000, category: 'spa', icon: 'Flower' },
  { id: 'waxing-nose-ear', name: 'Waxing Nose / Ear', description: 'Pain-free premium interior nostril and outer ear waxing.', durationMin: 15, priceBDT: 1000, category: 'spa', icon: 'Flower' },
  { id: 'waxing-hands', name: 'Waxing Hands', description: 'Full arm smooth waxing.', durationMin: 30, priceBDT: 2000, category: 'spa', icon: 'Flower' },
  { id: 'waxing-back', name: 'Waxing Back Upper Body', description: 'Upper back smooth waxing.', durationMin: 35, priceBDT: 3000, category: 'spa', icon: 'Flower' },
  { id: 'waxing-legs', name: 'Waxing Half Legs / Full Legs', description: 'Leg area hair removal waxing.', durationMin: 40, priceBDT: 3000, category: 'spa', icon: 'Flower' },
  { id: 'waxing-front', name: 'Waxing Front Upper Body', description: 'Chest and stomach area smooth waxing.', durationMin: 35, priceBDT: 3500, category: 'spa', icon: 'Flower' },
  { id: 'waxing-halfbody', name: 'Waxing Half Body (Neck + Hands + Front & Back)', description: 'Comprehensive upper torso waxing package.', durationMin: 70, priceBDT: 6000, category: 'spa', icon: 'Flower' },
  { id: 'waxing-fullbody', name: 'Waxing Full Body', description: 'Complete full body hair removal waxing.', durationMin: 100, priceBDT: 10000, category: 'spa', icon: 'Flower' },

  // MAKE-OVER ART
  { id: 'groom-makeup', name: 'Groom Make-Up', description: 'Blemish-free high definition camera matching groom face setting.', durationMin: 60, priceBDT: 4000, category: 'hair', icon: 'UserCheck' },
  { id: 'model-makeup', name: 'Studio / Model Make-Up', description: 'Studio flash-safe professional modeling look design.', durationMin: 75, priceBDT: 6000, category: 'hair', icon: 'UserCheck' },

  // BODY SPA / BODY CARE & MASSAGES
  { id: 'aromatic-bath', name: 'Aromatic Bath (15 Minutes)', description: 'Relaxing jacuzzi bath infused with premium essential oils.', durationMin: 15, priceBDT: 10000, category: 'spa', icon: 'Flower' },
  { id: 'bodyscrub-steam', name: 'Full Body Scrub with Steam', description: 'Organic dermabrasion scrub followed by full hot steam.', durationMin: 60, priceBDT: 5500, category: 'spa', icon: 'Flower' },
  { id: 'back-scrub', name: 'Back Scrub', description: 'Shedding dead cells and deep cleansing of the back.', durationMin: 30, priceBDT: 2400, category: 'spa', icon: 'Flower' },
  { id: 'leg-scrub', name: 'Leg Scrub', description: 'Exfoliation and smoothing for the legs.', durationMin: 25, priceBDT: 1200, category: 'spa', icon: 'Flower' },
  { id: 'hand-scrub', name: 'Hand Scrub', description: 'Smooth, hydrating hand scrub and wash.', durationMin: 20, priceBDT: 1000, category: 'spa', icon: 'Flower' },
  { id: 'bodyshop-scrub-steam', name: 'Body Shop Full Body Scrub with Steam', description: 'Super premium imported Body Shop exfoliation with steam.', durationMin: 70, priceBDT: 11000, category: 'spa', icon: 'Flower' },
  { id: 'body-smoothing-scrub', name: 'Body Smoothing Scrub', description: 'Cell renewal full body smoothing scrub.', durationMin: 50, priceBDT: 8000, category: 'spa', icon: 'Flower' },
  { id: 'unilever-full-scrub', name: 'Unilever Scrub Full Body Scrub', description: 'Hydrating body scrub using premium Unilever elements.', durationMin: 60, priceBDT: 6500, category: 'spa', icon: 'Flower' },
  { id: 'bodyshop-hand-scrub', name: 'Body Shop Hand Scrub', description: 'Premium hand hydration and shine scrub.', durationMin: 25, priceBDT: 2000, category: 'spa', icon: 'Flower' },
  { id: 'bodyshop-back-scrub', name: 'Body Shop Back Scrub', description: 'Advanced spine smoothing and steam session.', durationMin: 35, priceBDT: 6000, category: 'spa', icon: 'Flower' },
  { id: 'bodyshop-foot-scrub', name: 'Body Shop Foot Scrub', description: 'Dead skin removal with deep moisturizing foot scrub.', durationMin: 25, priceBDT: 2000, category: 'spa', icon: 'Flower' },
  { id: 'aloe-body-scrub', name: 'Aloe Vera Body Scrub Full Body', description: 'Sensitive skin safe cooling full body scrub.', durationMin: 55, priceBDT: 5000, category: 'spa', icon: 'Flower' },
  { id: 'royal-body-scrub', name: 'Royal Body Smoothing Scrub', description: 'The absolute pinnacle of luxury full body pampering.', durationMin: 90, priceBDT: 16000, category: 'spa', icon: 'Crown' },

  // HAND & FOOT CARE
  { id: 'manicure-classic', name: 'Manicure', description: 'Clean cuticle trim, buffing, and hand cream.', durationMin: 30, priceBDT: 800, category: 'spa', icon: 'Flower' },
  { id: 'pedicure-classic', name: 'Pedicure', description: 'Deep foot soak, heel scrubbing, and massage.', durationMin: 45, priceBDT: 1200, category: 'spa', icon: 'Flower' },
  { id: 'manicure-deluxe', name: 'Manicure Deluxe', description: 'Warm oils massage, paraffin mask hydration.', durationMin: 40, priceBDT: 1200, category: 'spa', icon: 'Flower' },
  { id: 'pedicure-deluxe', name: 'Pedicure Deluxe', description: 'Signature mud mask, hot stone massage.', durationMin: 55, priceBDT: 2300, category: 'spa', icon: 'Flower' },

  // MASSAGES
  { id: 'head-neck-foot-60', name: 'Neck, Head & Foot Massage (60 Mins)', description: 'Full tension release combo massage.', durationMin: 60, priceBDT: 2200, category: 'spa', icon: 'Flower' },
  { id: 'head-neck-foot-90', name: 'Neck, Head & Foot Massage (90 Mins)', description: 'Extended tension release combo massage.', durationMin: 90, priceBDT: 3000, category: 'spa', icon: 'Flower' },
  { id: 'back-neck-shoulder', name: 'Back, Neck & Shoulder Massage (30 Mins)', description: 'Quick trigger points de-stress massage.', durationMin: 30, priceBDT: 1500, category: 'spa', icon: 'Flower' },
  { id: 'thai-foot-45', name: 'Thai Foot Reflexology (45 Mins)', description: 'Pressure points mapping for foot recovery.', durationMin: 45, priceBDT: 1200, category: 'spa', icon: 'Flower' },
  { id: 'thai-foot-60', name: 'Thai Foot Reflexology (60 Mins)', description: 'Standard foot reflexology massage.', durationMin: 60, priceBDT: 1600, category: 'spa', icon: 'Flower' },
  { id: 'thai-foot-90', name: 'Thai Foot Reflexology (90 Mins)', description: 'Extended deep foot reflexology massage.', durationMin: 90, priceBDT: 2200, category: 'spa', icon: 'Flower' },
  { id: 'head-massage-tonic', name: 'Head Massage with Hair Tonic', description: 'Deep cerebral massage with nutrient-rich liquid (15 Mins).', durationMin: 15, priceBDT: 1000, category: 'spa', icon: 'Flower' },
  { id: 'head-massage-notonic', name: 'Head Massage without Hair Tonic', description: 'Standard dry scalp soothing massage (15 Mins).', durationMin: 15, priceBDT: 600, category: 'spa', icon: 'Flower' },
  { id: 'chair-massage', name: 'Chair Massage', description: 'Ergonomic neck-to-hip quick massage (20 Mins).', durationMin: 20, priceBDT: 1200, category: 'spa', icon: 'Flower' },

  // BODY MASSAGES
  { id: 'bamboo-massage-60', name: 'Bamboo Massage (60 Mins)', description: 'Warm bamboo rods rolling to break deep muscle fibers.', durationMin: 60, priceBDT: 3500, category: 'spa', icon: 'Flower' },
  { id: 'bamboo-massage-90', name: 'Bamboo Massage (90 Mins)', description: 'Extended deep tension bamboo therapy.', durationMin: 90, priceBDT: 4800, category: 'spa', icon: 'Flower' },
  { id: 'hotstone-massage-60', name: 'Hot Stone Massage (60 Mins)', description: 'Therapeutic warm volcanic basalt stones layout.', durationMin: 60, priceBDT: 3800, category: 'spa', icon: 'Flower' },
  { id: 'hotstone-massage-90', name: 'Hot Stone Massage (90 Mins)', description: 'Deep heat thermal stone recovery.', durationMin: 90, priceBDT: 5000, category: 'spa', icon: 'Flower' },
  { id: 'swedish-massage-60', name: 'Swedish Massage (60 Mins)', description: 'Classic long-stroke circulatory massage.', durationMin: 60, priceBDT: 3200, category: 'spa', icon: 'Flower' },
  { id: 'swedish-massage-90', name: 'Swedish Massage (90 Mins)', description: 'Extended light-to-medium Swedish massage.', durationMin: 90, priceBDT: 4200, category: 'spa', icon: 'Flower' },
  { id: 'aroma-massage-60', name: 'Aroma Massage (60 Mins)', description: 'Lavender/Eucalyptus therapeutic essential oil massage.', durationMin: 60, priceBDT: 2800, category: 'spa', icon: 'Flower' },
  { id: 'aroma-massage-90', name: 'Aroma Massage (90 Mins)', description: 'Total aromatherapy recharge.', durationMin: 90, priceBDT: 3700, category: 'spa', icon: 'Flower' },
  { id: 'traditional-massage-60', name: 'Indian / Traditional Massage (60 Mins)', description: 'Deep kneading physical muscle reset.', durationMin: 60, priceBDT: 2500, category: 'spa', icon: 'Flower' },
  { id: 'traditional-massage-90', name: 'Indian / Traditional Massage (90 Mins)', description: 'Full body restorative massage.', durationMin: 90, priceBDT: 3500, category: 'spa', icon: 'Flower' },
  { id: 'deeptissue-massage-60', name: 'Deep Tissue Body Massage (60 Mins)', description: 'High-pressure physical knot removal.', durationMin: 60, priceBDT: 3200, category: 'spa', icon: 'Flower' },
  { id: 'deeptissue-massage-90', name: 'Deep Tissue Body Massage (90 Mins)', description: 'Maestro muscle restoration.', durationMin: 90, priceBDT: 4200, category: 'spa', icon: 'Flower' },
  { id: 'thai-massage-60', name: 'Thai Body Massage (60 Mins)', description: 'Passive yoga stretches and compressions.', durationMin: 60, priceBDT: 3200, category: 'spa', icon: 'Flower' },
  { id: 'thai-massage-90', name: 'Thai Body Massage (90 Mins)', description: 'Extended physical stretch alignment.', durationMin: 90, priceBDT: 4200, category: 'spa', icon: 'Flower' },

  // SPORTS MASSAGE & CUPPING
  { id: 'cupping-back', name: 'Back Cupping with 30 Min Massage', description: 'Detoxifying vacuum cupping of the back with massage.', durationMin: 60, priceBDT: 4000, category: 'spa', icon: 'Flower' },
  { id: 'cupping-full', name: 'Full Body Cupping with 30 Min Massage', description: 'Full-body muscle blood flow cupping activation.', durationMin: 80, priceBDT: 7000, category: 'spa', icon: 'Flower' },
  { id: 'sports-massage-90', name: 'Sports Massage (90 Mins)', description: 'High-intensity athletic relief and joint flexibility.', durationMin: 90, priceBDT: 5500, category: 'spa', icon: 'Flower' },
  { id: 'cupping-fire-back', name: 'Back Fire Cupping with 30 Min Massage', description: 'Traditional dynamic heat vacuum cupping therapy.', durationMin: 60, priceBDT: 7000, category: 'spa', icon: 'Flame' },
  { id: 'cupping-fire-full', name: 'Full Body Fire Cupping with 30 Min Massage', description: 'The peak physical recovery standard for high fatigue.', durationMin: 90, priceBDT: 10000, category: 'spa', icon: 'Flame' },

  // PACKAGES
  { id: 'package-regular', name: 'REGULAR PACKAGE', description: 'Includes: Hair Cut, Adonis Special Facial, Pedicure & Manicure, Shave.', durationMin: 120, priceBDT: 5800, category: 'pack', icon: 'Crown' },
  { id: 'package-classic', name: 'CLASSIC PACKAGE', description: 'Includes: Hair Cut, Shave, Oil Massage, Pedicure & Manicure, Janssen Whitening Facial.', durationMin: 150, priceBDT: 10500, category: 'pack', icon: 'Crown' },
  { id: 'package-bridegroom-1', name: 'BRIDEGROOM PACKAGE (Single Day)', description: 'Includes: Hair Cutting and Setting, Fair Polish Face + Neck + Hands, Hair Spa, Pedicure and Manicure, Shave, Body Shop Vitamin C Facial, Personal Room, Make-Over Art, Body Massage.', durationMin: 200, priceBDT: 21000, category: 'pack', icon: 'Crown' },
  { id: 'package-bridegroom-2', name: 'BRIDEGROOM PACKAGE (2 Days)', description: 'Day 1: Hair Cut + Shampoo + Conditioner, Fair Polish, Hair Spa, Pedicure & Manicure, Shave. Day 2: Body Massage + Steam, Hair Shining, Body Shop Vitamin E Facial, Make-Over, Hair Setting.', durationMin: 360, priceBDT: 23500, category: 'pack', icon: 'Crown' },
  { id: 'package-business', name: 'BUSINESS PACKAGE', description: 'Includes: Stylish Hair Cut, Stylish Shave, Bigen Ammonia Free Color, Caring Hair Spa, Pedicure & Manicure, Body Shop Seaweed Facial, Swedish Body Massage 60 Min.', durationMin: 180, priceBDT: 17500, category: 'pack', icon: 'Crown' },
  { id: 'package-royal', name: 'ROYAL PACKAGE', description: 'Includes: Hair Cut, Shave, Deluxe Pedicure & Manicure, Ammonia Free Color Inova, L’Oréal Hair Spa, Gold Facial, Body Scrub with Steam.', durationMin: 210, priceBDT: 19500, category: 'pack', icon: 'Crown' },
  { id: 'package-spa-classic', name: 'CLASSIC SPA PACKAGE', description: 'Includes: Pedicure Manicure, Indian Traditional Massage, Steam with Shower, Body Scrub.', durationMin: 100, priceBDT: 10000, category: 'pack', icon: 'Crown' },
  { id: 'package-spa-business', name: 'BUSINESS SPA PACKAGE', description: 'Includes: Pedicure Deluxe, Manicure Deluxe, Deep Tissue Body Massage, Body Smoothing Scrub, Sauna with Shower.', durationMin: 150, priceBDT: 14000, category: 'pack', icon: 'Crown' },
  { id: 'package-spa-premium', name: 'PREMIUM SPA PACKAGE', description: 'Includes: Pedicure Deluxe, Manicure Deluxe, Swedish Massage, Royal Body Smoothing Scrub, Sauna / Steam with Shower.', durationMin: 180, priceBDT: 21000, category: 'pack', icon: 'Crown' }
];

// Add dummy barbers
const dummyBarbers = [
  {
    id: "tariq",
    name: "Babul Chandra Shil",
    experienceYears: 12,
    specialty: "Skin Fade & Modern Texturizing",
    portraitUrl: "/assets/images/babul_barbar.png",
    bio: "Renowned stylist with 12 years of craftsmanship. Expert in razor fades and sculpting bold executive silhouettes.",
    rating: 4.9
  },
  {
    id: "kamran",
    name: "Antor Mondol",
    experienceYears: 9,
    specialty: "Royal Shaves & Beard Architecture",
    portraitUrl: "/assets/images/master_barber_portrait_1779269169728.png",
    bio: "A true maestro of the straight razor, specialized in symmetrical beard mapping and hot towel restoration.",
    rating: 5.0
  },
  {
    id: "arif",
    name: "Rofiqul Islam",
    experienceYears: 7,
    specialty: "Classic Scissor Cuts & Scalp Health",
    portraitUrl: "/assets/images/rofiq_barbar.png",
    bio: "Dedicated to traditional high-end scissor sculpting and therapeutic scalp treatments to promote long-term volume.",
    rating: 4.8
  },
  {
    id: "javed",
    name: "Chandra",
    experienceYears: 10,
    specialty: "Hair Color & Keratin Treatments",
    portraitUrl: "/assets/images/chandra.png",
    bio: "Color specialist with a decade of expertise in ammonia-free dye systems, keratin bonding, and high-fashion tone transformations.",
    rating: 4.9
  },
  {
    id: "rohit",
    name: "Kalu Rupa Shil",
    experienceYears: 6,
    specialty: "Facial Treatments & Spa Services",
    portraitUrl: "/assets/images/kalu_rupa.png",
    bio: "Certified skin-care professional with advanced training in premium facials, body scrubs, and therapeutic massage techniques.",
    rating: 4.7
  },
  {
    id: "salim",
    name: "Saimon",
    experienceYears: 8,
    specialty: "Massage Therapy & Body Spa",
    portraitUrl: "/assets/images/saimon.png",
    bio: "Licensed massage therapist trained in Swedish, Deep Tissue, Thai and Aroma techniques. Known for his precision pressure-point therapy.",
    rating: 4.8
  },
  {
    id: "rakib",
    name: "Dulal Chandra",
    experienceYears: 5,
    specialty: "Modern Beard Styling & Waxing",
    portraitUrl: "/assets/images/dulal_chandra.png",
    bio: "Young and passionate groomer specializing in contemporary beard shaping, wax-based styling, and precise razor-line finishes.",
    rating: 4.6
  },
];

// Preserved user SMTP configuration
const userSmtp = {
  host: "mail.adonis.com.bd",
  port: 465,
  secure: true,
  user: "book@adonis.com.bd",
  pass: "O[^1G=reL9EXLz[S",
  fromEmail: "book@adonis.com.bd",
  adminEmails: "info@adonis.com.bd,booking@adonis.com.bd,itdepartmnet.adonis@gmail.com,kawsarhosen.dev@gmail.com"
};

// Update db object properties
db.services = newServices;
db.barbers = dummyBarbers;
db.smtp = userSmtp;

// Write back
fs.writeFileSync(DB_FILE, JSON.stringify(db, null, 2), 'utf-8');
console.log("db.json updated successfully with new services list, dummy barbers, and SMTP settings.");

import express from 'express';
import fs from 'fs';
import path from 'path';
import multer from 'multer';
import nodemailer from 'nodemailer';
import mysql from 'mysql2/promise';
import 'dotenv/config';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = 3001;
const UPLOADS_DIR = path.join(__dirname, 'public/uploads');
const DB_FILE = path.join(__dirname, 'db.json');
const USE_MYSQL = process.env.USE_MYSQL === 'true';
const MYSQL_CONFIG = {
  host: process.env.MYSQL_HOST || 'localhost',
  port: parseInt(process.env.MYSQL_PORT || '3306', 10),
  user: process.env.MYSQL_USER || 'root',
  password: process.env.MYSQL_PASSWORD || '',
  database: process.env.MYSQL_DATABASE || 'adonis_cms'
};

const FALLBACK_SMTP = {
  host: 'mail.adonis.com.bd',
  port: 465,
  secure: true,
  user: 'book@adonis.com.bd',
  pass: 'O[^1G=reL9EXLz[S',
  fromEmail: 'book@adonis.com.bd',
  adminEmails: 'info@adonis.com.bd,booking@adonis.com.bd,itdepartmnet.adonis@gmail.com,kawsarhosen.dev@gmail.com'
};

const CODE_SMTP = {
  host: process.env.SMTP_HOST || FALLBACK_SMTP.host,
  port: parseInt(process.env.SMTP_PORT || String(FALLBACK_SMTP.port), 10),
  secure: process.env.SMTP_SECURE ? process.env.SMTP_SECURE === 'true' : FALLBACK_SMTP.secure,
  user: process.env.SMTP_USER || FALLBACK_SMTP.user,
  pass: process.env.SMTP_PASS || FALLBACK_SMTP.pass,
  fromEmail: process.env.SMTP_FROM_EMAIL || process.env.SMTP_USER || FALLBACK_SMTP.fromEmail,
  adminEmails: process.env.SMTP_ADMIN_EMAILS || FALLBACK_SMTP.adminEmails
};

// Ensure uploads folder exists
if (!fs.existsSync(UPLOADS_DIR)) {
  fs.mkdirSync(UPLOADS_DIR, { recursive: true });
}

// Middleware
app.use(express.json({ limit: '10mb' }));

// Custom simple CORS middleware
app.use((req, res, next) => {
  res.header("Access-Control-Allow-Origin", "*");
  res.header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");
  res.header("Access-Control-Allow-Headers", "Content-Type, Authorization");
  if (req.method === 'OPTIONS') {
    return res.sendStatus(200);
  }
  next();
});

const asyncRoute = (handler: express.RequestHandler): express.RequestHandler => {
  return (req, res, next) => {
    Promise.resolve(handler(req, res, next)).catch(next);
  };
};

// Configure Multer for portrait uploads
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, UPLOADS_DIR);
  },
  filename: (req, file, cb) => {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1e9);
    const ext = path.extname(file.originalname);
    cb(null, 'barber-' + uniqueSuffix + ext);
  }
});
const upload = multer({ storage });

// Initial seed data if db.json is missing
const INITIAL_DATABASE = {
  services: [
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
      name: 'Skin Fade & Modern Styling',
      description: 'Expert low/mid/high zero fade blend, hair texturizing, and high-hold pomade styling.',
      durationMin: 60,
      priceBDT: 2500,
      category: 'hair',
      icon: 'Sparkles'
    },
    {
      id: 'royal-beard-shaping',
      name: 'Royal Beard Shaping',
      description: 'Beard trimming, symmetrical oil outlining, free-hand clippers sculpting, and moisturizing balm.',
      durationMin: 30,
      priceBDT: 1200,
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
  ],
  barbers: [
    {
      id: 'tariq',
      name: 'Babul Chandra Shil',
      experienceYears: 12,
      specialty: 'Skin Fade & Modern Texturizing',
      portraitUrl: '/assets/images/babul_barbar.png',
      bio: 'Renowned stylist with 12 years of craftsmanship. Expert in razor fades and sculpting bold executive silhouettes.',
      rating: 4.9
    },
    {
      id: 'kamran',
      name: 'Antor Mondol',
      experienceYears: 9,
      specialty: 'Royal Shaves & Beard Architecture',
      portraitUrl: '/assets/images/master_barber_portrait_1779269169728.png',
      bio: 'A true maestro of the straight razor, specialized in symmetrical beard mapping and hot towel restoration.',
      rating: 5.0
    },
    {
      id: 'arif',
      name: 'Rofiqul Islam',
      experienceYears: 7,
      specialty: 'Classic Scissor Cuts & Scalp Health',
      portraitUrl: '/assets/images/rofiq_barbar.png',
      bio: 'Dedicated to traditional high-end scissor sculpting and therapeutic scalp treatments to promote long-term volume.',
      rating: 4.8
    },
    {
      id: 'javed',
      name: 'Chandra',
      experienceYears: 10,
      specialty: 'Hair Color & Keratin Treatments',
      portraitUrl: '/assets/images/chandra.png',
      bio: 'Color specialist with a decade of expertise in ammonia-free dye systems, keratin bonding, and high-fashion tone transformations.',
      rating: 4.9
    },
    {
      id: 'rohit',
      name: 'Kalu Rupa Shil',
      experienceYears: 6,
      specialty: 'Facial Treatments & Spa Services',
      portraitUrl: '/assets/images/kalu_rupa.png',
      bio: 'Certified skin-care professional with advanced training in premium facials, body scrubs, and therapeutic massage techniques.',
      rating: 4.7
    },
    {
      id: 'salim',
      name: 'Saimon',
      experienceYears: 8,
      specialty: 'Massage Therapy & Body Spa',
      portraitUrl: '/assets/images/saimon.png',
      bio: 'Licensed massage therapist trained in Swedish, Deep Tissue, Thai and Aroma techniques. Known for his precision pressure-point therapy.',
      rating: 4.8
    },
    {
      id: 'rakib',
      name: 'Dulal Chandra',
      experienceYears: 5,
      specialty: 'Modern Beard Styling & Waxing',
      portraitUrl: '/assets/images/dulal_chandra.png',
      bio: 'Young and passionate groomer specializing in contemporary beard shaping, wax-based styling, and precise razor-line finishes.',
      rating: 4.6
    }
  ],
  settings: {
    brandName: 'ADONIS',
    brandSubtitle: 'Premium Grooming. Redefined Masculinity.',
    heroTitle: 'Craft Your Identity With Precision',
    heroSubtitle: 'Experience elite barbering at Adonis Men’s Grooming, where modern style meets timeless perfection in the heart of Dhaka.',
    heroBg: '/assets/images/adonis_executive_lounge_1779270704894.png',
    aboutStory: 'Adonis Men’s Grooming is a premium barbershop brand in Dhaka dedicated to redefining modern masculinity through precision grooming, luxury service, and personalized styling. Every cut, shave, and treatment is designed to elevate confidence and identity.',
    aboutDescription: 'We believe that grooming is not merely a transaction—it represents a curated ritual of premium transition. Nestled in Dhaka’s premier neighborhoods, Adonis pairs classic European barber heritage with high-end, contemporary Dubai hotel-standard lounge accommodations. From the selection of our premium organic grooming balms to the exact temperature parameters of our steaming mint towels, every detail is meticulously orchestrated to deliver perfection.',
    contactEmail: 'info@adonis.com.bd',
    openHoursDays: 'Everyday (Sat - Fri)',
    openHoursTime: '10:00 AM – 10:00 PM',
    phoneNumbers: ['+880 1919-700800', '+880 1700-600333'],
    facebookUrl: 'https://facebook.com/adonis.bd',
    instagramUrl: 'https://instagram.com/adonis.grooming',
    whatsappUrl: 'https://wa.me/8801919700800'
  },
  smtp: {
    host: '',
    port: 587,
    secure: false,
    user: '',
    pass: '',
    fromEmail: 'noreply@adonis.com.bd',
    adminEmails: 'admin@adonis.com.bd'
  },
  blogs: [
    {
      id: 'premium-mens-grooming-dhaka',
      slug: 'premium-mens-grooming-dhaka',
      title: 'Premium Men’s Grooming in Dhaka: The Adonis Guide to a Sharper Look',
      excerpt: 'Discover how professional haircuts, beard shaping, facials, massage, and premium salon care help modern men in Dhaka look sharper and feel more confident.',
      coverImage: '/assets/images/adonis_styling_chairs_1779270725139.png',
      contentHtml: `
        <h2>Why Men’s Grooming Matters More Than Ever</h2>
        <p>Modern grooming is not only about looking polished. It is about confidence, hygiene, presentation, and personal identity. At <strong>Adonis Men’s Grooming in Dhaka</strong>, every service is designed to help men look sharp while enjoying a calm, premium salon experience.</p>
        <h3>Precision Haircuts for Every Lifestyle</h3>
        <p>A professional haircut should match your face shape, profession, hair texture, and daily styling routine. Whether you prefer a classic scissor cut, a modern skin fade, or a refined executive style, the right haircut can instantly improve your overall appearance.</p>
        <ul><li>Clean fades and sharp blending</li><li>Face-shape-based consultation</li><li>Professional styling and finishing</li></ul>
        <h3>Beard Shaping and Hot Towel Shaves</h3>
        <p>For men with facial hair, beard maintenance is essential. A balanced beard line, clean cheek contour, and sharp neckline can define the jaw and create a more structured look.</p>
        <h4>Facials, Spa Care, and Relaxation</h4>
        <p>Dhaka’s weather, dust, and stress can affect skin and scalp health. Premium facials, hair spa treatments, massage, and body care help restore freshness and reduce everyday fatigue.</p>
      `,
      seoTitle: 'Premium Men’s Grooming in Dhaka | Adonis Men’s Grooming',
      seoDescription: 'Looking for the best men’s grooming salon in Dhaka? Learn how Adonis combines precision haircuts, beard styling, facials, spa care, and premium salon service.',
      status: 'published',
      createdAt: '2026-06-04T00:00:00.000Z',
      updatedAt: '2026-06-04T00:00:00.000Z'
    }
  ],
  bookings: []
};

// Database helper functions. JSON storage is the default so the app can run
// without MySQL. Set USE_MYSQL=true only when a MySQL database is available.
let poolPromise: Promise<mysql.Pool> | null = null;

async function getPool() {
  if (!USE_MYSQL) {
    throw new Error('MySQL is disabled. Set USE_MYSQL=true to use the MySQL adapter.');
  }

  if (!poolPromise) {
    poolPromise = (async () => {
      const pool = mysql.createPool({
        ...MYSQL_CONFIG,
        waitForConnections: true,
        connectionLimit: 10,
        namedPlaceholders: true
      });

      await initMysql(pool);
      return pool;
    })().catch((err) => {
      poolPromise = null;
      throw err;
    });
  }

  return poolPromise;
}

async function initMysql(pool: mysql.Pool) {
  await pool.query(`
    CREATE TABLE IF NOT EXISTS services (
      id VARCHAR(120) PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      description TEXT,
      durationMin INT NOT NULL DEFAULT 45,
      priceBDT INT NOT NULL DEFAULT 0,
      category VARCHAR(40) NOT NULL DEFAULT 'hair',
      icon VARCHAR(80) NOT NULL DEFAULT 'Scissors'
    )
  `);

  await pool.query(`
    CREATE TABLE IF NOT EXISTS barbers (
      id VARCHAR(120) PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      experienceYears INT NOT NULL DEFAULT 0,
      specialty VARCHAR(255),
      portraitUrl TEXT,
      bio TEXT,
      rating DECIMAL(3,1) NOT NULL DEFAULT 5.0
    )
  `);

  await pool.query(`
    CREATE TABLE IF NOT EXISTS cms_meta (
      meta_key VARCHAR(80) PRIMARY KEY,
      meta_value LONGTEXT NOT NULL
    )
  `);

  await pool.query(`
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
    )
  `);

  const [[serviceCount]] = await pool.query<mysql.RowDataPacket[]>('SELECT COUNT(*) AS total FROM services');
  if (serviceCount.total === 0) {
    await replaceServices(pool, INITIAL_DATABASE.services);
  }

  const [[barberCount]] = await pool.query<mysql.RowDataPacket[]>('SELECT COUNT(*) AS total FROM barbers');
  if (barberCount.total === 0) {
    await replaceBarbers(pool, INITIAL_DATABASE.barbers);
  }

  await pool.query(
    'INSERT IGNORE INTO cms_meta (meta_key, meta_value) VALUES (?, ?), (?, ?)',
    ['settings', JSON.stringify(INITIAL_DATABASE.settings), 'smtp', JSON.stringify(INITIAL_DATABASE.smtp)]
  );

  const [[blogCount]] = await pool.query<mysql.RowDataPacket[]>('SELECT COUNT(*) AS total FROM blogs');
  if (blogCount.total === 0) {
    await replaceBlogs(pool, INITIAL_DATABASE.blogs);
  }
}

function readJsonDb() {
  if (!fs.existsSync(DB_FILE)) {
    fs.writeFileSync(DB_FILE, JSON.stringify(INITIAL_DATABASE, null, 2));
  }

  try {
    const raw = fs.readFileSync(DB_FILE, 'utf8');
    const parsed = JSON.parse(raw);
    return {
      services: parsed.services?.length ? parsed.services : INITIAL_DATABASE.services,
      barbers: parsed.barbers?.length ? parsed.barbers : INITIAL_DATABASE.barbers,
      settings: { ...INITIAL_DATABASE.settings, ...(parsed.settings || {}) },
      smtp: { ...INITIAL_DATABASE.smtp, ...(parsed.smtp || {}) },
      blogs: parsed.blogs?.length ? parsed.blogs : INITIAL_DATABASE.blogs,
      bookings: parsed.bookings || []
    };
  } catch (err) {
    console.error('Failed to read db.json. Falling back to built-in data:', err);
    return { ...INITIAL_DATABASE, bookings: [] };
  }
}

function writeJsonDb(data: any) {
  const current = readJsonDb();
  const next = {
    services: data.services || current.services || INITIAL_DATABASE.services,
    barbers: data.barbers || current.barbers || INITIAL_DATABASE.barbers,
    settings: data.settings || current.settings || INITIAL_DATABASE.settings,
    smtp: data.smtp || current.smtp || INITIAL_DATABASE.smtp,
    blogs: data.blogs || current.blogs || INITIAL_DATABASE.blogs,
    bookings: data.bookings || current.bookings || []
  };

  fs.writeFileSync(DB_FILE, JSON.stringify(next, null, 2));
}

async function replaceServices(pool: mysql.Pool | mysql.PoolConnection, services: any[]) {
  await pool.query('DELETE FROM services');
  for (const service of services) {
    await pool.query(
      `INSERT INTO services (id, name, description, durationMin, priceBDT, category, icon)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [
        service.id,
        service.name,
        service.description || '',
        service.durationMin || 45,
        service.priceBDT || 0,
        service.category || 'hair',
        service.icon || 'Scissors'
      ]
    );
  }
}

async function replaceBarbers(pool: mysql.Pool | mysql.PoolConnection, barbers: any[]) {
  await pool.query('DELETE FROM barbers');
  for (const barber of barbers) {
    await pool.query(
      `INSERT INTO barbers (id, name, experienceYears, specialty, portraitUrl, bio, rating)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [
        barber.id,
        barber.name,
        barber.experienceYears || 0,
        barber.specialty || '',
        barber.portraitUrl || '',
        barber.bio || '',
        barber.rating || 5
      ]
    );
  }
}

async function replaceBlogs(pool: mysql.Pool | mysql.PoolConnection, blogs: any[]) {
  await pool.query('DELETE FROM blogs');
  for (const blog of blogs) {
    await pool.query(
      `INSERT INTO blogs (id, slug, title, excerpt, coverImage, contentHtml, seoTitle, seoDescription, status, createdAt, updatedAt)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        blog.id,
        blog.slug,
        blog.title,
        blog.excerpt || '',
        blog.coverImage || '',
        blog.contentHtml || '',
        blog.seoTitle || blog.title,
        blog.seoDescription || blog.excerpt || '',
        blog.status || 'draft',
        new Date(blog.createdAt || Date.now()),
        new Date(blog.updatedAt || Date.now())
      ]
    );
  }
}

async function readDb() {
  if (!USE_MYSQL) {
    return readJsonDb();
  }

  const pool = await getPool();
  const [services] = await pool.query<mysql.RowDataPacket[]>('SELECT * FROM services ORDER BY id');
  const [barbers] = await pool.query<mysql.RowDataPacket[]>('SELECT * FROM barbers ORDER BY id');
  const [blogs] = await pool.query<mysql.RowDataPacket[]>('SELECT * FROM blogs ORDER BY createdAt DESC');
  const [metaRows] = await pool.query<mysql.RowDataPacket[]>('SELECT meta_key, meta_value FROM cms_meta');

  const meta = Object.fromEntries(metaRows.map(row => [row.meta_key, JSON.parse(row.meta_value)]));

  return {
    services: services.map(service => ({
      ...service,
      durationMin: Number(service.durationMin),
      priceBDT: Number(service.priceBDT)
    })),
    barbers: barbers.map(barber => ({
      ...barber,
      experienceYears: Number(barber.experienceYears),
      rating: Number(barber.rating)
    })),
    settings: meta.settings || INITIAL_DATABASE.settings,
    smtp: meta.smtp || INITIAL_DATABASE.smtp,
    blogs: blogs.map(blog => ({
      ...blog,
      createdAt: new Date(blog.createdAt).toISOString(),
      updatedAt: new Date(blog.updatedAt).toISOString()
    })),
    bookings: []
  };
}

async function writeDb(data: any) {
  if (!USE_MYSQL) {
    writeJsonDb(data);
    return;
  }

  const pool = await getPool();
  const connection = await pool.getConnection();

  try {
    await connection.beginTransaction();
    await replaceServices(connection, data.services || []);
    await replaceBarbers(connection, data.barbers || []);
    await replaceBlogs(connection, data.blogs || []);
    await connection.query(
      `INSERT INTO cms_meta (meta_key, meta_value)
       VALUES (?, ?), (?, ?)
       ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value)`,
      [
        'settings',
        JSON.stringify(data.settings || INITIAL_DATABASE.settings),
        'smtp',
        JSON.stringify(data.smtp || INITIAL_DATABASE.smtp)
      ]
    );
    await connection.commit();
  } catch (err) {
    await connection.rollback();
    throw err;
  } finally {
    connection.release();
  }
}

// Mail Helper
async function sendBookingEmails(booking: any, serviceName: string, barberName: string, branchName: string, smtp: any) {
  if (!smtp.host || !smtp.user || !smtp.pass) {
    console.log("SMTP is not configured in code/env. Emails are bypassed.");
    return;
  }

  console.log("SMTP configured. Initializing transporter for:", smtp.host);
  const smtpPort = typeof smtp.port === 'string' ? parseInt(smtp.port) : smtp.port;
  const transporter = nodemailer.createTransport({
    host: smtp.host,
    port: smtpPort,
    secure: smtp.secure === true || smtp.secure === 'true',
    auth: {
      user: smtp.user,
      pass: smtp.pass
    }
  });

  const formattedDate = new Date(booking.date).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  const clientMailOptions = {
    from: `"${smtp.fromEmail ? smtp.fromEmail.split('@')[0] : 'Adonis Grooming'}" <${smtp.fromEmail || smtp.user}>`,
    to: booking.clientEmail,
    subject: `Grooming Session Confirmed: ${booking.bookingCode}`,
    html: `
      <div style="background-color: #0b0b0b; color: #ffffff; font-family: Georgia, serif; padding: 40px; border: 1px solid #32BBED; max-width: 600px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 30px;">
          <h2 style="color: #32BBED; text-transform: uppercase; letter-spacing: 4px; font-weight: normal; margin: 0; font-size: 24px;">ADONIS</h2>
          <p style="color: #888; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; margin-top: 5px;">Premium Grooming Lounge</p>
        </div>
        <div style="border-top: 1px solid rgba(200, 162, 74, 0.2); border-bottom: 1px solid rgba(200, 162, 74, 0.2); padding: 25px 0; margin-bottom: 25px;">
          <p style="margin: 0 0 15px 0; font-size: 14px;">Dear <strong>${booking.clientName}</strong>,</p>
          <p style="color: #ccc; font-size: 13px; line-height: 1.6; margin: 0 0 20px 0;">Your premium styling bay has been reserved. Here are your booking details:</p>
          <table style="width: 100%; font-size: 13px; color: #ccc;">
            <tr>
              <td style="padding: 6px 0; color: #32BBED; width: 40%;">CONFIRMATION CODE:</td>
              <td style="padding: 6px 0; font-weight: bold; color: #fff;">${booking.bookingCode}</td>
            </tr>
            <tr>
              <td style="padding: 6px 0; color: #32BBED;">LOUNGE LOCATION:</td>
              <td style="padding: 6px 0; color: #fff;">${branchName}</td>
            </tr>
            <tr>
              <td style="padding: 6px 0; color: #32BBED;">SERVICE SELECT:</td>
              <td style="padding: 6px 0; color: #fff;">${serviceName}</td>
            </tr>
            <tr>
              <td style="padding: 6px 0; color: #32BBED;">MASTER BARBER:</td>
              <td style="padding: 6px 0; color: #fff;">${barberName}</td>
            </tr>
            <tr>
              <td style="padding: 6px 0; color: #32BBED;">DATE SECURED:</td>
              <td style="padding: 6px 0; color: #fff;">${formattedDate}</td>
            </tr>
            <tr>
              <td style="padding: 6px 0; color: #32BBED;">APPOINTMENT TIME:</td>
              <td style="padding: 6px 0; color: #fff; font-weight: bold;">${booking.time}</td>
            </tr>
            ${booking.notes ? `
            <tr>
              <td style="padding: 6px 0; color: #32BBED; vertical-align: top;">SPECIAL NOTES:</td>
              <td style="padding: 6px 0; color: #999; font-style: italic;">"${booking.notes}"</td>
            </tr>` : ''}
          </table>
        </div>
        <p style="color: #888; font-size: 11px; line-height: 1.6; text-align: center; margin: 0;">
          Please present this email at the desk upon arrival. If you need to reschedule or cancel, please contact the branch directly.<br/>
          Thank you for choosing Adonis.
        </p>
      </div>
    `
  };

  const adminMailOptions = {
    from: `"${smtp.fromEmail ? smtp.fromEmail.split('@')[0] : 'Adonis Grooming'}" <${smtp.fromEmail || smtp.user}>`,
    to: smtp.adminEmails || smtp.user,
    subject: `[New Booking] ${booking.bookingCode} - ${booking.clientName}`,
    html: `
      <div style="background-color: #121212; color: #ffffff; font-family: Arial, sans-serif; padding: 30px; border-left: 4px solid #32BBED; max-width: 600px; margin: 0 auto;">
        <h3 style="color: #32BBED; margin-top: 0; margin-bottom: 20px; font-weight: normal; letter-spacing: 1px;">NEW BOOKING SECURED</h3>
        <p style="font-size: 13px; color: #ccc;">A new appointment has been scheduled through the Adonis web portal. Details:</p>
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; color: #ccc;">
          <tr style="border-bottom: 1px solid #222;">
            <td style="padding: 8px 0; color: #888; width: 40%;">Client Name:</td>
            <td style="padding: 8px 0; font-weight: bold; color: #fff;">${booking.clientName}</td>
          </tr>
          <tr style="border-bottom: 1px solid #222;">
            <td style="padding: 8px 0; color: #888;">Client Phone:</td>
            <td style="padding: 8px 0; color: #fff;">${booking.clientPhone}</td>
          </tr>
          <tr style="border-bottom: 1px solid #222;">
            <td style="padding: 8px 0; color: #888;">Client Email:</td>
            <td style="padding: 8px 0; color: #fff;">${booking.clientEmail}</td>
          </tr>
          <tr style="border-bottom: 1px solid #222;">
            <td style="padding: 8px 0; color: #888;">Booking Code:</td>
            <td style="padding: 8px 0; font-weight: bold; color: #32BBED;">${booking.bookingCode}</td>
          </tr>
          <tr style="border-bottom: 1px solid #222;">
            <td style="padding: 8px 0; color: #888;">Lounge Branch:</td>
            <td style="padding: 8px 0; color: #fff;">${branchName}</td>
          </tr>
          <tr style="border-bottom: 1px solid #222;">
            <td style="padding: 8px 0; color: #888;">Service:</td>
            <td style="padding: 8px 0; color: #fff;">${serviceName}</td>
          </tr>
          <tr style="border-bottom: 1px solid #222;">
            <td style="padding: 8px 0; color: #888;">Master Stylist:</td>
            <td style="padding: 8px 0; color: #fff;">${barberName}</td>
          </tr>
          <tr style="border-bottom: 1px solid #222;">
            <td style="padding: 8px 0; color: #888;">Date:</td>
            <td style="padding: 8px 0; color: #fff;">${formattedDate}</td>
          </tr>
          <tr style="border-bottom: 1px solid #222;">
            <td style="padding: 8px 0; color: #888;">Time:</td>
            <td style="padding: 8px 0; font-weight: bold; color: #32BBED;">${booking.time}</td>
          </tr>
          ${booking.notes ? `
          <tr style="border-bottom: 1px solid #222;">
            <td style="padding: 8px 0; color: #888; vertical-align: top;">Special Instructions:</td>
            <td style="padding: 8px 0; font-style: italic;">"${booking.notes}"</td>
          </tr>` : ''}
        </table>
        <p style="font-size: 11px; color: #666; margin-top: 25px; margin-bottom: 0;">
          This record has been logged in the local client database. Log in to the administrator portal to review.
        </p>
      </div>
    `
  };

  let clientSent = false;
  let adminSent = false;

  if (booking.clientEmail) {
    try {
      await transporter.sendMail(clientMailOptions);
      clientSent = true;
      console.log("Confirmation email dispatched to client successfully.");
    } catch (err) {
      console.error("Client email send failure:", err);
    }
  }

  try {
    await transporter.sendMail(adminMailOptions);
    adminSent = true;
    console.log("Notification email dispatched to admins successfully.");
  } catch (err) {
    console.error("Admin notification email send failure:", err);
  }

  if (!clientSent && !adminSent) {
    throw new Error('Both client and admin emails failed to send.');
  }
}

// REST API Endpoints

// GET complete database
app.get('/api/data', asyncRoute(async (req, res) => {
  const db = await readDb();
  // Safe SMTP data response (omit passphrase)
  const safeSmtp = { ...db.smtp, pass: db.smtp.pass ? '********' : '' };
  res.json({
    services: db.services,
    barbers: db.barbers,
    settings: db.settings,
    smtp: safeSmtp,
    blogs: db.blogs,
    bookings: db.bookings
  });
}));

// Update Site Settings
app.put('/api/settings', asyncRoute(async (req, res) => {
  const db = await readDb();
  db.settings = { ...db.settings, ...req.body };
  await writeDb(db);
  res.json({ success: true, settings: db.settings });
}));

// Update SMTP Settings
app.put('/api/smtp', asyncRoute(async (req, res) => {
  const db = await readDb();
  const newSmtp = req.body;

  // If password is sent as placeholder, keep current password
  if (newSmtp.pass === '********') {
    newSmtp.pass = db.smtp.pass;
  }

  db.smtp = { ...db.smtp, ...newSmtp };
  await writeDb(db);
  const safeSmtp = { ...db.smtp, pass: db.smtp.pass ? '********' : '' };
  res.json({ success: true, smtp: safeSmtp });
}));

// Services CRUD
app.post('/api/services', asyncRoute(async (req, res) => {
  const db = await readDb();
  const newService = req.body;
  if (!newService.id) {
    newService.id = newService.name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
  }
  db.services.push(newService);
  await writeDb(db);
  res.json({ success: true, service: newService });
}));

app.put('/api/services/:id', asyncRoute(async (req, res) => {
  const db = await readDb();
  const serviceId = req.params.id;
  const index = db.services.findIndex((s: any) => s.id === serviceId);
  if (index !== -1) {
    db.services[index] = { ...db.services[index], ...req.body };
    await writeDb(db);
    res.json({ success: true, service: db.services[index] });
  } else {
    res.status(404).json({ error: 'Service not found' });
  }
}));

app.delete('/api/services/:id', asyncRoute(async (req, res) => {
  const db = await readDb();
  const serviceId = req.params.id;
  db.services = db.services.filter((s: any) => s.id !== serviceId);
  await writeDb(db);
  res.json({ success: true });
}));

// Barbers CRUD
app.post('/api/barbers', asyncRoute(async (req, res) => {
  const db = await readDb();
  const newBarber = req.body;
  if (!newBarber.id) {
    newBarber.id = newBarber.name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
  }
  db.barbers.push(newBarber);
  await writeDb(db);
  res.json({ success: true, barber: newBarber });
}));

app.put('/api/barbers/:id', asyncRoute(async (req, res) => {
  const db = await readDb();
  const barberId = req.params.id;
  const index = db.barbers.findIndex((b: any) => b.id === barberId);
  if (index !== -1) {
    db.barbers[index] = { ...db.barbers[index], ...req.body };
    await writeDb(db);
    res.json({ success: true, barber: db.barbers[index] });
  } else {
    res.status(444).json({ error: 'Barber not found' });
  }
}));

app.delete('/api/barbers/:id', asyncRoute(async (req, res) => {
  const db = await readDb();
  const barberId = req.params.id;
  db.barbers = db.barbers.filter((b: any) => b.id !== barberId);
  await writeDb(db);
  res.json({ success: true });
}));

// Blogs CRUD
app.get('/api/blogs', asyncRoute(async (req, res) => {
  const db = await readDb();
  res.json(db.blogs);
}));

app.post('/api/blogs', asyncRoute(async (req, res) => {
  const db = await readDb();
  const now = new Date().toISOString();
  const newBlog = {
    ...req.body,
    id: req.body.id || req.body.slug || req.body.title?.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, ''),
    slug: req.body.slug || req.body.title?.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, ''),
    createdAt: req.body.createdAt || now,
    updatedAt: now
  };
  db.blogs.push(newBlog);
  await writeDb(db);
  res.json({ success: true, blog: newBlog });
}));

app.put('/api/blogs/:id', asyncRoute(async (req, res) => {
  const db = await readDb();
  const blogId = req.params.id;
  const index = db.blogs.findIndex((blog: any) => blog.id === blogId);
  if (index !== -1) {
    db.blogs[index] = { ...db.blogs[index], ...req.body, updatedAt: new Date().toISOString() };
    await writeDb(db);
    res.json({ success: true, blog: db.blogs[index] });
  } else {
    res.status(404).json({ success: false, error: 'Blog post not found' });
  }
}));

app.delete('/api/blogs/:id', asyncRoute(async (req, res) => {
  const db = await readDb();
  const blogId = req.params.id;
  db.blogs = db.blogs.filter((blog: any) => blog.id !== blogId);
  await writeDb(db);
  res.json({ success: true });
}));

// Image Upload
app.post('/api/upload', upload.single('portrait'), (req, res) => {
  if (req.file) {
    // Return relative URL for front-end access
    const fileUrl = `/uploads/${req.file.filename}`;
    res.json({ success: true, url: fileUrl });
  } else {
    res.status(400).json({ error: 'Upload failed' });
  }
});

// Bookings Endpoints
app.post('/api/bookings', asyncRoute(async (req, res) => {
  const booking = req.body;

  if (!booking.clientName || !booking.clientPhone || !booking.branchId || !booking.date || !booking.time || !booking.bookingCode) {
    return res.status(400).json({ success: false, error: 'Missing required booking details.' });
  }

  // Fetch names for email
  const service: any = INITIAL_DATABASE.services.find((s: any) => s.id === booking.serviceId) || { name: booking.serviceId || 'General Appointment' };
  const barber: any = INITIAL_DATABASE.barbers.find((b: any) => b.id === booking.barberId) || { name: booking.barberId || 'Not selected' };

  const branches = [
    { id: 'gulshan', name: 'Gulshan Premium Lounge', hours: '10AM to 7PM' },
    { id: 'bashundhara', name: 'Bashundhara Premium Lounge', hours: '10:00 AM – 10:00 PM' }
  ];
  const branch = branches.find(b => b.id === booking.branchId) || { name: booking.branchId };
  if (booking.branchId === 'gulshan' && ['08:00 PM', '09:00 PM'].includes(booking.time)) {
    return res.status(400).json({ success: false, error: 'Gulshan working hours are 10AM to 7PM.' });
  }

  if (!CODE_SMTP.host || !CODE_SMTP.user || !CODE_SMTP.pass) {
    return res.status(500).json({ success: false, error: 'Site SMTP is not configured.' });
  }

  try {
    await sendBookingEmails(booking, service.name, barber.name, branch.name, CODE_SMTP);
    res.json({ success: true, booking, emailSent: true });
  } catch (err: any) {
    console.error("Booking email send failure:", err);
    res.status(502).json({ success: false, error: err.message || 'Failed to send booking notification.' });
  }
}));

app.get('/api/bookings', asyncRoute(async (req, res) => {
  const db = await readDb();
  res.json(db.bookings);
}));

// SMTP Test Endpoint
app.get('/api/smtp/status', (req, res) => {
  res.json({
    success: true,
    configured: Boolean(CODE_SMTP.host && CODE_SMTP.user && CODE_SMTP.pass),
    host: CODE_SMTP.host,
    port: CODE_SMTP.port,
    secure: CODE_SMTP.secure,
    user: CODE_SMTP.user,
    fromEmail: CODE_SMTP.fromEmail,
    adminEmails: CODE_SMTP.adminEmails,
    hasPassword: Boolean(CODE_SMTP.pass)
  });
});

app.post('/api/smtp/test', asyncRoute(async (req, res) => {
  const testSmtp = { ...CODE_SMTP, ...req.body };

  if (!testSmtp.host || !testSmtp.user || !testSmtp.pass) {
    return res.json({ success: false, error: 'SMTP host, username, and password are required.' });
  }

  const smtpPort = typeof testSmtp.port === 'string' ? parseInt(testSmtp.port) : testSmtp.port;
  const testTransporter = nodemailer.createTransport({
    host: testSmtp.host,
    port: smtpPort,
    secure: testSmtp.secure === true || testSmtp.secure === 'true',
    auth: {
      user: testSmtp.user,
      pass: testSmtp.pass
    }
  });

  try {
    await testTransporter.verify();
    res.json({ success: true, message: 'SMTP connection successful! Server is ready to send emails.' });
  } catch (err: any) {
    console.error("SMTP Test Failure:", err);
    res.json({ success: false, error: err.message || 'SMTP connection failed. Check your settings.' });
  }
}));

app.use('/api', (err: any, req: express.Request, res: express.Response, next: express.NextFunction) => {
  console.error('API failure:', err);
  res.status(500).json({
    success: false,
    error: err?.message || 'CMS API error.'
  });
});

// Serve uploaded images statically
app.use('/uploads', express.static(UPLOADS_DIR));

// Serve static assets from multiple possible locations
const STATIC_DIRS = [
  path.join(__dirname, 'dist'),
  path.join(__dirname, 'public'),
  process.cwd(),
  path.join(process.cwd(), 'dist'),
  path.join(process.cwd(), 'public'),
];
for (const dir of STATIC_DIRS) {
  if (fs.existsSync(dir)) {
    app.use(express.static(dir));
  }
}

// SPA fallback - serve index.html only for non-API navigational routes
app.get('*', (req, res) => {
  if (req.path.startsWith('/api') || req.path.startsWith('/uploads')) return;
  // Only serve index.html for paths without file extensions (real page routes)
  if (!req.path.match(/\.\w+$/)) {
    for (const dir of ['dist', 'public', '']) {
      const base = path.join(__dirname, dir);
      const indexHtml = path.join(base, 'index.html');
      if (fs.existsSync(indexHtml)) {
        return res.sendFile(indexHtml);
      }
    }
    res.status(404).send('Not found');
  }
});

// Start server
app.listen(PORT, '0.0.0.0', () => {
  console.log(`Adonis API server running on http://localhost:${PORT}`);
});

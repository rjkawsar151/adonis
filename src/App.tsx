import React, { useState, useEffect, useRef } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { Header } from './components/Header';
import { BookingForm } from './components/BookingForm';
import { StyleProfiler } from './components/StyleProfiler';
import { LucideIcon } from './components/LucideIcon';
import { ServicesPage } from './components/ServicesPage';
import { AboutUsPage } from './components/AboutUsPage';
import { BarberCarousel } from './components/BarberCarousel';
import { AdminPanel } from './components/AdminPanel';
import { BlogPage } from './components/BlogPage';
import { navigateTo } from './navigation';
import {
  SERVICES as DEFAULT_SERVICES,
  BRANCHES,
  BARBERS as DEFAULT_BARBERS,
  TESTIMONIALS,
  VIP_PRIVILEGES,
  IMAGES,
  OPEN_HOURS,
  CONTACT_INFO,
  BLOG_POSTS as DEFAULT_BLOG_POSTS
} from './data';
import { Service, Barber, Booking, SiteSettings, SmtpSettings, BlogPost } from './types';

const DEFAULT_SETTINGS: SiteSettings = {
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
};

const DEFAULT_SMTP: SmtpSettings = {
  host: '',
  port: 587,
  secure: false,
  user: '',
  pass: '',
  fromEmail: 'noreply@adonis.com.bd',
  adminEmails: 'admin@adonis.com.bd'
};

export default function App() {
  // SPA routing state
  const [currentPath, setCurrentPath] = useState<string>(window.location.pathname);

  // Dynamic database states loaded from server API
  const [services, setServices] = useState<Service[]>(DEFAULT_SERVICES);
  const [barbers, setBarbers] = useState<Barber[]>(DEFAULT_BARBERS);
  const [settings, setSettings] = useState<SiteSettings>(DEFAULT_SETTINGS);
  const [smtp, setSmtp] = useState<SmtpSettings>(DEFAULT_SMTP);
  const [bookings, setBookings] = useState<Booking[]>([]);
  const [blogs, setBlogs] = useState<BlogPost[]>(DEFAULT_BLOG_POSTS);
  const [loading, setLoading] = useState<boolean>(true);

  // Service selection bridge state
  const [selectedServiceId, setSelectedServiceId] = useState<string>('');
  const [selectedBookingBranchId, setSelectedBookingBranchId] = useState<'gulshan' | 'bashundhara'>('gulshan');

  // Barber selection bridge state
  const [selectedBarberId, setSelectedBarberId] = useState<string>('');

  // Lightbox modal state
  const [lightbox, setLightbox] = useState<{ isOpen: boolean; imgUrl: string; title: string, subtitle: string } | null>(null);

  // VIP Join state
  const [vipModalOpen, setVipModalOpen] = useState(false);
  const [vipFormSubmitted, setVipFormSubmitted] = useState(false);
  const [vipName, setVipName] = useState('');
  const [vipPhone, setVipPhone] = useState('');

  // Active Map view state (Gulshan vs Bashundhara embeds)
  const [activeMapId, setActiveMapId] = useState<'gulshan' | 'bashundhara'>('gulshan');

  // Master Barber detail modal state
  const [selectedBarberDetail, setSelectedBarberDetail] = useState<Barber | null>(null);

  // Amenity carousel state
  const [amenityIndex, setAmenityIndex] = useState(0);
  const [amenityDirection, setAmenityDirection] = useState(1);

  // Featured services horizontal scroll ref
  const featuredScrollRef = useRef<HTMLDivElement>(null);
  const [featuredHover, setFeaturedHover] = useState(false);
  const scrollFeatured = (dir: 'left' | 'right') => {
    featuredScrollRef.current?.scrollBy({ left: dir === 'right' ? 330 : -330, behavior: 'smooth' });
  };

  // Full-bleed parallax backgrounds
  const servicesBgRef = useRef<HTMLDivElement>(null);
  const vipBgRef = useRef<HTMLDivElement>(null);
  useEffect(() => {
    const handleScroll = () => {
      [
        { el: servicesBgRef.current, depth: 34 },
        { el: vipBgRef.current, depth: 26 }
      ].forEach(({ el, depth }) => {
        if (!el) return;
        const rect = el.parentElement?.getBoundingClientRect();
        if (!rect) return;
        const progress = 1 - ((rect.top + rect.height) / (window.innerHeight + rect.height));
        const y = (progress - 0.5) * depth;
        el.style.transform = `translate3d(-50%, ${y}px, 0) scale(1.08)`;
      });
    };
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Infinite auto-scroll for featured services
  useEffect(() => {
    const el = featuredScrollRef.current;
    if (!el) return;
    const interval = setInterval(() => {
      if (featuredHover) return;
      const maxScroll = el.scrollWidth - el.clientWidth;
      if (el.scrollLeft >= maxScroll - 10) {
        el.scrollTo({ left: 0, behavior: 'smooth' });
      } else {
        el.scrollBy({ left: 330, behavior: 'smooth' });
      }
    }, 2200);
    return () => clearInterval(interval);
  }, [featuredHover]);

  const AMENITIES = [
    { title: 'Executive Lounge', desc: 'Leather chesterfield sofas, ambient gold lighting, and complimentary barista coffee.', img: '/assets/images/executive.png' },
    { title: 'Reception', desc: 'Premium reception area with a luxurious waiting lounge, warm lighting, elegant seating, and a welcoming ambiance for every Adonis guest.', img: '/assets/images/reception.png' },
    { title: 'Massage Room', desc: 'Private therapy suites with aromatherapy diffusers and heated beds for deep tissue relief.', img: '/assets/images/massage.png' },
    { title: 'Sauna & Steam', desc: 'Finnish dry sauna and Turkish-style steam chambers for deep pore detoxification and muscular unwind.', img: '/assets/images/steam.png' },
    { title: 'Jacuzzi Bath', desc: 'Hydrotherapy jacuzzi with chromotherapy lighting, water jets, and essential oil infusion for the ultimate soak.', img: '/assets/images/sauana.png' },
    { title: 'VIP Private Room', desc: 'Soundproofed suite with personal butler service, entertainment system, and dedicated master barber.', img: '/assets/images/vip.png' }
  ];

  // Fetch data from Express backend
  const fetchDbData = async () => {
    try {
      const res = await fetch('/api/data');
      if (!res.ok) throw new Error('API server unreachable');
      const data = await res.json();
      if (data) {
        if (data.services && data.services.length > 0) setServices(data.services);
        if (data.barbers && data.barbers.length > 0) setBarbers(data.barbers);
        if (data.settings) setSettings(data.settings);
        if (data.smtp) setSmtp(data.smtp);
        if (data.bookings) setBookings(data.bookings);
        if (data.blogs && data.blogs.length > 0) setBlogs(data.blogs);
      }
    } catch (err) {
      console.warn("Backend API not running. Working in standalone frontend client mode with fallback mock parameters.");
      try {
        const savedBookings = JSON.parse(localStorage.getItem('adonisBookings') || '[]');
        if (Array.isArray(savedBookings)) {
          setBookings(savedBookings);
        }
      } catch (storageErr) {
        console.warn("Local booking storage could not be loaded.", storageErr);
      }
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDbData();

    // Listen to custom routing events
    const handleNavigation = (e: Event) => {
      const customEvent = e as CustomEvent<string>;
      setCurrentPath(customEvent.detail);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    };
    window.addEventListener('adonis-navigate', handleNavigation);

    // Listen to popstate event
    const handlePopState = () => {
      setCurrentPath(window.location.pathname);
    };
    window.addEventListener('popstate', handlePopState);

    return () => {
      window.removeEventListener('adonis-navigate', handleNavigation);
      window.removeEventListener('popstate', handlePopState);
    };
  }, []);

  // Auto-cycle amenity carousel
  useEffect(() => {
    const interval = setInterval(() => {
      setAmenityDirection(1);
      setAmenityIndex(prev => (prev + 1) % AMENITIES.length);
    }, 5000);
    return () => clearInterval(interval);
  }, []);

  const handleBookingBridge = (serviceId: string, branchId: 'gulshan' | 'bashundhara' = 'gulshan') => {
    setSelectedServiceId(serviceId);
    setSelectedBookingBranchId(branchId);
    setSelectedBarberId(''); // Reset barber if service is chosen directly
    const element = document.getElementById('booking-section');
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  };

  const handleBarberBookingBridge = (barberId: string) => {
    setSelectedBarberId(barberId);
    setSelectedServiceId(''); // Reset service if barber is chosen directly
    setSelectedBookingBranchId('gulshan');
    const element = document.getElementById('booking-section');
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  };

  const handleVipSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (vipName.trim() && vipPhone.trim()) {
      setVipFormSubmitted(true);
    }
  };

  const closeVipModal = () => {
    setVipModalOpen(false);
    setVipFormSubmitted(false);
    setVipName('');
    setVipPhone('');
  };

  // Show all services in the horizontal scroll section
  const featuredServices = services;

  const renderFooter = () => {
    const phones = settings.phoneNumbers || CONTACT_INFO.phoneNumbers || [];
    const gulshanBranch = BRANCHES.find(branch => branch.id === 'gulshan');
    const bashundharaBranch = BRANCHES.find(branch => branch.id === 'bashundhara');
    return (
      <footer className="bg-black py-16 text-center border-t border-gold-400/20 text-xs mt-auto">
        <div className="max-w-7xl mx-auto px-4 md:px-8 space-y-10">

          <div className="flex flex-col md:flex-row items-center justify-between gap-6 border-b border-white/5 pb-8">
            <div className="text-left">
              <div className="mb-2">
                <img
                  src={IMAGES.logo}
                  alt="Adonis Men's Grooming"
                  referrerPolicy="no-referrer"
                  className="h-12 md:h-14 w-auto max-w-[220px] object-contain"
                />
              </div>
              <p className="text-[10px] text-gray-500 uppercase tracking-widest">
                {settings.brandSubtitle}
              </p>
            </div>

            {/* Timings */}
            <div className="bg-salon-gray/45 border border-white/5 px-6 py-3 text-center md:text-right space-y-1.5">
              <span className="text-[9px] font-mono text-gold-400 uppercase tracking-widest block font-bold">LOBBY OPERATION HOURS</span>
              <p className="text-white font-serif tracking-wider text-xs uppercase">{settings.openHoursDays}</p>
              <p className="text-gray-400 font-mono text-[11px]">{settings.openHoursTime}</p>
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 text-left text-xs uppercase tracking-widest pb-6 border-b border-white/5">
            <div>
              <h5 className="font-serif text-white text-xs tracking-wider mb-4 border-b border-gold-400/20 pb-1.5">Gulshan Salon</h5>
              <p className="text-[11px] text-gray-500 leading-relaxed font-sans normal-case mb-2">
                Rupayan Golden Age (2nd Floor), Plot 99, Road 37, Block CWN (C), Gulshan Avenue, Dhaka 1212.
              </p>
              <p className="text-[10px] text-gray-400 font-mono mb-2">Hours: {gulshanBranch?.hours || settings.openHoursTime}</p>
              <p className="text-[11px] text-gold-400 font-mono">{phones[0] || '+880 1919-700800'}</p>
            </div>
            <div>
              <h5 className="font-serif text-white text-xs tracking-wider mb-4 border-b border-gold-400/20 pb-1.5">Bashundhara Salon</h5>
              <p className="text-[11px] text-gray-500 leading-relaxed font-sans normal-case mb-2">
                Rahman Tower (Lift-4), Ka-1/B, Jagannathpur, Beside Hardco International School, Bashundhara.
              </p>
              <p className="text-[10px] text-gray-400 font-mono mb-2">Hours: {bashundharaBranch?.hours || settings.openHoursTime}</p>
              <p className="text-[11px] text-gold-400 font-mono">{phones[1] || phones[0] || '+880 1720-080091'}</p>
            </div>
            <div>
              <h5 className="font-serif text-white text-xs tracking-wider mb-4 border-b border-gold-400/20 pb-1.5">Navigation</h5>
              <ul className="space-y-2 text-[10px] text-gray-400">
                <li><a href="/" onClick={(e) => { e.preventDefault(); navigateTo('/'); }} className="hover:text-gold-400 transition-colors">Lounge</a></li>
                <li><a href="/services/gulshan" onClick={(e) => { e.preventDefault(); navigateTo('/services/gulshan'); }} className="hover:text-gold-400 transition-colors">Gulshan Services</a></li>
                <li><a href="/services/bashundhara" onClick={(e) => { e.preventDefault(); navigateTo('/services/bashundhara'); }} className="hover:text-gold-400 transition-colors">Bashundhara Services</a></li>
                <li><a href="/about" onClick={(e) => { e.preventDefault(); navigateTo('/about'); }} className="hover:text-gold-400 transition-colors">About Us</a></li>
              </ul>
            </div>
            <div>
              <h5 className="font-serif text-white text-xs tracking-wider mb-4 border-b border-gold-400/20 pb-1.5">Social Portals</h5>
              <div className="flex gap-4 items-center">
                {settings.facebookUrl && (
                  <a
                    href={settings.facebookUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="p-2 bg-salon-gray hover:bg-gold-400 hover:text-salon-black text-gold-400 transition-all"
                    aria-label="Facebook Link"
                  >
                    <LucideIcon name="Facebook" size={14} />
                  </a>
                )}
                {settings.instagramUrl && (
                  <a
                    href={settings.instagramUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="p-2 bg-salon-gray hover:bg-gold-400 hover:text-salon-black text-gold-400 transition-all"
                    aria-label="Instagram Link"
                  >
                    <LucideIcon name="Instagram" size={14} />
                  </a>
                )}
                {settings.whatsappUrl && (
                  <a
                    href={settings.whatsappUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="p-2 bg-salon-gray hover:bg-gold-400 hover:text-salon-black text-gold-400 transition-all"
                    aria-label="WhatsApp Link"
                  >
                    <LucideIcon name="MessageSquare" size={14} />
                  </a>
                )}
              </div>
            </div>
          </div>

          <div className="flex flex-col sm:flex-row items-center justify-between text-[10px] text-gray-500 tracking-widest mt-6">
            <p>© {new Date().getFullYear()} ADONIS MEN’S GROOMING. ALL RIGHTS PRIVILEGED.</p>
            <p className="mt-2 sm:mt-0 uppercase">Crafted for Elite Dignity • Dhaka, Bangladesh</p>
          </div>

        </div>
      </footer>
    );
  };

  // 1. SERVICES PAGE ROUTE
  if (currentPath === '/services' || currentPath === '/services/gulshan' || currentPath === '/services/bashundhara') {
    const serviceBranch = currentPath === '/services/bashundhara' ? 'bashundhara' : 'gulshan';
    return (
      <div className="bg-salon-black text-white selection:bg-gold-400 selection:text-salon-black min-h-screen relative font-sans leading-relaxed flex flex-col">
        <Header />
        <ServicesPage
          initialBranch={serviceBranch}
          onBookService={(serviceId, branchId) => {
            setSelectedServiceId(serviceId);
            setSelectedBookingBranchId(branchId);
            setSelectedBarberId('');
            navigateTo('/');
            setTimeout(() => {
              document.getElementById('booking-section')?.scrollIntoView({ behavior: 'smooth' });
            }, 250);
          }}
        />
        {renderFooter()}
      </div>
    );
  }

  // 2. ABOUT US PAGE ROUTE
  if (currentPath === '/about') {
    return (
      <div className="bg-salon-black text-white selection:bg-gold-400 selection:text-salon-black min-h-screen relative font-sans leading-relaxed flex flex-col">
        <Header />
        <AboutUsPage
          barbers={barbers}
          brandStory={settings.aboutStory}
          brandDescription={settings.aboutDescription}
          onBookBarber={(barberId) => {
            setSelectedBarberId(barberId);
            setSelectedServiceId('');
            navigateTo('/');
            setTimeout(() => {
              document.getElementById('booking-section')?.scrollIntoView({ behavior: 'smooth' });
            }, 250);
          }}
        />
        {renderFooter()}
      </div>
    );
  }

  if (currentPath === '/blog' || currentPath.startsWith('/blog/')) {
    const slug = currentPath.startsWith('/blog/') ? currentPath.replace('/blog/', '') : undefined;
    return (
      <div className="bg-salon-black text-white selection:bg-gold-400 selection:text-salon-black min-h-screen relative font-sans leading-relaxed flex flex-col">
        <Header />
        <BlogPage posts={blogs} slug={slug} />
        {renderFooter()}
      </div>
    );
  }

  // 3. HIDDEN CMS LOGIN ROUTE
  if (currentPath === '/login') {
    return (
      <div className="bg-salon-black text-white selection:bg-gold-400 selection:text-salon-black min-h-screen relative font-sans leading-relaxed flex flex-col">
        <AdminPanel
          services={services}
          barbers={barbers}
          settings={settings}
          smtp={smtp}
          bookings={bookings}
          blogs={blogs}
          onRefreshData={fetchDbData}
        />
      </div>
    );
  }

  // 4. LANDING PAGE ROUTE (Default `/`)
  return (
    <div className="bg-salon-black text-white selection:bg-gold-400 selection:text-salon-black min-h-screen relative font-sans leading-relaxed flex flex-col">
      {/* Absolute background accent lights */}
      <div className="absolute top-[20%] left-[-10%] h-[500px] w-[500px] rounded-full bg-gold-400/5 blur-[120px] pointer-events-none"></div>
      <div className="absolute top-[55%] right-[-10%] h-[500px] w-[500px] rounded-full bg-gold-400/5 blur-[120px] pointer-events-none"></div>

      <Header />

      {/* HERO SECTION - Background transparency increased and set to 50% opacity */}
      <section
        id="hero-section"
        className="relative h-screen flex items-center justify-center overflow-hidden bg-black"
      >
        {/* Parallax Cover Image - opacity updated to 75% */}
        <div className="absolute inset-0 z-0">
          <img
            src={settings.heroBg}
            alt="Adonis Luxury Barbershop Interior"
            referrerPolicy="no-referrer"
            className="w-full h-full object-cover scale-105 opacity-90 grayscale-[10%] brightness-[65%] contrast-[110%]"
          />
          {/* Moody Overlays creating cinematic gold depth contrast */}
          <div className="absolute inset-0 bg-gradient-to-t from-salon-black via-salon-black/45 to-transparent"></div>
          <div className="absolute inset-0 bg-gradient-to-r from-salon-black via-transparent to-salon-black/30"></div>
          <div className="absolute inset-0 bg-gradient-to-b from-salon-black/60 via-transparent to-transparent"></div>
        </div>

        {/* Spotlights */}
        <div className="absolute top-0 left-1/4 w-[1px] h-full bg-gradient-to-b from-transparent via-gold-400/10 to-transparent pointer-events-none"></div>
        <div className="absolute top-0 left-3/4 w-[1px] h-full bg-gradient-to-b from-transparent via-gold-400/10 to-transparent pointer-events-none"></div>

        {/* Hero Content */}
        <div className="relative z-10 max-w-5xl mx-auto px-4 text-center space-y-8 mt-16">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 1 }}
            className="space-y-4"
          >
            <span className="text-[10px] md:text-xs font-mono tracking-[0.4em] text-gold-400 uppercase font-semibold block gold-glow">
              Dhaka's Ultimate Elite Men's Lounge
            </span>
            <h1 className="font-serif text-3xl sm:text-5xl md:text-7xl leading-[1.1] text-[#fcfcfc] select-none italic font-normal">
              {settings.heroTitle.split(' ').slice(0, 3).join(' ')} <br />
              <span className="text-[#32BBED] not-italic font-normal uppercase tracking-widest block mt-2">
                {settings.heroTitle.split(' ').slice(3).join(' ') || 'With Precision'}
              </span>
            </h1>
            <p className="max-w-2xl mx-auto text-xs sm:text-sm md:text-base text-gray-400 font-sans tracking-wide leading-relaxed font-light">
              {settings.heroSubtitle}
            </p>
          </motion.div>

          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ delay: 0.6, duration: 1 }}
            className="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4"
          >
            <a
              href="#booking-section"
              onClick={(e) => {
                e.preventDefault();
                document.getElementById('booking-section')?.scrollIntoView({ behavior: 'smooth' });
              }}
              className="w-full sm:w-auto px-10 py-4 bg-[#32BBED] text-black hover:bg-[#b08d3c] font-serif text-xs font-bold uppercase tracking-widest transition-all duration-300 text-center cursor-pointer shadow-[0_4px_25px_rgba(200,162,74,0.2)]"
            >
              Book Appointment
            </a>
            <a
              href="/services/gulshan"
              onClick={(e) => {
                e.preventDefault();
                navigateTo('/services/gulshan');
              }}
              className="w-full sm:w-auto px-10 py-4 border border-[#32BBED] text-[#32BBED] hover:bg-[#32BBED] hover:text-black font-serif text-xs font-semibold uppercase tracking-widest transition-all duration-300 text-center cursor-pointer"
            >
              View Services
            </a>
          </motion.div>

          {/* Quick Stats */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ delay: 1, duration: 1 }}
            className="pt-12 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto border-t border-white/5 text-center text-xs font-mono tracking-widest text-gray-400 uppercase"
          >
            <div>
              <span className="text-gold-400 block font-serif text-lg font-bold mb-0.5">2</span>
              <span>Luxe Branches</span>
            </div>
            <div>
              <span className="text-gold-400 block font-serif text-lg font-bold mb-0.5">100+</span>
              <span>Master Barbers</span>
            </div>
            <div>
              <span className="text-gold-400 block font-serif text-lg font-bold mb-0.5">15k+</span>
              <span>Styled Men</span>
            </div>
            <div>
              <span className="text-gold-400 block font-serif text-lg font-bold mb-0.5">Open</span>
              <span>{settings.openHoursTime.split('–')[0] || '10 AM'} - {settings.openHoursTime.split('–')[1] || '7 PM'}</span>
            </div>
          </motion.div>
        </div>

        {/* Bottom wave fade */}
        <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-salon-black to-transparent pointer-events-none"></div>
      </section>

      {/* FEATURED SERVICES & PACKAGES ONLY */}
      <section id="services-section" className="py-20 md:py-24 bg-salon-black relative overflow-hidden border-b border-gold-400/10 text-center">
        <div
          ref={servicesBgRef}
          className="absolute inset-y-[-6%] left-1/2 w-[100vw] bg-cover bg-center bg-no-repeat opacity-[0.34] pointer-events-none"
          style={{
            backgroundImage: `url('/assets/images/adonis_styling_chairs_1779270725139.png')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            backgroundRepeat: 'no-repeat',
            transform: 'translate3d(-50%, 0, 0) scale(1.08)',
            willChange: 'transform'
          }}
        />
        <div className="absolute inset-0 w-full bg-gradient-to-b from-salon-black via-salon-black/82 to-salon-black pointer-events-none"></div>
        <div className="absolute inset-0 w-full bg-gradient-to-r from-salon-black/70 via-transparent to-salon-black/70 pointer-events-none"></div>
        <div className="relative z-10 max-w-7xl mx-auto px-4 md:px-8 space-y-16">
          <motion.div
            initial={{ opacity: 0, y: 28 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.35 }}
            transition={{ duration: 0.9, ease: [0.22, 1, 0.36, 1] }}
            className="text-center max-w-2xl mx-auto space-y-3"
          >
            <span className="text-[10px] font-mono tracking-[0.3em] text-gold-400 uppercase block">
              Adonis Featured Selection
            </span>
            <h2 className="font-serif text-3xl sm:text-4xl uppercase tracking-wider text-white">
              Signature Service & Packages
            </h2>
            <p className="text-xs text-gray-400 max-w-md mx-auto">
              Select one of our luxury signature treatments or combined VIP packages designed for ultimate executive care.
            </p>
          </motion.div>

          {/* Horizontal Scrolling Services Carousel */}
          <div className="relative w-full">
            {/* Left Arrow */}
            <button
              onClick={() => scrollFeatured('left')}
              className="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-5 z-10 h-10 w-10 border border-gold-400/30 bg-salon-black/95 text-gold-400 hover:bg-gold-400 hover:text-salon-black transition-all duration-300 items-center justify-center hidden md:flex shadow-lg cursor-pointer"
              aria-label="Scroll left"
            >
              <LucideIcon name="ChevronLeft" size={18} />
            </button>

            {/* Scroll Track */}
            <div
              ref={featuredScrollRef}
              onMouseEnter={() => setFeaturedHover(true)}
              onMouseLeave={() => setFeaturedHover(false)}
              className="flex gap-5 overflow-x-auto pb-3 px-0.5 snap-x snap-mandatory"
              style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}
            >
              {featuredServices.map((service, idx) => (
                <motion.div
                  key={service.id}
                  initial={{ opacity: 0, y: 26, scale: 0.96 }}
                  whileInView={{ opacity: 1, y: 0, scale: 1 }}
                  viewport={{ once: true, amount: 0.25 }}
                  transition={{ duration: 0.75, delay: Math.min(idx, 5) * 0.06, ease: [0.22, 1, 0.36, 1] }}
                  className="flex-shrink-0 w-72 bg-[#141414] hover:bg-[#1C1C1C] border-l-2 border-transparent hover:border-[#32BBED] p-6 flex flex-col justify-between transition-colors duration-300 relative group h-[290px] text-left border-y border-r border-white/5 hover:border-y-white/10 hover:border-r-white/10 snap-start"
                >
                  <div className="absolute top-0 left-0 right-0 h-[1.5px] bg-gradient-to-r from-transparent via-gold-400/0 group-hover:via-gold-400/60 to-transparent transition-all duration-500"></div>

                  <div className="space-y-4">
                    <div className="flex items-center justify-between">
                      <div className="p-2.5 bg-salon-gray text-gold-400 border border-gold-400/10 group-hover:border-gold-400/30 group-hover:bg-gold-400/5 transition-all">
                        <LucideIcon name={service.icon} size={20} />
                      </div>
                      <span className="text-[8px] font-mono tracking-widest text-gold-400 uppercase bg-gold-400/10 px-2 py-0.5 border border-gold-400/15">
                        {service.category === 'pack' ? 'Luxe Package' : 'Featured'}
                      </span>
                    </div>

                    <div>
                      <h4 className="font-serif text-sm uppercase tracking-wider text-white font-medium group-hover:text-gold-400 transition-colors line-clamp-1 pb-1">
                        {service.name}
                      </h4>
                      <p className="text-[11px] text-gray-400 line-clamp-3 font-sans leading-relaxed mt-1">
                        {service.description}
                      </p>
                    </div>
                  </div>

                  <div className="border-t border-white/5 pt-4 flex items-center justify-between mt-4">
                    <div>
                      <span className="text-[9px] text-gray-500 uppercase font-mono block">Starting from</span>
                      <span className="text-sm font-serif text-gold-400 font-bold">৳{service.priceBDT}</span>
                    </div>
                    <button
                      onClick={() => handleBookingBridge(service.id)}
                      className="px-4 py-1.5 border border-gold-400/20 text-gold-400 hover:bg-gold-400 hover:text-salon-black text-[10px] font-serif uppercase tracking-widest transition-all duration-300 cursor-pointer"
                    >
                      Book Now
                    </button>
                  </div>
                </motion.div>
              ))}
            </div>

            {/* Right Arrow */}
            <button
              onClick={() => scrollFeatured('right')}
              className="absolute right-0 top-1/2 -translate-y-1/2 translate-x-5 z-10 h-10 w-10 border border-gold-400/30 bg-salon-black/95 text-gold-400 hover:bg-gold-400 hover:text-salon-black transition-all duration-300 items-center justify-center hidden md:flex shadow-lg cursor-pointer"
              aria-label="Scroll right"
            >
              <LucideIcon name="ChevronRight" size={18} />
            </button>
          </div>

          {/* Scroll hint dots */}
          <div className="flex items-center justify-center gap-3 pt-2">
            <div className="h-px flex-1 max-w-16 bg-white/10"></div>
            <span className="text-[9px] font-mono tracking-widest text-gray-600 uppercase">Scroll to explore all services</span>
            <div className="h-px flex-1 max-w-16 bg-white/10"></div>
          </div>

          <div className="relative z-10">
            <motion.div
              initial={{ opacity: 0, y: 18 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true, amount: 0.4 }}
              transition={{ duration: 0.8, ease: [0.22, 1, 0.36, 1] }}
              className="pt-4"
            >
              <button
                onClick={() => navigateTo('/services/gulshan')}
                className="inline-flex items-center gap-2 border border-gold-400/30 text-gold-400 hover:border-gold-400 hover:bg-gold-400/5 px-8 py-3.5 text-xs font-serif uppercase tracking-widest transition-all cursor-pointer font-semibold"
              >
                <LucideIcon name="LayoutGrid" size={14} />
                Explore Complete Services Menu
              </button>
            </motion.div>

            {/* Interactive Styling Profiler */}
            <motion.div
              initial={{ opacity: 0, y: 30, scale: 0.98 }}
              whileInView={{ opacity: 1, y: 0, scale: 1 }}
              viewport={{ once: true, amount: 0.22 }}
              transition={{ duration: 0.95, ease: [0.22, 1, 0.36, 1] }}
              className="pt-8 border-t border-white/5"
            >
              <motion.div
                initial={{ opacity: 0, y: 18 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true, amount: 0.4 }}
                transition={{ duration: 0.85, ease: [0.22, 1, 0.36, 1] }}
                className="text-center max-w-xl mx-auto mb-8 space-y-2"
              >
                <span className="text-[9px] font-mono tracking-widest text-gold-400 uppercase">Anatomical Matching Engine</span>
                <h3 className="font-serif text-lg tracking-wider uppercase text-white">Uncertain of your face shape style?</h3>
                <p className="text-xs text-gray-400">Run our fast 30-second digital analyzer to identify and secure matching hair & beard contours.</p>
              </motion.div>
              <StyleProfiler onSelectRecommendedService={handleBookingBridge} />
            </motion.div>
          </div>
        </div>
      </section>

      {/* LUXURY AMENITIES - Fullscreen Carousel */}
      <section id="amenities-section" className="relative bg-salon-black overflow-hidden">
        {/* Section header pinned above carousel */}
        <div className="absolute top-0 left-0 right-0 z-20 py-8 md:py-14 bg-gradient-to-b from-salon-black via-salon-black/90 to-transparent pointer-events-none">
          <div className="text-center max-w-3xl mx-auto px-4 space-y-2 md:space-y-3">
            <span className="text-[9px] md:text-[10px] font-mono tracking-[0.3em] md:tracking-[0.4em] text-gold-400 uppercase font-semibold block gold-glow">
              Beyond The Chair
            </span>
            <h2 className="font-serif text-xl sm:text-3xl md:text-5xl uppercase tracking-wider text-white leading-tight">
              Exclusive Luxury <span className="text-gold-400">Amenities</span>
            </h2>
          </div>
        </div>

        {/* Carousel container */}
        <div className="relative h-[75vh] sm:h-[85vh] md:h-[90vh] w-full overflow-hidden">
          <AnimatePresence mode="wait">
            <motion.div
              key={amenityIndex}
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              transition={{ duration: 0.8 }}
              className="absolute inset-0"
            >
              {/* Image with zoom */}
              <motion.img
                src={AMENITIES[amenityIndex].img}
                alt={AMENITIES[amenityIndex].title}
                referrerPolicy="no-referrer"
                initial={{ scale: 1.3 }}
                animate={{ scale: 1 }}
                transition={{ duration: 5, ease: 'easeOut' }}
                className="absolute inset-0 w-full h-full object-cover"
              />

              {/* Dark vignette overlay */}
              <div className="absolute inset-0 bg-gradient-to-t from-salon-black/80 via-salon-black/30 to-salon-black/60"></div>
              <div className="absolute inset-0 bg-gradient-to-r from-salon-black/40 via-transparent to-salon-black/40"></div>
            </motion.div>
          </AnimatePresence>

          {/* Centered title glass morphic box */}
          <AnimatePresence mode="wait">
            <motion.div
              key={`text-${amenityIndex}`}
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -20 }}
              transition={{ duration: 0.6, delay: 0.3 }}
              className="absolute inset-0 z-10 flex flex-col items-center justify-center px-5 md:px-4"
            >
              <div className="bg-black/40 backdrop-blur-md border border-white/10 p-6 sm:p-8 md:p-12 max-w-lg w-full text-center shadow-2xl mx-0">
                <div className="w-10 md:w-12 h-px bg-gold-400/60 mx-auto mb-4 md:mb-6"></div>
                <h3 className="font-serif text-xl sm:text-2xl md:text-3xl lg:text-5xl uppercase tracking-wider text-white leading-tight">
                  {AMENITIES[amenityIndex].title}
                </h3>
                <p className="text-[11px] sm:text-xs md:text-sm text-gray-300 font-sans leading-relaxed mt-3 md:mt-4 max-w-md mx-auto">
                  {AMENITIES[amenityIndex].desc}
                </p>
                <div className="w-10 md:w-12 h-px bg-gold-400/60 mx-auto mt-4 md:mt-6"></div>
              </div>
            </motion.div>
          </AnimatePresence>

          {/* Navigation dots */}
          <div className="absolute bottom-6 md:bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 md:gap-3">
            {AMENITIES.map((_, i) => (
              <button
                key={i}
                onClick={() => { setAmenityDirection(i > amenityIndex ? 1 : -1); setAmenityIndex(i); }}
                className={`transition-all duration-500 cursor-pointer ${i === amenityIndex
                  ? 'w-6 md:w-8 h-1.5 md:h-2 bg-gold-400'
                  : 'w-1.5 md:w-2 h-1.5 md:h-2 bg-white/30 hover:bg-white/60'
                  }`}
                aria-label={`Go to slide ${i + 1}`}
              />
            ))}
          </div>

          {/* Side navigation arrows */}
          <button
            onClick={() => { setAmenityDirection(-1); setAmenityIndex(prev => (prev - 1 + AMENITIES.length) % AMENITIES.length); }}
            className="absolute left-2 md:left-8 top-1/2 -translate-y-1/2 z-20 w-9 h-9 md:w-12 md:h-12 flex items-center justify-center bg-black/30 hover:bg-gold-400/20 border border-white/10 hover:border-gold-400/40 transition-all duration-300 cursor-pointer group"
            aria-label="Previous slide"
          >
            <svg className="w-3.5 h-3.5 md:w-5 md:h-5 text-white group-hover:text-gold-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
              <path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <button
            onClick={() => { setAmenityDirection(1); setAmenityIndex(prev => (prev + 1) % AMENITIES.length); }}
            className="absolute right-2 md:right-8 top-1/2 -translate-y-1/2 z-20 w-9 h-9 md:w-12 md:h-12 flex items-center justify-center bg-black/30 hover:bg-gold-400/20 border border-white/10 hover:border-gold-400/40 transition-all duration-300 cursor-pointer group"
            aria-label="Next slide"
          >
            <svg className="w-3.5 h-3.5 md:w-5 md:h-5 text-white group-hover:text-gold-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
              <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </section>

      {/* MASTER BARBERS CARD CAROUSEL ON LANDING PAGE */}
      <section id="team-section" className="py-20 md:py-24 bg-salon-black relative border-b border-gold-400/10">
        <div className="max-w-7xl mx-auto px-4 md:px-8 space-y-16">
          <div className="text-center max-w-2xl mx-auto space-y-3">
            <span className="text-[10px] font-mono tracking-[0.3em] text-gold-400 uppercase block">
              Grooming Craft Academics
            </span>
            <h2 className="font-serif text-3xl sm:text-4xl uppercase tracking-wider text-white">
              Master Stylists
            </h2>
            <p className="text-xs text-gray-400">
              Select your master stylist from our carousel below. Each barber is specialized in advanced symmetry profiling.
            </p>
          </div>

          {/* Responsive Card Slider Carousel */}
          <BarberCarousel
            barbers={barbers}
            onBookBarber={handleBarberBookingBridge}
          />

          <div className="text-center pt-4">
            <button
              onClick={() => navigateTo('/about')}
              className="inline-flex items-center gap-1.5 border-b border-gold-400 py-1 text-gold-400 hover:text-white hover:border-white transition-all text-xs font-serif uppercase tracking-widest cursor-pointer"
            >
              Meet Dynamic Stylist Biographies & Gallery
            </button>
          </div>
        </div>
      </section>

      {/* VIP MEMBERSHIP CLUB SECTION */}
      <section id="vip-section" className="py-20 md:py-24 bg-salon-black relative overflow-hidden border-b border-[#32BBED]/10">
        <div
          ref={vipBgRef}
          className="absolute inset-y-[-6%] left-1/2 w-[100vw] bg-cover bg-center bg-no-repeat opacity-[0.38] pointer-events-none"
          style={{
            backgroundImage: `url('/assets/images/vip.png')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            backgroundRepeat: 'no-repeat',
            transform: 'translate3d(-50%, 0, 0) scale(1.08)',
            willChange: 'transform'
          }}
        />
        <div className="absolute inset-0 w-full bg-gradient-to-b from-salon-black via-salon-black/78 to-salon-black pointer-events-none"></div>
        <div className="absolute inset-0 w-full bg-gradient-to-r from-salon-black/80 via-salon-black/38 to-salon-black/80 pointer-events-none"></div>
        <div className="absolute right-0 top-1/2 -translate-y-1/2 w-96 h-96 rounded-full bg-[#32BBED]/5 blur-[120px] pointer-events-none"></div>

        <div className="relative z-10 max-w-7xl mx-auto px-4 md:px-8">
          <div className="p-0 md:p-6 relative grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

            <div className="absolute top-0 right-0 py-1 px-4 bg-[#32BBED] text-black font-serif text-[10px] tracking-widest uppercase font-semibold">
              ADONIS SIGNATURE
            </div>

            <motion.div
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true, amount: 0.32 }}
              transition={{ duration: 0.95, ease: [0.22, 1, 0.36, 1] }}
              className="lg:col-span-6 space-y-6 text-left bg-salon-black/50 border border-[#32BBED]/10 p-6 md:p-8 backdrop-blur-[2px]"
            >
              <span className="text-xs font-mono tracking-[0.3em] text-[#32BBED] uppercase font-semibold flex items-center gap-2">
                <LucideIcon name="Crown" size={16} /> Exclusive Membership Guild
              </span>
              <h2 className="font-serif text-3xl sm:text-4xl md:text-5xl uppercase tracking-wider leading-tight text-white mb-4">
                Adonis VIP Club
              </h2>
              <p className="text-gray-400 text-sm leading-relaxed font-light font-sans mb-6">
                Unlock priority booking, dedicated styling suites, and complimentary consultations in our private lounge. Elevate your grooming into an effortless ritual of elite lifestyle.
              </p>

              <ul className="space-y-4 text-[12px] uppercase tracking-wider text-gray-300">
                {VIP_PRIVILEGES.map((priv, idx) => (
                  <motion.li
                    key={idx}
                    initial={{ opacity: 0, y: 18 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, amount: 0.45 }}
                    transition={{ duration: 0.65, delay: idx * 0.06, ease: [0.22, 1, 0.36, 1] }}
                    className="flex items-center gap-3"
                  >
                    <div className="w-1.5 h-1.5 bg-[#32BBED] rotate-45 shrink-0"></div>
                    <div className="flex flex-col">
                      <span className="font-serif hover:text-[#32BBED] transition-colors">{priv.title}</span>
                      <span className="text-[10px] text-gray-500 lowercase tracking-normal font-sans pt-0.5">{priv.desc}</span>
                    </div>
                  </motion.li>
                ))}
              </ul>

              <motion.div
                initial={{ opacity: 0, y: 18 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true, amount: 0.55 }}
                transition={{ duration: 0.8, delay: 0.18, ease: [0.22, 1, 0.36, 1] }}
                className="pt-4"
              >
                <button
                  onClick={() => setVipModalOpen(true)}
                  className="w-full sm:w-auto border border-[#32BBED] text-[#32BBED] py-4 px-10 uppercase text-[11px] tracking-[0.2em] hover:bg-[#32BBED] hover:text-black transition-all cursor-pointer font-serif font-black"
                >
                  Join The Experience
                </button>
              </motion.div>
            </motion.div>

            <motion.div
              initial={{ opacity: 0, y: 34, scale: 0.96 }}
              whileInView={{ opacity: 1, y: 0, scale: 1 }}
              viewport={{ once: true, amount: 0.28 }}
              transition={{ duration: 1, delay: 0.08, ease: [0.22, 1, 0.36, 1] }}
              className="lg:col-span-6 flex justify-center"
            >
              <div className="aspect-[1.58] w-full max-w-md bg-gradient-to-br from-salon-black to-salon-gray border border-gold-400/40 rounded-none p-6 md:p-8 flex flex-col justify-between shadow-2xl relative select-none">
                <div className="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
                  <LucideIcon name="Crown" size={180} className="text-gold-400" />
                </div>

                <div className="flex items-start justify-between">
                  <div className="space-y-0.5">
                    <span className="text-[10px] font-mono text-gold-400/60 uppercase tracking-[0.2em] block">MEMBERSHIP CARD</span>
                    <span className="text-sm font-serif font-black tracking-widest text-white uppercase">ADONIS VIP GOLD</span>
                  </div>
                  <LucideIcon name="Crown" className="text-gold-400" size={24} />
                </div>

                <div className="space-y-1 my-6 text-left">
                  <span className="text-[8px] font-mono text-gray-500 uppercase tracking-widest block">CLIENT CREDENTIAL</span>
                  <span className="text-lg font-serif text-gray-100 uppercase tracking-wider block font-light">MEMBER RECORD</span>
                </div>

                <div className="flex items-end justify-between border-t border-gold-400/10 pt-4">
                  <div className="text-left">
                    <span className="text-[8px] font-mono text-gray-500 uppercase tracking-widest block">EXCLUSIVELY SERVICING</span>
                    <span className="text-[10px] font-sans text-gold-400 uppercase tracking-widest block mt-0.5">Gulshan & Bashundhara</span>
                  </div>
                  <span className="text-xs font-mono tracking-widest text-white/40">VIP - #8800</span>
                </div>
              </div>
            </motion.div>

          </div>
        </div>
      </section>

      {/* BOOKING SYSTEM SECTION */}
      <section id="booking-section" className="py-20 md:py-24 bg-salon-black relative border-b border-gold-400/10 scroll-mt-20">
        <div className="max-w-7xl mx-auto px-4 md:px-8 space-y-12">

          <div className="text-center max-w-2xl mx-auto space-y-3">
            <span className="text-[10px] font-mono tracking-[0.3em] text-gold-400 uppercase block">
              Sovereign Slot Reservation
            </span>
            <h2 className="font-serif text-3xl sm:text-4xl uppercase tracking-wider text-white">
              Secure Your Session
            </h2>
            <p className="text-xs text-gray-400">
              Complete our secure reservation checklist. Your transformation begins with one appointment. Open everyday in Dhaka.
            </p>
          </div>

          <BookingForm
            initialBranchId={selectedBookingBranchId}
            initialServiceId={selectedServiceId}
            services={services}
            onBookingSuccess={(booking) => setBookings(prev => [booking, ...prev].slice(0, 50))}
          />
        </div>
      </section>

      {/* BRANCH LOCATOR SECTION */}
      <section id="branch-section" className="py-20 md:py-24 bg-salon-gray relative border-b border-gold-400/10">
        <div className="max-w-7xl mx-auto px-4 md:px-8 space-y-16">

          <div className="text-center max-w-2xl mx-auto space-y-3">
            <span className="text-[10px] font-mono tracking-[0.3em] text-gold-400 uppercase block">
              Dhaka Premium Grooming lounges
            </span>
            <h2 className="font-serif text-3xl sm:text-4xl uppercase tracking-wider text-white">
              Lounge Locations
            </h2>
            <p className="text-xs text-gray-400">
              Visit us in Dhaka’s finest commercial quarters. Each space offers elite styling bays, high-speed Wi-Fi, and personalized services.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div className="lg:col-span-5 space-y-4">
              {BRANCHES.map(branch => {
                const isActive = activeMapId === branch.id;
                return (
                  <div
                    key={branch.id}
                    onClick={() => setActiveMapId(branch.id as any)}
                    className={`cursor-pointer p-6 border transition-all duration-300 relative text-left ${isActive
                      ? 'bg-salon-black border-gold-400 shadow-xl shadow-gold-400/5'
                      : 'bg-salon-black/45 border-white/5 hover:border-gold-400/40'
                      }`}
                  >
                    {isActive && (
                      <div className="absolute top-0 bottom-0 left-0 w-[3px] bg-gold-400"></div>
                    )}

                    <span className="text-[8px] font-mono text-gold-400 uppercase tracking-widest block font-bold mb-1">
                      {branch.id === 'gulshan' ? 'Gulshan Avenue HQ Lounge' : 'Bashundhara Elite Lounge'}
                    </span>
                    <h3 className="font-serif text-base uppercase tracking-wider text-white mb-2">{branch.name}</h3>

                    <p className="text-xs text-gray-400 leading-relaxed font-sans mb-4">{branch.address}</p>

                    <div className="text-[11px] text-gray-300 space-y-1 font-mono uppercase mb-4">
                      <div className="flex items-center gap-2">
                        <LucideIcon name="Clock" size={12} className="text-gold-400" />
                        <span>{branch.hours || settings.openHoursTime}</span>
                      </div>
                      {branch.phoneNumbers.map((phone, idx) => (
                        <div key={idx} className="flex items-center gap-2">
                          <LucideIcon name="Phone" size={12} className="text-gold-400" />
                          <span>{phone}</span>
                        </div>
                      ))}
                    </div>

                    <div className="flex gap-4">
                      <a
                        href={branch.directionsUrl}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="px-4 py-1.5 bg-gold-400 text-salon-black hover:bg-gold-500 font-serif text-[10px] uppercase font-bold tracking-widest transition-colors flex items-center gap-1 cursor-pointer"
                      >
                        <LucideIcon name="ExternalLink" size={10} />
                        Get Directions
                      </a>
                      <button
                        onClick={(e) => {
                          e.stopPropagation();
                          setActiveMapId(branch.id as any);
                          document.getElementById('branch-maps-container')?.scrollIntoView({ behavior: 'smooth' });
                        }}
                        className="px-4 py-1.5 border border-white/10 text-white hover:border-gold-400/50 text-[10px] font-sans uppercase tracking-widest transition-colors"
                      >
                        Preview Map
                      </button>
                    </div>
                  </div>
                );
              })}
            </div>

            <div id="branch-maps-container" className="lg:col-span-7 border border-white/10 p-2 bg-salon-black h-[350px] md:h-[450px] relative">
              <div className="absolute top-4 left-4 z-10 bg-salon-black/95 px-3 py-1.5 border border-gold-400/30 text-[9px] font-mono uppercase tracking-widest text-gold-400">
                Live Location Terminal • {activeMapId === 'gulshan' ? 'Gulshan Golden Age' : 'Bashundhara Studio'}
              </div>
              <iframe
                src={BRANCHES.find(b => b.id === activeMapId)?.googleMapEmbed}
                width="100%"
                height="100%"
                style={{ border: 0, filter: 'invert(90%) hue-rotate(180deg) opacity(85%) text-shadow(0 0 5px #000)' }}
                allowFullScreen={false}
                loading="lazy"
                title="Adonis Dhaka Maps View"
              ></iframe>
            </div>
          </div>
        </div>
      </section>

      {/* GALLERY SECTION */}
      <section id="gallery-section" className="py-20 md:py-24 bg-salon-black relative border-b border-gold-400/10">
        <div className="max-w-7xl mx-auto px-4 md:px-8 space-y-16">
          <div className="text-center max-w-2xl mx-auto space-y-3">
            <span className="text-[10px] font-mono tracking-[0.3em] text-gold-400 uppercase block">
              The Adonis Aesthetic Lounge
            </span>
            <h2 className="font-serif text-3xl sm:text-4xl uppercase tracking-wider text-white">
              Studio Portfolio
            </h2>
            <p className="text-xs text-gray-400 col-span-2">
              Indulge in cinematic lighting details, modern facial hair closeups, bespoke scissor techniques, and premium Dhaka lounge realities.
            </p>
          </div>

          <div className="columns-1 sm:columns-2 lg:columns-3 gap-4 space-y-4">
            {IMAGES.gallery.map((img, idx) => (
              <div
                key={idx}
                onClick={() => setLightbox({ isOpen: true, imgUrl: img.url, title: img.title, subtitle: img.subtitle })}
                className="break-inside-avoid bg-salon-gray border border-white/10 hover:border-gold-400/35 overflow-hidden transition-all duration-500 cursor-pointer group relative"
              >
                <img
                  src={img.url}
                  alt={img.title}
                  referrerPolicy="no-referrer"
                  className="w-full h-auto object-cover grayscale group-hover:grayscale-0 group-hover:scale-[1.03] transition-all duration-700 brightness-95"
                />

                <div className="absolute inset-0 bg-gradient-to-t from-salon-black via-salon-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-5 text-left">
                  <span className="text-[8px] font-mono text-gold-400 uppercase tracking-widest">Masterpiece {idx + 1}</span>
                  <h4 className="font-serif text-xs uppercase tracking-wider text-white font-medium mt-0.5">{img.title}</h4>
                  <p className="text-[10px] text-gray-400 font-sans mt-0.5">{img.subtitle}</p>
                </div>

                <div className="absolute top-3 right-3 h-7 w-7 bg-salon-black/80 border border-white/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                  <LucideIcon name="Compass" size={12} className="text-gold-400" />
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* TESTIMONIALS SECTION */}
      <section id="testimonials-section" className="py-20 md:py-24 bg-salon-gray relative border-b border-gold-400/10">
        <div className="max-w-7xl mx-auto px-4 md:px-8 space-y-16">
          <div className="text-center max-w-2xl mx-auto space-y-3">
            <span className="text-[10px] font-mono tracking-[0.3em] text-gold-400 uppercase block">
              Gilded Client Testimonials
            </span>
            <h2 className="font-serif text-3xl sm:text-4xl uppercase tracking-wider text-white">
              Vocal Appreciations
            </h2>
            <p className="text-xs text-gray-400">
              Read authentic reviews from local venture pioneers, multinational executives, and regular VIP club patrons.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {TESTIMONIALS.map(t => (
              <div
                key={t.id}
                className="bg-salon-black border border-white/5 p-8 relative flex flex-col justify-between h-[230px]"
              >
                <div className="absolute top-4 right-6 text-gold-400/5 font-serif text-5xl">”</div>

                <div className="space-y-4 text-left">
                  <div className="flex items-center gap-1">
                    {Array.from({ length: t.rating }).map((_, i) => (
                      <span key={i} className="text-yellow-400 font-serif text-xs">★</span>
                    ))}
                  </div>

                  <p className="text-xs text-gray-400 font-sans leading-relaxed italic">
                    "{t.comment}"
                  </p>
                </div>

                <div className="border-t border-white/5 pt-4 flex items-center gap-3 text-left">
                  <div className="h-8 w-8 rounded-full bg-gold-400/10 border border-gold-400/25 flex items-center justify-center font-serif text-xs font-bold text-gold-400">
                    {t.avatarLetter}
                  </div>
                  <div>
                    <h5 className="text-xs font-serif uppercase tracking-wide text-white">{t.author}</h5>
                    <span className="text-[9px] text-gray-500 font-mono uppercase tracking-widest block">{t.source}</span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CONTACT SECTION */}
      <section id="contact-section" className="py-20 md:py-24 bg-salon-gray relative border-b border-gold-400/10">
        <div className="max-w-7xl mx-auto px-4 md:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            <div className="lg:col-span-6 space-y-8 text-left">
              <span className="text-[10px] font-mono tracking-[0.3em] text-gold-400 uppercase block">
                Connect With Adonis Lobby
              </span>
              <h2 className="font-serif text-3xl sm:text-4xl uppercase tracking-wider text-white">
                Contact Directory
              </h2>
              <p className="text-gray-400 text-xs sm:text-sm leading-relaxed font-light">
                Secure your high-performance styling, reschedule active sessions, or resolve licensing inquiries by contacting our dedicated corporate groom deck directly.
              </p>

              <div className="space-y-6">

                {/* Branches */}
                <div className="space-y-4">
                  {BRANCHES.map(b => (
                    <div key={b.id} className="p-4 bg-salon-black border border-white/5 text-xs text-left">
                      <span className="font-serif text-xs uppercase tracking-widest text-gold-400 block pb-1 border-b border-white/5 mb-2 font-semibold">
                        📍 {b.name}
                      </span>
                      <p className="text-gray-400 leading-relaxed font-sans">{b.address}</p>
                      <p className="mt-2 text-gray-500 font-mono uppercase tracking-widest">Hours: {b.hours || settings.openHoursTime}</p>
                      <div className="mt-2 text-gold-400/80 font-mono tracking-widest flex flex-wrap gap-x-4">
                        {b.phoneNumbers.map(phone => (
                          <span key={phone}>{phone}</span>
                        ))}
                      </div>
                    </div>
                  ))}
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono tracking-widest uppercase">
                  <div className="p-4 bg-salon-black border border-white/5 space-y-1">
                    <span className="text-[9px] text-gray-500 block">General Email</span>
                    <a href={`mailto:${settings.contactEmail}`} className="text-white hover:text-gold-400 block mt-0.5 truncate lowercase">
                      {settings.contactEmail}
                    </a>
                  </div>
                  <div className="p-4 bg-salon-black border border-white/5 space-y-1">
                    <span className="text-[9px] text-gray-500 block">Lobby Domain</span>
                    <a href={CONTACT_INFO.website} target="_blank" rel="noopener noreferrer" className="text-white hover:text-gold-400 block mt-0.5 truncate">
                      adonis.com.bd
                    </a>
                  </div>
                </div>

              </div>
            </div>

            {/* Inquiry Form */}
            <div className="lg:col-span-6 bg-salon-black border border-white/10 p-8 text-left">
              <span className="text-[9px] font-mono tracking-widest text-gold-400 uppercase block mb-1">
                Lobby Dispatch
              </span>
              <h3 className="font-serif text-lg uppercase tracking-wider text-white mb-4">
                Transmit Inquiries
              </h3>

              <form onSubmit={(e) => { e.preventDefault(); alert("Thanks for contacting Adonis Men's Grooming. We will call you shortly."); }} className="space-y-4">
                <div>
                  <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Full Corporate Name</label>
                  <input
                    type="text"
                    required
                    placeholder="e.g. Zaynul Abedin"
                    className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Mobile Registry</label>
                  <input
                    type="tel"
                    required
                    placeholder="e.g. +880 1720-000000"
                    className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Inquiry Details</label>
                  <textarea
                    rows={3}
                    required
                    placeholder="Write your concerns or wholesale branch requests."
                    className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors resize-none"
                  />
                </div>
                <button
                  type="submit"
                  className="w-full py-3 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-xs font-bold uppercase tracking-widest transition-all cursor-pointer"
                >
                  Transmit Message
                </button>
              </form>
            </div>

          </div>
        </div>
      </section>

      {renderFooter()}

      {/* LIGHTBOX MODAL */}
      <AnimatePresence>
        {lightbox && lightbox.isOpen && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={() => setLightbox(null)}
            className="fixed inset-0 z-50 bg-black/95 flex flex-col items-center justify-center p-4 cursor-zoom-out"
          >
            <button
              onClick={() => setLightbox(null)}
              className="absolute top-6 right-6 text-white hover:text-gold-400 p-2"
              aria-label="Close Lightbox"
            >
              <LucideIcon name="X" size={32} />
            </button>

            <motion.div
              initial={{ scale: 0.95, y: 15 }}
              animate={{ scale: 1, y: 0 }}
              exit={{ scale: 0.95, y: 15 }}
              onClick={(e) => e.stopPropagation()}
              className="max-w-4xl max-h-[80vh] bg-salon-black border border-gold-400/20 p-2 relative text-left"
            >
              <img
                src={lightbox.imgUrl}
                alt={lightbox.title}
                referrerPolicy="no-referrer"
                className="max-h-[70vh] w-auto max-w-full object-contain"
              />
              <div className="p-4 bg-salon-black space-y-1.5 self-stretch">
                <span className="text-[8px] font-mono text-gold-400 uppercase tracking-widest">Active Showcase</span>
                <h4 className="font-serif text-sm uppercase text-white tracking-wider font-semibold">{lightbox.title}</h4>
                <p className="text-xs text-gray-400 font-sans">{lightbox.subtitle}</p>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* MASTER BARBER PHILOSOPHY POPUP */}
      <AnimatePresence>
        {selectedBarberDetail && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={() => setSelectedBarberDetail(null)}
            className="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4 cursor-default"
          >
            <motion.div
              initial={{ scale: 0.95, y: 15 }}
              animate={{ scale: 1, y: 0 }}
              exit={{ scale: 0.95, y: 15 }}
              onClick={(e) => e.stopPropagation()}
              className="max-w-md w-full bg-salon-card border border-gold-400/35 overflow-hidden shadow-2xl relative text-left"
            >
              <button
                onClick={() => setSelectedBarberDetail(null)}
                className="absolute top-4 right-4 text-white hover:text-gold-400 p-2 z-10 bg-black/70 rounded-full"
                aria-label="Close Barber Detail"
              >
                <LucideIcon name="X" size={18} />
              </button>

              <div className="aspect-[4/5] w-full overflow-hidden relative">
                <img
                  src={selectedBarberDetail.portraitUrl}
                  alt={selectedBarberDetail.name}
                  referrerPolicy="no-referrer"
                  className="w-full h-full object-cover grayscale brightness-95"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-salon-black via-transparent to-transparent"></div>
                <div className="absolute bottom-4 left-5 text-left">
                  <span className="text-[10px] font-mono bg-gold-400 text-salon-black px-2.5 py-0.5 uppercase tracking-widest font-black">
                    ★ {selectedBarberDetail.rating} Rating
                  </span>
                  <h4 className="font-serif text-lg uppercase tracking-wider text-white mt-2">{selectedBarberDetail.name}</h4>
                  <p className="text-xs text-gold-400 font-mono uppercase mt-0.5">{selectedBarberDetail.specialty}</p>
                </div>
              </div>

              <div className="p-6 space-y-4 text-left">
                <div>
                  <span className="text-[9px] text-gray-500 font-mono uppercase tracking-widest block">BARBER BIOGRAPHY</span>
                  <p className="text-xs text-gray-300 leading-relaxed font-sans mt-1">
                    "{selectedBarberDetail.bio}"
                  </p>
                </div>

                <div className="grid grid-cols-2 gap-4 text-xs font-mono tracking-widest uppercase border-t border-white/5 pt-4">
                  <div>
                    <span className="text-[8px] text-gray-500 block">REPRESENTED EXPERIENCE</span>
                    <span className="text-white font-serif text-sm mt-0.5 block">{selectedBarberDetail.experienceYears}+ Years</span>
                  </div>
                  <div>
                    <span className="text-[8px] text-gray-500 block">SALON ASSIGNMENT</span>
                    <span className="text-white font-sans text-[11px] mt-0.5 block">Gulshan & Bashundhara</span>
                  </div>
                </div>

                <button
                  onClick={() => {
                    setSelectedBarberDetail(null);
                    handleBarberBookingBridge(selectedBarberDetail.id);
                  }}
                  className="w-full py-3 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-xs font-bold uppercase tracking-widest transition-all cursor-pointer text-center"
                >
                  Book Session with {selectedBarberDetail.name.split(' ')[0]}
                </button>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* VIP CLUB SIGNUP MODAL */}
      <AnimatePresence>
        {vipModalOpen && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={closeVipModal}
            className="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
          >
            <motion.div
              initial={{ scale: 0.95, y: 15 }}
              animate={{ scale: 1, y: 0 }}
              exit={{ scale: 0.95, y: 15 }}
              onClick={(e) => e.stopPropagation()}
              className="max-w-md w-full bg-salon-card border border-gold-400/40 p-6 md:p-8 relative text-left"
            >
              <button
                onClick={closeVipModal}
                className="absolute top-4 right-4 text-white hover:text-gold-400 p-2"
                aria-label="Close VIP Register"
              >
                <LucideIcon name="X" size={18} />
              </button>

              <div className="space-y-4">
                <div className="text-center pb-2 border-b border-white/5">
                  <LucideIcon name="Crown" className="text-gold-400 mx-auto animate-pulse mb-1" size={32} />
                  <h4 className="font-serif text-base uppercase tracking-wider text-white">Join Adonis VIP Experience</h4>
                  <p className="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Acquire priority credentials</p>
                </div>

                {!vipFormSubmitted ? (
                  <form onSubmit={handleVipSubmit} className="space-y-4">
                    <div>
                      <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Gentleman Name</label>
                      <input
                        type="text"
                        required
                        value={vipName}
                        onChange={(e) => setVipName(e.target.value)}
                        placeholder="e.g. Asif Chowdhury"
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>

                    <div>
                      <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Phone Number Registry</label>
                      <input
                        type="tel"
                        required
                        value={vipPhone}
                        onChange={(e) => setVipPhone(e.target.value)}
                        placeholder="e.g. +880 1919-700800"
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>

                    <div className="p-3 bg-gold-400/5 border border-gold-400/10 text-[10px] text-gray-400 leading-relaxed">
                      By submitting authentication, you register your interest in the next cohort of members. Formal invitations are issued within 48 business hours.
                    </div>

                    <button
                      type="submit"
                      className="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-400 text-salon-black font-serif text-xs font-bold uppercase tracking-widest transition-all cursor-pointer"
                    >
                      Process Invitation Request
                    </button>
                  </form>
                ) : (
                  <motion.div
                    initial={{ opacity: 0, scale: 0.95 }}
                    animate={{ opacity: 1, scale: 1 }}
                    className="text-center py-6 space-y-4"
                  >
                    <LucideIcon name="Check" className="text-gold-400 mx-auto" size={40} />
                    <h5 className="font-serif text-sm uppercase tracking-wide text-white">Application Received</h5>
                    <p className="text-xs text-gray-400 leading-relaxed max-w-xs mx-auto">
                      Thank you, <strong className="text-white uppercase font-serif block mt-1">{vipName}</strong>. Our concierge deck will authenticate your credentials and reach out to {vipPhone}.
                    </p>
                    <button
                      onClick={closeVipModal}
                      className="px-6 py-2 border border-gold-400/30 text-white hover:border-gold-400 text-[10px] font-mono uppercase tracking-widest transition-all"
                    >
                      Understand
                    </button>
                  </motion.div>
                )}
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>

    </div>
  );
}

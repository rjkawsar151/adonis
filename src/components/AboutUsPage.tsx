import React, { useState, useEffect, useRef } from 'react';
import { LucideIcon } from './LucideIcon';
import { navigateTo } from '../navigation';
import { OptimizedImage } from './OptimizedImage';

interface AboutData {
  chairmanMessage: {
    name: string;
    designation: string;
    photo: string | null;
    title: string;
    speech: string;
    signature_image: string | null;
    quotation: string | null;
    is_active: boolean;
  } | null;
  mdMessage: {
    name: string;
    designation: string;
    photo: string | null;
    title: string;
    speech: string;
    signature_image: string | null;
    quotation: string | null;
    is_active: boolean;
  } | null;
  companyIntro: {
    title: string;
    subtitle: string | null;
    description: string;
    featured_image: string | null;
    is_active: boolean;
  } | null;
  missionsVisions: Array<{
    id: number;
    type: 'mission' | 'vision';
    title: string;
    short_description: string | null;
    content: string;
    icon_or_image: string | null;
    is_active: boolean;
  }>;
  coreValues: Array<{
    id: number;
    title: string;
    icon: string | null;
    description: string;
    is_active: boolean;
  }>;
  whyChooseUs: Array<{
    id: number;
    title: string;
    icon: string | null;
    description: string;
    is_active: boolean;
  }>;
  statistics: Array<{
    id: number;
    counter_number: string;
    suffix: string | null;
    label: string;
    icon: string | null;
    is_active: boolean;
  }>;
  timelines: Array<{
    id: number;
    year_or_date: string;
    title: string;
    description: string;
    image: string | null;
    is_active: boolean;
  }>;
  teamMembers: Array<{
    id: number;
    name: string;
    designation: string;
    photo: string | null;
    biography: string | null;
    facebook_url: string | null;
    linkedin_url: string | null;
    email: string | null;
    is_active: boolean;
  }>;
  cta: {
    title: string;
    description: string;
    primary_button_text: string;
    primary_button_url: string;
    secondary_button_text: string;
    secondary_button_url: string;
    background_image: string | null;
    is_active: boolean;
  } | null;
}

interface AboutUsPageProps {
  barbers: any[];
  onBookBarber: (barberId: string) => void;
}

const AnimatedCounter: React.FC<{ value: string; suffix?: string | null }> = ({ value, suffix }) => {
  const counterRef = useRef<HTMLSpanElement>(null);
  const [displayValue, setDisplayValue] = useState('0');
  const hasAnimated = useRef(false);

  useEffect(() => {
    const element = counterRef.current;
    if (!element) return;

    const numericValue = Number.parseFloat(String(value).replace(/[^0-9.-]/g, ''));
    if (!Number.isFinite(numericValue)) {
      setDisplayValue(value);
      return;
    }

    const decimals = String(value).includes('.') ? String(value).split('.')[1].length : 0;
    const renderValue = (current: number) => setDisplayValue(current.toFixed(decimals));

    const observer = new IntersectionObserver(([entry]) => {
      if (!entry.isIntersecting || hasAnimated.current) return;
      hasAnimated.current = true;
      observer.disconnect();

      if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        renderValue(numericValue);
        return;
      }

      const duration = 1800;
      const startTime = performance.now();
      const animate = (now: number) => {
        const progress = Math.min((now - startTime) / duration, 1);
        const easedProgress = 1 - Math.pow(1 - progress, 3);
        renderValue(numericValue * easedProgress);
        if (progress < 1) requestAnimationFrame(animate);
      };

      requestAnimationFrame(animate);
    }, { threshold: 0.35 });

    observer.observe(element);
    return () => observer.disconnect();
  }, [value]);

  return <span ref={counterRef}>{displayValue}{suffix}</span>;
};

export const AboutUsPage: React.FC<AboutUsPageProps> = ({ barbers, onBookBarber }) => {
  const [data, setData] = useState<AboutData | null>(null);

  useEffect(() => {
    const fetchAboutData = async () => {
      try {
        const res = await fetch('/api/about', {
          cache: 'no-store',
          headers: { Accept: 'application/json' },
        });
        if (res.ok) {
          const json = await res.json();
          setData(json);
        }
      } catch (err) {
        console.error('Failed to fetch about data', err);
      }
    };
    fetchAboutData();
  }, []);

  const chairman = data?.chairmanMessage;
  const md = data?.mdMessage;
  const intro = data?.companyIntro;
  const missions = data?.missionsVisions.filter(m => m.type === 'mission') || [];
  const visions = data?.missionsVisions.filter(m => m.type === 'vision') || [];
  const coreValues = data?.coreValues || [];
  const whyChooseUs = data?.whyChooseUs || [];
  const statistics = data?.statistics || [];
  const timelines = data?.timelines || [];
  const teamMembers = data?.teamMembers || [];
  const cta = data?.cta;

  return (
    <div className="bg-salon-black text-white relative min-h-screen overflow-hidden">
      {/* Dynamic Background Accents */}
      <div className="absolute top-[5%] left-[-10%] h-[700px] w-[700px] rounded-full bg-gold-400/5 blur-[180px] pointer-events-none"></div>
      <div className="absolute top-[40%] right-[-10%] h-[700px] w-[700px] rounded-full bg-gold-400/5 blur-[180px] pointer-events-none"></div>
      <div className="absolute bottom-[10%] left-[20%] h-[700px] w-[700px] rounded-full bg-gold-400/5 blur-[180px] pointer-events-none"></div>

      {/* Hero Banner */}
      <section className="relative pt-36 pb-20 border-b border-white/5 bg-gradient-to-b from-black to-salon-black text-center space-y-4">
        <span className="text-[10px] font-mono tracking-[0.4em] text-gold-400 uppercase block gold-glow">
          About Adonis Men's Grooming
        </span>
        <h1 className="font-serif text-4xl sm:text-5xl md:text-6xl uppercase tracking-wider text-white">
          Our Story & Legacy
        </h1>
        <div className="h-[1px] w-24 bg-gold-400 mx-auto my-3"></div>
      </section>

      <div className="max-w-7xl mx-auto px-4 md:px-8 py-16 space-y-32 relative z-10">

        {/* 1. Chairman's Message Section (Image Left, Message Right) */}
        {chairman && chairman.is_active && (
          <section className="bg-salon-gray/20 border border-white/5 p-8 md:p-12">
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
              {/* Photo Column */}
              <div className="lg:col-span-5 flex flex-col items-center text-center space-y-6">
                <div className="relative group max-w-sm w-full aspect-[4/5] bg-salon-gray overflow-hidden border border-white/10 p-2">
                  <div className="absolute -top-3 -left-3 w-10 h-10 border-t-2 border-l-2 border-gold-400/40 group-hover:border-gold-400 transition-colors duration-500"></div>
                  <div className="absolute -bottom-3 -right-3 w-10 h-10 border-b-2 border-r-2 border-gold-400/40 group-hover:border-gold-400 transition-colors duration-500"></div>
                  
                  {chairman.photo ? (
                    <OptimizedImage
                      src={chairman.photo}
                      alt={chairman.name}
                      className="w-full h-full object-cover grayscale-0 group-hover:grayscale transition-all duration-700"
                      width={800}
                      height={1000}
                      sizes="(max-width: 640px) 100vw, 400px"
                    />
                  ) : (
                    <div className="w-full h-full bg-salon-gray flex items-center justify-center text-gray-600">
                      <LucideIcon name="User" size={64} />
                    </div>
                  )}
                </div>
                <div>
                  <h3 className="font-serif text-xl uppercase tracking-wider text-white">{chairman.name}</h3>
                  <p className="text-[10px] font-mono tracking-widest text-gold-400 uppercase mt-1">{chairman.designation}</p>
                </div>
              </div>

              {/* Message Column */}
              <div className="lg:col-span-7 space-y-6 text-left">
                <span className="text-[9px] font-mono tracking-widest text-gold-400 uppercase">Chairman's Message</span>
                <h2 className="font-serif text-2xl sm:text-3xl uppercase tracking-wider text-white border-b border-white/5 pb-4">
                  {chairman.title}
                </h2>
                
                {chairman.quotation && (
                  <div className="pl-4 border-l-2 border-gold-400 py-1">
                    <p className="italic text-gold-400 font-serif text-sm sm:text-base leading-relaxed">
                      "{chairman.quotation}"
                    </p>
                  </div>
                )}

                <div 
                  className="text-gray-300 text-xs sm:text-sm leading-relaxed font-light space-y-4 markup-content"
                  dangerouslySetInnerHTML={{ __html: chairman.speech }}
                />

                {chairman.signature_image && (
                  <div className="pt-4">
                    <img 
                      src={chairman.signature_image} 
                      alt="Signature" 
                      loading="lazy"
                      decoding="async"
                      className="h-12 w-auto object-contain brightness-95 opacity-80" 
                    />
                  </div>
                )}
              </div>
            </div>
          </section>
        )}

        {/* 1b. Managing Director's Message Section (Message Left, Image Right) */}
        {md && md.is_active && (
          <section className="bg-salon-gray/20 border border-white/5 p-8 md:p-12">
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
              
              {/* Message Column (Order 1 on Desktop, order 2 on Mobile) */}
              <div className="lg:col-span-7 space-y-6 text-left lg:order-first order-last">
                <span className="text-[9px] font-mono tracking-widest text-gold-400 uppercase">Managing Director's Statement</span>
                <h2 className="font-serif text-2xl sm:text-3xl uppercase tracking-wider text-white border-b border-white/5 pb-4">
                  {md.title}
                </h2>
                
                {md.quotation && (
                  <div className="pl-4 border-l-2 border-gold-400 py-1">
                    <p className="italic text-gold-400 font-serif text-sm sm:text-base leading-relaxed">
                      "{md.quotation}"
                    </p>
                  </div>
                )}

                <div 
                  className="text-gray-300 text-xs sm:text-sm leading-relaxed font-light space-y-4 markup-content"
                  dangerouslySetInnerHTML={{ __html: md.speech }}
                />

                {md.signature_image && (
                  <div className="pt-4">
                    <img 
                      src={md.signature_image} 
                      alt="Signature" 
                      loading="lazy"
                      decoding="async"
                      className="h-12 w-auto object-contain brightness-95 opacity-80" 
                    />
                  </div>
                )}
              </div>

              {/* Photo Column (Order 2 on Desktop, order 1 on Mobile) */}
              <div className="lg:col-span-5 flex flex-col items-center text-center space-y-6 lg:order-last order-first">
                <div className="relative group max-w-sm w-full aspect-[4/5] bg-salon-gray overflow-hidden border border-white/10 p-2">
                  <div className="absolute -top-3 -left-3 w-10 h-10 border-t-2 border-l-2 border-gold-400/40 group-hover:border-gold-400 transition-colors duration-500"></div>
                  <div className="absolute -bottom-3 -right-3 w-10 h-10 border-b-2 border-r-2 border-gold-400/40 group-hover:border-gold-400 transition-colors duration-500"></div>
                  
                  {md.photo ? (
                    <OptimizedImage
                      src={md.photo}
                      alt={md.name}
                      className="w-full h-full object-cover grayscale-0 group-hover:grayscale transition-all duration-700"
                      width={800}
                      height={1000}
                      sizes="(max-width: 640px) 100vw, 400px"
                    />
                  ) : (
                    <div className="w-full h-full bg-salon-gray flex items-center justify-center text-gray-600">
                      <LucideIcon name="User" size={64} />
                    </div>
                  )}
                </div>
                <div>
                  <h3 className="font-serif text-xl uppercase tracking-wider text-white">{md.name}</h3>
                  <p className="text-[10px] font-mono tracking-widest text-gold-400 uppercase mt-1">{md.designation}</p>
                </div>
              </div>

            </div>
          </section>
        )}

        {/* 2. Company Introduction Section */}
        {intro && intro.is_active && (
          <section className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            {/* Editorial Content */}
            <div className="lg:col-span-7 space-y-6 text-left">
              <span className="text-[9px] font-mono tracking-widest text-gold-400 uppercase">Introduction</span>
              <h2 className="font-serif text-3xl uppercase tracking-wider text-white">
                {intro.title}
              </h2>
              {intro.subtitle && (
                <p className="text-[#C9A84C] font-serif text-base uppercase tracking-wider font-light">
                  {intro.subtitle}
                </p>
              )}
              <div 
                className="text-gray-400 text-xs sm:text-sm leading-relaxed font-light space-y-4 markup-content"
                dangerouslySetInnerHTML={{ __html: intro.description }}
              />
            </div>

            {/* Featured Image */}
            <div className="lg:col-span-5 relative group max-w-md mx-auto lg:max-w-full">
              <div className="absolute -top-3 -left-3 w-10 h-10 border-t-2 border-l-2 border-gold-400/40 pointer-events-none"></div>
              <div className="absolute -bottom-3 -right-3 w-10 h-10 border-b-2 border-r-2 border-gold-400/40 pointer-events-none"></div>
              <div className="aspect-[4/3] bg-salon-gray overflow-hidden border border-white/10 p-2">
                {intro.featured_image ? (
                  <OptimizedImage
                    src={intro.featured_image}
                    alt={intro.title}
                    className="w-full h-full object-cover grayscale-0 brightness-90 group-hover:grayscale transition-all duration-700"
                    width={800}
                    height={600}
                    sizes="(max-width: 640px) 100vw, 400px"
                  />
                ) : (
                  <div className="w-full h-full bg-salon-gray flex items-center justify-center text-gray-600">
                    <LucideIcon name="Image" size={64} />
                  </div>
                )}
              </div>
            </div>
          </section>
        )}

        {/* 3. Dedicated Mission & Vision Section */}
        {(missions.length > 0 || visions.length > 0) && (
          <section className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {/* Missions */}
            {missions.map(item => (
              <div key={item.id} className="bg-salon-gray/30 border border-white/5 p-8 flex flex-col justify-between hover:border-gold-400/20 transition-all duration-350">
                <div className="space-y-4 text-left">
                  <div className="flex items-center gap-3 text-gold-400">
                    <LucideIcon name={item.icon_or_image || "Compass"} size={24} />
                    <h3 className="font-serif text-lg uppercase tracking-wider text-white">{item.title}</h3>
                  </div>
                  {item.short_description && (
                    <p className="text-[11px] font-mono tracking-wider text-gold-400/80 uppercase">
                      {item.short_description}
                    </p>
                  )}
                  <div 
                    className="text-xs text-gray-400 leading-relaxed font-light markup-content"
                    dangerouslySetInnerHTML={{ __html: item.content }}
                  />
                </div>
              </div>
            ))}

            {/* Visions */}
            {visions.map(item => (
              <div key={item.id} className="bg-salon-gray/30 border border-white/5 p-8 flex flex-col justify-between hover:border-gold-400/20 transition-all duration-350">
                <div className="space-y-4 text-left">
                  <div className="flex items-center gap-3 text-gold-400">
                    <LucideIcon name={item.icon_or_image || "Sparkles"} size={24} />
                    <h3 className="font-serif text-lg uppercase tracking-wider text-white">{item.title}</h3>
                  </div>
                  {item.short_description && (
                    <p className="text-[11px] font-mono tracking-wider text-gold-400/80 uppercase">
                      {item.short_description}
                    </p>
                  )}
                  <div 
                    className="text-xs text-gray-400 leading-relaxed font-light markup-content"
                    dangerouslySetInnerHTML={{ __html: item.content }}
                  />
                </div>
              </div>
            ))}
          </section>
        )}

        {/* 4. Core Values Section */}
        {coreValues.length > 0 && (
          <section className="space-y-12">
            <div className="text-center max-w-2xl mx-auto space-y-3">
              <span className="text-[9px] font-mono tracking-widest text-gold-400 uppercase">Core Beliefs</span>
              <h2 className="font-serif text-3xl uppercase tracking-wider text-white">Our Core Values</h2>
              <div className="h-[1px] w-12 bg-gold-400 mx-auto"></div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {coreValues.map(val => (
                <div key={val.id} className="p-6 bg-salon-gray/40 border border-white/5 hover:border-gold-400/25 transition-all duration-300 text-left space-y-3">
                  <div className="flex items-center gap-3 text-[#C9A84C]">
                    <LucideIcon name={val.icon || "Crown"} size={18} />
                    <span className="font-serif text-sm uppercase tracking-wider font-semibold text-white">{val.title}</span>
                  </div>
                  <p className="text-[11px] text-gray-400 leading-relaxed font-sans font-light">
                    {val.description}
                  </p>
                </div>
              ))}
            </div>
          </section>
        )}

        {/* 5. Why Choose Us Section */}
        {whyChooseUs.length > 0 && (
          <section className="space-y-12">
            <div className="text-center max-w-2xl mx-auto space-y-3">
              <span className="text-[9px] font-mono tracking-widest text-gold-400 uppercase">Premium Distinction</span>
              <h2 className="font-serif text-3xl uppercase tracking-wider text-white">Why Choose Us</h2>
              <div className="h-[1px] w-12 bg-gold-400 mx-auto"></div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              {whyChooseUs.map(point => (
                <div key={point.id} className="p-6 bg-salon-gray/30 border border-white/5 hover:border-gold-400/20 transition-all duration-300 text-left space-y-3 flex flex-col justify-between">
                  <div className="space-y-3">
                    <div className="text-[#C9A84C] bg-salon-gray p-3 rounded-none inline-block border border-white/5">
                      <LucideIcon name={point.icon || "Star"} size={20} />
                    </div>
                    <h4 className="font-serif text-xs sm:text-sm uppercase tracking-wider text-white font-bold">{point.title}</h4>
                    <p className="text-[11px] text-gray-400 leading-relaxed font-light">{point.description}</p>
                  </div>
                </div>
              ))}
            </div>
          </section>
        )}

        {/* 6. Company Statistics (Counters) Section */}
        {statistics.length > 0 && (
          <section className="bg-salon-gray/10 border border-white/5 py-12 px-6 grid grid-cols-2 md:grid-cols-5 gap-8 text-center items-center justify-center">
            {statistics.map(stat => (
              <div key={stat.id} className="space-y-2">
                <div className="flex justify-center text-[#C9A84C]">
                  <LucideIcon name={stat.icon || "Crown"} size={22} />
                </div>
                <div className="font-serif text-2xl sm:text-3xl md:text-4xl text-white font-bold tracking-tight">
                  <AnimatedCounter value={stat.counter_number} suffix={stat.suffix} />
                </div>
                <div className="text-[9px] font-mono tracking-widest text-gray-400 uppercase">
                  {stat.label}
                </div>
              </div>
            ))}
          </section>
        )}

        {/* 7. Journey/Timeline Section (Optimized to be Compact Grid) */}
        {timelines.length > 0 && (
          <section className="space-y-12">
            <div className="text-center max-w-2xl mx-auto space-y-3">
              <span className="text-[9px] font-mono tracking-widest text-gold-400 uppercase">Milestones</span>
              <h2 className="font-serif text-3xl uppercase tracking-wider text-white">Our Journey</h2>
              <div className="h-[1px] w-12 bg-gold-400 mx-auto"></div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto text-left">
              {timelines.map(t => (
                <div key={t.id} className="bg-salon-gray/20 p-5 border border-white/5 hover:border-gold-400/20 transition-all duration-300 flex gap-4 items-start rounded-none">
                  <span className="px-2 py-1 bg-gold-400 text-black text-[9px] font-mono uppercase tracking-widest font-extrabold shrink-0 mt-1">
                    {t.year_or_date}
                  </span>
                  <div className="space-y-2">
                    <h3 className="font-serif text-sm uppercase tracking-wider text-white font-semibold">{t.title}</h3>
                    <p className="text-[11px] text-gray-400 leading-relaxed font-light">{t.description}</p>
                    {t.image && (
                      <div className="aspect-[16/9] w-full overflow-hidden border border-white/5 max-h-24 mt-2">
                        <OptimizedImage
                          src={t.image}
                          alt={t.title}
                          className="w-full h-full object-cover grayscale-0 hover:grayscale transition-all duration-500"
                          width={300}
                          height={150}
                          sizes="150px"
                        />
                      </div>
                    )}
                  </div>
                </div>
              ))}
            </div>
          </section>
        )}

        {/* 8. Management/Leadership Team Section */}
        {teamMembers.length > 0 && (
          <section className="space-y-12">
            <div className="text-center max-w-2xl mx-auto space-y-3">
              <span className="text-[9px] font-mono tracking-widest text-gold-400 uppercase">Management Team</span>
              <h2 className="font-serif text-3xl uppercase tracking-wider text-white">Leadership</h2>
              <div className="h-[1px] w-12 bg-gold-400 mx-auto"></div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
              {teamMembers.map(member => (
                <div key={member.id} className="bg-salon-gray/20 border border-white/5 hover:border-gold-400/35 transition-all duration-500 text-left overflow-hidden flex flex-col justify-between">
                  <div>
                    {/* Member photo */}
                    <div className="aspect-[4/5] w-full overflow-hidden relative">
                      {member.photo ? (
                        <OptimizedImage
                          src={member.photo}
                          alt={member.name}
                          className="w-full h-full object-cover grayscale-0 brightness-90 hover:brightness-95 hover:scale-[1.02] hover:grayscale transition-all duration-700"
                          width={500}
                          height={625}
                          sizes="(max-width: 640px) 100vw, 300px"
                        />
                      ) : (
                        <div className="w-full h-full bg-salon-gray flex items-center justify-center text-gray-600">
                          <LucideIcon name="User" size={64} />
                        </div>
                      )}
                    </div>

                    {/* Member Info */}
                    <div className="p-6 space-y-3">
                      <div>
                        <h4 className="font-serif text-base uppercase tracking-wider text-white">{member.name}</h4>
                        <p className="text-[9px] font-mono tracking-widest text-gold-400 uppercase mt-0.5">{member.designation}</p>
                      </div>
                      
                      {member.biography && (
                        <p className="text-xs text-gray-400 leading-relaxed font-light">{member.biography}</p>
                      )}
                    </div>
                  </div>

                  {/* Social/Links */}
                  <div className="px-6 pb-6 pt-2 flex items-center gap-4 border-t border-white/5">
                    {member.facebook_url && (
                      <a href={member.facebook_url} target="_blank" rel="noreferrer" className="text-gray-500 hover:text-gold-400 transition-colors">
                        <LucideIcon name="Facebook" size={16} />
                      </a>
                    )}
                    {member.linkedin_url && (
                      <a href={member.linkedin_url} target="_blank" rel="noreferrer" className="text-gray-500 hover:text-gold-400 transition-colors">
                        <LucideIcon name="Linkedin" size={16} />
                      </a>
                    )}
                    {member.email && (
                      <a href={`mailto:${member.email}`} className="text-gray-500 hover:text-gold-400 transition-colors">
                        <LucideIcon name="Mail" size={16} />
                      </a>
                    )}
                  </div>
                </div>
              ))}
            </div>
          </section>
        )}

        {/* 8b. Dynamic Grooming Experts Section */}
        {barbers.length > 0 && (
          <section className="space-y-12 border-t border-white/5 pt-16">
            <div className="text-center max-w-2xl mx-auto space-y-3">
              <span className="text-[9px] font-mono tracking-widest text-gold-400 uppercase">Dhaka's Finest Groomers</span>
              <h2 className="font-serif text-3xl uppercase tracking-wider text-white">Our Experts</h2>
              <div className="h-[1px] w-12 bg-gold-400 mx-auto"></div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
              {barbers.map(barber => (
                <div key={barber.id} className="bg-salon-gray/20 border border-white/5 hover:border-gold-400/35 transition-all duration-300 text-left overflow-hidden flex flex-col justify-between group">
                  <div>
                    {/* Barber photo */}
                    <div className="aspect-[4/5] w-full overflow-hidden relative">
                      {barber.portraitUrl ? (
                        <OptimizedImage
                          src={barber.portraitUrl}
                          alt={barber.name}
                          className="w-full h-full object-cover grayscale-0 brightness-90 group-hover:brightness-95 group-hover:scale-[1.02] group-hover:grayscale transition-all duration-500"
                          width={400}
                          height={500}
                          sizes="250px"
                        />
                      ) : (
                        <div className="w-full h-full bg-salon-gray flex items-center justify-center text-gray-600">
                          <LucideIcon name="User" size={48} />
                        </div>
                      )}
                      <div className="absolute top-3 right-3 bg-salon-black/90 px-2 py-0.5 border border-gold-400/30 text-[8px] font-mono text-gold-400 tracking-wider">
                        ★ {barber.rating || '4.9'}
                      </div>
                    </div>

                    {/* Barber Info */}
                    <div className="p-5 space-y-1">
                      <h4 className="font-serif text-sm uppercase tracking-wider text-white">{barber.name}</h4>
                      <p className="text-[9px] font-mono tracking-widest text-gold-400 uppercase">{barber.specialty}</p>
                    </div>
                  </div>

                  {/* Booking CTA */}
                  <div className="p-5 pt-0">
                    <button
                      onClick={() => onBookBarber(barber.id)}
                      className="w-full py-2 bg-[#32BBED] hover:bg-gold-400 text-black font-serif text-[10px] uppercase font-bold tracking-widest transition-all duration-300 cursor-pointer text-center"
                    >
                      Book Session
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </section>
        )}

        {/* 9. Call-To-Action (CTA) Section */}
        {cta && cta.is_active && (
          <section className="relative overflow-hidden border border-white/10 p-8 sm:p-12 md:p-16 text-center">
            {/* Background image option */}
            {cta.background_image && (
              <div className="absolute inset-0 z-0">
                <img 
                  src={cta.background_image} 
                  alt="CTA Background" 
                  className="w-full h-full object-cover brightness-[0.15]" 
                />
              </div>
            )}
            
            <div className="relative z-10 max-w-2xl mx-auto space-y-6">
              <h2 className="font-serif text-3xl sm:text-4xl uppercase tracking-wider text-white leading-tight">
                {cta.title}
              </h2>
              <p className="text-xs sm:text-sm text-gray-400 leading-relaxed font-light">
                {cta.description}
              </p>
              
              <div className="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                {/* Book Session Trigger */}
                <button
                  onClick={() => {
                    if (cta.primary_button_url.startsWith('#')) {
                      // Navigate home and trigger booking scroll
                      navigateTo('/');
                      setTimeout(() => {
                        const el = document.querySelector(cta.primary_button_url);
                        if (el) el.scrollIntoView({ behavior: 'smooth' });
                      }, 200);
                    } else {
                      window.location.href = cta.primary_button_url;
                    }
                  }}
                  className="w-full sm:w-auto px-6 py-3 bg-[#C9A84C] hover:bg-[#b08d3c] text-black font-serif text-xs font-extrabold uppercase tracking-widest transition-colors cursor-pointer text-center"
                >
                  {cta.primary_button_text}
                </button>
                
                {/* Explore Services */}
                <button
                  onClick={() => navigateTo(cta.secondary_button_url)}
                  className="w-full sm:w-auto px-6 py-3 bg-transparent border border-white/20 hover:border-gold-400 text-white hover:text-gold-400 font-serif text-xs font-extrabold uppercase tracking-widest transition-all cursor-pointer text-center"
                >
                  {cta.secondary_button_text}
                </button>
              </div>
            </div>
          </section>
        )}

        {/* Back navigation */}
        <div className="pt-8 text-center border-t border-white/5">
          <button
            onClick={() => navigateTo('/')}
            className="inline-flex items-center gap-2 border-b border-white/20 hover:border-gold-400 hover:text-gold-400 pb-1 text-xs font-mono uppercase tracking-widest text-gray-400 transition-all cursor-pointer"
          >
            ← Back To Adonis Lounge
          </button>
        </div>

      </div>
    </div>
  );
};

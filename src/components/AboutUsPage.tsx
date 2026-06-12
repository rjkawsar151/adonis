import React from 'react';
import { Barber } from '../types';
import { LucideIcon } from './LucideIcon';
import { IMAGES } from '../data';
import { navigateTo } from '../navigation';

interface AboutUsPageProps {
  barbers: Barber[];
  onBookBarber: (barberId: string) => void;
  brandStory?: string;
  brandDescription?: string;
}

export const AboutUsPage: React.FC<AboutUsPageProps> = ({
  barbers,
  onBookBarber,
  brandStory = 'Adonis Men’s Grooming is a premium barbershop brand in Dhaka dedicated to redefining modern masculinity through precision grooming, luxury service, and personalized styling. Every cut, shave, and treatment is designed to elevate confidence and identity.',
  brandDescription = 'We believe that grooming is not merely a transaction—it represents a curated ritual of premium transition. Nestled in Dhaka’s premier neighborhoods, Adonis pairs classic European barber heritage with high-end, contemporary Dubai hotel-standard lounge accommodations. From the selection of our premium organic grooming balms to the exact temperature parameters of our steaming mint towels, every detail is meticulously orchestrated to deliver perfection.'
}) => {
  return (
    <div className="py-28 bg-salon-black text-white relative">
      {/* Decorative gradients */}
      <div className="absolute top-[20%] right-[-10%] h-[500px] w-[500px] rounded-full bg-gold-400/5 blur-[120px] pointer-events-none"></div>
      <div className="absolute top-[60%] left-[-10%] h-[500px] w-[500px] rounded-full bg-gold-400/5 blur-[120px] pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 md:px-8 space-y-24 relative z-10">

        {/* Editorial Brand Story */}
        <section className="space-y-16">
          <div className="text-center max-w-2xl mx-auto space-y-4">
            <span className="text-[10px] font-mono tracking-[0.4em] text-gold-400 uppercase font-semibold block gold-glow">
              Our Heritage & Philosophy
            </span>
            <h1 className="font-serif text-4xl sm:text-5xl md:text-6xl font-normal uppercase tracking-wider text-white">
              Brand Story
            </h1>
            <div className="h-[1px] w-24 bg-gold-400 mx-auto my-4"></div>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            {/* Image display with decorative corners */}
            <div className="lg:col-span-5 relative group max-w-md mx-auto lg:max-w-full">
              <div className="absolute -top-3 -left-3 w-10 h-10 border-t-2 border-l-2 border-gold-400/50 pointer-events-none group-hover:border-gold-400 transition-colors duration-500"></div>
              <div className="absolute -bottom-3 -right-3 w-10 h-10 border-b-2 border-r-2 border-gold-400/50 pointer-events-none group-hover:border-gold-400 transition-colors duration-500"></div>

              <div className="aspect-[4/5] bg-salon-gray overflow-hidden border border-white/10 p-2">
                <img
                  src={IMAGES.shaveBg || IMAGES.shaveAction}
                  alt="Traditional shave at Adonis Lounge"
                  referrerPolicy="no-referrer"
                  className="w-full h-full object-cover grayscale brightness-90 contrast-110 group-hover:grayscale-0 transition-all duration-700 group-hover:scale-[1.02]"
                />
              </div>
            </div>

            {/* Editorial brand texts */}
            <div className="lg:col-span-7 space-y-6 text-left">
              <h2 className="font-serif text-2xl sm:text-3xl uppercase tracking-wider text-gold-400">
                Redefining Masculinity
              </h2>

              <p className="text-gray-300 text-sm md:text-base leading-relaxed font-light">
                “{brandStory}”
              </p>

              <p className="text-gray-400 text-xs sm:text-sm leading-relaxed font-light">
                {brandDescription}
              </p>

              {/* Core Values grid */}
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                <div className="p-4 bg-salon-gray/40 border border-white/5 space-y-2">
                  <div className="flex items-center gap-2 text-gold-400">
                    <LucideIcon name="Compass" size={14} />
                    <span className="font-serif text-xs uppercase tracking-wider font-semibold">Precision Craft</span>
                  </div>
                  <p className="text-[11px] text-gray-500 lowercase leading-relaxed font-sans first-letter:uppercase">
                    Every cut is mathematically mapped to enhance facial bone structures.
                  </p>
                </div>
                <div className="p-4 bg-salon-gray/40 border border-white/5 space-y-2">
                  <div className="flex items-center gap-2 text-gold-400">
                    <LucideIcon name="Crown" size={14} />
                    <span className="font-serif text-xs uppercase tracking-wider font-semibold">Luxury Standard</span>
                  </div>
                  <p className="text-[11px] text-gray-500 lowercase leading-relaxed font-sans first-letter:uppercase">
                    High-end materials, organic tonics, and hot-towels recreate a 5-star resort environment.
                  </p>
                </div>
                <div className="p-4 bg-salon-gray/40 border border-white/5 space-y-2">
                  <div className="flex items-center gap-2 text-gold-400">
                    <LucideIcon name="Flower" size={14} />
                    <span className="font-serif text-xs uppercase tracking-wider font-semibold">Caring Spas</span>
                  </div>
                  <p className="text-[11px] text-gray-500 lowercase leading-relaxed font-sans first-letter:uppercase">
                    Premium detox formulas, massage protocols, and skin re-hydration treatments.
                  </p>
                </div>
                <div className="p-4 bg-salon-gray/40 border border-white/5 space-y-2">
                  <div className="flex items-center gap-2 text-gold-400">
                    <LucideIcon name="Shield" size={14} />
                    <span className="font-serif text-xs uppercase tracking-wider font-semibold">Sovereign Service</span>
                  </div>
                  <p className="text-[11px] text-gray-500 lowercase leading-relaxed font-sans first-letter:uppercase">
                    Strict scheduling overrides, private rooms, and VIP club memberships.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Master Stylists Portfolio */}
        <section className="space-y-12">
          <div className="text-center max-w-2xl mx-auto space-y-3">
            <span className="text-[10px] font-mono tracking-[0.3em] text-gold-400 uppercase block">
              Dhaka's Elite Craft Leaders
            </span>
            <h2 className="font-serif text-3xl sm:text-4xl uppercase tracking-wider text-white">
              Barber's Portfolio
            </h2>
            <div className="h-[1px] w-16 bg-gold-400 mx-auto my-3"></div>
            <p className="text-xs text-gray-400 leading-relaxed max-w-sm mx-auto">
              Our stylists undergo intensive training to align cuts with modern executive guidelines. Meet our masters below.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {barbers.map((barber) => (
              <div
                key={barber.id}
                className="bg-salon-gray border border-white/5 hover:border-gold-400/30 group transition-all duration-550 relative text-left overflow-hidden flex flex-col justify-between"
              >
                <div className="space-y-0">
                  {/* Portrait photo */}
                  <div className="aspect-[4/5] w-full overflow-hidden relative">
                    <img
                      src={barber.portraitUrl}
                      alt={barber.name}
                      referrerPolicy="no-referrer"
                      className="w-full h-full object-cover grayscale brightness-90 group-hover:brightness-95 group-hover:scale-[1.02] group-hover:grayscale-0 transition-all duration-700"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-salon-gray via-transparent to-transparent opacity-40"></div>
                    <div className="absolute top-3 right-3 bg-salon-black/95 px-2 py-0.5 border border-gold-400/35 text-[9px] font-mono text-gold-400 tracking-wider uppercase flex items-center gap-1">
                      <span className="text-yellow-400 text-[8px]">★</span> {barber.rating} RATED
                    </div>
                  </div>

                  {/* Description Info */}
                  <div className="p-6 space-y-3">
                    <div>
                      <h4 className="font-serif text-base uppercase tracking-wider text-white group-hover:text-gold-400 transition-colors">
                        {barber.name}
                      </h4>
                      <p className="text-[10px] font-mono tracking-widest text-gold-400 uppercase mt-0.5">
                        {barber.specialty}
                      </p>
                    </div>

                    <p className="text-[11px] text-gray-400 leading-relaxed font-sans font-light line-clamp-3">
                      "{barber.bio}"
                    </p>

                    <div className="text-[10px] text-gray-500 font-mono uppercase tracking-wider border-t border-white/5 pt-3">
                      Lead Stylist • {barber.experienceYears}+ Years Craft Experience
                    </div>
                  </div>
                </div>

                {/* Direct CTA */}
                <div className="p-6 pt-0">
                  <button
                    onClick={() => onBookBarber(barber.id)}
                    className="w-full py-3 bg-transparent border border-gold-400/20 hover:bg-[#32BBED] hover:border-[#32BBED] text-gold-400 hover:text-black font-serif text-[10px] uppercase font-bold tracking-widest transition-all duration-300 cursor-pointer text-center"
                  >
                    Book Session
                  </button>
                </div>
              </div>
            ))}
          </div>
        </section>

        {/* Back navigation */}
        <div className="pt-8 text-center">
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

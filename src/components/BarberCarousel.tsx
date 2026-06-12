import React, { useState, useEffect } from 'react';
import { Barber } from '../types';
import { LucideIcon } from './LucideIcon';

interface BarberCarouselProps {
  barbers: Barber[];
  onBookBarber: (barberId: string) => void;
}

export const BarberCarousel: React.FC<BarberCarouselProps> = ({ barbers, onBookBarber }) => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [itemsPerPage, setItemsPerPage] = useState(3);

  // Set items per page based on viewport width
  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth < 640) {
        setItemsPerPage(1); // Mobile
      } else if (window.innerWidth < 1024) {
        setItemsPerPage(2); // Tablet
      } else {
        setItemsPerPage(3); // Desktop
      }
    };

    handleResize();
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const totalSlides = Math.max(0, barbers.length - itemsPerPage + 1);

  useEffect(() => {
    setCurrentIndex((prev) => Math.min(prev, Math.max(0, totalSlides - 1)));
  }, [totalSlides]);

  // Auto play carousel
  useEffect(() => {
    if (barbers.length <= itemsPerPage) return;

    const interval = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % totalSlides);
    }, 5000);

    return () => clearInterval(interval);
  }, [totalSlides, barbers.length, itemsPerPage]);

  const handleNext = () => {
    if (barbers.length <= itemsPerPage) return;
    setCurrentIndex((prev) => (prev + 1) % totalSlides);
  };

  const handlePrev = () => {
    if (barbers.length <= itemsPerPage) return;
    setCurrentIndex((prev) => (prev - 1 + totalSlides) % totalSlides);
  };

  if (barbers.length === 0) return null;

  return (
    <div className="relative w-full max-w-6xl mx-auto px-4 md:px-12 select-none group">

      {/* Slider viewport */}
      <div className="overflow-hidden w-full py-4">
        <div
          className="flex transition-transform duration-500 ease-out gap-6"
          style={{
            transform: `translateX(-${currentIndex * (100 / itemsPerPage)}%)`,
          }}
        >
          {barbers.map((barber) => (
            <div
              key={barber.id}
              className="flex-shrink-0 bg-salon-gray border border-white/5 hover:border-gold-400/25 transition-all duration-300 relative text-left flex flex-col justify-between"
              style={{
                width: `calc(${100 / itemsPerPage}% - ${(itemsPerPage - 1) * 24 / itemsPerPage}px)`
              }}
            >
              <div>
                {/* Image */}
                <div className="aspect-[4/5] w-full overflow-hidden relative">
                  <img
                    src={barber.portraitUrl}
                    alt={barber.name}
                    referrerPolicy="no-referrer"
                    className="w-full h-full object-cover object-[50%_15%] brightness-95 hover:grayscale hover:brightness-90 transition-all duration-550"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-salon-gray via-transparent to-transparent opacity-30"></div>

                  {/* Rating Badge */}
                  <div className="absolute top-3 right-3 bg-salon-black/95 border border-gold-400/20 px-2 py-0.5 text-[9px] font-mono text-gold-400 uppercase tracking-widest flex items-center gap-1">
                    <span className="text-yellow-400 text-[8px]">★</span> {barber.rating}
                  </div>
                </div>

                {/* Content */}
                <div className="p-5 space-y-2">
                  <span className="text-[8px] font-mono text-gold-400 uppercase tracking-[0.2em] font-semibold block">Lead Master Barber</span>
                  <h4 className="font-serif text-sm uppercase tracking-wider text-white group-hover:text-gold-400 transition-colors">
                    {barber.name}
                  </h4>
                  <p className="text-[10px] font-mono tracking-widest text-gray-400 uppercase">
                    {barber.specialty}
                  </p>
                  <p className="text-[10px] text-gray-500 block">
                    Grooming Specialist • {barber.experienceYears}+ Years Craft Experience
                  </p>
                </div>
              </div>

              {/* Book Button */}
              <div className="p-5 pt-0">
                <button
                  onClick={() => onBookBarber(barber.id)}
                  className="w-full py-2.5 bg-[#32BBED] hover:bg-[#b08d3c] text-black hover:text-black font-serif text-[10px] font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer text-center"
                >
                  Book Session
                </button>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Navigation Arrows (visible if content overflows) */}
      {barbers.length > itemsPerPage && (
        <>
          <button
            onClick={handlePrev}
            className="absolute left-[-10px] md:left-2 top-1/2 -translate-y-1/2 h-10 w-10 bg-salon-black border border-white/10 hover:border-gold-400 hover:text-gold-400 flex items-center justify-center text-white transition-all cursor-pointer rounded-none z-10"
            aria-label="Previous Slide"
          >
            <LucideIcon name="ChevronLeft" size={20} />
          </button>
          <button
            onClick={handleNext}
            className="absolute right-[-10px] md:right-2 top-1/2 -translate-y-1/2 h-10 w-10 bg-salon-black border border-white/10 hover:border-gold-400 hover:text-gold-400 flex items-center justify-center text-white transition-all cursor-pointer rounded-none z-10"
            aria-label="Next Slide"
          >
            <LucideIcon name="ChevronRight" size={20} />
          </button>
        </>
      )}

      {/* Dot Indicators */}
      {totalSlides > 1 && (
        <div className="flex justify-center gap-1.5 mt-4">
          {Array.from({ length: totalSlides }).map((_, idx) => (
            <button
              key={idx}
              onClick={() => setCurrentIndex(idx)}
              className={`h-1.5 transition-all duration-350 ${currentIndex === idx ? 'w-6 bg-gold-400' : 'w-1.5 bg-white/20 hover:bg-white/40'
                }`}
              aria-label={`Slide ${idx + 1}`}
            ></button>
          ))}
        </div>
      )}

    </div>
  );
};

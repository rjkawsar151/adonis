import React, { useState, useEffect } from 'react';
import { Testimonial } from '../types';
import { LucideIcon } from './LucideIcon';

interface ReviewCarouselProps {
  testimonials: Testimonial[];
}

export const ReviewCarousel: React.FC<ReviewCarouselProps> = ({ testimonials }) => {
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

  const totalSlides = Math.max(0, testimonials.length - itemsPerPage + 1);

  useEffect(() => {
    setCurrentIndex((prev) => Math.min(prev, Math.max(0, totalSlides - 1)));
  }, [totalSlides]);

  // Auto play carousel
  useEffect(() => {
    if (testimonials.length <= itemsPerPage) return;

    const interval = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % totalSlides);
    }, 5000);

    return () => clearInterval(interval);
  }, [totalSlides, testimonials.length, itemsPerPage]);

  const handleNext = () => {
    if (testimonials.length <= itemsPerPage) return;
    setCurrentIndex((prev) => (prev + 1) % totalSlides);
  };

  const handlePrev = () => {
    if (testimonials.length <= itemsPerPage) return;
    setCurrentIndex((prev) => (prev - 1 + totalSlides) % totalSlides);
  };

  if (testimonials.length === 0) return null;

  return (
    <div className="relative w-full max-w-6xl mx-auto px-4 md:px-12 select-none group">
      {/* Slider viewport */}
      <div className="overflow-hidden w-full py-4">
        <div
          className="flex transition-transform duration-500 ease-out gap-6"
          style={{
            transform: `translateX(calc(-${currentIndex} * (100% + 24px) / ${itemsPerPage}))`,
          }}
        >
          {testimonials.map((t) => (
            <div
              key={t.id}
              className="flex-shrink-0 bg-salon-black border border-white/5 p-8 relative flex flex-col justify-between h-[230px] hover:border-gold-400/25 transition-all duration-300"
              style={{
                width: `calc(${100 / itemsPerPage}% - ${(itemsPerPage - 1) * 24 / itemsPerPage}px)`
              }}
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

      {/* Navigation Arrows */}
      {testimonials.length > itemsPerPage && (
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

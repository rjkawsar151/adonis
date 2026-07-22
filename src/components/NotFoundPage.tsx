import React from 'react';
import { navigateTo } from '../navigation';
import { LucideIcon } from './LucideIcon';

export const NotFoundPage: React.FC = () => {
  return (
    <div className="w-full bg-salon-black min-h-screen text-white pt-32 pb-24 flex items-center justify-center relative overflow-hidden">
      {/* Premium ambient glows */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-[600px] w-[600px] rounded-full bg-gold-400/5 blur-[150px] pointer-events-none"></div>

      <div className="max-w-md w-full mx-auto px-6 text-center relative z-10 space-y-8">
        {/* Large stylized 404 */}
        <div className="relative inline-block select-none">
          <h1 className="font-serif text-[100px] sm:text-[130px] leading-none uppercase tracking-widest text-transparent bg-clip-text bg-gradient-to-b from-white via-white/80 to-white/10 opacity-80">
            404
          </h1>
          <div className="absolute inset-0 flex items-center justify-center">
            <span className="text-xs font-mono uppercase tracking-[0.3em] text-[#C9A84C] bg-salon-black/95 px-4 py-1 border border-white/5 gold-glow mt-8">
              Trimmed Route
            </span>
          </div>
        </div>

        {/* Content details */}
        <div className="space-y-3">
          <h2 className="font-serif text-lg sm:text-xl uppercase tracking-wider text-white">
            Beyond the Grooming Limit
          </h2>
          <p className="text-[11px] sm:text-xs text-gray-400 leading-relaxed font-light max-w-sm mx-auto">
            The path you followed is out of style, or has been cleanly shaved away. Let us guide you back to the sanctuary of Adonis lounges.
          </p>
        </div>

        {/* Action Controls */}
        <div className="flex flex-col sm:flex-row gap-3 justify-center pt-4">
          <button
            onClick={() => navigateTo('/')}
            className="px-6 py-3 bg-[#32BBED] hover:bg-[#b08d3c] text-black font-serif text-[10px] font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer text-center"
          >
            Lounge Home
          </button>
          
          <button
            onClick={() => navigateTo('/book')}
            className="px-6 py-3 bg-salon-gray hover:bg-white/10 border border-white/10 text-white font-serif text-[10px] font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer text-center"
          >
            Book Appointment
          </button>
        </div>
      </div>
    </div>
  );
};

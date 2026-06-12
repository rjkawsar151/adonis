import React, { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { SERVICES, BARBERS } from '../data';
import { LucideIcon } from './LucideIcon';

interface StyleProfilerProps {
  onSelectRecommendedService: (serviceId: string) => void;
}

export const StyleProfiler: React.FC<StyleProfilerProps> = ({ onSelectRecommendedService }) => {
  const [step, setStep] = useState<number>(0);
  const [faceShape, setFaceShape] = useState<string>('');
  const [hairLength, setHairLength] = useState<string>('');
  const [stylingGoal, setStylingGoal] = useState<string>('');
  const [result, setResult] = useState<any | null>(null);

  const faceShapes = [
    { id: 'oval', label: 'Oval Face', desc: 'Balanced proportions, uniform details', icon: 'Smile' },
    { id: 'square', label: 'Square Face', desc: 'Strong jawline, sharp structural corners', icon: 'ShieldAlert' },
    { id: 'round', label: 'Round Face', desc: 'Soft features, equal height and width', icon: 'Sparkles' },
    { id: 'diamond', label: 'Diamond Face', desc: 'Wide cheekbones, narrow chin line', icon: 'Crown' }
  ];

  const lengths = [
    { id: 'short', label: 'Short Fade / Crop', desc: 'Buzz cuts, high fades, textured crops' },
    { id: 'medium', label: 'Medium Length', desc: 'Classic pompadours, side parts, slick-backs' },
    { id: 'long', label: 'Long Locks / Volume', desc: 'Long flow, bun length, textured waves' }
  ];

  const goals = [
    { id: 'sharp', label: 'Sharp Executive Identity', desc: 'A sharp, zero skin-fade modern style' },
    { id: 'classic', label: 'Classic Elegance', desc: 'Timeless scissor-cut contours & premium hold' },
    { id: 'detox', label: 'Complete Scalp & Face Detox', desc: 'Rejuvenating herbal spa & charcoal vacuum skin lift' },
    { id: 'royal', label: 'Royal Shave & Sculpting', desc: 'Straight razor hot towel clean-cut master detailing' }
  ];

  const calculateRecommendation = () => {
    let recommendedServiceId = 'precision-haircut';
    let explanation = '';
    let barberId = 'tariq';

    if (stylingGoal === 'sharp') {
      recommendedServiceId = 'skin-fade';
      explanation = 'Your hard jawline benefits from a high-contrast skin fade. The zero-blend sides create vertical depth to emphasize natural bone-structure symmetries.';
      barberId = 'tariq';
    } else if (stylingGoal === 'classic') {
      recommendedServiceId = 'precision-haircut';
      explanation = 'To preserve your elegant, balanced bone structures, a customized manual scissors-only contour styling is suggested. This creates soft modern styling.';
      barberId = 'arif';
    } else if (stylingGoal === 'detox') {
      recommendedServiceId = 'hair-spa';
      explanation = 'Deep conditioning steam therapy combined with our signature scalp spa targets polluted pores, reinforcing root architecture.';
      barberId = 'arif';
    } else if (stylingGoal === 'royal') {
      recommendedServiceId = 'vip-grooming-package';
      explanation = 'The full royal transformation: a symmetrical straight-razor clean finish paired with an executive contour sculpt.';
      barberId = 'kamran';
    } else {
      if (faceShape === 'round') {
        recommendedServiceId = 'skin-fade';
        explanation = 'A structured tight shadow skin fade will offset facial roundness, generating length and structural depth above the forehead.';
        barberId = 'tariq';
      } else {
        recommendedServiceId = 'precision-haircut';
        explanation = 'The quintessential classic cut tailored exactly to your hair flow, prioritizing timeless masculine elegance.';
        barberId = 'arif';
      }
    }

    const service = SERVICES.find(s => s.id === recommendedServiceId) || SERVICES[0];
    const barber = BARBERS.find(b => b.id === barberId) || BARBERS[0];

    setResult({
      service,
      explanation,
      barber
    });
  };

  const resetProfiler = () => {
    setStep(0);
    setFaceShape('');
    setHairLength('');
    setStylingGoal('');
    setResult(null);
  };

  return (
    <div className="w-full max-w-2xl mx-auto bg-salon-card border border-gold-400/20 p-6 md:p-8 relative">
      <div className="absolute top-0 right-0 py-1.5 px-3 bg-gold-400/10 border-l border-b border-gold-400/20 font-mono text-[9px] uppercase tracking-widest text-gold-400">
        AI Identity Matcher
      </div>

      <div className="mb-6">
        <h4 className="font-serif text-base md:text-lg uppercase tracking-wider text-white">Grooming Profile Architect</h4>
        <p className="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Discover customized styling for your anatomical features</p>
      </div>

      <AnimatePresence mode="wait">
        {step === 0 && (
          <motion.div
            key="step0"
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -10 }}
            className="space-y-4"
          >
            <p className="text-xs text-gray-300 leading-relaxed font-sans">
              Welcome to the Adonis elite profiling system. Our diagnostic engine maps your facial bone-shape, current length, and professional standards to suggest the ideal master service.
            </p>
            <button
              onClick={() => setStep(1)}
              className="px-6 py-2.5 bg-transparent border border-[#32BBED] hover:bg-[#32BBED] hover:text-black text-[#32BBED] font-serif text-xs uppercase tracking-widest transition-all duration-300 cursor-pointer"
            >
              Begin Diagnosis
            </button>
          </motion.div>
        )}

        {step === 1 && (
          <motion.div
            key="step1"
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -10 }}
            className="space-y-4"
          >
            <span className="text-[10px] font-mono tracking-widest uppercase text-gold-400">Section 1 / 3</span>
            <h5 className="font-serif text-sm tracking-widest uppercase text-white mb-4">Select Your Faceline Shape</h5>
            <div className="grid grid-cols-2 gap-3">
              {faceShapes.map(fs => (
                <button
                  key={fs.id}
                  onClick={() => { setFaceShape(fs.id); setStep(2); }}
                  className="p-4 text-left border border-white/5 bg-salon-black hover:border-gold-400/40 transition-colors cursor-pointer group"
                >
                  <div className="flex items-center gap-2">
                    <LucideIcon name={fs.icon} className="text-gold-400/60 group-hover:text-gold-400 transition-colors" size={16} />
                    <span className="text-xs font-serif uppercase tracking-wide text-white font-medium">{fs.label}</span>
                  </div>
                  <p className="text-[10px] text-gray-500 mt-1">{fs.desc}</p>
                </button>
              ))}
            </div>
          </motion.div>
        )}

        {step === 2 && (
          <motion.div
            key="step2"
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -10 }}
            className="space-y-4"
          >
            <span className="text-[10px] font-mono tracking-widest uppercase text-gold-400">Section 2 / 3</span>
            <h5 className="font-serif text-sm tracking-widest uppercase text-white mb-4">Choose Target Hair Length</h5>
            <div className="space-y-2.5">
              {lengths.map(lg => (
                <button
                  key={lg.id}
                  onClick={() => { setHairLength(lg.id); setStep(3); }}
                  className="w-full p-3.5 text-left border border-white/5 bg-salon-black hover:border-gold-400/40 transition-all flex items-center justify-between cursor-pointer group"
                >
                  <div>
                    <span className="text-xs font-serif uppercase text-white font-medium block">{lg.label}</span>
                    <span className="text-[10px] text-gray-500 mt-0.5 block">{lg.desc}</span>
                  </div>
                  <LucideIcon name="ChevronRight" className="text-gold-400 opacity-30 group-hover:opacity-100 group-hover:translate-x-1 transition-all" size={16} />
                </button>
              ))}
            </div>
            <button onClick={() => setStep(1)} className="text-[10px] uppercase font-mono tracking-widest text-gray-400 hover:text-white mt-4 block">
              ← Back to face shapes
            </button>
          </motion.div>
        )}

        {step === 3 && (
          <motion.div
            key="step3"
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -10 }}
            className="space-y-4"
          >
            <span className="text-[10px] font-mono tracking-widest uppercase text-gold-400">Section 3 / 3</span>
            <h5 className="font-serif text-sm tracking-widest uppercase text-white mb-4">Primary Identity Treatment Goal</h5>
            <div className="space-y-2.5">
              {goals.map(gl => (
                <button
                  key={gl.id}
                  onClick={() => {
                    setStylingGoal(gl.id);
                    setStep(4);
                    // We generate recommendations on next step
                  }}
                  className="w-full p-3.5 text-left border border-white/5 bg-salon-black hover:border-gold-400/40 transition-all flex items-center justify-between cursor-pointer group"
                >
                  <div>
                    <span className="text-xs font-serif uppercase text-white font-medium block">{gl.label}</span>
                    <span className="text-[10px] text-gray-500 mt-0.5 block">{gl.desc}</span>
                  </div>
                  <LucideIcon name="ChevronRight" className="text-gold-400 opacity-30 group-hover:opacity-100 group-hover:translate-x-1 transition-all" size={16} />
                </button>
              ))}
            </div>
            <button onClick={() => setStep(2)} className="text-[10px] uppercase font-mono tracking-widest text-gray-400 hover:text-white mt-4 block">
              ← Back to lengths
            </button>
          </motion.div>
        )}

        {step === 4 && (
          <motion.div
            key="step4"
            initial={{ opacity: 0, scale: 0.95 }}
            animate={{ opacity: 1, scale: 1 }}
            onAnimationComplete={calculateRecommendation}
            className="text-center py-6"
          >
            <LucideIcon name="Sparkles" className="text-gold-400 animate-spin mx-auto mb-3" size={32} />
            <h5 className="font-serif text-sm uppercase tracking-widest text-white">Anatomical Profile Analysis in Progress</h5>
            <p className="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Sizing bone symmetries & styling goals for Dhaka seasons...</p>
          </motion.div>
        )}

        {step === 4 && result && (
          <motion.div
            key="result"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="space-y-4"
          >
            <span className="text-[10px] font-mono tracking-widest uppercase text-gold-400">Diagnosis Formulated</span>
            <h5 className="font-serif text-sm tracking-widest uppercase text-white">Recommended Styling Blueprint</h5>

            {/* Recommended Service Box */}
            <div className="p-4 bg-salon-black border border-gold-400/35 relative">
              <div className="flex md:items-center justify-between flex-col md:flex-row gap-3">
                <div className="flex items-center gap-3">
                  <div className="p-2.5 bg-gold-400 text-salon-black">
                    <LucideIcon name={result.service.icon} size={18} />
                  </div>
                  <div>
                    <h6 className="font-serif text-xs uppercase tracking-wider text-white font-medium">{result.service.name}</h6>
                    <span className="text-[10px] text-gray-400 font-sans">{result.service.durationMin} minutes</span>
                  </div>
                </div>
                <div className="text-right">
                  <span className="text-sm font-serif text-gold-400 font-bold block">৳{result.service.priceBDT}</span>
                </div>
              </div>
              <p className="text-[11px] text-gray-400 mt-3 italic font-sans border-l-2 border-gold-400/55 pl-3 leading-relaxed">
                {result.explanation}
              </p>
            </div>

            {/* Recommended Stylist Row */}
            <div className="flex items-center justify-between p-3.5 bg-salon-black/45 border border-white/5">
              <div className="flex items-center gap-3">
                <img
                  src={result.barber.portraitUrl}
                  alt={result.barber.name}
                  referrerPolicy="no-referrer"
                  className="w-10 h-10 rounded-full object-cover grayscale border border-gold-400/20"
                />
                <div>
                  <span className="text-[9px] text-gold-400 uppercase font-mono tracking-widest block">Recommended Stylist</span>
                  <span className="font-serif text-xs text-white uppercase mt-0.5 block">{result.barber.name}</span>
                </div>
              </div>
              <span className="text-[10px] text-gray-400 font-mono italic">★ {result.barber.rating}</span>
            </div>

            {/* CTAs */}
            <div className="grid grid-cols-2 gap-3 pt-2">
              <button
                onClick={() => {
                  onSelectRecommendedService(result.service.id);
                  // scroll to booking form beautifully
                  const element = document.getElementById('booking-section');
                  if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                  }
                }}
                className="py-3 bg-[#32BBED] hover:bg-[#b08d3c] text-black font-serif text-xs font-bold uppercase tracking-widest transition-all cursor-pointer"
              >
                Auto-Fill Booking Form
              </button>
              <button
                onClick={resetProfiler}
                className="py-3 bg-transparent border border-white/15 text-white hover:border-white/40 font-serif text-xs uppercase tracking-widest transition-all cursor-pointer"
              >
                Re-Diagnose Faceline
              </button>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
};

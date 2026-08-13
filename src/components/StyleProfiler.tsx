import React, { useState, useEffect, useRef } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { SERVICES } from '../data';
import { Barber } from '../types';
import { LucideIcon } from './LucideIcon';

interface StyleProfilerProps {
  onSelectRecommendedService: (serviceId: string) => void;
  barbers: Barber[];
}

/* ── Typewriter helper ── */
const Typewriter: React.FC<{ text: string; speed?: number; className?: string }> = ({
  text, speed = 28, className = ''
}) => {
  const [displayed, setDisplayed] = useState('');
  const [done, setDone] = useState(false);
  useEffect(() => {
    setDisplayed('');
    setDone(false);
    let i = 0;
    const t = setInterval(() => {
      i++;
      setDisplayed(text.slice(0, i));
      if (i >= text.length) { clearInterval(t); setDone(true); }
    }, speed);
    return () => clearInterval(t);
  }, [text, speed]);
  return (
    <span className={className}>
      {displayed}
      {!done && <span className="inline-block w-[2px] h-[1em] bg-[#32BBED] animate-pulse align-middle ml-[1px]" />}
    </span>
  );
};

/* ── Scanning bar that sweeps once ── */
const ScanLine: React.FC = () => (
  <motion.div
    className="absolute left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-[#32BBED]/70 to-transparent pointer-events-none z-10"
    initial={{ top: '0%', opacity: 0 }}
    animate={{ top: ['0%', '100%'], opacity: [0, 1, 1, 0] }}
    transition={{ duration: 1.6, ease: 'linear', times: [0, 0.05, 0.9, 1] }}
  />
);

/* ── Pulsing dot indicator ── */
const PulseDot: React.FC<{ color?: string }> = ({ color = '#32BBED' }) => (
  <span className="relative inline-flex h-2 w-2 mr-2">
    <span
      className="animate-ping absolute inline-flex h-full w-full rounded-full opacity-60"
      style={{ backgroundColor: color }}
    />
    <span className="relative inline-flex rounded-full h-2 w-2" style={{ backgroundColor: color }} />
  </span>
);

/* ── Neural grid background ── */
const NeuralGrid: React.FC = () => (
  <div className="absolute inset-0 overflow-hidden pointer-events-none opacity-[0.04]">
    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <pattern id="sg-grid" width="24" height="24" patternUnits="userSpaceOnUse">
          <path d="M 24 0 L 0 0 0 24" fill="none" stroke="#32BBED" strokeWidth="0.5" />
        </pattern>
      </defs>
      <rect width="100%" height="100%" fill="url(#sg-grid)" />
    </svg>
  </div>
);

/* ── Step progress bar ── */
const StepBar: React.FC<{ current: number; total: number }> = ({ current, total }) => (
  <div className="flex gap-1 mb-5">
    {Array.from({ length: total }).map((_, i) => (
      <motion.div
        key={i}
        className="h-[2px] flex-1 rounded-full overflow-hidden bg-white/10"
        initial={false}
      >
        <motion.div
          className="h-full"
          style={{ background: i < current ? '#32BBED' : i === current ? '#C9A84C' : 'transparent' }}
          initial={{ scaleX: 0, originX: 0 }}
          animate={{ scaleX: i <= current ? 1 : 0 }}
          transition={{ duration: 0.4, ease: 'easeOut' }}
        />
      </motion.div>
    ))}
  </div>
);

export const StyleProfiler: React.FC<StyleProfilerProps> = ({ onSelectRecommendedService, barbers }) => {
  const [step, setStep] = useState<number>(0);
  const [faceShape, setFaceShape] = useState<string>('');
  const [hairLength, setHairLength] = useState<string>('');
  const [stylingGoal, setStylingGoal] = useState<string>('');
  const [result, setResult] = useState<any | null>(null);
  const [scanning, setScanning] = useState(false);
  const [scanKey, setScanKey] = useState(0);
  const containerRef = useRef<HTMLDivElement>(null);

  const faceShapes = [
    { id: 'oval',    label: 'Oval Face',    desc: 'Balanced proportions, uniform details',       icon: 'Smile' },
    { id: 'square',  label: 'Square Face',  desc: 'Strong jawline, sharp structural corners',    icon: 'ShieldAlert' },
    { id: 'round',   label: 'Round Face',   desc: 'Soft features, equal height and width',       icon: 'Sparkles' },
    { id: 'diamond', label: 'Diamond Face', desc: 'Wide cheekbones, narrow chin line',           icon: 'Crown' }
  ];

  const lengths = [
    { id: 'short',  label: 'Short Fade / Crop',      desc: 'Buzz cuts, high fades, textured crops' },
    { id: 'medium', label: 'Medium Length',           desc: 'Classic pompadours, side parts, slick-backs' },
    { id: 'long',   label: 'Long Locks / Volume',     desc: 'Long flow, bun length, textured waves' }
  ];

  const goals = [
    { id: 'sharp',   label: 'Sharp Executive Identity',      desc: 'A sharp, zero skin-fade modern style',                  icon: 'Zap' },
    { id: 'classic', label: 'Classic Elegance',              desc: 'Timeless scissor-cut contours & premium hold',           icon: 'Award' },
    { id: 'detox',   label: 'Complete Scalp & Face Detox',   desc: 'Rejuvenating herbal spa & charcoal vacuum skin lift',    icon: 'Droplets' },
    { id: 'royal',   label: 'Royal Shave & Sculpting',       desc: 'Straight razor hot towel clean-cut master detailing',   icon: 'Crown' }
  ];

  const triggerScan = (nextStep: () => void) => {
    setScanning(true);
    setScanKey(k => k + 1);
    setTimeout(() => { setScanning(false); nextStep(); }, 700);
  };

  const calculateRecommendation = () => {
    let recommendedServiceId = 'precision-haircut';
    let explanation = '';

    if (stylingGoal === 'sharp') {
      recommendedServiceId = 'skin-fade';
      explanation = 'Your hard jawline benefits from a high-contrast skin fade. The zero-blend sides create vertical depth to emphasize natural bone-structure symmetries.';
    } else if (stylingGoal === 'classic') {
      recommendedServiceId = 'precision-haircut';
      explanation = 'To preserve your elegant, balanced bone structures, a customized manual scissors-only contour styling is suggested. This creates soft modern styling.';
    } else if (stylingGoal === 'detox') {
      recommendedServiceId = 'hair-spa';
      explanation = 'Deep conditioning steam therapy combined with our signature scalp spa targets polluted pores, reinforcing root architecture.';
    } else if (stylingGoal === 'royal') {
      recommendedServiceId = 'vip-grooming-package';
      explanation = 'The full royal transformation: a symmetrical straight-razor clean finish paired with an executive contour sculpt.';
    } else {
      if (faceShape === 'round') {
        recommendedServiceId = 'skin-fade';
        explanation = 'A structured tight shadow skin fade will offset facial roundness, generating length and structural depth above the forehead.';
      } else {
        recommendedServiceId = 'precision-haircut';
        explanation = 'The quintessential classic cut tailored exactly to your hair flow, prioritizing timeless masculine elegance.';
      }
    }

    const service = SERVICES.find(s => s.id === recommendedServiceId) || SERVICES[0];
    // Pick a random barber from the live barbers list
    const pool = barbers.length > 0 ? barbers : [];
    const barber = pool.length > 0
      ? pool[Math.floor(Math.random() * pool.length)]
      : null;
    setResult({ service, explanation, barber });
  };

  const resetProfiler = () => {
    setStep(0); setFaceShape(''); setHairLength('');
    setStylingGoal(''); setResult(null);
  };

  /* shared motion config */
  const slideIn  = { initial: { opacity: 0, y: 14 }, animate: { opacity: 1, y: 0 }, exit: { opacity: 0, y: -14 } };
  const duration = { transition: { duration: 0.35, ease: [0.22, 1, 0.36, 1] as any } };

  return (
    <div
      ref={containerRef}
      className="w-full max-w-2xl mx-auto relative overflow-hidden"
      style={{
        background: 'linear-gradient(135deg, #0a0a0a 0%, #0d0d10 60%, #070b0f 100%)',
        border: '1px solid rgba(50,187,237,0.18)',
        boxShadow: '0 0 40px rgba(50,187,237,0.06), inset 0 0 60px rgba(0,0,0,0.4)'
      }}
    >
      {/* Neural grid bg */}
      <NeuralGrid />

      {/* Corner accent glows */}
      <div className="absolute top-0 left-0 w-24 h-24 pointer-events-none"
        style={{ background: 'radial-gradient(circle at 0% 0%, rgba(50,187,237,0.12) 0%, transparent 70%)' }} />
      <div className="absolute bottom-0 right-0 w-24 h-24 pointer-events-none"
        style={{ background: 'radial-gradient(circle at 100% 100%, rgba(201,168,76,0.10) 0%, transparent 70%)' }} />

      {/* Scan line (fires on each step transition) */}
      {scanning && <ScanLine key={scanKey} />}

      {/* ── HEADER ── */}
      <div className="relative px-6 pt-5 pb-4 border-b border-white/[0.05]">
        {/* Top-right badge */}
        <div className="absolute top-0 right-0 flex items-center gap-1.5 px-3 py-1.5 text-[8px] font-mono uppercase tracking-[0.2em]"
          style={{ background: 'rgba(50,187,237,0.08)', borderLeft: '1px solid rgba(50,187,237,0.2)', borderBottom: '1px solid rgba(50,187,237,0.2)', color: '#32BBED' }}>
          <PulseDot color="#32BBED" />
          AI Engine · Active
        </div>

        {/* Title row */}
        <div className="flex items-start gap-3 pt-1">
          <div className="mt-0.5 p-2 flex-shrink-0"
            style={{ background: 'rgba(50,187,237,0.1)', border: '1px solid rgba(50,187,237,0.25)' }}>
            <LucideIcon name="Cpu" size={18} className="text-[#32BBED]" />
          </div>
          <div>
            <h4 className="font-serif text-base md:text-lg uppercase tracking-wider text-white leading-tight">
              Grooming Profile Architect
            </h4>
            <p className="text-[9px] font-mono tracking-[0.18em] mt-0.5"
              style={{ color: 'rgba(50,187,237,0.6)' }}>
              Neural Facial Mapping · Precision Style Diagnostics
            </p>
          </div>
        </div>

        {/* Step bar — visible from step 1 onwards */}
        {step >= 1 && step <= 4 && (
          <motion.div className="mt-4" initial={{ opacity: 0 }} animate={{ opacity: 1 }}>
            <StepBar current={step - 1} total={3} />
            <div className="flex justify-between text-[8px] font-mono tracking-widest -mt-0.5"
              style={{ color: 'rgba(255,255,255,0.25)' }}>
              <span>FACELINE</span><span>LENGTH</span><span>GOAL</span>
            </div>
          </motion.div>
        )}
      </div>

      {/* ── CONTENT ── */}
      <div className="p-6 md:p-8">
        <AnimatePresence mode="wait">

          {/* STEP 0 — Welcome */}
          {step === 0 && (
            <motion.div key="step0" {...slideIn} {...duration} className="space-y-6">
              {/* Decorative neural ring */}
              <div className="flex items-center justify-center">
                <div className="relative w-20 h-20">
                  <motion.div
                    className="absolute inset-0 rounded-full border-2"
                    style={{ borderColor: 'rgba(50,187,237,0.3)' }}
                    animate={{ scale: [1, 1.12, 1], opacity: [0.4, 0.8, 0.4] }}
                    transition={{ duration: 2.5, repeat: Infinity, ease: 'easeInOut' }}
                  />
                  <motion.div
                    className="absolute inset-2 rounded-full border"
                    style={{ borderColor: 'rgba(201,168,76,0.25)' }}
                    animate={{ scale: [1.05, 0.95, 1.05], opacity: [0.3, 0.7, 0.3] }}
                    transition={{ duration: 2.5, repeat: Infinity, ease: 'easeInOut', delay: 0.4 }}
                  />
                  <div className="absolute inset-0 flex items-center justify-center">
                    <LucideIcon name="ScanFace" size={28} className="text-[#32BBED]" />
                  </div>
                </div>
              </div>

              <div className="text-center space-y-2">
                <p className="text-xs text-gray-300 leading-relaxed font-sans max-w-sm mx-auto">
                  Our AI diagnostic engine maps your facial bone-structure, current hair length, and styling objectives to engineer the ideal grooming treatment — personalised to your anatomy.
                </p>
                <p className="text-[9px] font-mono tracking-widest" style={{ color: 'rgba(50,187,237,0.5)' }}>
                  3-STEP · 60-SECOND PROFILE SCAN
                </p>
              </div>

              {/* Capability chips */}
              <div className="flex flex-wrap gap-2 justify-center">
                {['Facial Geometry', 'Hair Texture Map', 'Style DNA', 'Expert Match'].map(chip => (
                  <span key={chip} className="px-2.5 py-1 text-[8px] font-mono uppercase tracking-wider border"
                    style={{ background: 'rgba(50,187,237,0.06)', borderColor: 'rgba(50,187,237,0.18)', color: 'rgba(50,187,237,0.7)' }}>
                    {chip}
                  </span>
                ))}
              </div>

              <div className="flex justify-center">
                <button
                  onClick={() => { triggerScan(() => setStep(1)); }}
                  className="relative group px-8 py-3 font-serif text-xs uppercase tracking-widest overflow-hidden transition-all duration-300 cursor-pointer"
                  style={{ background: 'rgba(50,187,237,0.1)', border: '1px solid rgba(50,187,237,0.4)', color: '#32BBED' }}
                >
                  <span className="relative z-10 flex items-center gap-2">
                    <LucideIcon name="Scan" size={14} />
                    Initiate AI Scan
                  </span>
                  <motion.div
                    className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                    style={{ background: 'rgba(50,187,237,0.15)' }}
                  />
                </button>
              </div>
            </motion.div>
          )}

          {/* STEP 1 — Face Shape */}
          {step === 1 && (
            <motion.div key="step1" {...slideIn} {...duration} className="space-y-4">
              <div className="flex items-center gap-2 mb-1">
                <PulseDot color="#C9A84C" />
                <span className="text-[9px] font-mono tracking-[0.2em] uppercase" style={{ color: 'rgba(201,168,76,0.9)' }}>
                  Module 01 · Facial Geometry
                </span>
              </div>
              <h5 className="font-serif text-sm tracking-widest uppercase text-white">
                <Typewriter text="Identify Your Facial Architecture" speed={30} />
              </h5>

              <div className="grid grid-cols-2 gap-2.5 pt-1">
                {faceShapes.map(fs => (
                  <motion.button
                    key={fs.id}
                    whileHover={{ scale: 1.02 }}
                    whileTap={{ scale: 0.98 }}
                    onClick={() => { setFaceShape(fs.id); triggerScan(() => setStep(2)); }}
                    className="p-4 text-left relative overflow-hidden group cursor-pointer"
                    style={{ background: 'rgba(255,255,255,0.02)', border: '1px solid rgba(255,255,255,0.07)' }}
                  >
                    {/* hover glow */}
                    <motion.div className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                      style={{ background: 'rgba(50,187,237,0.06)', borderColor: 'rgba(50,187,237,0.3)' }} />
                    <div className="relative">
                      <div className="flex items-center gap-2 mb-1.5">
                        <div className="p-1.5" style={{ background: 'rgba(50,187,237,0.1)', border: '1px solid rgba(50,187,237,0.2)' }}>
                          <LucideIcon name={fs.icon} size={12} className="text-[#32BBED]" />
                        </div>
                        <span className="text-[10px] font-serif uppercase tracking-wide text-white font-medium group-hover:text-[#32BBED] transition-colors">
                          {fs.label}
                        </span>
                      </div>
                      <p className="text-[9px] text-gray-500 leading-relaxed">{fs.desc}</p>
                    </div>
                    {/* selected indicator corner */}
                    <div className="absolute top-0 right-0 w-0 h-0 opacity-0 group-hover:opacity-100 transition-opacity"
                      style={{ borderTop: '16px solid rgba(50,187,237,0.4)', borderLeft: '16px solid transparent' }} />
                  </motion.button>
                ))}
              </div>
            </motion.div>
          )}

          {/* STEP 2 — Hair Length */}
          {step === 2 && (
            <motion.div key="step2" {...slideIn} {...duration} className="space-y-4">
              <div className="flex items-center gap-2 mb-1">
                <PulseDot color="#C9A84C" />
                <span className="text-[9px] font-mono tracking-[0.2em] uppercase" style={{ color: 'rgba(201,168,76,0.9)' }}>
                  Module 02 · Hair Texture Map
                </span>
              </div>
              <h5 className="font-serif text-sm tracking-widest uppercase text-white">
                <Typewriter text="Select Target Hair Length Profile" speed={30} />
              </h5>

              <div className="space-y-2 pt-1">
                {lengths.map((lg, i) => (
                  <motion.button
                    key={lg.id}
                    initial={{ opacity: 0, x: -10 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: i * 0.08 }}
                    whileHover={{ x: 4 }}
                    onClick={() => { setHairLength(lg.id); triggerScan(() => setStep(3)); }}
                    className="w-full p-3.5 text-left relative overflow-hidden flex items-center justify-between group cursor-pointer"
                    style={{ background: 'rgba(255,255,255,0.02)', border: '1px solid rgba(255,255,255,0.07)' }}
                  >
                    <motion.div className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                      style={{ background: 'rgba(50,187,237,0.05)' }} />
                    <div className="relative">
                      <span className="text-xs font-serif uppercase text-white font-medium group-hover:text-[#32BBED] transition-colors block">{lg.label}</span>
                      <span className="text-[9px] text-gray-500 mt-0.5 block">{lg.desc}</span>
                    </div>
                    <div className="relative flex items-center gap-1.5 flex-shrink-0">
                      <span className="text-[8px] font-mono tracking-widest opacity-0 group-hover:opacity-100 transition-opacity" style={{ color: '#32BBED' }}>SELECT</span>
                      <LucideIcon name="ChevronRight" className="text-[#32BBED] opacity-20 group-hover:opacity-100 transition-all" size={14} />
                    </div>
                  </motion.button>
                ))}
              </div>

              <button onClick={() => setStep(1)}
                className="text-[9px] uppercase font-mono tracking-widest text-gray-500 hover:text-[#32BBED] transition-colors flex items-center gap-1 cursor-pointer pt-1">
                <LucideIcon name="ArrowLeft" size={10} /> Back
              </button>
            </motion.div>
          )}

          {/* STEP 3 — Goal */}
          {step === 3 && (
            <motion.div key="step3" {...slideIn} {...duration} className="space-y-4">
              <div className="flex items-center gap-2 mb-1">
                <PulseDot color="#C9A84C" />
                <span className="text-[9px] font-mono tracking-[0.2em] uppercase" style={{ color: 'rgba(201,168,76,0.9)' }}>
                  Module 03 · Style DNA
                </span>
              </div>
              <h5 className="font-serif text-sm tracking-widest uppercase text-white">
                <Typewriter text="Define Your Primary Identity Goal" speed={30} />
              </h5>

              <div className="space-y-2 pt-1">
                {goals.map((gl, i) => (
                  <motion.button
                    key={gl.id}
                    initial={{ opacity: 0, x: -10 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: i * 0.07 }}
                    whileHover={{ x: 4 }}
                    onClick={() => { setStylingGoal(gl.id); setStep(4); }}
                    className="w-full p-3.5 text-left relative overflow-hidden flex items-center gap-3 group cursor-pointer"
                    style={{ background: 'rgba(255,255,255,0.02)', border: '1px solid rgba(255,255,255,0.07)' }}
                  >
                    <motion.div className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"
                      style={{ background: 'rgba(201,168,76,0.04)' }} />
                    <div className="relative flex-shrink-0 p-2"
                      style={{ background: 'rgba(201,168,76,0.08)', border: '1px solid rgba(201,168,76,0.18)' }}>
                      <LucideIcon name={gl.icon} size={13} className="text-gold-400" />
                    </div>
                    <div className="relative flex-1 min-w-0">
                      <span className="text-xs font-serif uppercase text-white font-medium group-hover:text-gold-400 transition-colors block truncate">{gl.label}</span>
                      <span className="text-[9px] text-gray-500 mt-0.5 block truncate">{gl.desc}</span>
                    </div>
                    <LucideIcon name="ChevronRight" className="relative text-gold-400 opacity-20 group-hover:opacity-100 flex-shrink-0 transition-all" size={14} />
                  </motion.button>
                ))}
              </div>

              <button onClick={() => setStep(2)}
                className="text-[9px] uppercase font-mono tracking-widest text-gray-500 hover:text-[#32BBED] transition-colors flex items-center gap-1 cursor-pointer pt-1">
                <LucideIcon name="ArrowLeft" size={10} /> Back
              </button>
            </motion.div>
          )}

          {/* STEP 4 — AI Processing */}
          {step === 4 && !result && (
            <motion.div
              key="step4"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              onAnimationComplete={calculateRecommendation}
              className="py-10 space-y-6"
            >
              {/* Animated rings */}
              <div className="flex justify-center">
                <div className="relative w-24 h-24">
                  {[0, 1, 2].map(i => (
                    <motion.div
                      key={i}
                      className="absolute rounded-full border"
                      style={{
                        inset: `${i * 8}px`,
                        borderColor: i === 0 ? 'rgba(50,187,237,0.5)' : i === 1 ? 'rgba(201,168,76,0.3)' : 'rgba(50,187,237,0.2)'
                      }}
                      animate={{ rotate: i % 2 === 0 ? 360 : -360, scale: [1, 1.04, 1] }}
                      transition={{ duration: 2 + i * 0.6, repeat: Infinity, ease: 'linear' }}
                    />
                  ))}
                  <div className="absolute inset-0 flex items-center justify-center">
                    <LucideIcon name="Brain" size={22} className="text-[#32BBED]" />
                  </div>
                </div>
              </div>

              {/* Scanning text sequence */}
              <div className="space-y-2 font-mono text-[9px] tracking-widest uppercase">
                {[
                  { label: 'Facial geometry mapped', color: '#32BBED' },
                  { label: 'Hair texture indexed', color: '#32BBED' },
                  { label: 'Style DNA encoded', color: '#32BBED' },
                  { label: 'Expert match computing…', color: '#C9A84C' },
                ].map((item, i) => (
                  <motion.div
                    key={i}
                    className="flex items-center gap-2"
                    initial={{ opacity: 0, x: -8 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: i * 0.3 }}
                  >
                    <motion.span
                      animate={{ opacity: [0.4, 1, 0.4] }}
                      transition={{ duration: 1.2, repeat: Infinity, delay: i * 0.3 }}
                      style={{ color: item.color }}>▸</motion.span>
                    <span style={{ color: 'rgba(255,255,255,0.5)' }}>{item.label}</span>
                  </motion.div>
                ))}
              </div>

              {/* Progress bar */}
              <div className="w-full h-[2px] rounded-full overflow-hidden" style={{ background: 'rgba(255,255,255,0.06)' }}>
                <motion.div
                  className="h-full rounded-full"
                  style={{ background: 'linear-gradient(90deg, #32BBED, #C9A84C)' }}
                  initial={{ width: '0%' }}
                  animate={{ width: '100%' }}
                  transition={{ duration: 1.5, ease: 'easeInOut' }}
                />
              </div>
            </motion.div>
          )}

          {/* STEP 4 — Result */}
          {step === 4 && result && (
            <motion.div
              key="result"
              initial={{ opacity: 0, y: 16 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, ease: [0.22, 1, 0.36, 1] }}
              className="space-y-4"
            >
              {/* Header */}
              <div className="flex items-center gap-2">
                <PulseDot color="#C9A84C" />
                <span className="text-[9px] font-mono tracking-[0.2em] uppercase text-gold-400">
                  AI Analysis Complete · Blueprint Formulated
                </span>
              </div>

              {/* Recommended service card */}
              <div className="relative overflow-hidden p-4"
                style={{ background: 'rgba(201,168,76,0.04)', border: '1px solid rgba(201,168,76,0.3)' }}>
                {/* Corner mark */}
                <div className="absolute top-0 right-0 w-0 h-0"
                  style={{ borderTop: '22px solid rgba(201,168,76,0.35)', borderLeft: '22px solid transparent' }} />
                <div className="absolute top-0 right-0 text-[7px] font-mono text-gold-400 pr-0.5 pt-0.5">✦</div>

                <div className="flex items-start justify-between gap-3 mb-3">
                  <div className="flex items-center gap-3">
                    <div className="p-2.5 bg-gold-400 text-salon-black flex-shrink-0">
                      <LucideIcon name={result.service.icon} size={16} />
                    </div>
                    <div>
                      <span className="text-[8px] font-mono tracking-widest text-gold-400 uppercase block mb-0.5">Recommended Treatment</span>
                      <h6 className="font-serif text-xs uppercase tracking-wider text-white font-medium">{result.service.name}</h6>
                      <span className="text-[9px] text-gray-400">{result.service.durationMin} min session</span>
                    </div>
                  </div>
                  <div className="text-right flex-shrink-0">
                    <span className="text-base font-serif text-gold-400 font-bold block">৳{result.service.priceBDT}</span>
                  </div>
                </div>

                {/* AI explanation — typewriter */}
                <div className="border-l-2 border-gold-400/40 pl-3 text-[10px] text-gray-400 italic font-sans leading-relaxed">
                  <Typewriter text={result.explanation} speed={18} />
                </div>
              </div>

              {/* Stylist recommendation */}
              {result.barber && (
              <div className="flex items-center justify-between p-3.5 relative overflow-hidden"
                style={{ background: 'rgba(50,187,237,0.04)', border: '1px solid rgba(50,187,237,0.15)' }}>
                <div className="flex items-center gap-3">
                  <div className="relative">
                    {result.barber.portraitUrl ? (
                      <img
                        src={result.barber.portraitUrl}
                        alt={result.barber.name}
                        className="w-10 h-10 rounded-full object-cover border"
                        style={{ borderColor: 'rgba(50,187,237,0.3)', filter: 'grayscale(20%)' }}
                      />
                    ) : (
                      <div className="w-10 h-10 rounded-full flex items-center justify-center"
                        style={{ background: 'rgba(50,187,237,0.1)', border: '1px solid rgba(50,187,237,0.3)' }}>
                        <LucideIcon name="User" size={18} className="text-[#32BBED]" />
                      </div>
                    )}
                    <div className="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full flex items-center justify-center"
                      style={{ background: '#32BBED' }}>
                      <div className="w-1.5 h-1.5 rounded-full bg-white" />
                    </div>
                  </div>
                  <div>
                    <span className="text-[8px] font-mono tracking-widest uppercase block" style={{ color: 'rgba(50,187,237,0.7)' }}>
                      AI-Matched Stylist
                    </span>
                    <span className="font-serif text-xs text-white uppercase mt-0.5 block">{result.barber.name}</span>
                  </div>
                </div>
                <div className="text-right">
                  <span className="text-[9px] font-mono" style={{ color: '#C9A84C' }}>★ {result.barber.rating}</span>
                  <span className="text-[8px] text-gray-500 block font-mono">Expert Match</span>
                </div>
              </div>
              )}

              {/* CTAs */}
              <div className="grid grid-cols-2 gap-2.5 pt-1">
                <motion.button
                  whileHover={{ scale: 1.02 }}
                  whileTap={{ scale: 0.98 }}
                  onClick={() => onSelectRecommendedService(result.service.id)}
                  className="py-3 font-serif text-[10px] font-bold uppercase tracking-widest transition-all cursor-pointer flex items-center justify-center gap-1.5"
                  style={{ background: '#32BBED', color: '#000' }}
                >
                  <LucideIcon name="CalendarCheck" size={12} />
                  Auto-Fill Booking
                </motion.button>
                <motion.button
                  whileHover={{ scale: 1.02 }}
                  whileTap={{ scale: 0.98 }}
                  onClick={resetProfiler}
                  className="py-3 font-serif text-[10px] uppercase tracking-widest transition-all cursor-pointer flex items-center justify-center gap-1.5"
                  style={{ background: 'transparent', border: '1px solid rgba(255,255,255,0.12)', color: 'rgba(255,255,255,0.7)' }}
                >
                  <LucideIcon name="RefreshCcw" size={12} />
                  Re-Diagnose
                </motion.button>
              </div>
            </motion.div>
          )}

        </AnimatePresence>
      </div>

      {/* ── FOOTER STATUS BAR ── */}
      <div className="px-6 py-2.5 flex items-center justify-between border-t"
        style={{ borderColor: 'rgba(255,255,255,0.04)', background: 'rgba(0,0,0,0.3)' }}>
        <span className="text-[8px] font-mono tracking-widest uppercase" style={{ color: 'rgba(50,187,237,0.4)' }}>
          ADONIS · AI v2.4
        </span>
        <div className="flex items-center gap-3">
          {['FACELINE', 'TEXTURE', 'DNA'].map((lbl, i) => (
            <span key={lbl} className="text-[7px] font-mono tracking-widest flex items-center gap-1"
              style={{ color: step > i ? 'rgba(50,187,237,0.7)' : 'rgba(255,255,255,0.15)' }}>
              <span className="inline-block w-1 h-1 rounded-full"
                style={{ background: step > i ? '#32BBED' : 'rgba(255,255,255,0.12)' }} />
              {lbl}
            </span>
          ))}
        </div>
      </div>
    </div>
  );
};

import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { LucideIcon } from './LucideIcon';
import { navigateTo } from '../navigation';

interface Offer {
  id: number;
  title: string;
  subtitle: string | null;
  description: string | null;
  badge: string | null;
  icon: string | null;
  original_price: number | null;
  discounted_price: number | null;
  discount_percent: number | null;
  image: string | null;
  valid_until: string | null;
  branch: string;
  is_active: boolean;
  sort_order: number;
}

const BRANCH_LABELS: Record<string, string> = {
  all: 'All Branches',
  gulshan: 'Gulshan',
  bashundhara: 'Bashundhara',
};

const BRANCH_FILTERS = ['all', 'gulshan', 'bashundhara'];

export const OffersPage: React.FC = () => {
  const [offers, setOffers] = useState<Offer[]>([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState<string>('all');

  useEffect(() => {
    fetch('/api/offers')
      .then(r => r.json())
      .then((data: Offer[]) => {
        setOffers(data.filter(o => o.is_active));
      })
      .catch(() => setOffers([]))
      .finally(() => setLoading(false));
  }, []);

  const filtered = filter === 'all'
    ? offers
    : offers.filter(o => o.branch === filter || o.branch === 'all');

  return (
    <div className="bg-salon-black text-white relative min-h-screen overflow-hidden">
      {/* Ambient glows */}
      <div className="absolute top-[5%] left-[-10%] h-[600px] w-[600px] rounded-full bg-gold-400/5 blur-[160px] pointer-events-none" />
      <div className="absolute top-[40%] right-[-10%] h-[600px] w-[600px] rounded-full bg-[#32BBED]/5 blur-[160px] pointer-events-none" />
      <div className="absolute bottom-[10%] left-[20%] h-[600px] w-[600px] rounded-full bg-gold-400/4 blur-[160px] pointer-events-none" />

      {/* ── HERO BANNER ── */}
      <section className="relative pt-36 pb-20 border-b border-white/5 bg-gradient-to-b from-black to-salon-black text-center space-y-4">
        <motion.span
          initial={{ opacity: 0, y: -10 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="text-[10px] font-mono tracking-[0.4em] text-gold-400 uppercase block gold-glow"
        >
          Exclusive Deals & Packages
        </motion.span>
        <motion.h1
          initial={{ opacity: 0, y: 10 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.1 }}
          className="font-serif text-4xl sm:text-5xl md:text-6xl uppercase tracking-wider text-white"
        >
          Offers & Packages
        </motion.h1>
        <div className="h-[1px] w-24 bg-gold-400 mx-auto my-3" />
        <motion.p
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 0.7, delay: 0.2 }}
          className="text-xs text-gray-400 max-w-lg mx-auto leading-relaxed"
        >
          Curated grooming packages and time-limited deals designed to give you the ultimate Adonis experience at exceptional value.
        </motion.p>
      </section>

      <div className="max-w-7xl mx-auto px-4 md:px-8 py-16 relative z-10 space-y-12">

        {/* ── BRANCH FILTER TABS ── */}
        <div className="flex items-center justify-center gap-2 flex-wrap">
          {BRANCH_FILTERS.map(b => (
            <button
              key={b}
              onClick={() => setFilter(b)}
              className={`px-5 py-2 text-[10px] font-mono uppercase tracking-widest transition-all duration-300 cursor-pointer border ${
                filter === b
                  ? 'bg-gold-400 text-black border-gold-400'
                  : 'bg-transparent text-gray-400 border-white/10 hover:border-gold-400/40 hover:text-white'
              }`}
            >
              {BRANCH_LABELS[b]}
            </button>
          ))}
        </div>

        {/* ── LOADING STATE ── */}
        {loading && (
          <div className="flex flex-col items-center justify-center py-24 space-y-4">
            <motion.div
              className="w-10 h-10 border-2 border-gold-400/30 border-t-gold-400 rounded-full"
              animate={{ rotate: 360 }}
              transition={{ duration: 1, repeat: Infinity, ease: 'linear' }}
            />
            <p className="text-[10px] font-mono tracking-widest text-gray-500 uppercase">
              Loading Offers...
            </p>
          </div>
        )}

        {/* ── EMPTY STATE ── */}
        {!loading && filtered.length === 0 && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="text-center py-24 space-y-4"
          >
            <LucideIcon name="Tag" size={40} className="text-white/10 mx-auto" />
            <p className="text-sm font-serif uppercase tracking-wider text-white/30">
              No Active Offers
            </p>
            <p className="text-[10px] text-gray-600 font-mono tracking-widest uppercase">
              Check back soon for exclusive deals
            </p>
          </motion.div>
        )}

        {/* ── OFFER CARDS GRID ── */}
        {!loading && filtered.length > 0 && (
          <AnimatePresence mode="popLayout">
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {filtered.map((offer, idx) => (
                <motion.div
                  key={offer.id}
                  layout
                  initial={{ opacity: 0, y: 24 }}
                  animate={{ opacity: 1, y: 0 }}
                  exit={{ opacity: 0, scale: 0.96 }}
                  transition={{ duration: 0.45, delay: idx * 0.06, ease: [0.22, 1, 0.36, 1] }}
                  className="relative group flex flex-col overflow-hidden"
                  style={{
                    background: 'linear-gradient(145deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%)',
                    border: '1px solid rgba(255,255,255,0.07)',
                  }}
                >
                  {/* Hover glow */}
                  <div className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"
                    style={{ background: 'linear-gradient(145deg, rgba(201,168,76,0.05) 0%, transparent 60%)' }} />

                  {/* Optional image */}
                  {offer.image && (
                    <div className="relative h-44 w-full overflow-hidden flex-shrink-0">
                      <img
                        src={offer.image}
                        alt={offer.title}
                        className="w-full h-full object-cover brightness-75 group-hover:brightness-90 group-hover:scale-[1.04] transition-all duration-700"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-salon-black via-transparent to-transparent" />
                    </div>
                  )}

                  {/* Card body */}
                  <div className="flex flex-col flex-1 p-6 space-y-4 relative">
                    {/* Top row: badge + branch */}
                    <div className="flex items-center justify-between gap-2 flex-wrap">
                      {offer.badge && (
                        <span className="px-2.5 py-0.5 text-[8px] font-mono uppercase tracking-widest bg-gold-400 text-black font-bold">
                          {offer.badge}
                        </span>
                      )}
                      {offer.branch !== 'all' && (
                        <span className="px-2 py-0.5 text-[8px] font-mono uppercase tracking-widest border border-[#32BBED]/30 text-[#32BBED]">
                          {BRANCH_LABELS[offer.branch] ?? offer.branch}
                        </span>
                      )}
                    </div>

                    {/* Icon + Title */}
                    <div className="flex items-start gap-3">
                      <div className="flex-shrink-0 p-2.5 mt-0.5"
                        style={{ background: 'rgba(201,168,76,0.1)', border: '1px solid rgba(201,168,76,0.2)' }}>
                        <LucideIcon name={offer.icon || 'Tag'} size={16} className="text-gold-400" />
                      </div>
                      <div className="min-w-0">
                        <h3 className="font-serif text-sm uppercase tracking-wider text-white group-hover:text-gold-400 transition-colors leading-tight">
                          {offer.title}
                        </h3>
                        {offer.subtitle && (
                          <p className="text-[10px] font-mono text-gray-400 tracking-widest mt-0.5 uppercase truncate">
                            {offer.subtitle}
                          </p>
                        )}
                      </div>
                    </div>

                    {/* Description */}
                    {offer.description && (
                      <p className="text-[11px] text-gray-400 leading-relaxed font-sans border-l border-gold-400/25 pl-3">
                        {offer.description}
                      </p>
                    )}

                    {/* Pricing */}
                    {(offer.discounted_price || offer.original_price) && (
                      <div className="flex items-end gap-3 pt-1">
                        {offer.discounted_price && (
                          <span className="font-serif text-2xl text-gold-400 font-bold">
                            ৳{offer.discounted_price.toLocaleString()}
                          </span>
                        )}
                        {offer.original_price && (
                          <span className="font-mono text-sm text-gray-500 line-through">
                            ৳{offer.original_price.toLocaleString()}
                          </span>
                        )}
                        {offer.discount_percent && (
                          <span className="ml-auto px-2 py-0.5 text-[9px] font-mono font-bold tracking-widest bg-red-500/15 text-red-400 border border-red-500/20">
                            -{offer.discount_percent}% OFF
                          </span>
                        )}
                      </div>
                    )}

                    {/* Validity */}
                    {offer.valid_until && (
                      <div className="flex items-center gap-1.5 text-[9px] font-mono tracking-widest text-gray-500 uppercase">
                        <LucideIcon name="Clock" size={10} className="text-gray-600" />
                        Valid Until: <span className="text-gray-400">{offer.valid_until}</span>
                      </div>
                    )}

                    {/* Spacer + CTA */}
                    <div className="flex-1" />
                    <button
                      onClick={() => navigateTo('/book')}
                      className="w-full py-3 mt-2 bg-[#32BBED] hover:bg-gold-400 text-black font-serif text-[10px] font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer flex items-center justify-center gap-2"
                    >
                      <LucideIcon name="Calendar" size={12} />
                      Claim This Offer
                    </button>
                  </div>

                  {/* Corner accent */}
                  <div className="absolute top-0 right-0 w-0 h-0 pointer-events-none"
                    style={{ borderTop: '28px solid rgba(201,168,76,0.2)', borderLeft: '28px solid transparent' }} />
                </motion.div>
              ))}
            </div>
          </AnimatePresence>
        )}

        {/* ── BACK NAV ── */}
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

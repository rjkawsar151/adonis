import React, { useEffect, useState, useMemo } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { LucideIcon } from './LucideIcon';
import { navigateTo } from '../navigation';
import { COMPLETE_PRICE_LIST, GULSHAN_PACKAGES, PriceGroup } from '../data';

const CATEGORY_ICONS: Record<string, string> = {
  'HAIR CUTTING':         'Scissors',
  'HAIR SETTING':         'Wind',
  'SPECIAL CHARGE LIST':  'Star',
  'HAIR DYE & OTHERS':    'Droplets',
  'HAIR TREATMENT':       'Sparkles',
  'FACE MASSAGE & SCRUB': 'Smile',
  'FAIR POLISH':          'SunMedium',
  'MILK FAIR POLISH':     'Milk',
  'FACIAL':               'UserCheck',
  'BODY FASHION & WAX':   'Zap',
  'SPA & BODY SCRUB':     'Flower',
  'HAND & FOOT CARE':     'Hand',
  'MASSAGE LAB':          'Activity',
  'SPORTS & CUPPING':     'Dumbbell',
  'MAKE-OVER ART':        'Palette',
};

const CATEGORY_GROUPS: { label: string; categories: string[]; icon: string }[] = [
  { label: 'All Services',   categories: [],                                      icon: 'Layers' },
  { label: 'Hair',           categories: ['HAIR CUTTING','HAIR SETTING','HAIR DYE & OTHERS','HAIR TREATMENT'], icon: 'Scissors' },
  { label: 'Face & Skin',    categories: ['FACE MASSAGE & SCRUB','FAIR POLISH','MILK FAIR POLISH','FACIAL'],    icon: 'Smile' },
  { label: 'Body & Spa',     categories: ['SPA & BODY SCRUB','BODY FASHION & WAX','HAND & FOOT CARE'],         icon: 'Flower' },
  { label: 'Massage',        categories: ['MASSAGE LAB','SPORTS & CUPPING'],      icon: 'Activity' },
  { label: 'Make-Over',      categories: ['MAKE-OVER ART'],                       icon: 'Palette' },
  { label: 'Special',        categories: ['SPECIAL CHARGE LIST'],                 icon: 'Star' },
  { label: 'Packages',       categories: ['__packages__'],                        icon: 'Crown' },
];

interface FlatService {
  category: string;
  categoryIcon: string;
  name: string;
  description?: string;
  price: string;
}

interface ServicesPageProps {
  initialBranch?: 'gulshan' | 'bashundhara';
  onBookService?: (serviceId: string, branchId: 'gulshan' | 'bashundhara') => void;
}

export const ServicesPage: React.FC<ServicesPageProps> = ({ initialBranch = 'gulshan', onBookService }) => {
  const [selectedBranch, setSelectedBranch] = useState<'gulshan' | 'bashundhara'>(initialBranch);
  const [activeGroup, setActiveGroup] = useState(0);
  const [searchQuery, setSearchQuery] = useState('');
  const [expandedCat, setExpandedCat] = useState<string | null>(null);

  useEffect(() => {
    setSelectedBranch(initialBranch);
    setSearchQuery('');
    setExpandedCat(null);
  }, [initialBranch]);

  const handleBranchRoute = (branchId: 'gulshan' | 'bashundhara') => {
    setSelectedBranch(branchId);
    navigateTo(`/services/${branchId}`);
  };

  const isPackages = CATEGORY_GROUPS[activeGroup].categories[0] === '__packages__';

  const filteredGroups: PriceGroup[] = useMemo(() => {
    const groupCats = CATEGORY_GROUPS[activeGroup].categories;
    const base: PriceGroup[] = groupCats.length === 0
      ? COMPLETE_PRICE_LIST
      : COMPLETE_PRICE_LIST.filter(g => groupCats.includes(g.category));

    if (!searchQuery.trim()) return base;
    const q = searchQuery.toLowerCase();
    return base
      .map(g => ({
        ...g,
        items: g.items.filter(
          item => item.name.toLowerCase().includes(q) || (item.description ?? '').toLowerCase().includes(q)
        )
      }))
      .filter(g => g.items.length > 0);
  }, [activeGroup, searchQuery]);

  const flatServices: FlatService[] = useMemo(() => {
    return filteredGroups.flatMap(g =>
      g.items.map(item => ({
        category: g.category,
        categoryIcon: CATEGORY_ICONS[g.category] ?? 'ChevronRight',
        name: item.name,
        description: item.description,
        price: item.price,
      }))
    );
  }, [filteredGroups]);

  const totalItems = flatServices.length;

  const cleanPrice = (p: string) => {
    return p.replace(/\/-.*$/, '').replace(/& Above.*$/, '').replace('& Above', '').trim();
  };

  return (
    <div className="py-28 min-h-screen bg-salon-black text-white relative overflow-x-hidden">
      {/* Ambient glows */}
      <div className="absolute top-[8%]  left-[-8%]  h-[500px] w-[500px] rounded-full bg-gold-400/4 blur-[130px] pointer-events-none" />
      <div className="absolute bottom-[15%] right-[-8%] h-[500px] w-[500px] rounded-full bg-gold-400/4 blur-[130px] pointer-events-none" />

      <div className="max-w-7xl mx-auto px-4 md:px-8 relative z-10 space-y-14">

        {/* ── Page Header ── */}
        <div className="text-center max-w-3xl mx-auto space-y-4">
          <span className="text-xs font-mono tracking-[0.32em] text-gold-400 uppercase font-semibold block gold-glow">
            Adonis Men's Grooming · Price List 2025
          </span>
          <h1 className="font-serif text-4xl sm:text-5xl md:text-6xl font-normal uppercase tracking-wider text-white">
            Services &amp; Pricing
          </h1>
          <div className="h-[1px] w-24 bg-gold-400 mx-auto" />
          <p className="text-base text-gray-400 leading-relaxed max-w-xl mx-auto">
            Browse our complete grooming catalog. Select your branch location below to view details.
          </p>
        </div>

        {/* ── Branch Selector ── */}
        <div className="max-w-md mx-auto space-y-3">
          <span className="text-xs font-mono tracking-[0.25em] text-gold-400/80 uppercase text-center block">
            Step 1: Select Your Location
          </span>
          <div className="flex gap-4">
            <button
              onClick={() => handleBranchRoute('gulshan')}
              className={`flex-1 py-4 border text-xs font-mono uppercase tracking-widest transition-all duration-300 cursor-pointer flex flex-col items-center gap-1.5 ${
                selectedBranch === 'gulshan'
                  ? 'bg-salon-gray border-gold-400 text-gold-400 shadow-md shadow-gold-400/10'
                  : 'bg-black/30 border-white/8 text-gray-500 hover:border-gold-400/30 hover:text-white'
              }`}
            >
              <LucideIcon name="MapPin" size={14} className={selectedBranch === 'gulshan' ? 'text-gold-400' : 'text-gray-500'} />
              <span>Gulshan Avenue</span>
            </button>
            <button
              onClick={() => handleBranchRoute('bashundhara')}
              className={`flex-1 py-4 border text-xs font-mono uppercase tracking-widest transition-all duration-300 cursor-pointer flex flex-col items-center gap-1.5 ${
                selectedBranch === 'bashundhara'
                  ? 'bg-salon-gray border-gold-400 text-gold-400 shadow-md shadow-gold-400/10'
                  : 'bg-black/30 border-white/8 text-gray-500 hover:border-gold-400/30 hover:text-white'
              }`}
            >
              <LucideIcon name="MapPin" size={14} className={selectedBranch === 'bashundhara' ? 'text-gold-400' : 'text-gray-500'} />
              <span>Bashundhara Studio</span>
            </button>
          </div>
        </div>

        {selectedBranch === 'gulshan' ? (
          <>
            {/* ── Group Filter Tabs ── */}
            <div className="overflow-x-auto pb-2 -mx-4 px-4">
              <div className="flex gap-2 min-w-max md:flex-wrap md:justify-center">
                {CATEGORY_GROUPS.map((grp, idx) => {
                  const active = activeGroup === idx;
                  return (
                    <button
                      key={grp.label}
                      onClick={() => { setActiveGroup(idx); setSearchQuery(''); }}
                      className={`flex items-center gap-2 px-4 py-2.5 border text-xs font-mono uppercase tracking-widest transition-all duration-300 cursor-pointer whitespace-nowrap ${
                        active
                          ? 'bg-salon-gray border-gold-400 text-gold-400 shadow-md shadow-gold-400/10'
                          : 'bg-black/30 border-white/8 text-gray-400 hover:border-gold-400/40 hover:text-white'
                      }`}
                    >
                      <LucideIcon name={grp.icon} size={12} />
                      {grp.label}
                    </button>
                  );
                })}
              </div>
            </div>

            {/* ── Search (hidden on Packages tab) ── */}
            {!isPackages && (
              <div className="relative max-w-md mx-auto">
                <input
                  type="text"
                  value={searchQuery}
                  onChange={e => setSearchQuery(e.target.value)}
                  placeholder="Search services (e.g. keratin, massage, facial)…"
                  className="w-full bg-salon-gray text-white text-sm border border-white/10 px-10 py-3.5 focus:outline-none focus:border-gold-400 transition-colors placeholder:text-gray-600"
                />
                <div className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                  <LucideIcon name="Search" size={14} />
                </div>
                {searchQuery && (
                  <button
                    onClick={() => setSearchQuery('')}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white"
                  >
                    <LucideIcon name="X" size={14} />
                  </button>
                )}
              </div>
            )}

            {/* ══════════════════════════════════════════════════ */}
            {/* PACKAGES VIEW */}
            {/* ══════════════════════════════════════════════════ */}
            {isPackages ? (
              <div className="space-y-6">
                <div className="text-center space-y-1 mb-8">
                  <p className="text-sm text-gray-400 font-mono uppercase tracking-widest">Gulshan Branch · Exclusive Bundles</p>
                  <p className="text-xs text-gray-600 italic">All packages include a discount vs. individual service pricing.</p>
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                  {GULSHAN_PACKAGES.map((pkg, i) => (
                    <motion.div
                      key={pkg.name}
                      initial={{ opacity: 0, y: 20 }}
                      animate={{ opacity: 1, y: 0 }}
                      transition={{ duration: 0.4, delay: i * 0.05 }}
                      className="bg-salon-gray border border-white/8 hover:border-gold-400/40 transition-all duration-300 flex flex-col group relative overflow-hidden"
                    >
                      <div className="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-gold-400/0 group-hover:via-gold-400 to-transparent transition-all duration-500" />

                      <div className="p-6 flex-1 space-y-4">
                        <div className="flex items-start justify-between gap-3">
                          <div>
                            <span className="text-[10px] font-mono tracking-widest text-gold-400 uppercase bg-gold-400/10 px-2 py-0.5 border border-gold-400/15 inline-block mb-2">
                              Gulshan Package
                            </span>
                            <h3 className="font-serif text-lg uppercase tracking-wider text-white group-hover:text-gold-400 transition-colors leading-tight">
                              {pkg.name}
                            </h3>
                          </div>
                          <LucideIcon name="Crown" size={18} className="text-gold-400/60 shrink-0 mt-1" />
                        </div>

                        <ul className="space-y-1.5">
                          {pkg.includes.map((item, ii) => (
                            <li key={ii} className="flex items-start gap-2 text-sm text-gray-400 font-sans leading-snug">
                              <div className="w-1 h-1 bg-gold-400/60 rotate-45 shrink-0 mt-1.5" />
                              {item}
                            </li>
                          ))}
                        </ul>
                      </div>

                      <div className="border-t border-white/8 px-6 py-4 flex items-center justify-between bg-black/20">
                        <div className="space-y-0.5">
                          <span className="text-[11px] text-gray-600 uppercase font-mono block">
                            Save ৳{pkg.discount}
                          </span>
                          <div className="flex items-baseline gap-2">
                            <span className="text-xl font-serif text-gold-400 font-bold">৳{pkg.price}</span>
                            <span className="text-xs text-gray-600 line-through font-mono">৳{pkg.originalPrice}</span>
                          </div>
                        </div>
                        <button
                          onClick={() => onBookService && onBookService(pkg.name, selectedBranch)}
                          className="px-5 py-2 border border-gold-400/40 text-gold-400 hover:bg-gold-400 hover:text-black text-xs font-serif uppercase tracking-widest transition-all duration-300 cursor-pointer"
                        >
                          Book Now
                        </button>
                      </div>
                    </motion.div>
                  ))}
                </div>
              </div>
            ) : (
              /* ══════════════════════════════════════════════════ */
              /* ACCORDION SERVICE LIST (OPTIMIZED FOR MOBILE)      */
              /* ══════════════════════════════════════════════════ */
              <div className="space-y-4">
                {/* Result count */}
                <div className="text-right text-xs font-mono text-gray-600 uppercase tracking-widest pb-2">
                  {filteredGroups.reduce((acc, curr) => acc + curr.items.length, 0)} services matched
                </div>

                <AnimatePresence mode="wait">
                  {filteredGroups.length === 0 ? (
                    <motion.div
                      key="empty"
                      initial={{ opacity: 0 }}
                      animate={{ opacity: 1 }}
                      exit={{ opacity: 0 }}
                      className="text-center py-20 border border-dashed border-white/10 space-y-4"
                    >
                      <LucideIcon name="SearchX" className="mx-auto text-gray-600" size={32} />
                      <p className="text-sm text-gray-400 uppercase tracking-widest">No services match your search.</p>
                      <button
                        onClick={() => setSearchQuery('')}
                        className="px-6 py-2 border border-gold-400 text-gold-400 text-xs font-serif uppercase tracking-widest hover:bg-gold-400 hover:text-black transition-colors"
                      >
                        Clear Search
                      </button>
                    </motion.div>
                  ) : (
                    <motion.div
                      key="accordion"
                      initial={{ opacity: 0 }}
                      animate={{ opacity: 1 }}
                      exit={{ opacity: 0 }}
                      className="space-y-4 max-w-4xl mx-auto"
                    >
                      {filteredGroups.map((group) => {
                        const isOpen = expandedCat === group.category || searchQuery.trim() !== '';
                        const groupIcon = CATEGORY_ICONS[group.category] ?? 'ChevronRight';
                        return (
                          <div key={group.category} className="border border-white/5 bg-salon-gray/20 overflow-hidden">
                            {/* Category Header Button */}
                            <button
                              onClick={() => setExpandedCat(expandedCat === group.category ? null : group.category)}
                              className="w-full flex items-center justify-between p-4 sm:p-5 hover:bg-salon-gray/40 transition-colors text-left cursor-pointer"
                            >
                              <div className="flex items-center gap-3 sm:gap-4">
                                <div className="w-10 h-10 rounded-full bg-gold-400/10 border border-gold-400/20 flex items-center justify-center text-gold-400 shrink-0">
                                  <LucideIcon name={groupIcon} size={18} />
                                </div>
                                <div>
                                  <h3 className="font-serif text-base sm:text-lg uppercase tracking-wider text-white">
                                    {group.category}
                                  </h3>
                                  <span className="text-[11px] font-mono uppercase tracking-widest text-gray-500">
                                    {group.items.length} service{group.items.length !== 1 ? 's' : ''}
                                  </span>
                                </div>
                              </div>
                              <LucideIcon
                                name={isOpen ? 'ChevronUp' : 'ChevronDown'}
                                size={16}
                                className="text-gold-400/70"
                              />
                            </button>

                            {/* Services List inside Category */}
                            <AnimatePresence initial={false}>
                              {isOpen && (
                                <motion.div
                                  initial={{ height: 0, opacity: 0 }}
                                  animate={{ height: 'auto', opacity: 1 }}
                                  exit={{ height: 0, opacity: 0 }}
                                  transition={{ duration: 0.25, ease: 'easeInOut' }}
                                >
                                  <div className="px-4 pb-5 pt-1 divide-y divide-white/5 space-y-3">
                                    {group.items.map((item) => (
                                      <div
                                        key={item.name}
                                        className="pt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-left group/item"
                                      >
                                        <div className="space-y-1">
                                          <h4 className="font-serif text-sm uppercase tracking-wider text-white group-hover/item:text-gold-400 transition-colors">
                                            {item.name}
                                          </h4>
                                          {item.description && (
                                            <p className="text-xs sm:text-sm text-gray-500 leading-relaxed max-w-xl font-light">
                                              {item.description}
                                            </p>
                                          )}
                                        </div>
                                        <div className="flex items-center justify-between sm:justify-end gap-5 shrink-0">
                                          <span className="text-base font-serif font-bold text-gold-400 font-mono">
                                            ৳{cleanPrice(item.price)}
                                          </span>
                                          <button
                                            onClick={() => onBookService && onBookService(`${group.category.replace(' LIST', '')} - ${item.name}`, selectedBranch)}
                                            className="px-3.5 py-1.5 border border-gold-400/30 text-gold-400 hover:bg-gold-400 hover:text-black hover:border-gold-400 text-xs font-mono uppercase tracking-widest transition-all duration-300 cursor-pointer rounded-sm"
                                          >
                                            Book
                                          </button>
                                        </div>
                                      </div>
                                    ))}
                                  </div>
                                </motion.div>
                              )}
                            </AnimatePresence>
                          </div>
                        );
                      })}
                    </motion.div>
                  )}
                </AnimatePresence>
              </div>
            )}
          </>
        ) : (
          /* ══════════════════════════════════════════════════ */
          /* BASHUNDHARA BRANCH COMING SOON DISPLAY             */
          /* ══════════════════════════════════════════════════ */
          <motion.div
            initial={{ opacity: 0, y: 15 }}
            animate={{ opacity: 1, y: 0 }}
            className="max-w-xl mx-auto bg-salon-gray/30 border border-white/8 p-8 md:p-10 space-y-6 text-center"
          >
            <div className="w-12 h-12 rounded-full bg-gold-400/10 border border-gold-400/20 flex items-center justify-center text-gold-400 mx-auto">
              <LucideIcon name="Sparkles" size={22} />
            </div>
            <div className="space-y-2">
              <h3 className="font-serif text-xl sm:text-2xl uppercase tracking-wider text-white">
                Bashundhara Pricing
              </h3>
              <p className="text-sm text-gray-400 font-sans leading-relaxed">
                Prices for our Bashundhara Premium Lounge will be updated soon. 
                In the meantime, you can reach out to us directly or visit our studio for detailed information and custom bookings.
              </p>
            </div>

            <div className="h-px bg-white/5 w-16 mx-auto" />

            <div className="space-y-1.5 text-sm text-gray-300">
              <p className="font-serif uppercase tracking-widest text-xs text-gold-400 font-semibold">
                Bashundhara Lounge Info
              </p>
              <p className="font-light max-w-sm mx-auto leading-relaxed">
                Rahman Tower (Lift-4), Ka-1/B, Jagannathpur, Beside Hardco International School, Bashundhara, Dhaka.
              </p>
              <p className="text-xs text-gray-500 font-mono uppercase tracking-widest mt-1">
                Hours: 10:00 AM – 10:00 PM (Sat - Fri)
              </p>
            </div>

            <div className="flex flex-col sm:flex-row gap-4 justify-center pt-4">
              <a
                href="tel:+8801720080091"
                className="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gold-400 text-black hover:bg-gold-500 transition-colors font-serif text-xs font-bold uppercase tracking-widest cursor-pointer"
              >
                <LucideIcon name="PhoneCall" size={14} />
                Call Branch
              </a>
              <a
                href="https://www.google.com/maps?sca_esv=c0a979c20fc01ddf&biw=1920&bih=911&sxsrf=ANbL-n6rBQHc6On-WNWo1_ntWaHqkg8ONQ:1779702997846&gs_lp=Egxnd3Mtd2l6LXNlcnAiCWFkb25pcyBiYSoCCAAyEBAuGK8BGMcBGIAEGIoFGCcyCxAAGIAEGIoFGJECMgoQABiABBgUGIcCMgsQABiABBiKBRiRAjIQEC4YFBivARjHARiHAhiABDILEAAYgAQYigUYkQIyBRAAGIAEMgUQABiABDIFEAAYgAQyBRAAGIAEMh0QLhivARjHARiABBiKBRiXBRjcBBjeBBjgBNgBAUiWFVAAWMwNcAF4AZABAJgBsQGgAckHqgEDMC42uAEDyAEA-AEBmAIHoALlB8ICBBAjGCfCAgoQIxiABBiKBRgnwgIQEC4YgAQYigUYxwEYrwEYJ8ICEBAAGIAEGIoFGEMYsQMYyQPCAgoQABiABBiKBRhDwgIOEC4YrwEYxwEYkgMYgATCAgUQLhiABMICCxAuGK8BGMcBGIAEmAMAugYGCAEQARgUkgcDMS42oAeVZbIHAzAuNrgH4gfCBwUwLjMuNMgHFYAIAQ&um=1&ie=UTF-8&fb=1&gl=bd&sa=X&geocode=KXuG98vmx1U3MY1A3OZhVIwb&daddr=Ka-1/B,+4th+Floor,+Rahman+Tower,+Main+Road,+Dhaka+1229"
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center justify-center gap-2 px-8 py-3.5 border border-white/15 text-white hover:bg-white/5 transition-colors font-serif text-xs font-semibold uppercase tracking-widest cursor-pointer"
              >
                <LucideIcon name="Navigation" size={14} />
                Get Directions
              </a>
            </div>
          </motion.div>
        )}

        {/* ── Booking CTA Banner ── */}
        <div className="border border-gold-400/25 bg-salon-gray/30 p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
          <div className="text-center md:text-left space-y-2">
            <span className="text-xs font-mono text-gold-400 uppercase tracking-widest block">Ready to experience it?</span>
            <h3 className="font-serif text-2xl md:text-3xl uppercase tracking-wider text-white">Book Your Appointment</h3>
            <p className="text-sm text-gray-400 max-w-md">
              Choose your preferred service and barber, pick a time, and let Adonis handle the rest.
            </p>
          </div>
          <button
            onClick={() => { navigateTo('/'); setTimeout(() => document.getElementById('booking-section')?.scrollIntoView({ behavior: 'smooth' }), 250); }}
            className="shrink-0 border border-gold-400 text-gold-400 py-4 px-10 uppercase text-xs tracking-[0.2em] hover:bg-gold-400 hover:text-black transition-all cursor-pointer font-serif font-black"
          >
            Book Now
          </button>
        </div>

        {/* ── Back link ── */}
        <div className="pt-2 text-center">
          <button
            onClick={() => navigateTo('/')}
            className="inline-flex items-center gap-2 border-b border-white/20 hover:border-gold-400 hover:text-gold-400 pb-1 text-sm font-mono uppercase tracking-widest text-gray-400 transition-all cursor-pointer"
          >
            ← Back To Adonis Lounge
          </button>
        </div>

      </div>
    </div>
  );
};

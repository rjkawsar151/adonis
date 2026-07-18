import React, { useState, useMemo } from 'react';
import { COMPLETE_PRICE_LIST, GULSHAN_PACKAGES, PriceListItem, PriceGroup } from '../data';
import { LucideIcon } from './LucideIcon';
import { motion, AnimatePresence } from 'motion/react';

interface PriceListCatalogProps {
  onSelectService?: (serviceName: string) => void;
}

export const PriceListCatalog: React.FC<PriceListCatalogProps> = ({ onSelectService }) => {
  const [activeTab, setActiveTab] = useState<'individual' | 'packages'>('individual');
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState<string>('ALL');

  // Categories list
  const categories = useMemo(() => {
    return ['ALL', ...COMPLETE_PRICE_LIST.map((g) => g.category)];
  }, []);

  // Filter individual services
  const filteredIndividualServices = useMemo(() => {
    let result = COMPLETE_PRICE_LIST;

    if (selectedCategory !== 'ALL') {
      result = result.filter((g) => g.category === selectedCategory);
    }

    if (searchQuery.trim()) {
      const query = searchQuery.toLowerCase();
      result = result
        .map((g) => {
          const matchedItems = g.items.filter(
            (item) =>
              item.name.toLowerCase().includes(query) ||
              (item.description && item.description.toLowerCase().includes(query))
          );
          return {
            ...g,
            items: matchedItems,
          };
        })
        .filter((g) => g.items.length > 0);
    }

    return result;
  }, [selectedCategory, searchQuery]);

  // Filter packages
  const filteredPackages = useMemo(() => {
    if (!searchQuery.trim()) return GULSHAN_PACKAGES;
    const query = searchQuery.toLowerCase();
    return GULSHAN_PACKAGES.filter(
      (pkg) =>
        pkg.name.toLowerCase().includes(query) ||
        pkg.includes.some((inc) => inc.toLowerCase().includes(query))
    );
  }, [searchQuery]);

  const handleBookNow = (serviceName: string) => {
    if (onSelectService) {
      onSelectService(serviceName);
    }
    const element = document.getElementById('booking-section');
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  };

  return (
    <div className="w-full bg-[#0B0B0B] text-white border border-white/5 py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto rounded-none">

      {/* Header Container */}
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-[#32BBED]/20">
        <div>
          <span className="text-xs font-mono tracking-[0.3em] text-[#32BBED] uppercase block mb-1">
            Official Dhaka Lounge Tariffs
          </span>
          <h3 className="font-serif text-3xl md:text-4xl uppercase tracking-wider text-white">
            Gulshan Price Catalogue
          </h3>
          <p className="text-xs text-gray-500 max-w-xl mt-2 leading-relaxed">
            Uncompromising standards, premium imported brands, and expert styling craftsmanship. Select any treatment to begin booking.
          </p>
        </div>

        {/* Tab Switcher */}
        <div className="flex bg-white/5 p-1 border border-white/10 w-fit shrink-0">
          <button
            onClick={() => {
              setActiveTab('individual');
              setSearchQuery('');
            }}
            className={`px-5 py-2.5 font-serif text-xs uppercase tracking-wider transition-all cursor-pointer ${activeTab === 'individual'
                ? 'bg-[#32BBED] text-black font-bold'
                : 'text-gray-400 hover:text-white'
              }`}
          >
            Individual Treatments
          </button>
          <button
            onClick={() => {
              setActiveTab('packages');
              setSearchQuery('');
            }}
            className={`px-5 py-2.5 font-serif text-xs uppercase tracking-wider transition-all cursor-pointer ${activeTab === 'packages'
                ? 'bg-[#32BBED] text-black font-bold'
                : 'text-gray-400 hover:text-white'
              }`}
          >
            VIP Promo Packages
          </button>
        </div>
      </div>

      {/* Search and Filters Strip */}
      <div className="flex flex-col md:flex-row gap-4 items-center justify-between mb-8">
        {/* Search Input */}
        <div className="relative w-full md:max-w-md">
          <span className="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
            <LucideIcon name="Search" size={16} />
          </span>
          <input
            type="text"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            placeholder={
              activeTab === 'individual'
                ? "Search 100+ services (e.g. shampoo, rebonding, shave)..."
                : "Search packages (e.g. bridegroom, classic, spa)..."
            }
            className="w-full bg-[#141414] border border-white/10 py-3 pl-10 pr-4 text-xs tracking-wide text-white placeholder-gray-500 focus:outline-none focus:border-[#32BBED] transition-colors"
          />
          {searchQuery && (
            <button
              onClick={() => setSearchQuery('')}
              className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-white"
            >
              <LucideIcon name="X" size={14} />
            </button>
          )}
        </div>

        {/* Individual Categories Sroller */}
        {activeTab === 'individual' && (
          <div className="w-full md:max-w-2xl overflow-x-auto no-scrollbar flex gap-2 py-1">
            {categories.map((cat) => (
              <button
                key={cat}
                onClick={() => setSelectedCategory(cat)}
                className={`px-4 py-2 text-[10px] uppercase font-mono tracking-widest whitespace-nowrap transition-all rounded-none cursor-pointer ${selectedCategory === cat
                    ? 'border border-[#32BBED] text-[#32BBED] bg-[#32BBED]/5'
                    : 'border border-white/5 bg-[#141414] text-gray-400 hover:text-white hover:border-white/20'
                  }`}
              >
                {cat.replace(' LIST', '').replace(' AND ', ' & ')}
              </button>
            ))}
          </div>
        )}
      </div>

      {/* Main Catalog Viewbox */}
      <AnimatePresence mode="wait">
        {activeTab === 'individual' ? (
          <motion.div
            key="individual"
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -10 }}
            transition={{ duration: 0.3 }}
            className="space-y-12"
          >
            {filteredIndividualServices.length === 0 ? (
              <div className="text-center py-16 border border-white/5 bg-[#141414]">
                <div className="text-[#32BBED] mb-3 flex justify-center">
                  <LucideIcon name="Search" size={32} />
                </div>
                <h4 className="font-serif text-sm uppercase tracking-wider text-white">No Treatment Matches Found</h4>
                <p className="text-xs text-gray-500 mt-1">Refine your search term or select another category filter strip.</p>
              </div>
            ) : (
              filteredIndividualServices.map((group) => (
                <div key={group.category} className="space-y-4">
                  {/* Category Section Header */}
                  <div className="flex items-center gap-3 border-b border-white/5 pb-2">
                    <div className="w-1.5 h-3 bg-[#32BBED]"></div>
                    <h4 className="font-serif text-sm uppercase tracking-[0.2em] text-[#32BBED]">
                      {group.category}
                    </h4>
                    <span className="text-[10px] font-mono text-gray-500">
                      ({group.items.length} items)
                    </span>
                  </div>

                  {/* Pricing Grid */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {group.items.map((item, idx) => (
                      <div
                        key={idx}
                        className="bg-[#141414] hover:bg-[#1C1C1C] border border-white/5 p-4 flex flex-col justify-between group transition-all duration-300 rounded-none hover:border-[#32BBED]/25 relative"
                      >
                        <div className="flex justify-between items-start gap-4 mb-2">
                          <div className="space-y-1">
                            <h5 className="font-serif text-xs uppercase tracking-wider text-white group-hover:text-[#32BBED] transition-colors">
                              {item.name}
                            </h5>
                            {item.description && (
                              <p className="text-[11px] text-gray-500 leading-relaxed font-sans line-clamp-2">
                                {item.description}
                              </p>
                            )}
                          </div>
                          <div className="text-right shrink-0">
                            <span className="text-xs font-mono font-bold text-[#32BBED] bg-[#32BBED]/5 border border-[#32BBED]/10 px-2 py-1 block">
                              ৳{item.price}
                            </span>
                          </div>
                        </div>

                        {/* Hover Overlay Button */}
                        <div className="mt-3 pt-3 border-t border-white/5 flex justify-end">
                          <button
                            onClick={() => handleBookNow(`${group.category.replace(' LIST', '')} - ${item.name}`)}
                            className="text-[10px] font-serif uppercase tracking-widest text-gray-400 hover:text-[#32BBED] flex items-center gap-1.5 transition-colors cursor-pointer"
                          >
                            <span>Book Grooming</span>
                            <LucideIcon name="ChevronRight" size={12} />
                          </button>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              ))
            )}
          </motion.div>
        ) : (
          <motion.div
            key="packages"
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -10 }}
            transition={{ duration: 0.3 }}
            className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            {filteredPackages.length === 0 ? (
              <div className="col-span-full text-center py-16 border border-white/5 bg-[#141414]">
                <div className="text-[#32BBED] mb-3 flex justify-center">
                  <LucideIcon name="Search" size={32} />
                </div>
                <h4 className="font-serif text-sm uppercase tracking-wider text-white">No Package Matches Found</h4>
                <p className="text-xs text-gray-500 mt-1">Search via package titles or embedded treatment names.</p>
              </div>
            ) : (
              filteredPackages.map((pkg, idx) => (
                <div
                  key={idx}
                  className="bg-[#1C1C1C] border border-[#32BBED]/25 hover:border-[#32BBED] flex flex-col justify-between p-6 relative rounded-none hover:shadow-2xl hover:shadow-[#32BBED]/5 transition-all duration-300"
                >
                  {/* Discount Badge */}
                  <div className="absolute top-0 right-0 bg-[#32BBED] text-black text-[9px] font-mono uppercase px-3 py-1 font-black tracking-widest">
                    Save ৳{pkg.discount}
                  </div>

                  <div className="space-y-6">
                    <div>
                      <span className="text-[9px] font-mono tracking-[0.25em] text-[#32BBED] uppercase block">
                        {pkg.multiday ? 'MULTI-DAY EXPEDITION' : 'SIGNATURE PACKAGE'}
                      </span>
                      <h4 className="font-serif text-base uppercase tracking-wider text-white mt-1">
                        {pkg.name}
                      </h4>
                    </div>

                    {/* Rates block */}
                    <div className="border-y border-white/5 py-4 flex items-center justify-between">
                      <div>
                        <span className="text-[9px] text-gray-500 uppercase tracking-widest block">Original Rate</span>
                        <span className="text-xs line-through text-gray-500">৳{pkg.originalPrice}</span>
                      </div>
                      <div className="text-right">
                        <span className="text-[9px] text-[#32BBED] uppercase tracking-widest block font-bold">Package Price</span>
                        <span className="text-lg font-serif font-black text-[#32BBED]">৳{pkg.price}</span>
                      </div>
                    </div>

                    {/* Inclusions */}
                    <div className="space-y-2.5">
                      <span className="text-[10px] font-mono tracking-widest text-gray-400 uppercase block">
                        Included treatments:
                      </span>
                      <ul className="space-y-2">
                        {pkg.includes.map((inc, index) => (
                          <li key={index} className="flex items-start gap-2 text-xs text-gray-300">
                            <div className="w-1.5 h-1.5 bg-[#32BBED] rotate-45 shrink-0 mt-1.5"></div>
                            <span className="font-sans leading-tight">{inc}</span>
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>

                  <div className="mt-8 border-t border-white/5 pt-4">
                    <button
                      onClick={() => handleBookNow(`Package: ${pkg.name}`)}
                      className="w-full py-3 border border-[#32BBED] text-[#32BBED] bg-transparent font-serif text-[11px] font-bold uppercase tracking-widest hover:bg-[#32BBED] hover:text-black transition-all cursor-pointer text-center"
                    >
                      Book Package Experience
                    </button>
                  </div>
                </div>
              ))
            )}
          </motion.div>
        )}
      </AnimatePresence>

      {/* Static pricing info bar */}
      <div className="mt-12 p-4 bg-[#141414] border-l-2 border-[#32BBED] flex flex-col sm:flex-row items-center justify-between gap-4 text-[10px] text-gray-400 tracking-[0.1em] uppercase">
        <div className="flex items-center gap-2">
          <LucideIcon name="ShieldAlert" size={14} className="text-[#32BBED]" />
          <span>Rates are subjected to premium brand products and expert consultation fees.</span>
        </div>
        <span>Adonis Dhaka Men's Grooming Co. Ltd.</span>
      </div>
    </div>
  );
};

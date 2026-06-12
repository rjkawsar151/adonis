import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { LucideIcon } from './LucideIcon';
import { IMAGES } from '../data';
import { navigateTo } from '../navigation';

export const Header: React.FC = () => {
  const [isScrolled, setIsScrolled] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [currentPath, setCurrentPath] = useState(window.location.pathname);
  const whatsappUrl = `https://wa.me/8801919700800?text=${encodeURIComponent("Hello Adonis, I want to book a grooming appointment. Please share available slots.")}`;

  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 50) {
        setIsScrolled(true);
      } else {
        setIsScrolled(false);
      }
    };
    window.addEventListener('scroll', handleScroll);

    const handleNavigation = (e: Event) => {
      const customEvent = e as CustomEvent<string>;
      setCurrentPath(customEvent.detail);
    };
    window.addEventListener('adonis-navigate', handleNavigation);

    const handlePopState = () => {
      setCurrentPath(window.location.pathname);
    };
    window.addEventListener('popstate', handlePopState);

    return () => {
      window.removeEventListener('scroll', handleScroll);
      window.removeEventListener('adonis-navigate', handleNavigation);
      window.removeEventListener('popstate', handlePopState);
    };
  }, []);

  const navItems = [
    { label: 'Lounge', href: '/', hash: '#hero-section' },
    { label: 'Services', href: '/services/gulshan' },
    { label: 'About Us', href: '/about' },
    { label: 'Blog', href: '/blog' },
    { label: 'Booking', href: '/', hash: '#booking-section' },
    { label: 'Locations', href: '/', hash: '#branch-section' }
  ];

  const handleNavClick = (e: React.MouseEvent<HTMLAnchorElement>, href: string, hash?: string) => {
    e.preventDefault();
    setMobileMenuOpen(false);

    const isSamePage = currentPath === href;

    if (!isSamePage) {
      navigateTo(href);
    }

    if (hash) {
      // If we switched pages, wait for the page to render, then scroll
      const delay = isSamePage ? 0 : 200;
      setTimeout(() => {
        const element = document.querySelector(hash);
        if (element) {
          const topOffset = 80; // height of sticky header
          const elementPosition = element.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - topOffset;
          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });
        }
      }, delay);
    }
  };

  return (
    <>
      <header
        className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${isScrolled
            ? 'glass-panel border-b border-gold-400/10 py-3.5 shadow-lg'
            : 'bg-transparent py-6 border-b border-white/0'
          }`}
      >
        <div className="max-w-7xl mx-auto px-4 md:px-8 flex items-center justify-between">
          {/* Logo */}
          <a
            href="/"
            onClick={(e) => handleNavClick(e, '/', '#hero-section')}
            className="flex items-center group"
          >
            <img
              src={IMAGES.logo}
              alt="Adonis Logo"
              referrerPolicy="no-referrer"
              className="h-10 md:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105"
            />
          </a>

          {/* Desktop Navigation */}
          <nav className="hidden lg:flex items-center space-x-7">
            {navItems.map((item) => (
              <a
                key={item.label}
                href={item.hash ? `${item.href}${item.hash}` : item.href}
                onClick={(e) => handleNavClick(e, item.href, item.hash)}
                className={`text-xs font-sans uppercase tracking-[0.15em] transition-all duration-300 relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:right-0 after:h-[1px] after:bg-gold-400 after:transition-transform after:duration-300 ${currentPath === item.href && !item.hash
                    ? 'text-gold-400 after:scale-x-100'
                    : 'text-gray-300 hover:text-gold-400 after:scale-x-0 hover:after:scale-x-100'
                  }`}
              >
                {item.label}
              </a>
            ))}
          </nav>

          {/* Booking Trigger CTA */}
          <div className="hidden lg:block">
            <a
              href="/#booking-section"
              onClick={(e) => handleNavClick(e, '/', '#booking-section')}
              className="px-6 py-2.5 bg-[#32BBED] text-black hover:bg-[#b08d3c] font-serif text-xs font-bold uppercase tracking-widest transition-all duration-350 cursor-pointer shadow-lg shadow-[#32BBED]/10 active:scale-95 text-center block"
            >
              Book Now
            </a>
          </div>

          {/* Mobile menu trigger */}
          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            className="lg:hidden p-1 text-white hover:text-gold-400 transition-colors"
            aria-label="Toggle Menu"
          >
            <LucideIcon name={mobileMenuOpen ? 'X' : 'Menu'} size={24} />
          </button>
        </div>

        {/* Mobile Navigation Drawer */}
        <AnimatePresence>
          {mobileMenuOpen && (
            <motion.div
              initial={{ opacity: 0, height: 0 }}
              animate={{ opacity: 1, height: 'auto' }}
              exit={{ opacity: 0, height: 0 }}
              transition={{ duration: 0.3 }}
              className="lg:hidden bg-salon-black border-b border-gold-400/15 overflow-hidden"
            >
              <div className="px-4 py-6 space-y-4 flex flex-col">
                {navItems.map((item) => (
                  <a
                    key={item.label}
                    href={item.hash ? `${item.href}${item.hash}` : item.href}
                    onClick={(e) => handleNavClick(e, item.href, item.hash)}
                    className={`text-xs font-sans uppercase tracking-widest py-1 transition-colors ${currentPath === item.href && !item.hash ? 'text-gold-400' : 'text-gray-300 hover:text-gold-400'
                      }`}
                  >
                    {item.label}
                  </a>
                ))}
                <div className="pt-2 border-t border-white/5">
                  <a
                    href="/#booking-section"
                    onClick={(e) => handleNavClick(e, '/', '#booking-section')}
                    className="w-full text-center px-6 py-3 bg-[#32BBED] text-black font-serif text-xs font-bold uppercase tracking-widest transition-all block"
                  >
                    Secure Grooming Slot
                  </a>
                </div>
              </div>
            </motion.div>
          )}
        </AnimatePresence>
      </header>

      {/* Floating Action Button (FAB) */}
      <div className="fixed bottom-6 right-4 z-40 filter drop-shadow-[0_4px_12px_rgba(200,162,74,0.35)]">
        <a
          href={whatsappUrl}
          target="_blank"
          rel="noopener noreferrer"
          className="flex items-center gap-2 px-4 py-2.5 rounded-full bg-[#25D366] text-black font-serif text-[10px] font-bold uppercase tracking-widest hover:bg-[#1ebe5d] transition-all active:scale-95 shadow-xl border border-white/10"
          aria-label="Chat with Adonis on WhatsApp"
        >
          <LucideIcon name="MessageCircle" size={14} />
          <span>WhatsApp</span>
        </a>
      </div>
    </>
  );
};

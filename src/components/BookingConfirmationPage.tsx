import React, { useEffect, useState } from 'react';
import { motion } from 'motion/react';
import { BRANCHES } from '../data';
import { Booking, Service } from '../types';
import { LucideIcon } from './LucideIcon';
import { navigateTo } from '../navigation';

interface BookingConfirmationPageProps {
  services: Service[];
}

export const BookingConfirmationPage: React.FC<BookingConfirmationPageProps> = ({ services }) => {
  const [booking, setBooking] = useState<Booking | null>(null);
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    try {
      // Attempt to retrieve the latest booking from localStorage
      const latest = localStorage.getItem('latestBooking');
      if (latest) {
        setBooking(JSON.parse(latest));
      } else {
        // Fallback to checking the first item of list if any
        const list = JSON.parse(localStorage.getItem('adonisBookings') || '[]');
        if (list.length > 0) {
          setBooking(list[0]);
        }
      }
    } catch (err) {
      console.error('Failed to read booking from localStorage', err);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    if (booking) {
      const fbq = (window as any).fbq;
      if (fbq) {
        fbq('track', 'Schedule', {
          content_name: 'Salon Appointment',
          value: 0.00,
          currency: 'BDT'
        });
      }
    }
  }, [booking]);

  if (loading) {
    return (
      <div className="w-full bg-salon-black min-h-screen text-white pt-32 pb-24 flex items-center justify-center">
        <div className="text-xs font-mono tracking-widest text-gold-400 uppercase animate-pulse">
          Retrieving reservation record...
        </div>
      </div>
    );
  }

  // Graceful fallback for direct visits/no bookings
  if (!booking) {
    return (
      <div className="w-full bg-salon-black min-h-screen text-white pt-32 pb-24 flex items-center justify-center relative overflow-hidden">
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-[600px] w-[600px] rounded-full bg-gold-400/5 blur-[150px] pointer-events-none"></div>
        <div className="max-w-md w-full mx-auto px-6 text-center relative z-10 space-y-8">
          <div className="relative inline-block select-none">
            <LucideIcon name="ShieldAlert" size={48} className="text-gold-400/60 mx-auto mb-4" />
            <h1 className="font-serif text-2xl uppercase tracking-wider text-white">
              No Active Reservation
            </h1>
          </div>
          <p className="text-xs text-gray-400 leading-relaxed font-light max-w-sm mx-auto">
            We couldn't locate any recent booking confirmation details in this browser session. Let us guide you back to the booking desk.
          </p>
          <div className="flex flex-col sm:flex-row gap-3 justify-center pt-4">
            <button
              onClick={() => navigateTo('/')}
              className="px-6 py-3 bg-[#32BBED] hover:bg-[#b08d3c] text-black font-serif text-[10px] font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer text-center"
            >
              Go to Lounge Home
            </button>
            <button
              onClick={() => {
                navigateTo('/book');
              }}
              className="px-6 py-3 bg-salon-gray hover:bg-white/10 border border-white/10 text-white font-serif text-[10px] font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer text-center"
            >
              Book Reservation
            </button>
          </div>
        </div>
      </div>
    );
  }

  const getBranchDetails = (bId: string) => BRANCHES.find(b => b.id === bId);
  const getBranchName = (bId: string) => getBranchDetails(bId)?.name || bId;
  const getBranchPhone = (bId: string) => getBranchDetails(bId)?.phoneNumbers?.[0] || '+880 1919-700800';
  const getTelHref = (phone: string) => `tel:${phone.replace(/[^\d+]/g, '')}`;
  const formatBookingDate = (bookingDate: string) => new Date(bookingDate).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  const getServiceName = (sId?: string) => {
    if (!sId) return 'General Appointment';
    const s = services.find(sv => sv.id === sId);
    return s ? `${s.name} (৳${s.priceBDT})` : sId;
  };

  const getWhatsAppUrl = (b: Booking) => {
    const branch = getBranchDetails(b.branchId);
    const branchPhone = getBranchPhone(b.branchId);
    const cleanPhone = branchPhone.replace(/[^\d]/g, '');
    const serviceName = getServiceName(b.serviceId);
    const formattedDate = formatBookingDate(b.date);

    const text = `Hello Adonis Men's Grooming, I would like to confirm my booking:
- Booking Code: ${b.bookingCode}
- Name: ${b.clientName}
- Phone: ${b.clientPhone}
- Branch: ${branch?.name || b.branchId}
- Service: ${serviceName}
- Date: ${formattedDate}
- Time: ${b.time}
${b.notes ? `- Notes: ${b.notes}` : ''}`;

    return `https://wa.me/${cleanPhone}?text=${encodeURIComponent(text)}`;
  };

  const branch = getBranchDetails(booking.branchId);
  const branchPhone = getBranchPhone(booking.branchId);

  return (
    <div className="w-full bg-salon-black min-h-screen text-white pt-32 pb-24 relative overflow-hidden flex items-center justify-center">
      {/* Decorative premium radial glows */}
      <div className="absolute top-[20%] left-[-10%] h-[500px] w-[500px] rounded-full bg-gold-400/5 blur-[120px] pointer-events-none"></div>
      <div className="absolute bottom-[20%] right-[-10%] h-[500px] w-[500px] rounded-full bg-gold-400/5 blur-[120px] pointer-events-none"></div>

      <div className="max-w-xl w-full mx-auto px-6 relative z-10 space-y-8">
        {/* Success header animation */}
        <div className="text-center space-y-2">
          <motion.div
            initial={{ scale: 0 }}
            animate={{ scale: 1 }}
            transition={{ type: 'spring', stiffness: 200, damping: 15 }}
            className="inline-flex items-center justify-center h-16 w-16 rounded-full bg-green-500/10 border border-green-500 text-green-400 mb-2"
          >
            <LucideIcon name="Check" size={32} />
          </motion.div>
          <motion.h2
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.15 }}
            className="font-serif text-2xl sm:text-3xl uppercase tracking-wider text-green-400"
          >
            Booking Confirmed!
          </motion.h2>
          <motion.p
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ delay: 0.3 }}
            className="text-xs text-gray-400 max-w-sm mx-auto font-sans leading-relaxed"
          >
            Your premium grooming session is successfully reserved. Our team looks forward to welcoming you.
          </motion.p>
        </div>

        {/* Boarding Pass receipt container */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ type: 'spring', stiffness: 100, damping: 20, delay: 0.4 }}
          className="bg-salon-gray border border-gold-400/30 relative overflow-hidden shadow-2xl"
        >
          <div className="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-gold-400 via-gold-500 to-gold-400"></div>

          {/* Ticket Header */}
          <div className="p-4 border-b border-gold-400/10 flex items-center justify-between">
            <span className="text-[10px] font-mono text-gold-400 uppercase tracking-widest">
              ADONIS LOUNGE TERMINAL
            </span>
            <span className="bg-gold-400/15 text-gold-400 font-mono text-xs px-3 py-1 uppercase tracking-widest border border-gold-400/30 font-bold">
              {booking.bookingCode}
            </span>
          </div>

          {/* Ticket details */}
          <div className="p-6 grid grid-cols-2 gap-y-4 gap-x-2 text-xs text-left">
            <div>
              <span className="text-[9px] text-gray-500 font-mono uppercase tracking-widest block">VIP CLIENT</span>
              <span className="text-white font-serif uppercase tracking-wider font-semibold mt-0.5 block truncate">
                {booking.clientName}
              </span>
            </div>
            <div>
              <span className="text-[9px] text-gray-500 font-mono uppercase tracking-widest block">CLIENT PHONE</span>
              <span className="text-white font-mono mt-0.5 block">
                {booking.clientPhone}
              </span>
            </div>

            <div className="col-span-2 border-t border-white/5 pt-3.5">
              <span className="text-[9px] text-gray-500 font-mono uppercase tracking-widest block">LOUNGE ASSIGNMENT</span>
              <span className="text-white font-sans mt-0.5 block font-medium">
                {getBranchName(booking.branchId)}
              </span>
            </div>

            <div className="col-span-2 border-t border-white/5 pt-3.5">
              <span className="text-[9px] text-gray-500 font-mono uppercase tracking-widest block">SELECTED SERVICE</span>
              <span className="text-white font-serif mt-0.5 block text-xs">
                {getServiceName(booking.serviceId)}
              </span>
            </div>

            <div className="border-t border-white/5 pt-3.5">
              <span className="text-[9px] text-gray-500 font-mono uppercase tracking-widest block">DATE SECURED</span>
              <span className="text-white font-sans font-medium mt-0.5 block text-xs">
                {formatBookingDate(booking.date)}
              </span>
            </div>

            <div className="border-t border-white/5 pt-3.5">
              <span className="text-[9px] text-gray-500 font-mono uppercase tracking-widest block">RESERVED TIME</span>
              <span className="text-gold-400 font-serif uppercase tracking-wide mt-0.5 block text-sm font-semibold">
                {booking.time}
              </span>
            </div>

            {booking.notes && (
              <div className="col-span-2 border-t border-white/5 pt-3.5">
                <span className="text-[9px] text-gray-500 font-mono uppercase tracking-widest block">TREATMENT NOTE</span>
                <span className="text-gray-400 italic font-sans mt-0.5 block text-[11px] leading-relaxed">
                  "{booking.notes}"
                </span>
              </div>
            )}
          </div>

          {/* Ticket perforations decoration */}
          <div className="absolute bottom-[72px] -left-3 h-6 w-6 rounded-full bg-salon-black border-r border-gold-400/30"></div>
          <div className="absolute bottom-[72px] -right-3 h-6 w-6 rounded-full bg-salon-black border-l border-gold-400/30"></div>
          <div className="border-t border-dashed border-gold-400/30 mx-4 my-2"></div>

          {/* Ticket Footer details */}
          <div className="p-4 bg-salon-black/40 text-center">
            <p className="text-[9px] text-gray-500 uppercase tracking-widest">
              Please present confirmation code upon arrival at lounge front desk.
            </p>
          </div>
        </motion.div>

        {/* Premium Action bridges */}
        <motion.div
          initial={{ opacity: 0, y: 15 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.6 }}
          className="border border-gold-400/20 bg-salon-gray/50 p-5 space-y-4 text-left shadow-xl"
        >
          <div className="space-y-1.5 pb-2 border-b border-white/5">
            <span className="text-[9px] text-gold-400 font-mono uppercase tracking-widest block font-bold">
              LOUNGE DIRECT CONTACT
            </span>
            <p className="text-xs text-gray-300 font-sans leading-relaxed">
              {branch?.address || 'Dhaka, Bangladesh'}
            </p>
            <p className="text-xs text-gray-400 font-mono uppercase tracking-widest">
              Phone Line: <span className="text-white">{branchPhone}</span>
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <a
              href={getWhatsAppUrl(booking)}
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center justify-center gap-2 px-4 py-3 bg-[#25D366] hover:bg-[#1ebe5d] text-black font-serif text-[10px] font-bold uppercase tracking-widest transition-all"
            >
              <LucideIcon name="MessageCircle" size={15} />
              Confirm via WhatsApp
            </a>

            <a
              href={getTelHref(branchPhone)}
              className="flex items-center justify-center gap-2 px-4 py-3 border border-white/10 text-white hover:bg-white/5 font-serif text-[10px] font-bold uppercase tracking-widest transition-all"
            >
              <LucideIcon name="PhoneCall" size={15} />
              Call Lounge Deck
            </a>

          </div>
        </motion.div>

        {/* Go back CTA */}
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.8 }}
          className="flex justify-center"
        >
          <button
            onClick={() => navigateTo('/')}
            className="px-10 py-3.5 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-xs font-bold uppercase tracking-widest transition-all cursor-pointer font-semibold shadow-lg shadow-gold-400/10"
          >
            Return to Lounge Home
          </button>
        </motion.div>
      </div>
    </div>
  );
};

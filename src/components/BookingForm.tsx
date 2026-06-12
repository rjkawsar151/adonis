import React, { useState, useEffect } from 'react';
import { motion } from 'motion/react';
import { BRANCHES, OPEN_HOURS, COMPLETE_PRICE_LIST } from '../data';
import { Booking, Service } from '../types';
import { LucideIcon } from './LucideIcon';

const ADMIN_BOOKING_EMAILS = [
  'info@adonis.com.bd',
  'booking@adonis.com.bd',
  'mens.amgcl@gmail.com'
];

interface BookingFormProps {
  initialBranchId?: string;
  initialServiceId?: string;
  onBookingSuccess?: (booking: Booking) => void;
  services: Service[];
}

export const BookingForm: React.FC<BookingFormProps> = ({
  initialBranchId = 'gulshan',
  initialServiceId = '',
  onBookingSuccess,
  services
}) => {
  // Booking Form State
  const [selectedBranch, setSelectedBranch] = useState<string>(initialBranchId);
  const [selectedService, setSelectedService] = useState<string>(initialServiceId);

  const [clientName, setClientName] = useState<string>('');
  const [clientPhone, setClientPhone] = useState<string>('');
  const [clientEmail, setClientEmail] = useState<string>('');
  const [date, setDate] = useState<string>('');
  const [time, setTime] = useState<string>('');
  const [notes, setNotes] = useState<string>('');

  // UI State
  const [successBooking, setSuccessBooking] = useState<Booking | null>(null);
  const [errorMsg, setErrorMsg] = useState<string>('');
  const [submitting, setSubmitting] = useState<boolean>(false);
  const [phpMailerOk, setPhpMailerOk] = useState<boolean | null>(null);
  const [emailStatus, setEmailStatus] = useState<'pending' | 'sent' | 'failed' | null>(null);

  // Service search & category accordion state
  const [searchQuery, setSearchQuery] = useState<string>('');
  const [openCategories, setOpenCategories] = useState<Set<string>>(new Set());

  // Check if PHP mailer is available
  useEffect(() => {
    fetch('./sendmail.php?test=1')
      .then(r => r.json()).then(d => {
        setPhpMailerOk(d.success === true);
        d.success && console.log('sendmail.php available, methods:', d.methods);
      }).catch(() => setPhpMailerOk(false));
  }, []);

  // Update selection if prop changes
  useEffect(() => {
    if (initialServiceId) {
      setSelectedService(initialServiceId);
    }
  }, [initialServiceId]);

  useEffect(() => {
    setSelectedBranch(initialBranchId);
    if (initialBranchId === 'gulshan' && ['08:00 PM', '09:00 PM'].includes(time)) {
      setTime('');
    }
  }, [initialBranchId, time]);

  const handleBranchChange = (branchId: string) => {
    setSelectedBranch(branchId);
    if (branchId === 'gulshan' && ['08:00 PM', '09:00 PM'].includes(time)) {
      setTime('');
    }
  };



  // Generate date options for the next 7 days in a premium picker
  const getDateOptions = () => {
    const options = [];
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    for (let i = 0; i < 7; i++) {
      const d = new Date();
      d.setDate(d.getDate() + i);

      const dayName = days[d.getDay()];
      const dateNum = d.getDate();
      const monthName = months[d.getMonth()];
      const year = d.getFullYear();
      const isoString = d.toISOString().split('T')[0];

      options.push({
        isoString,
        formatted: `${dayName}, ${monthName} ${dateNum}`,
        dayName,
        dateNum,
        monthName
      });
    }
    return options;
  };

  const timeSlots = [
    '10:00 AM',
    '11:15 AM',
    '12:30 PM',
    '01:45 PM',
    '03:00 PM',
    '04:15 PM',
    '05:30 PM',
    '06:45 PM',
    '08:00 PM',
    '09:00 PM'
  ];
  const selectedBranchDetails = BRANCHES.find(branch => branch.id === selectedBranch);
  const availableTimeSlots = selectedBranch === 'gulshan'
    ? timeSlots.filter(slot => !['08:00 PM', '09:00 PM'].includes(slot))
    : timeSlots;

  // Toggle a category open/closed
  const toggleCategory = (cat: string) => {
    setOpenCategories(prev => {
      const next = new Set(prev);
      if (next.has(cat)) next.delete(cat);
      else next.add(cat);
      return next;
    });
  };

  // Filtered price list based on search query
  const filteredPriceList = COMPLETE_PRICE_LIST
    .map(group => ({
      ...group,
      items: group.items.filter(item =>
        !searchQuery.trim() ||
        item.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
        (item.description || '').toLowerCase().includes(searchQuery.toLowerCase())
      )
    }))
    .filter(group => group.items.length > 0);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrorMsg('');

    // Verification
    if (!selectedBranch) {
      setErrorMsg('Please select your preferred Adonis branch.');
      return;
    }
    if (!date) {
      setErrorMsg('Please choose an appointment date.');
      return;
    }
    if (!time) {
      setErrorMsg('Please choose an available luxury time slot.');
      return;
    }
    if (!clientName.trim()) {
      setErrorMsg('Please enter your name for VIP client logging.');
      return;
    }
    if (!clientPhone.trim() || clientPhone.length < 8) {
      setErrorMsg('Please enter a valid phone number for details verification.');
      return;
    }
    if (clientEmail.trim() && !clientEmail.includes('@')) {
      setErrorMsg('Please enter a valid email address, or leave the email field blank.');
      return;
    }

    setSubmitting(true);

    // Generate unique boarding pass confirmation code
    const randAlpha = Array.from({ length: 3 }, () => String.fromCharCode(65 + Math.floor(Math.random() * 26))).join('');
    const randNum = Math.floor(100 + Math.random() * 900);
    const bookingCode = `AD-${randAlpha}-${randNum}`;

    const newBooking: Booking = {
      id: crypto.randomUUID ? crypto.randomUUID() : Math.random().toString(36).substring(2, 9),
      clientName,
      clientPhone,
      clientEmail: clientEmail.trim(),
      branchId: selectedBranch,
      serviceId: selectedService || undefined,
      barberId: undefined,
      date,
      time,
      notes,
      bookingCode
    };

    try {
      const savedBookings = JSON.parse(localStorage.getItem('adonisBookings') || '[]');
      localStorage.setItem('adonisBookings', JSON.stringify([newBooking, ...savedBookings].slice(0, 50)));

      setSuccessBooking(newBooking);
      if (onBookingSuccess) {
        onBookingSuccess(newBooking);
      }

      // Reset Form fields
      setClientName('');
      setClientPhone('');
      setClientEmail('');
      setNotes('');
      setTime('');
      setDate('');

      const branchInfo = BRANCHES.find(b => b.id === selectedBranch);
      const svcInfo = services.find(s => s.id === selectedService);
      fetch('./sendmail.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          clientName: newBooking.clientName,
          clientPhone: newBooking.clientPhone,
          clientEmail: newBooking.clientEmail,
          branchId: newBooking.branchId,
          branchName: branchInfo?.name || selectedBranch,
          serviceName: svcInfo?.name || selectedService || 'General Appointment',
          date: newBooking.date,
          time: newBooking.time,
          notes: newBooking.notes,
          bookingCode: newBooking.bookingCode
        })
      }).then(r => r.json()).then(d => {
        if (d.success) { setEmailStatus('sent'); console.log('sendmail.php:', d.method, d); }
        else { setEmailStatus('failed'); console.error('sendmail.php error:', d.error); }
      }).catch(e => { setEmailStatus('failed'); console.error('sendmail.php fetch failed:', e); });
    } catch (err) {
      console.error(err);
      setErrorMsg('Booking could not be saved in this browser. Please call the branch or try again in a moment.');
    } finally {
      setSubmitting(false);
    }
  };

  const getBranchName = (bId: string) => BRANCHES.find(b => b.id === bId)?.name || bId;
  const getBranchDetails = (bId: string) => BRANCHES.find(b => b.id === bId);
  const getBranchPhone = (bId: string) => getBranchDetails(bId)?.phoneNumbers?.[0] || '+880 1919-700800';
  const getTelHref = (phone: string) => `tel:${phone.replace(/[^\d+]/g, '')}`;
  const formatBookingDate = (bookingDate: string) => new Date(bookingDate).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
  const getServiceName = (sId?: string) => {
    if (!sId) return 'General Appointment (No service selected)';
    const s = services.find(sv => sv.id === sId);
    return s ? `${s.name} (৳${s.priceBDT})` : sId;
  };

  const getConfirmationMailto = (booking: Booking) => {
    const branch = getBranchDetails(booking.branchId);
    const branchPhone = getBranchPhone(booking.branchId);
    const subject = `Adonis booking confirmation ${booking.bookingCode}`;
    const body = [
      `Dear ${booking.clientName},`,
      '',
      'Thanks for booking with Adonis Men\'s Grooming Salon.',
      '',
      `Booking code: ${booking.bookingCode}`,
      `Service: ${getServiceName(booking.serviceId)}`,
      `Date: ${formatBookingDate(booking.date)}`,
      `Time: ${booking.time}`,
      `Branch: ${branch?.name || getBranchName(booking.branchId)}`,
      `Address: ${branch?.address || ''}`,
      `Phone: ${branchPhone}`,
      '',
      'For any changes, please call the branch directly.',
      '',
      'Adonis Men\'s Grooming Salon'
    ].join('\n');

    return `mailto:${encodeURIComponent(booking.clientEmail)}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
  };

  const getAdminBookingMailto = (booking: Booking) => {
    const branch = getBranchDetails(booking.branchId);
    const branchPhone = getBranchPhone(booking.branchId);
    const subject = `[New Booking] ${booking.bookingCode} - ${booking.clientName}`;
    const body = [
      'New booking from Adonis website.',
      '',
      `Booking code: ${booking.bookingCode}`,
      `Client name: ${booking.clientName}`,
      `Client phone: ${booking.clientPhone}`,
      `Client email: ${booking.clientEmail || 'Not provided'}`,
      `Service: ${getServiceName(booking.serviceId)}`,
      `Date: ${formatBookingDate(booking.date)}`,
      `Time: ${booking.time}`,
      `Branch: ${branch?.name || getBranchName(booking.branchId)}`,
      `Address: ${branch?.address || ''}`,
      `Branch phone: ${branchPhone}`,
      booking.notes ? `Notes: ${booking.notes}` : '',
      '',
      'This email was prepared by the static website booking form.'
    ].filter(Boolean).join('\n');

    return `mailto:${ADMIN_BOOKING_EMAILS.join(',')}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
  };

  const getWhatsAppUrl = (booking: Booking) => {
    const branch = getBranchDetails(booking.branchId);
    const branchPhone = getBranchPhone(booking.branchId);
    const cleanPhone = branchPhone.replace(/[^\d]/g, '');
    const serviceName = getServiceName(booking.serviceId);
    const formattedDate = formatBookingDate(booking.date);

    const text = `Hello Adonis Men's Grooming, I would like to confirm my booking:
- Booking Code: ${booking.bookingCode}
- Name: ${booking.clientName}
- Phone: ${booking.clientPhone}
- Branch: ${branch?.name || booking.branchId}
- Service: ${serviceName}
- Date: ${formattedDate}
- Time: ${booking.time}
${booking.notes ? `- Notes: ${booking.notes}` : ''}`;

    return `https://wa.me/${cleanPhone}?text=${encodeURIComponent(text)}`;
  };

  return (
    <div className="w-full max-w-4xl mx-auto rounded-none border border-gold-400/20 bg-salon-black text-white overflow-hidden shadow-2xl relative">
      <div className="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-gold-400 to-transparent"></div>

      {/* Tab Selectors */}
      <div className="border-b border-gold-400/10">
        <div className="py-4 text-center text-sm tracking-widest font-serif uppercase text-gold-400 bg-salon-gray border-b-2 border-gold-400 font-semibold">
          Reserve Grooming Session
        </div>
      </div>

      <div className="p-6 md:p-10">
        {!successBooking ? (
                <form onSubmit={handleSubmit} className="space-y-8">
                  {/* Branch Selection */}
                  <div>
                    <label className="block text-xs font-serif uppercase tracking-widest text-gold-400 mb-3">
                      Step 1: Choose Lounge Location
                    </label>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      {BRANCHES.map(branch => (
                        <div
                          key={branch.id}
                          onClick={() => handleBranchChange(branch.id)}
                          className={`cursor-pointer p-4 rounded-none border transition-all duration-300 relative ${selectedBranch === branch.id
                              ? 'border-gold-400 bg-salon-gray/80 gold-border-glow'
                              : 'border-white/10 bg-salon-black hover:border-gold-400/50'
                            }`}
                        >
                          <div className="flex items-start justify-between">
                            <div>
                              <h4 className="font-serif text-sm tracking-wider uppercase flex items-center gap-2">
                                <LucideIcon name="MapPin" className="text-gold-400" size={16} />
                                {branch.name}
                              </h4>
                              <p className="text-xs text-gray-400 mt-2 font-sans line-clamp-2">
                                {branch.address}
                              </p>
                              {branch.hours && (
                                <p className="text-[10px] text-gold-400/80 mt-2 font-mono uppercase tracking-widest">
                                  Hours: {branch.hours}
                                </p>
                              )}
                            </div>
                            <div className="flex items-center justify-center h-5 w-5 rounded-full border border-gold-400/40">
                              {selectedBranch === branch.id && (
                                <div className="h-2.5 w-2.5 rounded-full bg-gold-400"></div>
                              )}
                            </div>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>

                  {/* Service Selection */}
                  <div>
                    <label className="block text-xs font-serif uppercase tracking-widest text-gold-400 mb-2">
                      Step 2: Select Grooming Service (Optional)
                    </label>

                    {/* Search bar */}
                    <div className="relative mb-3">
                      <div className="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                        <LucideIcon name="Search" size={13} className="text-gold-400/50" />
                      </div>
                      <input
                        type="text"
                        value={searchQuery}
                        onChange={e => setSearchQuery(e.target.value)}
                        placeholder="Search any service (e.g. Facial, Massage, Hair Cut)..."
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 pl-8 pr-8 py-2.5 focus:outline-none focus:border-gold-400/50 transition-colors placeholder-gray-600"
                      />
                      {searchQuery && (
                        <button
                          type="button"
                          onClick={() => setSearchQuery('')}
                          className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white transition-colors"
                        >
                          <LucideIcon name="X" size={13} />
                        </button>
                      )}
                    </div>

                    {/* Active selection badge */}
                    {selectedService && (
                      <div className="mb-2 px-3 py-2 bg-gold-400/10 border border-gold-400/30 flex items-center justify-between">
                        <div className="flex items-center gap-2 min-w-0">
                          <LucideIcon name="CheckCircle" size={13} className="text-gold-400 shrink-0" />
                          <span className="text-[11px] text-white font-sans truncate">{selectedService}</span>
                        </div>
                        <button
                          type="button"
                          onClick={() => setSelectedService('')}
                          className="text-[10px] text-gold-400/70 hover:text-gold-400 font-mono uppercase tracking-widest shrink-0 ml-3 transition-colors"
                        >
                          Clear
                        </button>
                      </div>
                    )}

                    {/* Category accordion */}
                    <div
                      className="border border-white/5 bg-salon-black overflow-y-auto"
                      style={{ maxHeight: '280px' }}
                    >
                      {filteredPriceList.length === 0 ? (
                        <div className="px-4 py-8 text-center">
                          <LucideIcon name="SearchX" size={20} className="text-gray-600 mx-auto mb-2" />
                          <p className="text-xs text-gray-500 font-sans">No services match &ldquo;{searchQuery}&rdquo;</p>
                        </div>
                      ) : (
                        filteredPriceList.map(group => {
                          const isOpen = openCategories.has(group.category) || !!searchQuery.trim();
                          return (
                            <div key={group.category} className="border-b border-white/5 last:border-b-0">
                              {/* Category header */}
                              <button
                                type="button"
                                onClick={() => toggleCategory(group.category)}
                                className="w-full flex items-center justify-between px-3 py-2.5 hover:bg-salon-gray/20 transition-colors text-left"
                              >
                                <div className="flex items-center gap-2">
                                  <span className="text-[10px] font-mono tracking-widest text-gold-400 uppercase">{group.category}</span>
                                  <span className="text-[9px] text-gray-600 font-mono">({group.items.length})</span>
                                </div>
                                <LucideIcon
                                  name={isOpen ? 'ChevronUp' : 'ChevronDown'}
                                  size={12}
                                  className="text-gold-400/50 shrink-0"
                                />
                              </button>

                              {/* Service items */}
                              {isOpen && (
                                <div className="px-2 pb-2 space-y-1">
                                  {group.items.map(item => (
                                    <div
                                      key={item.name}
                                      onClick={() => setSelectedService(prev => prev === item.name ? '' : item.name)}
                                      className={`cursor-pointer px-3 py-2 flex items-center justify-between transition-all duration-150 ${
                                        selectedService === item.name
                                          ? 'bg-gold-400/12 border border-gold-400/40'
                                          : 'bg-salon-gray/15 border border-white/5 hover:border-white/15 hover:bg-salon-gray/30'
                                      }`}
                                    >
                                      <div className="flex items-center gap-2 min-w-0">
                                        {selectedService === item.name && (
                                          <div className="h-1.5 w-1.5 rounded-full bg-gold-400 shrink-0" />
                                        )}
                                        <div className="min-w-0">
                                          <p className="text-[11px] text-white font-sans leading-tight truncate">{item.name}</p>
                                          {item.description && (
                                            <p className="text-[9px] text-gray-500 font-sans mt-0.5 line-clamp-1">{item.description}</p>
                                          )}
                                        </div>
                                      </div>
                                      <span className="text-[11px] font-mono text-gold-400 font-semibold shrink-0 ml-2">{item.price}</span>
                                    </div>
                                  ))}
                                </div>
                              )}
                            </div>
                          );
                        })
                      )}
                    </div>
                  </div>

                  {/* Barber selection removed */}

                  {/* Date & Time Picker */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {/* Date */}
                    <div>
                      <label className="block text-xs font-serif uppercase tracking-widest text-gold-400 mb-3">
                        Step 3: Select Preferred Date
                      </label>
                      <div className="grid grid-cols-3 md:grid-cols-4 gap-2">
                        {getDateOptions().map(dt => (
                          <button
                            key={dt.isoString}
                            type="button"
                            onClick={() => setDate(dt.isoString)}
                            className={`p-2 transition-all duration-300 flex flex-col items-center justify-center ${date === dt.isoString
                                ? 'bg-gold-400 text-salon-black font-semibold border border-gold-400'
                                : 'bg-salon-gray/60 border border-white/5 text-gray-300 hover:border-gold-400/40'
                              }`}
                          >
                            <span className="text-[8px] uppercase tracking-widest">{dt.dayName}</span>
                            <span className="text-sm font-serif my-0.5">{dt.dateNum}</span>
                            <span className="text-[8px] uppercase font-mono tracking-wider">{dt.monthName}</span>
                          </button>
                        ))}
                      </div>
                    </div>

                    {/* Time */}
                    <div>
                      <label className="block text-xs font-serif uppercase tracking-widest text-gold-400 mb-3">
                        Step 4: Pick Luxury Slot
                      </label>
                      <div className="grid grid-cols-3 gap-2">
                        {availableTimeSlots.map(slot => (
                          <button
                            key={slot}
                            type="button"
                            onClick={() => setTime(slot)}
                            className={`p-2 text-xs transition-all duration-300 ${time === slot
                                ? 'bg-gold-400 text-salon-black font-semibold border border-gold-400'
                                : 'bg-salon-gray/60 border border-white/5 text-gray-300 hover:border-gold-400/40'
                              }`}
                          >
                            {slot}
                          </button>
                        ))}
                      </div>
                    </div>
                  </div>

                  {/* Client Details */}
                  <div className="border-t border-gold-400/15 pt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
                    <div>
                      <label className="block text-xs font-serif uppercase tracking-widest text-gold-400 mb-2">
                        VIP Name *
                      </label>
                      <input
                        type="text"
                        required
                        value={clientName}
                        onChange={(e) => setClientName(e.target.value)}
                        placeholder="e.g. Asif Chowdhury"
                        className="w-full bg-salon-gray text-white text-sm border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-serif uppercase tracking-widest text-gold-400 mb-2">
                        Phone Number *
                      </label>
                      <input
                        type="tel"
                        required
                        value={clientPhone}
                        onChange={(e) => setClientPhone(e.target.value)}
                        placeholder="e.g. +880 1919-700800"
                        className="w-full bg-salon-gray text-white text-sm border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-serif uppercase tracking-widest text-gold-400 mb-2">
                        Email Address (Optional)
                      </label>
                      <input
                        type="email"
                        value={clientEmail}
                        onChange={(e) => setClientEmail(e.target.value)}
                        placeholder="e.g. asif@mail.com"
                        className="w-full bg-salon-gray text-white text-sm border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>
                    <div className="md:col-span-3">
                      <label className="block text-xs font-serif uppercase tracking-widest text-gold-400 mb-2">
                        Special Instructions (Optional)
                      </label>
                      <textarea
                        rows={2}
                        value={notes}
                        onChange={(e) => setNotes(e.target.value)}
                        placeholder="Detail any allergies, specific fade requests, or beverage preference."
                        className="w-full bg-salon-gray text-white text-sm border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors resize-none"
                      />
                    </div>
                  </div>

                  {/* Feedback / Error */}
                  {errorMsg && (
                    <div className="p-3.5 bg-red-950/40 border border-red-500/30 text-red-400 text-xs tracking-wider flex items-center gap-2">
                      <LucideIcon name="ShieldAlert" size={16} className="shrink-0" />
                      <span>{errorMsg}</span>
                    </div>
                  )}

                  {/* Primary Submit */}
                  <div className="flex flex-col items-center justify-center space-y-3 pt-4">
                    <button
                      type="submit"
                      disabled={submitting}
                      className="w-full md:w-auto px-12 py-4 bg-[#32BBED] hover:bg-[#b08d3c] text-black font-serif text-xs font-bold uppercase tracking-widest transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-lg shadow-[#32BBED]/10 cursor-pointer disabled:opacity-50"
                    >
                      {submitting ? 'Registering...' : 'Authenticate VIP Booking'}
                    </button>
                    <p className="text-[10px] text-gray-500 text-center uppercase tracking-widest leading-relaxed">
                      YOUR TRANSFORMATION BEGINS WITH ONE APPOINTMENT. SELECTED LOUNGE HOURS: {selectedBranchDetails?.hours || OPEN_HOURS.hours}.
                    </p>
                  </div>
                </form>
              ) : (
                /* Success receipt - Luxury Boarding Pass design */
                <motion.div
                  initial={{ opacity: 0, scale: 0.95 }}
                  animate={{ opacity: 1, scale: 1 }}
                  transition={{ duration: 0.5 }}
                  className="space-y-6 max-w-lg mx-auto"
                >
                  <div className="text-center space-y-2">
                    <div className="inline-flex items-center justify-center h-14 w-14 rounded-full bg-green-500 text-white mb-1 animate-bounce">
                      <LucideIcon name="Check" size={28} />
                    </div>
                    <h3 className="font-serif text-2xl uppercase tracking-wider text-green-400">Booking Confirmed!</h3>
                    <p className="text-xs text-gray-300 max-w-sm mx-auto font-sans">
                      Your premium grooming session is reserved. Our team has received your booking request.
                    </p>
                  </div>

                  {/* Luxury Ticket Visual */}
                  <div className="bg-salon-gray border border-gold-400/35 relative overflow-hidden">
                    <div className="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gold-400 via-gold-500 to-gold-400"></div>

                    {/* Ticket Header */}
                    <div className="p-4 border-b border-gold-400/10 flex items-center justify-between">
                      <div className="flex items-center gap-2">
                        <span className="text-[10px] font-serif text-white/50 tracking-widest uppercase">ADONIS INTEL</span>
                      </div>
                      <span className="bg-gold-400/15 text-gold-400 font-mono text-[9px] px-2.5 py-0.5 uppercase tracking-widest border border-gold-400/20">
                        {successBooking.bookingCode}
                      </span>
                    </div>

                    {/* Ticket Grid info */}
                    <div className="p-5 grid grid-cols-2 gap-y-4 gap-x-2 text-xs text-left">
                      <div>
                        <span className="text-[9px] text-gold-400 font-mono uppercase tracking-widest block">VIP CLIENT</span>
                        <span className="text-white font-serif uppercase tracking-wide font-semibold mt-0.5 block">{successBooking.clientName}</span>
                      </div>
                      <div>
                        <span className="text-[9px] text-gold-400 font-mono uppercase tracking-widest block">CONTACT</span>
                        <span className="text-white font-sans mt-0.5 block">{successBooking.clientPhone}</span>
                      </div>

                      <div className="col-span-2 border-t border-white/5 pt-3">
                        <span className="text-[9px] text-gold-400 font-mono uppercase tracking-widest block">LOUNGE BRANCH</span>
                        <span className="text-white font-sans mt-0.5 block font-medium">
                          {getBranchName(successBooking.branchId)}
                        </span>
                      </div>

                      {successBooking.serviceId && (
                        <div className="col-span-2 border-t border-white/5 pt-3">
                          <span className="text-[9px] text-gold-400 font-mono uppercase tracking-widest block">SELECTED SERVICE</span>
                          <span className="text-white font-sans mt-0.5 block font-medium text-[11px]">
                            {getServiceName(successBooking.serviceId)}
                          </span>
                        </div>
                      )}

                      {/* Barber row removed */}

                      <div className="border-t border-white/5 pt-3">
                        <span className="text-[9px] text-gold-400 font-mono uppercase tracking-widest block">SERVICE TIMING</span>
                        <span className="text-white font-serif uppercase tracking-wide mt-0.5 block text-gold-400 font-medium">
                          {successBooking.time}
                        </span>
                      </div>

                      <div className="col-span-2 border-t border-white/5 pt-3">
                        <span className="text-[9px] text-gold-400 font-mono uppercase tracking-widest block">DATE SECURED</span>
                        <span className="text-white font-sans font-medium mt-0.5 block text-xs">
                          {formatBookingDate(successBooking.date)}
                        </span>
                      </div>

                      {successBooking.notes && (
                        <div className="col-span-2 border-t border-white/5 pt-3">
                          <span className="text-[9px] text-gold-400 font-mono uppercase tracking-widest block">TREATMENT NOTE</span>
                          <span className="text-gray-400 italic font-sans mt-0.5 block text-[11px] leading-relaxed">
                            "{successBooking.notes}"
                          </span>
                        </div>
                      )}
                    </div>

                    {/* Barbering Ticket Cutout Decorative lines */}
                    <div className="absolute bottom-12 -left-3 h-6 w-6 rounded-full bg-salon-black border-r border-gold-400/35"></div>
                    <div className="absolute bottom-12 -right-3 h-6 w-6 rounded-full bg-salon-black border-l border-gold-400/35"></div>
                    <div className="border-t-2 border-dashed border-gold-400/25 mx-4 my-2"></div>

                    {/* Footer receipt */}
                    <div className="p-4 bg-salon-black/40 text-center">
                      <p className="text-[9px] text-gray-500 uppercase tracking-widest">
                        Adonis Grooming Lobby, Dhaka • Please keep your confirmation code for reference.
                      </p>
                    </div>
                  </div>

                  {emailStatus === 'sent' && (
                    <div className="flex items-center justify-center gap-2 text-green-400 text-[10px] font-mono uppercase tracking-widest">
                      <LucideIcon name="CheckCircle" size={12} />
                      Booking notification sent
                    </div>
                  )}

                  {(() => {
                    const branch = getBranchDetails(successBooking.branchId);
                    const branchPhone = getBranchPhone(successBooking.branchId);

                    return (
                      <div className="border border-gold-400/20 bg-salon-gray/50 p-4 space-y-4 text-left">
                        <div className="space-y-2">
                          <span className="text-[9px] text-gold-400 font-mono uppercase tracking-widest block">Contact Adonis Men's Grooming Salon</span>
                          <div className="flex items-start gap-2 text-xs text-gray-300">
                            <LucideIcon name="MapPin" size={15} className="text-gold-400 shrink-0 mt-0.5" />
                            <span>{branch?.address || 'Dhaka, Bangladesh'}</span>
                          </div>
                          <div className="flex items-center gap-2 text-xs text-gray-300">
                            <LucideIcon name="Phone" size={15} className="text-gold-400 shrink-0" />
                            <span>{branchPhone}</span>
                          </div>
                        </div>

                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                          <a
                            href={getWhatsAppUrl(successBooking)}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="flex items-center justify-center gap-2 px-4 py-3 bg-[#25D366] hover:bg-[#1ebe5d] text-black font-serif text-xs font-bold uppercase tracking-widest transition-all"
                          >
                            <LucideIcon name="MessageCircle" size={15} />
                            Confirm via WhatsApp
                          </a>

                          <a
                            href={getTelHref(branchPhone)}
                            className="flex items-center justify-center gap-2 px-4 py-3 border border-white/15 text-white hover:bg-white/5 font-serif text-xs font-bold uppercase tracking-widest transition-all"
                          >
                            <LucideIcon name="PhoneCall" size={15} />
                            Call Contact Us
                          </a>

                          <a
                            href={getAdminBookingMailto(successBooking)}
                            className="flex items-center justify-center gap-2 px-4 py-3 border border-gold-400/40 text-gold-400 hover:bg-gold-400 hover:text-salon-black font-serif text-xs font-bold uppercase tracking-widest transition-all"
                          >
                            <LucideIcon name="Send" size={15} />
                            Send To Admin
                          </a>

                          {successBooking.clientEmail && (
                            <a
                              href={getConfirmationMailto(successBooking)}
                              className="flex items-center justify-center gap-2 px-4 py-3 border border-white/15 text-white hover:border-gold-400 hover:text-gold-400 font-serif text-xs font-bold uppercase tracking-widest transition-all"
                            >
                              <LucideIcon name="Mail" size={15} />
                              Email Booking Copy
                            </a>
                          )}
                        </div>
                      </div>
                    );
                  })()}

                  <div className="flex justify-center">
                    <button
                      onClick={() => setSuccessBooking(null)}
                      className="px-8 py-3 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-xs font-bold uppercase tracking-widest transition-all cursor-pointer"
                    >
                      Book Another Session
                    </button>
                  </div>
                </motion.div>
              )}
      </div>
    </div>
  );
};

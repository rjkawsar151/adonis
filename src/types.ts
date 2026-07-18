export interface Service {
  id: string;
  name: string;
  description: string;
  durationMin: number;
  priceBDT: number;
  category: 'hair' | 'beard' | 'spa' | 'pack';
  icon: string; // lucide icon name
}

export interface Branch {
  id: string;
  name: string;
  fullName: string;
  address: string;
  googleMapEmbed: string;
  directionsUrl: string;
  phoneNumbers: string[];
  hours?: string;
}

export interface Barber {
  id: string;
  name: string;
  experienceYears: number;
  specialty: string;
  portraitUrl: string;
  bio: string;
  rating: number;
}

export interface Testimonial {
  id: string;
  author: string;
  rating: number;
  comment: string;
  source: string;
  avatarLetter: string;
}

export interface Booking {
  id: string;
  clientName: string;
  clientPhone: string;
  clientEmail: string;
  branchId: string;
  serviceId?: string;
  barberId?: string;
  date: string;
  time: string;
  notes?: string;
  bookingCode: string;
}

export interface SiteSettings {
  brandName: string;
  brandSubtitle: string;
  heroTitle: string;
  heroSubtitle: string;
  heroBg: string;
  aboutStory: string;
  aboutDescription: string;
  contactEmail: string;
  openHoursDays: string;
  openHoursTime: string;
  phoneNumbers: string[];
  facebookUrl: string;
  instagramUrl: string;
  whatsappUrl: string;
}

export interface SmtpSettings {
  host: string;
  port: number;
  secure: boolean;
  user: string;
  pass: string;
  fromEmail: string;
  adminEmails: string; // Comma-separated admin emails
}

export interface PriceListItem {
  name: string;
  price: string;
  description?: string;
}

export interface PriceGroup {
  category: string;
  items: PriceListItem[];
}

export interface BlogPost {
  id: string;
  slug: string;
  title: string;
  excerpt: string;
  coverImage: string;
  contentHtml: string;
  seoTitle: string;
  seoDescription: string;
  status: 'draft' | 'published';
  createdAt: string;
  updatedAt: string;
}

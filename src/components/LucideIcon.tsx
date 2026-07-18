import React from 'react';
import {
  Scissors,
  Sparkles,
  Smile,
  Flame,
  Flower,
  ShieldAlert,
  UserCheck,
  Crown,
  Menu,
  X,
  ChevronLeft,
  ChevronRight,
  MapPin,
  Search,
  SearchX,
  PhoneCall,
  Navigation,
  Check,
  CheckCircle,
  MessageCircle,
  MessageSquare,
  Briefcase,
  User,
  DollarSign,
  Calendar,
  Mail,
  ArrowLeft,
  ArrowRight,
  LayoutGrid,
  ExternalLink,
  Phone,
  Clock,
  Facebook,
  Instagram,
  Compass,
  Wind,
  Star,
  Droplets,
  SunMedium,
  Milk,
  Zap,
  Hand,
  Activity,
  Dumbbell,
  Palette,
  Layers,
  AlertTriangle,
  type LucideIcon as LucideIconType,
} from 'lucide-react';

// Map of icon name strings to their components for dynamic lookup
const ICON_MAP: Record<string, LucideIconType> = {
  Scissors,
  Sparkles,
  Smile,
  Flame,
  Flower,
  ShieldAlert,
  UserCheck,
  Crown,
  Menu,
  X,
  ChevronLeft,
  ChevronRight,
  MapPin,
  Search,
  SearchX,
  PhoneCall,
  Navigation,
  Check,
  CheckCircle,
  MessageCircle,
  MessageSquare,
  Briefcase,
  User,
  DollarSign,
  Calendar,
  Mail,
  ArrowLeft,
  ArrowRight,
  LayoutGrid,
  ExternalLink,
  Phone,
  Clock,
  Facebook,
  Instagram,
  Compass,
  Wind,
  Star,
  Droplets,
  SunMedium,
  Milk,
  Zap,
  Hand,
  Activity,
  Dumbbell,
  Palette,
  Layers,
  AlertTriangle,
};

interface LucideIconProps {
  name: string;
  className?: string;
  size?: number;
}

export const LucideIcon: React.FC<LucideIconProps> = ({ name, className = '', size = 24 }) => {
  const IconComponent = ICON_MAP[name];

  if (!IconComponent) {
    // Fallback icon if name not found in map
    return <Sparkles className={className} size={size} />;
  }

  return <IconComponent className={className} size={size} />;
};

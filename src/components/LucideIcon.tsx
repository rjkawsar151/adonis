import React from 'react';
import * as Icons from 'lucide-react';

interface LucideIconProps {
  name: string;
  className?: string;
  size?: number;
}

export const LucideIcon: React.FC<LucideIconProps> = ({ name, className = '', size = 24 }) => {
  // Safe lookup for available icons to safeguard typescript and runtime errors
  const IconComponent = (Icons as any)[name];
  
  if (!IconComponent) {
    // Fallback icon in case something is missing
    return <Icons.Sparkles className={className} size={size} />;
  }

  return <IconComponent className={className} size={size} />;
};

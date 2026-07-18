import React, { useState, useEffect, useRef } from 'react';

interface LazySectionProps {
  id: string;
  className?: string;
  placeholderHeight?: string;
  rootMargin?: string;
  children: React.ReactNode;
}

export const LazySection: React.FC<LazySectionProps> = ({
  id,
  className = '',
  placeholderHeight = '400px',
  rootMargin = '400px',
  children,
}) => {
  const [isIntersecting, setIsIntersecting] = useState(false);
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (typeof window === 'undefined') return;
    if (!('IntersectionObserver' in window)) {
      setIsIntersecting(true);
      return;
    }

    const isMobile = window.matchMedia('(max-width: 767px)').matches;
    const resolvedMargin = isMobile ? '600px' : '800px';

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsIntersecting(true);
          observer.disconnect();
        }
      },
      { rootMargin: resolvedMargin }
    );

    if (ref.current) {
      observer.observe(ref.current);
    }

    return () => observer.disconnect();
  }, [rootMargin]);

  return (
    <div
      ref={ref}
      id={id}
      className={className}
      style={!isIntersecting ? { minHeight: placeholderHeight } : undefined}
    >
      {isIntersecting ? children : null}
    </div>
  );
};

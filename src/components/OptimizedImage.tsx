import React from 'react';

/**
 * Resolves an image path to optimized WebP versions with responsive srcset.
 * Falls back to original PNG if WebP not available.
 */
export function getOptimizedImagePath(originalPath: string): string {
  if (!originalPath) return '';
  const normalizedPath = /^(?:https?:)?\/\//i.test(originalPath)
    ? originalPath
    : `/${originalPath.replace(/^\/+/, '')}`;

  if (!/\/assets\/(?:images\/)?[^/]+\.png$/i.test(normalizedPath)) {
    return normalizedPath;
  }
  // Replace /assets/images/foo.png → /assets/images/optimized/foo.webp
  return normalizedPath
    .replace(/\/assets\/images\/([^/]+)\.png$/, '/assets/images/optimized/$1.webp')
    .replace(/\/assets\/([^/]+)\.png$/, '/assets/images/optimized/$1.webp');
}

export function getImageSrcSet(originalPath: string): string {
  if (!originalPath) return '';
  const normalizedPath = /^(?:https?:)?\/\//i.test(originalPath)
    ? originalPath
    : `/${originalPath.replace(/^\/+/, '')}`;

  if (!/\/assets\/(?:images\/)?[^/]+\.png$/i.test(normalizedPath)) {
    return '';
  }

  const basePath = normalizedPath
    .replace(/\/assets\/images\/([^/]+)\.png$/, '/assets/images/optimized/$1')
    .replace(/\/assets\/([^/]+)\.png$/, '/assets/images/optimized/$1');

  // Build srcset with available responsive sizes
  return [
    `${basePath}-400w.webp 400w`,
    `${basePath}-800w.webp 800w`,
    `${basePath}.webp 1672w`,
  ].join(', ');
}

interface OptimizedImageProps {
  src: string;
  alt: string;
  className?: string;
  width?: number;
  height?: number;
  sizes?: string;
  loading?: 'lazy' | 'eager';
  fetchPriority?: 'high' | 'low' | 'auto';
  style?: React.CSSProperties;
  onClick?: () => void;
  referrerPolicy?: React.HTMLAttributeReferrerPolicy;
}

/**
 * Optimized image component that:
 * - Serves WebP format with PNG fallback
 * - Uses responsive srcset for mobile-appropriate sizes
 * - Supports lazy loading and fetch priority
 * - Includes explicit width/height to prevent CLS
 */
export const OptimizedImage: React.FC<OptimizedImageProps> = ({
  src,
  alt,
  className = '',
  width,
  height,
  sizes = '(max-width: 640px) 400px, (max-width: 1024px) 800px, 1200px',
  loading = 'lazy',
  fetchPriority = 'auto',
  style,
  onClick,
  referrerPolicy = 'no-referrer',
}) => {
  const webpSrc = getOptimizedImagePath(src);
  const srcSet = getImageSrcSet(src);
  // PNG fallback path
  const pngFallback = (/^(?:https?:)?\/\//i.test(src) ? src : `/${src.replace(/^\/+/, '')}`)
    .replace(/\/assets\/images\/([^/]+)\.png$/, '/assets/images/optimized/$1.png')
    .replace(/\/assets\/([^/]+)\.png$/, '/assets/images/optimized/$1.png');

  return (
    <picture className="contents">
      {srcSet && <source type="image/webp" srcSet={srcSet} sizes={sizes} />}
      {srcSet && <source type="image/png" srcSet={pngFallback} />}
      <img
        src={webpSrc}
        alt={alt}
        className={className}
        width={width}
        height={height}
        loading={loading}
        fetchPriority={loading === 'lazy' && fetchPriority === 'auto' ? 'low' : fetchPriority}
        decoding={loading === 'lazy' ? 'async' : 'auto'}
        style={style}
        onClick={onClick}
        referrerPolicy={referrerPolicy}
        onError={(event) => {
          const image = event.currentTarget;
          const originalPath = /^(?:https?:)?\/\//i.test(src) ? src : `/${src.replace(/^\/+/, '')}`;
          if (image.src !== new URL(originalPath, window.location.origin).href) {
            image.parentElement?.querySelectorAll('source').forEach(source => source.remove());
            image.srcset = '';
            image.src = originalPath;
          }
        }}
      />
    </picture>
  );
};

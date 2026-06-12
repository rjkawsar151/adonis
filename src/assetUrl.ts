const ABSOLUTE_URL = /^(?:[a-z][a-z0-9+.-]*:)?\/\//i;

export const getAssetBasePath = () => {
  if (typeof window === 'undefined') return '';
  const configured = (window as typeof window & { __ADONIS_ASSET_BASE__?: string }).__ADONIS_ASSET_BASE__;
  if (configured) return configured.replace(/\/$/, '');

  const currentScript = document.currentScript as HTMLScriptElement | null;
  const scripts = Array.from(document.querySelectorAll<HTMLScriptElement>('script[src]'));
  const scriptSrc = currentScript?.src || scripts.find(script => script.src.includes('/build/assets/'))?.src || '';
  const url = scriptSrc ? new URL(scriptSrc, window.location.href) : null;
  const match = url?.pathname.match(/^(.*)\/build\/assets\//);
  return match?.[1] || '';
};

export const assetUrl = (path?: string | null) => {
  if (!path) return '';
  if (ABSOLUTE_URL.test(path) || path.startsWith('data:') || path.startsWith('mailto:') || path.startsWith('tel:')) {
    return path;
  }

  const base = getAssetBasePath();
  if (path.startsWith('/assets/') || path.startsWith('/uploads/') || path.startsWith('/build/')) {
    return `${base}${path}`;
  }

  return path;
};

export const navigateTo = (path: string) => {
  window.history.pushState({}, '', path);
  window.dispatchEvent(new CustomEvent('adonis-navigate', { detail: path }));
};

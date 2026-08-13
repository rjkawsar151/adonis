import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import path from 'path';
import {defineConfig} from 'vite';

export default defineConfig(({ command }) => {
  return {
    base: '/build/',
    publicDir: command === 'build' ? false : 'public',
    plugins: [react(), tailwindcss()],
    esbuild: {
      drop: command === 'build' ? ['console', 'debugger'] : [],
    },
    build: {
      outDir: 'public/build',
      emptyOutDir: true,
      // Enable minification and compression
      minify: 'esbuild',
      rollupOptions: {
        input: path.resolve(__dirname, 'src/main.tsx'),
        output: {
          entryFileNames: 'assets/app.js',
          chunkFileNames: (chunkInfo) => {
            if (chunkInfo.name === 'vendor') {
              return 'assets/vendor.js';
            }
            return 'assets/[name].js';
          },
          assetFileNames: (assetInfo) => {
            if (assetInfo.name && assetInfo.name.endsWith('.css')) {
              return 'assets/app.css';
            }
            return 'assets/[name][extname]';
          },
          manualChunks(id) {
            if (id.includes('node_modules')) {
              return 'vendor';
            }
          }
        },
      },
      // Target modern browsers for smaller bundles
      target: 'es2020',
      // Enable chunk size warnings
      chunkSizeWarningLimit: 250,
    },
    resolve: {
      alias: {
        '@': path.resolve(__dirname, '.'),
      },
    },
    server: {
      // HMR is disabled in AI Studio via DISABLE_HMR env var.
      // Do not modify—file watching is disabled to prevent flickering during agent edits.
      hmr: process.env.DISABLE_HMR !== 'true',
      // Disable file watching when DISABLE_HMR is true to save CPU during agent edits.
      watch: process.env.DISABLE_HMR === 'true' ? null : {},
      proxy: {
        '/api': {
          target: 'http://localhost:8000',
          changeOrigin: true,
          secure: false
        },
        '/uploads': {
          target: 'http://localhost:8000',
          changeOrigin: true,
          secure: false
        }
      }
    },
  };
});

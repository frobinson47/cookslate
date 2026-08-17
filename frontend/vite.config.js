import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
  plugins: [
    react(),
    tailwindcss(),
    VitePWA({
      registerType: 'autoUpdate',
      workbox: {
        skipWaiting: true,
        clientsClaim: true,
        globPatterns: ['**/*.{js,css,html,svg,png,ico,woff2}'],
        navigateFallbackDenylist: [/^\/api\//, /^\/uploads\//],
        runtimeCaching: [
          {
            // Cache API responses (recipes, lists, etc.)
            urlPattern: /\/api\//,
            handler: 'NetworkFirst',
            options: {
              cacheName: 'api-cache',
              expiration: {
                maxEntries: 200,
                maxAgeSeconds: 7 * 24 * 60 * 60, // 7 days
              },
              networkTimeoutSeconds: 3,
            },
          },
          {
            // Cache recipe images. StaleWhileRevalidate (not CacheFirst) so a
            // regenerated photo at the same URL (e.g. admin AI photo, which
            // overwrites full.webp/thumb.webp in place) self-heals on the next
            // load instead of being stuck behind a 30-day CacheFirst hit.
            urlPattern: /\/uploads\//,
            handler: 'StaleWhileRevalidate',
            options: {
              cacheName: 'image-cache',
              expiration: {
                maxEntries: 500,
                maxAgeSeconds: 30 * 24 * 60 * 60, // 30 days
              },
            },
          },
        ],
      },
      manifest: {
        name: 'Cookslate',
        short_name: 'Cookslate',
        description: 'Your recipes. Your way.',
        start_url: '/',
        display: 'standalone',
        background_color: '#F5EDE3',
        theme_color: '#C75B39',
        share_target: {
          action: '/add',
          method: 'GET',
          params: {
            title: 'title',
            text: 'text',
            url: 'url',
          },
        },
        icons: [
          {
            src: '/favicon.svg',
            sizes: 'any',
            type: 'image/svg+xml',
            purpose: 'any',
          },
          {
            src: '/icon-192.png',
            sizes: '192x192',
            type: 'image/png',
          },
          {
            src: '/icon-512.png',
            sizes: '512x512',
            type: 'image/png',
            purpose: 'any maskable',
          },
        ],
      },
    }),
  ],
  base: '/',
  server: {
    port: 5176,
    strictPort: true,
    proxy: {
      '/api': {
        target: 'http://crumble.fmr.local:8888',
        changeOrigin: true,
      },
      '/uploads': {
        target: 'http://crumble.fmr.local:8888',
        changeOrigin: true,
      }
    }
  },
  build: {
    outDir: 'dist',
  }
})

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/css/landing.css',
        'resources/css/menus.css',
        'resources/scss/app.scss',
        'resources/js/app.js',
        'resources/js/scripts/menu.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js'
      ],
      refresh: true
    }),
    vue()
  ]
});

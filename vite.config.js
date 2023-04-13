import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/landing.css',
        'resources/scss/app.scss',
        'resources/css/login.css',
        'resources/js/app.js',
        'resources/js/menu.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js'
      ],
      refresh: true
    }),
    vue()
  ]
});

import { defineConfig } from "cypress";

export default defineConfig({
  e2e: {
    baseUrl: 'https://bus-inclusive.my.id',
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
    // Menyesuaikan konfigurasi untuk testing live server yang cepat
    viewportWidth: 1280,
    viewportHeight: 720,
    defaultCommandTimeout: 10000,
  },
});

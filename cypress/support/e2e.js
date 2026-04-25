// cypress/support/e2e.js
// This file is processed and loaded automatically before your test files.

// Menangani Uncaught Exceptions agar Cypress tidak langsung fail
// misalnya jika ada error third-party script di frontend
Cypress.on('uncaught:exception', (err, runnable) => {
  // returning false here prevents Cypress from failing the test
  return false;
});

// Anda bisa menambahkan custom commands di sini jika diperlukan

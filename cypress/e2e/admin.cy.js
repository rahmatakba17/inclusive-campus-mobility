describe('Skenario Dasbor Admin (Sesuai AKUN_SIMULASI.md)', () => {
  beforeEach(() => {
    // Login sebagai admin
    cy.visit('/login', { failOnStatusCode: false });
    cy.get('input[name="email"]').type('admin@unhas.ac.id');
    cy.get('input[name="password"]').type('password');
    cy.get('button[type="submit"]').click();
  });

  it('TC-201: Admin Melihat Statistik Dasbor', () => {
    // Memastikan Admin berada di dashboard
    cy.visit('/admin/dashboard', { failOnStatusCode: false });
    
    // Asumsi halaman memuat elemen statistik
    cy.get('body').should('contain', 'Fleet');
    cy.get('body').should('contain', 'Total Orders');
  });

  it('TC-202: Admin Mengakses Daftar Penumpang', () => {
    cy.visit('/admin/users', { failOnStatusCode: false });
    // Memastikan daftar penumpang dimuat
    cy.get('table').should('exist'); // Asumsi menggunakan tag table
  });

  it('TC-203: Admin Mengakses Laporan Pemasukan', () => {
    cy.visit('/admin/revenue', { failOnStatusCode: false });
    // Memastikan laporan dimuat
    cy.get('body').should('contain', 'Laporan');
  });
});

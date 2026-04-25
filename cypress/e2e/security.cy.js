describe('Skenario Keamanan Otorisasi Berlapis (Sesuai AKUN_SIMULASI.md)', () => {
  it('TC-301: Admin Ditolak Mengakses Dasbor Penumpang (Inconsistency Protection)', () => {
    // 1. Login sebagai Admin
    cy.visit('/login', { failOnStatusCode: false });
    cy.get('input[name="email"]').type('admin@unhas.ac.id');
    cy.get('input[name="password"]').type('password');
    cy.get('button[type="submit"]').click();
    
    // 2. Paksa akses URL penumpang
    cy.visit('/user/dashboard', { failOnStatusCode: false });
    
    // 3. Pastikan dikembalikan ke area admin (atau 403)
    cy.url().should('not.include', '/user/dashboard');
  });

  it('TC-302: Penumpang Ditolak Mengakses Dasbor Admin (403 Forbidden)', () => {
    // 1. Login sebagai Penumpang
    cy.visit('/login', { failOnStatusCode: false });
    cy.get('input[name="email"]').type('ani@gmail.com');
    cy.get('input[name="password"]').type('password');
    cy.get('button[type="submit"]').click();
    
    // 2. Paksa akses URL admin
    cy.visit('/admin/dashboard', { failOnStatusCode: false });
    
    // 3. Pastikan ditolak (mendapatkan forbidden/redirect)
    cy.url().should('not.include', '/admin/dashboard');
  });
});

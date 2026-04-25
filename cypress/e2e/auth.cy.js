describe('Skenario Autentikasi (Berdasarkan AKUN_SIMULASI.md)', () => {
  beforeEach(() => {
    // Memberikan jeda 3 detik agar server live tidak mendeteksi ini sebagai serangan DDoS (Mencegah 429)
    cy.wait(3000);
    cy.visit('/login', { failOnStatusCode: false });
  });

  it('TC-001: Login Admin Transportasi Valid', () => {
    // Menggunakan akun dari seeder
    cy.get('input[name="email"]').type('admin@unhas.ac.id');
    cy.get('input[name="password"]').type('password');
    cy.get('button[type="submit"]').click();
    
    // Memastikan diarahkan ke halaman dashboard atau root
    // Karena kita tidak tahu persis URL dashboard (bisa /admin/dashboard)
    cy.url().should('not.include', '/login');
  });

  it('TC-002: Login Penumpang Civitas Valid', () => {
    cy.get('input[name="email"]').type('budi@unhas.ac.id');
    cy.get('input[name="password"]').type('password');
    cy.get('button[type="submit"]').click();
    
    cy.url().should('not.include', '/login');
  });

  it('TC-003: Login Email Tidak Terdaftar (Negative)', () => {
    cy.get('input[name="email"]').type('notfound@domain.com');
    cy.get('input[name="password"]').type('wrongpassword');
    cy.get('button[type="submit"]').click();
    
    cy.url().should('include', '/login');
  });

  it('TC-004: Login Password Salah (Negative)', () => {
    cy.get('input[name="email"]').type('admin@unhas.ac.id');
    cy.get('input[name="password"]').type('password_salah_123');
    cy.get('button[type="submit"]').click();
    
    cy.url().should('include', '/login');
  });
});

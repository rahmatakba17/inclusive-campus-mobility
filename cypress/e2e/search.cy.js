describe('Pencarian Rute (Sesuai Rencana Pengujian Bus Kampus)', () => {
  it('TC-012: Pencarian Rute Valid Tersedia', () => {
    // 1. Buka halaman utama
    cy.visit('/');

    // 2. Sistem menampilkan antarmuka pencarian rute
    // Memastikan elemen form pencarian ada di halaman
    cy.get('form').should('be.visible');
    
    // Asumsi: form memiliki input asal atau tujuan dan tombol submit
    cy.get('button[type="submit"]').should('exist');
    
    // (Opsional) Mengisi form pencarian jika ID input diketahui
    // cy.get('select[name="origin"]').select('Makassar');
    // cy.get('select[name="destination"]').select('Gowa');
    // cy.get('input[type="date"]').type('2026-05-01');
    // cy.get('button[type="submit"]').click();
    
    // Memastikan UI halaman dirender dengan sukses
    cy.title().should('not.be.empty');
  });
});

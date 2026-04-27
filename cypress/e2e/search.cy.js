describe('Pencarian Rute (Sesuai Rencana Pengujian Bus Kampus)', () => {
  it('TC-012: Pencarian Rute Valid Tersedia', () => {
    // 1. Buka halaman utama
    cy.visit('/');

    // 2. Sistem menampilkan antarmuka pencarian rute (via tombol CTA ke halaman Guest Booking)
    cy.contains('Pesanan Akses Tamu', { matchCase: false }).should('be.visible').click();

    // 3. Pastikan dialihkan ke halaman guest buses
    cy.url().should('include', '/guest/buses');

    // 4. Pastikan elemen slider armada muncul di halaman guest buses
    cy.get('h1').contains('Pilih Armada', { matchCase: false }).should('be.visible');
    
    // 5. Pastikan komponen rute (slider) sudah dimuat
    cy.contains('Rute Perintis → Gowa', { matchCase: false }).should('be.visible');
    cy.contains('Rute Gowa → Perintis', { matchCase: false }).should('be.visible');

    // Memastikan UI halaman dirender dengan sukses
    cy.title().should('not.be.empty');
  });
});

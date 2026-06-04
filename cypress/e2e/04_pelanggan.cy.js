describe("Pelanggan API", () => {
    let pelangganId;
    const email = `test_${Date.now()}@email.com`;

    before(() => cy.loginAsAdmin());

    it("GET /pelanggan - sukses", () => {
        cy.apiRequest("GET", "/pelanggan").then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
        });
    });

    it("POST /pelanggan - sukses", () => {
        cy.apiRequest("POST", "/pelanggan", {
            pelanggan_nama: "Budi Test",
            pelanggan_alamat: "Jl. Test No. 1",
            pelanggan_notelp: "081234567890",
            pelanggan_email: email,
        }).then((res) => {
            expect(res.status).to.eq(201);
            expect(res.body.success).to.eq(true);
            expect(res.body.data.pelanggan_nama).to.eq("Budi Test");
            pelangganId = res.body.data.pelanggan_id;
        });
    });

    it("POST /pelanggan - gagal validasi (email duplikat)", () => {
        cy.apiRequest("POST", "/pelanggan", {
            pelanggan_nama: "Duplikat",
            pelanggan_alamat: "Jl. Duplikat",
            pelanggan_notelp: "081111111111",
            pelanggan_email: email,
        }).then((res) => {
            expect(res.status).to.eq(422);
            expect(res.body.success).to.eq(false);
        });
    });

    it("POST /pelanggan - gagal validasi (nomor telepon bukan angka)", () => {
        cy.apiRequest("POST", "/pelanggan", {
            pelanggan_nama: "Test",
            pelanggan_alamat: "Jl. Test",
            pelanggan_notelp: "abcdefgh",
            pelanggan_email: `other_${Date.now()}@email.com`,
        }).then((res) => {
            expect(res.status).to.eq(422);
            expect(res.body.success).to.eq(false);
        });
    });

    it("GET /pelanggan/{id} - sukses", () => {
        cy.apiRequest("GET", `/pelanggan/${pelangganId}`).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.data.pelanggan_id).to.eq(pelangganId);
        });
    });

    it("GET /pelanggan/{id} - tidak ditemukan", () => {
        cy.apiRequest("GET", "/pelanggan/99999").then((res) => {
            expect(res.status).to.eq(404);
            expect(res.body.success).to.eq(false);
        });
    });

    it("PATCH /pelanggan/{id} - sukses", () => {
        cy.apiRequest("PATCH", `/pelanggan/${pelangganId}`, {
            pelanggan_alamat: "Jl. Updated No. 99",
        }).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.data.pelanggan_alamat).to.eq("Jl. Updated No. 99");
        });
    });

    it("DELETE /pelanggan/{id} - sukses", () => {
        cy.apiRequest("DELETE", `/pelanggan/${pelangganId}`).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
        });
    });
});

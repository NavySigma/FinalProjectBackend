describe("Alat API", () => {
    let alatId;
    let kategoriId;

    before(() => {
        cy.loginAsAdmin().then(() => {
            // Buat kategori dulu sebagai dependency
            cy.apiRequest("POST", "/kategori", {
                kategori_nama: "Kategori Alat Test",
            }).then((res) => {
                kategoriId = res.body.data.kategori_id;
            });
        });
    });

    after(() => {
        cy.apiRequest("DELETE", `/kategori/${kategoriId}`);
    });

    it("GET /alat - sukses", () => {
        cy.apiRequest("GET", "/alat").then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
        });
    });

    it("POST /alat - sukses", () => {
        cy.apiRequest("POST", "/alat", {
            alat_kategori_id: kategoriId,
            alat_nama: "Tenda Test",
            alat_deskripsi: "Deskripsi tenda test",
            alat_hargaperhari: 50000,
            alat_stok: 5,
        }).then((res) => {
            expect(res.status).to.eq(201);
            expect(res.body.success).to.eq(true);
            expect(res.body.data.alat_nama).to.eq("Tenda Test");
            alatId = res.body.data.alat_id;
        });
    });

    it("POST /alat - gagal validasi (field wajib kosong)", () => {
        cy.apiRequest("POST", "/alat", { alat_nama: "Tanpa Kategori" }).then(
            (res) => {
                expect(res.status).to.eq(422);
                expect(res.body.success).to.eq(false);
                expect(res.body).to.have.property("errors");
            },
        );
    });

    it("POST /alat - gagal validasi (stok negatif)", () => {
        cy.apiRequest("POST", "/alat", {
            alat_kategori_id: kategoriId,
            alat_nama: "Alat Stok Negatif",
            alat_deskripsi: "Desc",
            alat_hargaperhari: 10000,
            alat_stok: -1,
        }).then((res) => {
            expect(res.status).to.eq(422);
            expect(res.body.success).to.eq(false);
        });
    });

    it("GET /alat/{id} - sukses", () => {
        cy.apiRequest("GET", `/alat/${alatId}`).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.data.alat_id).to.eq(alatId);
        });
    });

    it("GET /alat/{id} - tidak ditemukan", () => {
        cy.apiRequest("GET", "/alat/99999").then((res) => {
            expect(res.status).to.eq(404);
            expect(res.body.success).to.eq(false);
        });
    });

    it("PATCH /alat/{id} - sukses", () => {
        cy.apiRequest("PATCH", `/alat/${alatId}`, {
            alat_stok: 20,
            alat_hargaperhari: 75000,
        }).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.data.alat_stok).to.eq(20);
        });
    });

    it("DELETE /alat/{id} - sukses", () => {
        cy.apiRequest("DELETE", `/alat/${alatId}`).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
        });
    });
});

describe("Kategori API", () => {
    let kategoriId;

    before(() => cy.loginAsAdmin());

    it("GET /kategori - sukses", () => {
        cy.apiRequest("GET", "/kategori").then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
        });
    });

    it("POST /kategori - sukses", () => {
        cy.apiRequest("POST", "/kategori", {
            kategori_nama: "Test Kategori",
        }).then((res) => {
            expect(res.status).to.eq(201);
            expect(res.body.success).to.eq(true);
            expect(res.body.data.kategori_nama).to.eq("Test Kategori");
            kategoriId = res.body.data.kategori_id;
        });
    });

    it("POST /kategori - gagal validasi (nama kosong)", () => {
        cy.apiRequest("POST", "/kategori", {}).then((res) => {
            expect(res.status).to.eq(422);
            expect(res.body.success).to.eq(false);
            expect(res.body).to.have.property("errors");
        });
    });

    it("GET /kategori/{id} - sukses", () => {
        cy.apiRequest("GET", `/kategori/${kategoriId}`).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.data.kategori_id).to.eq(kategoriId);
        });
    });

    it("GET /kategori/{id} - tidak ditemukan", () => {
        cy.apiRequest("GET", "/kategori/99999").then((res) => {
            expect(res.status).to.eq(404);
            expect(res.body.success).to.eq(false);
        });
    });

    it("PATCH /kategori/{id} - sukses", () => {
        cy.apiRequest("PATCH", `/kategori/${kategoriId}`, {
            kategori_nama: "Kategori Updated",
        }).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.data.kategori_nama).to.eq("Kategori Updated");
        });
    });

    it("DELETE /kategori/{id} - sukses", () => {
        cy.apiRequest("DELETE", `/kategori/${kategoriId}`).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
        });
    });
});

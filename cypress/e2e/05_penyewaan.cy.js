describe("Penyewaan API", () => {
    let penyewaanId;
    let pelangganId;

    before(() => {
        cy.loginAsAdmin().then(() => {
            cy.apiRequest("POST", "/pelanggan", {
                pelanggan_nama: "Pelanggan Sewa",
                pelanggan_alamat: "Jl. Sewa No. 1",
                pelanggan_notelp: "082345678901",
                pelanggan_email: `sewa_${Date.now()}@email.com`,
            }).then((res) => {
                pelangganId = res.body.data.pelanggan_id;
            });
        });
    });

    after(() => {
        cy.apiRequest("DELETE", `/pelanggan/${pelangganId}`);
    });

    it("GET /penyewaan - sukses", () => {
        cy.apiRequest("GET", "/penyewaan").then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
        });
    });

    it("POST /penyewaan - sukses", () => {
        cy.apiRequest("POST", "/penyewaan", {
            penyewaan_pelanggan_id: pelangganId,
            penyewaan_tglsewa: "2026-06-10",
            penyewaan_tglkembali: "2026-06-12",
            penyewaan_totalharga: 200000,
        }).then((res) => {
            expect(res.status).to.eq(201);
            expect(res.body.success).to.eq(true);
            penyewaanId = res.body.data.penyewaan_id;
        });
    });

    it("POST /penyewaan - gagal validasi (tgl kembali sebelum tgl sewa)", () => {
        cy.apiRequest("POST", "/penyewaan", {
            penyewaan_pelanggan_id: pelangganId,
            penyewaan_tglsewa: "2026-06-10",
            penyewaan_tglkembali: "2026-06-08",
            penyewaan_totalharga: 100000,
        }).then((res) => {
            expect(res.status).to.eq(422);
            expect(res.body.success).to.eq(false);
        });
    });

    it("POST /penyewaan - gagal validasi (pelanggan tidak ada)", () => {
        cy.apiRequest("POST", "/penyewaan", {
            penyewaan_pelanggan_id: 99999,
            penyewaan_tglsewa: "2026-06-10",
            penyewaan_tglkembali: "2026-06-12",
            penyewaan_totalharga: 100000,
        }).then((res) => {
            expect(res.status).to.eq(422);
            expect(res.body.success).to.eq(false);
        });
    });

    it("GET /penyewaan/{id} - sukses", () => {
        cy.apiRequest("GET", `/penyewaan/${penyewaanId}`).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.data.penyewaan_id).to.eq(penyewaanId);
        });
    });

    it("PATCH /penyewaan/{id} - update status", () => {
        cy.apiRequest("PATCH", `/penyewaan/${penyewaanId}`, {
            penyewaan_sttspembayaran: "Lunas",
            penyewaan_sttskembali: "Sudah Kembali",
        }).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.data.penyewaan_sttspembayaran).to.eq("Lunas");
        });
    });

    it("DELETE /penyewaan/{id} - sukses", () => {
        cy.apiRequest("DELETE", `/penyewaan/${penyewaanId}`).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
        });
    });
});

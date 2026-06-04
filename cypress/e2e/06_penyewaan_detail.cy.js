describe("Penyewaan Detail API", () => {
    let detailId;
    let penyewaanId;
    let alatId;
    let pelangganId;
    let kategoriId;

    before(() => {
        cy.loginAsAdmin().then(() => {
            cy.apiRequest("POST", "/pelanggan", {
                pelanggan_nama: "Pelanggan Detail",
                pelanggan_alamat: "Jl. Detail No. 1",
                pelanggan_notelp: "083456789012",
                pelanggan_email: `detail_${Date.now()}@email.com`,
            }).then((res) => {
                pelangganId = res.body.data.pelanggan_id;

                cy.apiRequest("POST", "/penyewaan", {
                    penyewaan_pelanggan_id: pelangganId,
                    penyewaan_tglsewa: "2026-06-10",
                    penyewaan_tglkembali: "2026-06-12",
                    penyewaan_totalharga: 0,
                }).then((res2) => {
                    penyewaanId = res2.body.data.penyewaan_id;
                });
            });

            cy.apiRequest("POST", "/kategori", {
                kategori_nama: "Kat Detail",
            }).then((res) => {
                kategoriId = res.body.data.kategori_id;

                cy.apiRequest("POST", "/alat", {
                    alat_kategori_id: kategoriId,
                    alat_nama: "Alat Detail Test",
                    alat_deskripsi: "Desc",
                    alat_hargaperhari: 50000,
                    alat_stok: 10,
                }).then((res2) => {
                    alatId = res2.body.data.alat_id;
                });
            });
        });
    });

    after(() => {
        cy.apiRequest("DELETE", `/penyewaan/${penyewaanId}`);
        cy.apiRequest("DELETE", `/pelanggan/${pelangganId}`);
        cy.apiRequest("DELETE", `/alat/${alatId}`);
        cy.apiRequest("DELETE", `/kategori/${kategoriId}`);
    });

    it("GET /penyewaan-detail - sukses", () => {
        cy.apiRequest("GET", "/penyewaan-detail").then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
        });
    });

    it("POST /penyewaan-detail - sukses", () => {
        cy.apiRequest("POST", "/penyewaan-detail", {
            penyewaan_detail_penyewaan_id: penyewaanId,
            penyewaan_detail_alat_id: alatId,
            penyewaan_detail_jumlah: 2,
            penyewaan_detail_subharga: 100000,
        }).then((res) => {
            expect(res.status).to.eq(201);
            expect(res.body.success).to.eq(true);
            expect(res.body.data.penyewaan_detail_jumlah).to.eq(2);
            detailId = res.body.data.penyewaan_detail_id;
        });
    });

    it("POST /penyewaan-detail - gagal validasi (jumlah 0)", () => {
        cy.apiRequest("POST", "/penyewaan-detail", {
            penyewaan_detail_penyewaan_id: penyewaanId,
            penyewaan_detail_alat_id: alatId,
            penyewaan_detail_jumlah: 0,
            penyewaan_detail_subharga: 100000,
        }).then((res) => {
            expect(res.status).to.eq(422);
            expect(res.body.success).to.eq(false);
        });
    });

    it("GET /penyewaan-detail/{id} - sukses", () => {
        cy.apiRequest("GET", `/penyewaan-detail/${detailId}`).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.data.penyewaan_detail_id).to.eq(detailId);
        });
    });

    it("PATCH /penyewaan-detail/{id} - sukses", () => {
        cy.apiRequest("PATCH", `/penyewaan-detail/${detailId}`, {
            penyewaan_detail_jumlah: 3,
            penyewaan_detail_subharga: 150000,
        }).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.data.penyewaan_detail_jumlah).to.eq(3);
        });
    });

    it("DELETE /penyewaan-detail/{id} - sukses", () => {
        cy.apiRequest("DELETE", `/penyewaan-detail/${detailId}`).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
        });
    });
});

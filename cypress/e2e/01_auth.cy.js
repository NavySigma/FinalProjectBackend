const API = Cypress.env("apiUrl");

describe("Auth API", () => {
    it("POST /auth/register - sukses", () => {
        cy.request({
            method: "POST",
            url: `${API}/auth/register`,
            body: {
                admin_username: `admin_${Date.now()}`,
                admin_password: "password123",
            },
            failOnStatusCode: false,
        }).then((res) => {
            expect(res.status).to.eq(201);
            expect(res.body.success).to.eq(true);
            expect(res.body.data).to.have.property("admin_username");
        });
    });

    it("POST /auth/register - gagal validasi (field kosong)", () => {
        cy.request({
            method: "POST",
            url: `${API}/auth/register`,
            body: {},
            failOnStatusCode: false,
        }).then((res) => {
            expect(res.status).to.eq(400);
            expect(res.body.success).to.eq(false);
        });
    });

    it("POST /auth/login - sukses", () => {
        cy.request({
            method: "POST",
            url: `${API}/auth/login`,
            body: { admin_username: "admin", admin_password: "password123" },
            failOnStatusCode: false,
        }).then((res) => {
            expect(res.status).to.eq(200);
            expect(res.body.success).to.eq(true);
            expect(res.body).to.have.property("accesstoken");
        });
    });

    it("POST /auth/login - gagal (password salah)", () => {
        cy.request({
            method: "POST",
            url: `${API}/auth/login`,
            body: { admin_username: "admin", admin_password: "salah" },
            failOnStatusCode: false,
        }).then((res) => {
            expect(res.status).to.eq(401);
            expect(res.body.success).to.eq(false);
        });
    });

    it("POST /auth/logout - sukses", () => {
        cy.loginAsAdmin().then(() => {
            cy.apiRequest("POST", "/auth/logout").then((res) => {
                expect(res.status).to.eq(200);
                expect(res.body.success).to.eq(true);
            });
        });
    });
});

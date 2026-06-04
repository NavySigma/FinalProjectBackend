// Global support file - runs before every test file

// Custom command: login and get token
Cypress.Commands.add("loginAsAdmin", () => {
    return cy
        .request({
            method: "POST",
            url: `${Cypress.env("apiUrl")}/auth/login`,
            body: {
                admin_username: "admin",
                admin_password: "password123",
            },
        })
        .then((res) => {
            Cypress.env("token", res.body.accesstoken);
        });
});

// Custom command: authenticated request
Cypress.Commands.add("apiRequest", (method, url, body = null) => {
    const options = {
        method,
        url: `${Cypress.env("apiUrl")}${url}`,
        headers: {
            Authorization: `Bearer ${Cypress.env("token")}`,
            Accept: "application/json",
        },
        failOnStatusCode: false,
    };
    if (body) options.body = body;
    return cy.request(options);
});

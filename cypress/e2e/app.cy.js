describe('template spec', () => {
  it('basic test', () => {
    cy.visit('/');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password');
    cy.get('button[name="submit"]').click();
    
    cy.get('.login-wrapper').should('not.exist');

    cy.get('#notification_1').should('exist');
    
    cy.contains('li', 'Dodaj nowy adres').click({force: true});
    cy.get('input[name="new_address"]').type('aa');
    cy.contains('li', 'ul. Staszica AA, 89-300 Wyrzysk | pilski | wielkopolskie').click();
    cy.get('button[name="submit"]').click({force: true});

    cy.get('#notification_2').should('exist');
  })
})
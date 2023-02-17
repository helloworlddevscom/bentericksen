const LoginPage =  require('../pageObjects/Login.page')

describe('HR Director Log In', () => {
    it('Admin login', () => {
        LoginPage.open()
        LoginPage.login()

        expect(browser).toHaveUrl(`${process.env.APP_URL}/admin/business`)

        const businesses = $('#business_table').$('tbody').$$('tr')

        expect(businesses).toBeElementsArrayOfSize( { gte: 1 } )
    })
})
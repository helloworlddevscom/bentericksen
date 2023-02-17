const LoginPage =  require('../pageObjects/Login.page')
const AdminBusiness =  require('../pageObjects/AdminBusiness.page')

describe('HR Director Create Business', () => {
    it('should allow you to create a business', () => {
        const APP_URL = process.env.APP_URL || 'http://hrdirector.localhost'

        LoginPage.open()
        LoginPage.login()

        AdminBusiness.create()
        AdminBusiness.build({
              radios: ['#bonuspro_enabled_yes']
        })

        expect(browser).toHaveUrl(`${APP_URL}/user`)
    })
})

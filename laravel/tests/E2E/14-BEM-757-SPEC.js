const LoginPage =  require('../pageObjects/Login.page')
const AdminBusiness =  require('../pageObjects/AdminBusiness.page')
const PolicyEditor = require('../pageObjects/PolicyEditor.page')

describe('BEM-757: Policy Editor Reset button malfunction', () => {
    it('Saves, cancells, resets', () => {
        const businessName = `E2E BEM-757 Test ${Date.now()}`

        LoginPage.open()
        LoginPage.login()

        AdminBusiness.create()
        AdminBusiness.build({
            properties: {
              '[name=name]': businessName
            },
            radios: ['#bonuspro_enabled_yes']
        })

        PolicyEditor.resetAll()
        PolicyEditor.open()
        PolicyEditor.edit(0, 'test')
        PolicyEditor.openPolicy(0)

        browser.pause(4000)
        browser.switchToFrame($('.cke_wysiwyg_frame'))
        expect($('body')).toHaveTextContaining('test')
        browser.switchToParentFrame()

        PolicyEditor.open()
        PolicyEditor.reset(0)
        PolicyEditor.openPolicy(0)

        browser.pause(4000)
        browser.switchToFrame($('.cke_wysiwyg_frame'))

        expect($('body').getText()).toEqual(expect.not.stringContaining('test'))
    })
})

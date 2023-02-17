const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')

describe('Policy Update System', () => {
    it('Successfully executes basic policy update procedure', () => {
      const businessName = `E2E Policy Update System ${Date.now()}`

        LoginPage.open()
        LoginPage.login()
  
        AdminBusiness.create()
        AdminBusiness.build({
          properties: {
            '[name=name]': businessName
          }
        })

        //$('[name=employees]').addValue('2')
        //$('#modalEmployeeCount').$('button=Save').click()
        

        browser.pause(1000)

        $('a=POLICIES').click()

        $('a=RESET POLICIES').click()
        
        browser.pause(1000)

        $('a=Reset Policies').click()

        browser.url(`${process.env.APP_URL}/admin/policies`)

        $('[name=col_1_search]').addValue('Welcome')

        $('a=EDIT').click()

        expect(browser).toHaveUrl(`${process.env.APP_URL}/admin/policies/20/edit`)

        $('[name="policy_effective_date"]').waitForDisplayed({ timeout: 3000 })
        
        browser.pause(1000)

        $('[name="policy_effective_date"]').click()
        $(`[data-handler="selectYear"]`).selectByVisibleText((new Date()).getFullYear() + 1)
        $(`[data-handler="selectDay"]`).click()
        
        browser.switchToFrame($('.cke_wysiwyg_frame'))

        $('body').addValue('<h1>E2E</h1> testing...')

        browser.switchToParentFrame()

        browser.pause(3000)
        
        $('button=SAVE').scrollIntoView()

        $('button=SAVE').click()

        expect(browser).toHaveUrl(`${process.env.APP_URL}/admin/policies`)

        browser.url(`${process.env.APP_URL}/admin/policies/updates`)

        $('a=ADD NEW').click()

        expect(browser).toHaveUrl(`${process.env.APP_URL}/admin/policies/updates/create`)

        $('[name="policies[20]"]').click()

        $('button=NEXT').click()

        $('input[value="NEXT"]').click()

        $('[name=title]').addValue(`Global Test Welcome Update ${Date.now()}`)

        $('[name="start_date"]').click()        
        $('[data-handler="selectDay"]').click()

        const editors = $$('.cke_wysiwyg_frame')

        editors.forEach((editor) => {
          browser.switchToFrame(editor)
          $('body').addValue('<h1>E2E</h1> testing...')
          browser.switchToParentFrame()
        })

        $('button=FINALIZE UPDATE').click()


        expect($('a=(( Placeholder )) New Update Created')).toBeDisplayed()


        browser.url(`${process.env.APP_URL}/admin/policies/updates`)

        const buttons = $$('.paginate_button')
        buttons[buttons.length - 1 ] && buttons[buttons.length - 1 ].click()

        $('a=UPDATE NOW').click()

        expect(browser).toHaveUrl(`${process.env.APP_URL}/admin/policies/updates`)

        browser.pause(5000)

        AdminBusiness.viewAs({
          properties: {
            businessName
          }
        })

        browser.pause(2000)

        $('button=Update').click()

        browser.pause(1000)

        $('button=Continue').click()
        
        browser.pause(1000)

        expect($('h4=WELCOME')).toBeDisplayed()

        browser.pause(1000)

        $('button=Accept').click()

        browser.pause(1000)
        
        $('a=Make Additional Policy Changes').waitForDisplayed({ timeout: 30000 })
        $('a=Make Additional Policy Changes').click()

        browser.pause(3000)

        $$('a=EDIT')[0].click()

        $('.cke_wysiwyg_frame').waitForDisplayed({ timeout: 3000 })
        
        browser.switchToFrame($('.cke_wysiwyg_frame'))

        expect($('body')).toHaveTextContaining('E2E')
        expect($('body')).toHaveTextContaining('testing...')

        browser.url(`${process.env.APP_URL}/auth/logout`)
    })
})
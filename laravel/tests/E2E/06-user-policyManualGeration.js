const LoginPage = require('../pageObjects/Login.page')
const AdminBusiness = require('../pageObjects/AdminBusiness.page')

describe('Policy Manual Generation', () => {
  it.skip('Will generate a policy manual for a business', () => {
      const businessName = `E2E Policy Update System ${Date.now()}`

      LoginPage.open()
      LoginPage.login()

      AdminBusiness.create()
      AdminBusiness.build({
        properties: {
          '[name=name]': businessName
        }
      })

      $('a=POLICIES').click()

      $('a=UPDATE POLICY MANUAL').click()

      browser.pause(1000)

      $('a=Update Manual').click()

      browser.pause(5000)

      browser.url(`${process.env.APP_URL}/user/policies/manual`)
      
      browser.pause(5000)

      expect($('embed')).toExist()
  })
})
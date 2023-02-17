const LoginPage =  require('../pageObjects/Login.page')

describe('HR Director Create Business', () => {
  it('should allow you to create a business', () => {
    process.env.APP_URL = 'https://bentericksen-staging.metaltoad-sites.com'

    browser.url(process.env.APP_URL)

    LoginPage.open()
    LoginPage.login()

    // expect(browser).toHaveUrl(`${process.env.APP_URL}/admin/business`)

    Array(200).fill(1).map((n, i) => n * i).forEach((n) => {
      browser.pause(1000)

      browser.url(`${process.env.APP_URL}/admin/business/create`)

      $('[name=name]').addValue(`Streamline Dental ${n}`)
      $('[name=address1]').addValue('E2E Test 01 Address')
      $('[name=address2]').addValue('Apt. 101')
      $('[name=city]').addValue('Portland')
      $('[name=state]').selectByVisibleText('Oregon')

      $('[name=postal_code]').doubleClick()
      $('[name=postal_code]').addValue('97035')

      $('[name=phone1]').click()
      $('[name=phone1]').addValue('1111111111')

      $('[name=primary_first_name]').addValue(n % 2 ? 'Jane' : 'John')
      $('[name=primary_last_name]').addValue('Dev')
      $('[name=primary_email]').addValue(`mike+demodentalsd${n}@helloworlddevs.com`)
      $('[name=owner_login_password]').addValue('password')

      $('[name=type]').selectByVisibleText('Dental')
      $('[name=subtype]').selectByVisibleText('General')
      $('[name="asa[type]"]').selectByVisibleText('Basic')

      $('[name="asa[expiration]"]').click()

      $('[data-handler="selectYear"]').selectByVisibleText((new Date()).getFullYear() + 1)
      $('[data-handler="selectDay"]').click()

      $('#enabled_sop_no').click()

      $('h4=CONTACT SUPPORT').scrollIntoView()

      $('button=SAVE').click()

      browser.pause(1000)
    })
  })
})

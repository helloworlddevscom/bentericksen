describe('Service Enabled', () => {
  it('Bonus Pro Only', () => {
      browser.url(process.env.APP_URL)

      $('[name=email]').addValue('admin@example.com')
      $('[name=password]').addValue('fff')
      $('[type=submit]').click()

      $('td=aa_template Test Business').$('..').$('a=EDIT').click()

      $('#hrdirector_enabled_no').waitForDisplayed({ timeout: 5000 })

      $('#hrdirector_enabled_no').scrollIntoView()
      $('#hrdirector_enabled_no').click()
      $('#bonuspro_enabled_yes').click()

      $('button=SAVE').click()

      browser.url(`${process.env.APP_URL}/auth/logout`)

      $('[name=email]').addValue('owner1@example.com')
      $('[name=password]').addValue('fff')
      $('[type=submit]').click()

      expect($$('.nav-slot.disabled')).toBeElementsArrayOfSize( { eq: 8 } )

      expect($('a[href="/bonuspro"]')).toExist()

      browser.url(`${process.env.APP_URL}/auth/logout`)
  })

  it('HR Director Only', () => {
      browser.url(process.env.APP_URL)

      $('[name=email]').addValue('admin@example.com')
      $('[name=password]').addValue('fff')
      $('[type=submit]').click()

      $('td=aa_template Test Business').$('..').$('a=EDIT').click()

      $('#hrdirector_enabled_yes').waitForDisplayed({ timeout: 5000 })
      $('#hrdirector_enabled_yes').scrollIntoView()
      $('#hrdirector_enabled_yes').click()
      $('#bonuspro_enabled_no').click()

      $('button=SAVE').click()

      browser.url(`${process.env.APP_URL}/auth/logout`)

      $('[name=email]').addValue('owner1@example.com')
      $('[name=password]').addValue('fff')
      $('[type=submit]').click()

      expect($$('.nav-root.enabled')).toBeElementsArrayOfSize( { eq: 8 } )

      expect($('a[href="/bonuspro"]')).not.toExist()

      browser.url(`${process.env.APP_URL}/auth/logout`)
  })

  it('HR Director + Bonus Pro', () => {
      browser.url(process.env.APP_URL)

      $('[name=email]').addValue('admin@example.com')
      $('[name=password]').addValue('fff')
      $('[type=submit]').click()

      $('td=aa_template Test Business').$('..').$('a=EDIT').click()

      $('#hrdirector_enabled_yes').waitForDisplayed({ timeout: 5000 })
      $('#hrdirector_enabled_yes').scrollIntoView()
      $('#hrdirector_enabled_yes').click()
      $('#bonuspro_enabled_yes').click()

      $('button=SAVE').click()

      browser.url(`${process.env.APP_URL}/auth/logout`)

      $('[name=email]').addValue('owner1@example.com')
      $('[name=password]').addValue('fff')
      $('[type=submit]').click()

      expect($$('.nav-root.enabled')).toBeElementsArrayOfSize( { eq: 9 } )

      browser.url(`${process.env.APP_URL}/auth/logout`)
  })
})

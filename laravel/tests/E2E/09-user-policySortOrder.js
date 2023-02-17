describe('Policy Sort Order', () => {
  xit('Allows admin to control sort order for businesses', () => {
    browser.url(process.env.APP_URL)

    $('[name=email]').addValue('admin@example.com')
    $('[name=password]').addValue('fff')
    $('[type=submit]').click()

    $('a=VIEW AS CLIENT').waitForDisplayed({ timeout: 10000 })
    
    browser.pause(5000)

    $('a=VIEW AS CLIENT').click()

    $('a=POLICIES').click()

    $('a=RESET POLICIES').click()
    
    browser.pause(5000)

    $('a=Reset Policies').click()
    browser.url(`${process.env.APP_URL}/auth/logout`)

    $('[name=email]').addValue('owner1@example.com')
    $('[name=password]').addValue('fff')
    $('[type=submit]').click()

    $('a=POLICIES').click()

    $('a=POLICY EDITOR').click()

    $('td=VACATION/PTO').scrollIntoView()

    expect($('tr:nth-child(44) td:nth-child(1)')).toHaveText('VACATION/PTO')

    browser.url(`${process.env.APP_URL}/auth/logout`)

    $('[name=email]').addValue('admin@example.com')
    $('[name=password]').addValue('fff')
    $('[type=submit]').click()

    browser.url(`${process.env.APP_URL}/admin/policies`)

    $('a=SORT').scrollIntoView()

    $('a=SORT').click()

    $('[href="/admin/policies/sort/4"]').click()

    const elem = $('div=Vacation Benefits ( VACATION BENEFITS )')
    const elems = $$(`tr.ui-sortable-handle`)

    let target = $(`tr.ui-sortable-handle:nth-child(24)`)

    target.scrollIntoView()

    browser.pause(1000)

    elem.dragAndDrop({x: 0, y: -444})

    target = $(`tr.ui-sortable-handle:nth-child(5)`)

    target.scrollIntoView()

    browser.pause(1000)

    elem.dragAndDrop({x: 0, y: -447})

    target = $(`h4=Benefits`)

    target.scrollIntoView()
    
    browser.pause(1000)

    elem.dragAndDrop({x: -5, y: -400})

    $('button=SAVE').scrollIntoView()
    $('button=SAVE').click()

    browser.url(`${process.env.APP_URL}/auth/logout`)

    $('[name=email]').addValue('owner1@example.com')
    $('[name=password]').addValue('fff')
    $('[type=submit]').click()

    $('a=POLICIES').click()
    $('a=POLICY EDITOR').click()

    $('a=RESTORE DEFAULT SORT').scrollIntoView()
    $('a=RESTORE DEFAULT SORT').click()
    
    $('td=VISION BENEFITS').scrollIntoView()

    browser.saveScreenshot('./policy-order-screenshot.png')

    expect($('tr:nth-child(39) td:nth-child(1)')).toHaveText('VACATION/PTO')

  })
})
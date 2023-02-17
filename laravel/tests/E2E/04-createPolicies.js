describe('Policies', () => {
    it('can create new policy', () => {
        browser.url(process.env.APP_URL)

        expect(browser).toHaveUrl(`${process.env.APP_URL}/auth/login`)

        $('[name=email]').addValue('admin@example.com')
        $('[name=password]').addValue('fff')
        $('[type=submit]').click()

        expect(browser).toHaveUrl(`${process.env.APP_URL}/admin/business`)

        browser.url(`${process.env.APP_URL}/admin/policies/create`)
        
        $('[name=admin_name]').addValue('Test Policy 01')
        $('[name=manual_name]').addValue('Test Policy - 01')
        
        $('[name=employee_range_min]').addValue('0')
        $('[name=employee_range_max]').addValue('999')

        $('#policy_benefit_state').selectByVisibleText('All States')

        $('[name="policy_effective_date"]').click()        
        $('[data-handler="selectDay"]').click()

        $('#policy_required_default').selectByVisibleText('Optional')

        $('.cke_contents').click()

        $('.cke_wysiwyg_frame').addValue('test')

        $$('[type=submit]')[1].click()

        expect(browser).toHaveUrl(`${process.env.APP_URL}/admin/policies`)

        browser.pause(2000)

        $('[name=col_1_search]').addValue('Test Policy 01')

        
    })
})
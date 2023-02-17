describe('HR Director initial page', () => {
    it('should have the right title', () => {
        browser.url(process.env.APP_URL)
        expect(browser).toHaveTitle('HR Director')
        expect(browser).toHaveUrl(`${process.env.APP_URL}/auth/login`)
    })
})
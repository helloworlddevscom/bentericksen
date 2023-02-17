module.exports = class Page {
    constructor() {
        this.title = 'My Page'
    }

    async open (path) {
        await browser.url(path)
    }

    addValue(element, value) {
        element.click()
        browser.pause(100)
        element.clearValue()
        browser.pause(100)
        element.setValue(value)
        browser.pause(100)
        browser.execute((elem) => {
            const event = new Event('blur')
            elem.dispatchEvent(event)
        }, element)
        browser.pause(100)
    }
}
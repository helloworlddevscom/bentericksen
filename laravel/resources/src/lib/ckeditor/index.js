import loadScript from '@/utils/loadScript'

async function main() {
    await loadScript('/assets/js/plugins/ckeditor/ckeditor.js')
    await loadScript('/assets/js/plugins/ckeditor/plugins/nanospell/autoload.js')
    await loadScript('/assets/js/plugins/ckeditor/plugins/lite/lite-interface.js')
}

export default main()

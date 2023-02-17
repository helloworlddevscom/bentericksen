import loadScript from '@/utils/loadScript'
import './calendar.css'

async function main() {
  await loadScript('/assets/scripts/plugins/calendar/calendar.js')
  await loadScript('/assets/scripts/plugins/calendar/underscore-min.js')
}

export default main()
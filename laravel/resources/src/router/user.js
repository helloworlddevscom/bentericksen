import matchRoutes from '@/utils/matchRoutes'

const ckEditorRoutes = [
  /(user\/policies\/\d{1,}\/edit)/g,
  /user/g,
  /user\/policies\/create/g,
  /user\/job-descriptions\/create/g,
  /(user\/job-descriptions\/\d{1,}\/edit)/g,
]

export const router = async () => {
  const path = window.location.pathname
  
  if ( matchRoutes(ckEditorRoutes, path) ) {
      const _ckeLoad = await import ('@/lib/ckeditor')
      await _ckeLoad.default
  }

  import ('@/lib/user')
  import ('@/lib/user/renewal')
  import ('@/lib/user/employeeCount')

  if ( /user\/policies/g.test(path) ) {
    import ('@/pages/User/Policies/List')
  }

  if ( /(user\/policies\/create)/g.test(path) ) {
    import ('@/pages/User/Policies/Create')
  }

  if ( /(user\/policies\/\d{1,}\/edit)/g.test(path) ) {
    import ('@/pages/User/Policies/Edit')
  }

  if ( /user\/employees/g.test(path) ) {
    import ('@/pages/User/Employees/List')
  }

  if ( /(user\/employees\/create)/g.test(path) ) {
    await import ('@/lib/jqueryUI')
    import ('@/pages/User/Employees/Create')
  }

  if ( /(user\/employees\/\d{1,}\/edit)/g.test(path) ) {
    await import ('@/lib/jqueryUI')
    await import ('@/lib/jqueryUI/dateTimePicker')
    import ('@/pages/User/Employees/Edit')
  }

  if ( /user\/employees\/time-off-requests/g.test(path) ) {
    await import ('@/lib/jqueryUI')
    await import ('@/lib/jqueryUI/dateTimePicker')
    const _calLoad = await import ('@/lib/calendar')
    await _calLoad.default
  }

  if ( /user\/employees\/time-off-requests\/\d{1,}/g.test(path) ) {
    await import ('@/lib/jqueryUI')
    await import ('@/lib/jqueryUI/dateTimePicker')
    import ('@/pages/User/Employees/TimeOffRequests/Edit')
  }

  if ( /user\/employees\/number/g.test(path) ) {
    import ('@/pages/User/Employees/Number')
  }

  if ( /user\/employees\/excel/g.test(path) ) {
    import ('@/pages/User/Employees/Excel')
  }

  if ( /user\/forms/g.test(path) ) {
    import ('@/pages/User/Forms/List')
  }

  if ( /user\/job-descriptions/g.test(path) ) {
    import ('@/pages/User/JobDescriptions/List')
  }

  if ( /user\/job-descriptions\/create/g.test(path) ) {
    import ('@/pages/User/JobDescriptions/Create')
  }

  if ( /(user\/job-descriptions\/\d{1,}\/edit)/g.test(path) ) {
    import ('@/pages/User/JobDescriptions/Edit')
  }

  if ( /user\/faqs/g.test(path) ) {
    await import ('@/lib/jqueryUI')
    import ('@/pages/User/Tools/Faqs')
  }

  if ( /user\/calculators/g.test(path) ) {
    await import ('@/lib/jqueryUI')
    import ('@/pages/User/Tools/Calculators')
  }

  if (/user\/licensure-certifications/g.test(path) ) {
    import ('@/pages/User/Settings/LicensureCertifications')
  }
}
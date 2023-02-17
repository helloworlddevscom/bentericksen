import matchRoutes from '@/utils/matchRoutes'

const ckEditorRoutes = [
  /(admin\/policies\/\d{1,}\/edit)/g,
  /(admin\/policies\/create)/g,
  /(admin\/faqs\/create)/g,
  /(admin\/job-descriptions\/create)/g,
  /(admin\/job-descriptions\/\d{1,}\/edit)/g,
  /(admin\/help\/create)/g,
  /(admin\/help\/\d{1,}\/edit)/g,
  /(admin\/policies\/updates\/create)/g,
  /(admin\/policies\/updates\/\d{1,})/g
]

export const router = async () => {
  const path = window.location.pathname

    if ( /(admin\/business\/.{0,})/g.test(path) ) {
        await import('@/utils/masks')
        await import ('@/lib/calendar/calendar.css')
        import('@/pages/Admin/Business/Form')
    }

    if ( matchRoutes(ckEditorRoutes, path) ) {
         const _c = await import ('@/lib/ckeditor')
         await _c.default
    }

    if (matchRoutes([/(admin\/policies\/\d{1,}\/edit)/g], path)) {
      import('@/pages/Admin/Policies/Edit')
    }

    if (matchRoutes([/(admin\/policies\/updates\/\d{1,})/g], path)) {
      await import('@/pages/Admin/Policies/Updates/Create')
    }
    
    switch (window.location.pathname) {
        case '/admin/forms':
          import('@/pages/Admin/Forms/List')
          break;
        case '/admin/help':
            import('@/pages/Admin/Help/List')
            break;
        case '/admin/policies':
            import('@/pages/Admin/Policies/index')
            break;
        case '/admin/faqs/create':
            import('@/pages/Admin/Faqs/Create')
            break;
        case '/admin/policies/updates':
            import('@/pages/Admin/Policies/updater')
            break;
        case '/admin/job-descriptions':
            import('@/pages/Admin/job_description')
            break;
        case '/admin/job-descriptions/create':
          import('@/pages/Admin/job_description/create')
          break;
        case '/admin/policies/update/create':
          import('@/pages/Admin/Policies/Updates/Create')
          break;

    }
}

export default router
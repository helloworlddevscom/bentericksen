export default async function main() {
  await Promise.all([
      import ('@/lib/bootstrap/bootstrap-3-3-1.min.css'),
      import ('@/assets/styles/global.scss'),
      import ('@/assets/styles/global-admin.scss'),
      import ('font-awesome/scss/font-awesome.scss'),
      import ('@/lib/jquery'),
      import ('@/lib/axios')
  ])

  const { router } = await import('@/router/admin')

  await Promise.all([
      import('@/lib/bootstrap/bootstrap-3-3-1.min'),
      import('@/lib/jqueryUI'),
      import ('@/lib/dataTables'),
  ])

  await router()

  import ('@/lib/admin')

  window.PAGE_INIT.forEach((init) => init())

  setTimeout(() => $('.curtain').hide(), 500)
}
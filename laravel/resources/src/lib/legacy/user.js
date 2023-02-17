export default async function main() {
  await Promise.all([
    import ('@/lib/bootstrap/bootstrap-3-3-1.min.css'),
    import ('@/assets/styles/global-user.scss'),
    import ('@/assets/styles/global.scss'),
    import ('font-awesome/scss/font-awesome.scss'),
    import ('@/lib/jquery'),
    import ('@/lib/axios')
  ])

  const { router } = await import('@/router/user')

  await Promise.all([
    import('@/lib/bootstrap/bootstrap-3-3-1.min'),
  ])

  await router()

  window.PAGE_INIT.forEach((init) => init())

  setTimeout(() => $('.curtain').hide(), 1000)
}
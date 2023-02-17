async function main() {
  await Promise.all([
      import ('@/lib/bootstrap/bootstrap-3-3-1.min.css'),
      import ('@/assets/styles/global.scss'),
      import ('@/assets/styles/global-consultant.scss'),
      import ('font-awesome/scss/font-awesome.scss'),
      import ('@/lib/jquery'),
      import ('@/lib/axios')
  ])

  const { router } = await import('@/router/consultant')

  await Promise.all([
      import('@/lib/bootstrap/bootstrap-3-3-1.min'),
      import('@/lib/jqueryUI')
  ])

  await router()

  //import ('@/lib/consultant')

  window.PAGE_INIT.forEach((init) => init())

  setTimeout(() => $('.curtain').hide(), 500)
}

main()
  

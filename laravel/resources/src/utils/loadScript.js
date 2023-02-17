export default function loadScript (url) {
  var n = document.createElement('script')
  n.type = 'text/javascript'
  n.async = !0
  n.src = url
  var a = document.getElementsByTagName('script')[0]
  const promise = new Promise((resolve, reject) => {
      n.addEventListener('load', resolve)
  })
  a.parentNode.insertBefore(n, a)
  return promise
}
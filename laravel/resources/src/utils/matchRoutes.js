export default function(routes, route) {
    return routes.map((routeExpression) => routeExpression.test(route)).includes(true)
}
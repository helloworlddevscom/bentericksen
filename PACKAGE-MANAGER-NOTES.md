# Bent Ericksen Package Manager Notes (NPM and Composer)

This file explains some of the vendor packages in this project, what they're used for, and how to upgrade them.

# Composer Packages

See laravel/composer.json.

* laravel/framework - this is Laravel core.
* laravel/tinker - script writing tools for Laravel
* h4cc/wkhtmltopdf-amd64 - related to the wkhtmltopdf software, which generates PDFs on the server.
* barryvdh/laravel-debugbar - The developer debug bar that appears on the bottom of the page.
* phpunit/phpunit - PHP testing framework; also uses the following:
  * mockery/mockery
  * fzaninotto/faker
* guzzlehttp/guzzle - HTTP client
* zizaco/entrust - Role-based permissions library. This is obsolete since role-based permissions were added to Laravel Core in 5.4 (?). Could be removed but would require some refactoring.

# NPM Packages

See laravel/package.json

Upgrading these is a little trickier than upgrading the PHP Composer packages. Some have to be pinned to certain versions.

* laravel-mix - Webpack integration for Laravel. Webpack is a dependency of this and is not listed in package.json. Plugins include:
  * babel-loader
  * cross-env
  * sass-loader
  * style-loader
  * vue-loader
* babel-core@7.0.0-bridge.0 - Bridge package between old babel-core and newer @babel/core. As of Feb. 2019, jest and vue-jest still need this. See https://github.com/vuejs/vue-jest/issues/160
* @babel/core - Core Babel package. Needed by Jest. (Laravel Mix and Webpack 4 have their own separate copy.)
  * @babel/polyfill - IE polyfills
* bootstrap
  * popper.js - popup management library for Bootstrap
* browser-sync - live reload browser when Vue or JS files change
  * browser-sync-webpack-plugin
* eslint - code linting tool for JS. As of Feb. 2019, this is pinned to 3.x version because that's what Codacy needs.
  * eslint-config-vue - Special eslint plugins for working with .vue files. Upgrading these may break Codacy.
  * eslint-plugin-vue
* moment
* vue - The Vue.JS framework. Includes some related packages:
  * vuex - Flux-like state management for Vue (like Redux)
* jest - Test runner. Includes some related packages:
  * vue-jest
  * @vue/test-utils

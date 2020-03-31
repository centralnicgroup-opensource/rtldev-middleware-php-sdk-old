# [5.2.0](https://github.com/hexonet/php-sdk/compare/v5.1.0...v5.2.0) (2020-03-31)


### Features

* **apiclient:** automatic IDN conversion of API command parameters to punycode ([79936b0](https://github.com/hexonet/php-sdk/commit/79936b0f9e49cc56e839657e1af30d76d78aa60a))

# [5.1.0](https://github.com/hexonet/php-sdk/compare/v5.0.1...v5.1.0) (2020-03-13)


### Features

* **apiclient:** support bulk parameters through nested array in API command ([5494a41](https://github.com/hexonet/php-sdk/commit/5494a41516eda545e0605663671dbcea95a8d848))

## [5.0.1](https://github.com/hexonet/php-sdk/compare/v5.0.0...v5.0.1) (2020-01-22)


### Bug Fixes

* **composer:** cleanup; trigger new release for commit 99b5b35 ([0b63c3f](https://github.com/hexonet/php-sdk/commit/0b63c3f92669ae7575bf7dfb65e87fee3e3b3f53))

# [5.0.0](https://github.com/hexonet/php-sdk/compare/v4.5.5...v5.0.0) (2020-01-22)


### Code Refactoring

* **php5 support:** review to still support PHP5; for refactoring our 3rd party integrations ([cd652d5](https://github.com/hexonet/php-sdk/commit/cd652d57de70dfdc88402dfbbe19848b5ca25446))


### BREAKING CHANGES

* **php5 support:** APIClient's method requestNextResponsePage now throws an Exception instead of an
Error (PHP5 compatibility). We will review in future in direction of PHP7 only.

## [4.5.5](https://github.com/hexonet/php-sdk/compare/v4.5.4...v4.5.5) (2019-10-04)


### Bug Fixes

* **responsetemplate/mgr:** improve description of `423 Empty API response` ([63d6c4c](https://github.com/hexonet/php-sdk/commit/63d6c4c))

## [4.5.4](https://github.com/hexonet/php-sdk/compare/v4.5.3...v4.5.4) (2019-09-18)


### Bug Fixes

* **npm:** review package.json ([2684e71](https://github.com/hexonet/php-sdk/commit/2684e71))

## [4.5.3](https://github.com/hexonet/php-sdk/compare/v4.5.2...v4.5.3) (2019-09-18)


### Bug Fixes

* **release process:** fix path to composer in travis ([cc3f453](https://github.com/hexonet/php-sdk/commit/cc3f453))
* **release process:** review configuration ([6fa9481](https://github.com/hexonet/php-sdk/commit/6fa9481))

## [4.5.2](https://github.com/hexonet/php-sdk/compare/v4.5.1...v4.5.2) (2019-08-16)


### Bug Fixes

* **APIClient:** change default SDK url ([c5505fe](https://github.com/hexonet/php-sdk/commit/c5505fe))

## [4.5.1](https://github.com/hexonet/php-sdk/compare/v4.5.0...v4.5.1) (2019-06-14)


### Bug Fixes

* **APIClient:** fix typo in method call ([c932484](https://github.com/hexonet/php-sdk/commit/c932484))

# [4.5.0](https://github.com/hexonet/php-sdk/compare/v4.4.1...v4.5.0) (2019-04-16)


### Features

* **responsetemplate:** add isPending method ([f12c64f](https://github.com/hexonet/php-sdk/commit/f12c64f))

## [4.4.1](https://github.com/hexonet/php-sdk/compare/v4.4.0...v4.4.1) (2019-04-04)


### Bug Fixes

* **APIClient:** return apiclient instance in setUserAgent method ([ec469ab](https://github.com/hexonet/php-sdk/commit/ec469ab))

# [4.4.0](https://github.com/hexonet/php-sdk/compare/v4.3.1...v4.4.0) (2019-04-01)


### Features

* **apiclient:** review user-agent header usage ([6aa5342](https://github.com/hexonet/php-sdk/commit/6aa5342))

## [4.3.1](https://github.com/hexonet/php-sdk/compare/v4.3.0...v4.3.1) (2018-10-24)


### Bug Fixes

* **phpDocumentor:** install missing dep graphviz ([52252af](https://github.com/hexonet/php-sdk/commit/52252af))

# [4.3.0](https://github.com/hexonet/php-sdk/compare/v4.2.0...v4.3.0) (2018-10-24)

# [4.2.0](https://github.com/hexonet/php-sdk/compare/v4.1.0...v4.2.0) (2018-10-17)


### Bug Fixes

* **dependabot:** minor release on build commit msg ([900fc97](https://github.com/hexonet/php-sdk/commit/900fc97))

# [4.1.0](https://github.com/hexonet/php-sdk/compare/v4.0.4...v4.1.0) (2018-10-15)


### Features

* **client:** add method getSession ([fae89c0](https://github.com/hexonet/php-sdk/commit/fae89c0))

## [4.0.4](https://github.com/hexonet/php-sdk/compare/v4.0.3...v4.0.4) (2018-10-05)


### Bug Fixes

* **docs:** review jsdoc comments ([0d8f5d7](https://github.com/hexonet/php-sdk/commit/0d8f5d7))

## [4.0.3](https://github.com/hexonet/php-sdk/compare/v4.0.2...v4.0.3) (2018-10-05)


### Bug Fixes

* **docs:** fix class docs and ([c1a4e5e](https://github.com/hexonet/php-sdk/commit/c1a4e5e))

## [4.0.1](https://github.com/hexonet/php-sdk/compare/v4.0.0...v4.0.1) (2018-10-05)


### Bug Fixes

* **composer:** consider ResponseParser namespace for autload ([032aac3](https://github.com/hexonet/php-sdk/commit/032aac3))

# [4.0.0](https://github.com/hexonet/php-sdk/compare/v3.0.3...v4.0.0) (2018-10-05)


### Bug Fixes

* **travis:** try config review ([dfdc6cd](https://github.com/hexonet/php-sdk/commit/dfdc6cd))
* **travis:** try release process review ([c7cee36](https://github.com/hexonet/php-sdk/commit/c7cee36))


### Features

* **4.0.0:** Merge pull request [#2](https://github.com/hexonet/php-sdk/issues/2) from hexonet/v4.0.0 ([55d095c](https://github.com/hexonet/php-sdk/commit/55d095c))


### BREAKING CHANGES

* **4.0.0:** Review in direction of our generic UML Diagram.

### Changelog

All notable changes to this project will be documented in this file. Dates are displayed in UTC.

#### [v3.0.3](https://github.com/hexonet/php-sdk/compare/v3.0.2...v3.0.3) (3 July 2018)

- added api documentation [`034c492`](https://github.com/hexonet/php-sdk/commit/034c492e5c9f29084207fc0a0c657451b570cafc)
- Update README.md [`8def453`](https://github.com/hexonet/php-sdk/commit/8def453f8ccda2e807574ec47e955b3489355915)

#### [v3.0.2](https://github.com/hexonet/php-sdk/compare/v3.0.1...v3.0.2) (2 July 2018)

- added changelog generator [`896848e`](https://github.com/hexonet/php-sdk/commit/896848e8ab67a88f8cd86647ca0f1c8cfe342672)

#### [v3.0.1](https://github.com/hexonet/php-sdk/compare/v3.0.0...v3.0.1) (2 July 2018)

- readme: add slack chat badge [`b58e774`](https://github.com/hexonet/php-sdk/commit/b58e774943bc9f85e5bbce863fcd8140ca5be9b6)
- readme: fix getting started [`e8ec52c`](https://github.com/hexonet/php-sdk/commit/e8ec52c3060e7c24a6ed89436f1fcdb38373c7ef)
- readme: added packagist module version badge [`76c2f49`](https://github.com/hexonet/php-sdk/commit/76c2f49a59c817406ea1d61d13577d688c867628)
- readme: add license and contributing badge [`91b0559`](https://github.com/hexonet/php-sdk/commit/91b05594504f443f1ab8942e5540a59b3c762fca)
- readme: added php version badge [`729e8b2`](https://github.com/hexonet/php-sdk/commit/729e8b2bc3937d0480eeab0bf9b5e5f7586414c4)

### [v3.0.0](https://github.com/hexonet/php-sdk/compare/v2.0.3...v3.0.0) (21 June 2018)

- moved connect method as static method to connection class [`c34761f`](https://github.com/hexonet/php-sdk/commit/c34761f88e9f44c755cf128c7d9aec8cb195d3ea)

#### [v2.0.3](https://github.com/hexonet/php-sdk/compare/v2.0.2...v2.0.3) (21 June 2018)

- remove composer.lock from .gitignore [`d2c6368`](https://github.com/hexonet/php-sdk/commit/d2c636805bdb25336a8552654dfcc83d417e9396)

#### [v2.0.2](https://github.com/hexonet/php-sdk/compare/v2.0.1...v2.0.2) (21 June 2018)

- added class map to composer.json [`0bd34e0`](https://github.com/hexonet/php-sdk/commit/0bd34e039e77b65fa51b6da8412b69c3f9f2e1e5)

### [v2.0.1](https://github.com/hexonet/php-sdk/compare/v1.0.0...v2.0.1) (21 June 2018)

- added phpDocumentor [`4175b9d`](https://github.com/hexonet/php-sdk/commit/4175b9d8c258bcf1337a689669c01c84db0651fc)

#### v1.0.0 (21 June 2018)

- added unit tests [`b6cedd4`](https://github.com/hexonet/php-sdk/commit/b6cedd4c9c8ac5bc491ab10662491024bdfe4847)
- added unit tests for class connection and response [`1b3bfbb`](https://github.com/hexonet/php-sdk/commit/1b3bfbb8a0fb6852b690bb61d1954a3febbc0488)
- minor cleanup [`777b1ce`](https://github.com/hexonet/php-sdk/commit/777b1cec613f46ed06e93283e4c502ba646dd439)
- setup phpunit; phpcs; phpcsf [`f88965b`](https://github.com/hexonet/php-sdk/commit/f88965b668140b8c2e9434df7581466b27b7c47f)
- updated readme [`f6cfcae`](https://github.com/hexonet/php-sdk/commit/f6cfcaef78be59fd494e1ab2aaa94d8f9ae4d766)
- update readme [`f7e01ea`](https://github.com/hexonet/php-sdk/commit/f7e01ea92bcf013bd843bf163c63ce8250001020)
- added .gitignore and phpunit.xml [`79d3127`](https://github.com/hexonet/php-sdk/commit/79d31277de5c188167062c20c4a963a02c6d197f)
- updated readme.md [`2b3cda3`](https://github.com/hexonet/php-sdk/commit/2b3cda3d8fcd6ce73adc32b8a33d847614f954cd)
- reviewed repository structure; added basic files [`7fdd40c`](https://github.com/hexonet/php-sdk/commit/7fdd40c0bcbd8b1b6789b1b62d2690b2e34b674a)
- initial release [`433b056`](https://github.com/hexonet/php-sdk/commit/433b0560f65f8053bc07118c6fa810cccf44d9e2)

## [6.0.6](https://github.com/hexonet/php-sdk/compare/v6.0.5...v6.0.6) (2021-05-21)


### Bug Fixes

* **responsetranslator:** fixed preg_quote usage, added delimiter ([8988cdd](https://github.com/hexonet/php-sdk/commit/8988cdd1cb896693f145511e56a1eb5a979dc242))
* **responsetranslator:** reviewed regex and added translation case ([c513fe6](https://github.com/hexonet/php-sdk/commit/c513fe65391288779346bc2c33503f22b6142c1c))

## [6.0.5](https://github.com/hexonet/php-sdk/compare/v6.0.4...v6.0.5) (2021-05-21)


### Bug Fixes

* **responsetranslator:** fixed regular expression used ([e974948](https://github.com/hexonet/php-sdk/commit/e974948156495dbf61bb3f6a1959f39a899e24ff))

## [6.0.4](https://github.com/hexonet/php-sdk/compare/v6.0.3...v6.0.4) (2021-05-21)


### Bug Fixes

* **responsetranslator:** review generic replacement logic ([c76d2f6](https://github.com/hexonet/php-sdk/commit/c76d2f6e7119e3d689322c0c9cf1d5619bc099b4))

## [6.0.3](https://github.com/hexonet/php-sdk/compare/v6.0.2...v6.0.3) (2021-05-21)


### Bug Fixes

* **responsetranslator:** added templates for translating CheckDomainTransfer ([9f812db](https://github.com/hexonet/php-sdk/commit/9f812db89ba6e5e6ff13d017c9e514b89abc4007))

## [6.0.2](https://github.com/hexonet/php-sdk/compare/v6.0.1...v6.0.2) (2021-05-11)


### Bug Fixes

* **api docs:** cleanup build folder before generating the docs to avoid keeping deprecated files ([563266c](https://github.com/hexonet/php-sdk/commit/563266c89b9f69d4067955a12366da9dfd79bb4d))

## [6.0.1](https://github.com/hexonet/php-sdk/compare/v6.0.0...v6.0.1) (2021-04-06)


### Bug Fixes

* **response translator:** fix response translator regarding ACL response case ([4fa8ef8](https://github.com/hexonet/php-sdk/commit/4fa8ef8051973cfe02a700c98c66f7afd4114095))

# [6.0.0](https://github.com/hexonet/php-sdk/compare/v5.8.9...v6.0.0) (2021-04-06)


### Bug Fixes

* **response translator:** added missing typehints ([8b3acb7](https://github.com/hexonet/php-sdk/commit/8b3acb7aa83adc5a459413bd7e519b7360538103))


### Features

* **response translator:** added initial version coming with rewrite/restructuring from scratch ([8f19444](https://github.com/hexonet/php-sdk/commit/8f19444479cea5feac6d7fcd427b2dbb675c0bec))


### BREAKING CHANGES

* **response translator:** Downward incompatible restructuring (Merge of Response/ResponseTemplate, Rewrite ResponseTemplateManager) and introducing ResponseTranslator

## [5.8.9](https://github.com/hexonet/php-sdk/compare/v5.8.8...v5.8.9) (2021-01-21)


### Bug Fixes

* **ci:** exclude build/api-cache from @semantic-release/git assets ([11cfe91](https://github.com/hexonet/php-sdk/commit/11cfe91a4eae2964ec985d532eb3fea2f40c9774))

## [5.8.8](https://github.com/hexonet/php-sdk/compare/v5.8.7...v5.8.8) (2021-01-21)


### Bug Fixes

* **ci:** ignore tag commits ([dbda7ef](https://github.com/hexonet/php-sdk/commit/dbda7ef1b0759862840a340530208c1afbc87843))

## [5.8.7](https://github.com/hexonet/php-sdk/compare/v5.8.6...v5.8.7) (2021-01-21)


### Bug Fixes

* **ci:** add missing composer update for autoloader ([3cb0525](https://github.com/hexonet/php-sdk/commit/3cb05256e25b0b583dad433d243118f9b4957ada))
* **ci:** apt package installation reviewed ([a17e3ce](https://github.com/hexonet/php-sdk/commit/a17e3ce7cea9a51982c35c405d65afeb5defeef9))
* **ci:** fixed phpcs reported issues ([6b061d3](https://github.com/hexonet/php-sdk/commit/6b061d3eda807da09eb29f045d2f1892d9f6ae17))
* **ci:** migration from Travis CI to github actions ([e3d86b9](https://github.com/hexonet/php-sdk/commit/e3d86b99f24fcd68e78f3f576253ac32bebffc28))
* **phpdocumentor:** upgrade; review config; add to release step ([2ed94d0](https://github.com/hexonet/php-sdk/commit/2ed94d0963731827bebce9b224b4e380aba04c82))
* **responsetemplatemanager:** method __wakeup must have public visibility ([9f3c7f1](https://github.com/hexonet/php-sdk/commit/9f3c7f19adcd17fd57bb8f97ef92aa82ff5204e9))

## [5.8.6](https://github.com/hexonet/php-sdk/compare/v5.8.5...v5.8.6) (2020-07-23)


### Bug Fixes

* **apiclient:** use php version without extra data ([a9efa97](https://github.com/hexonet/php-sdk/commit/a9efa971302e4dbce449045917427477068e3546))

## [5.8.5](https://github.com/hexonet/php-sdk/compare/v5.8.4...v5.8.5) (2020-07-17)


### Bug Fixes

* **apiclient:** fixed log method call to use correct argument type ([0ba6cdd](https://github.com/hexonet/php-sdk/commit/0ba6cdd87c601153ad0e91e099ea293ccb6cd6ef))

## [5.8.4](https://github.com/hexonet/php-sdk/compare/v5.8.3...v5.8.4) (2020-07-15)


### Bug Fixes

* **apiclient:** fixed types and a nasty return+else ([7492c9f](https://github.com/hexonet/php-sdk/commit/7492c9fb59b5ab4367a82a8330fd0654151e2401))

## [5.8.3](https://github.com/hexonet/php-sdk/compare/v5.8.2...v5.8.3) (2020-04-27)


### Bug Fixes

* **apiclient:** remove deprecated private method toUpperCaseKeys ([bbab21e](https://github.com/hexonet/php-sdk/commit/bbab21ec869d87ab5ffbde5ee0590a3c4ac8e233))

## [5.8.2](https://github.com/hexonet/php-sdk/compare/v5.8.1...v5.8.2) (2020-04-15)


### Bug Fixes

* **apiclient:** fixed automatic idn conversion ([aae4fe6](https://github.com/hexonet/php-sdk/commit/aae4fe68e4c63485d1023c014af03c5862f25f50))

## [5.8.1](https://github.com/hexonet/php-sdk/compare/v5.8.0...v5.8.1) (2020-04-09)


### Bug Fixes

* **security:** fixed password replace mechanism ([f22ab11](https://github.com/hexonet/php-sdk/commit/f22ab113019c3da571fac769e0388d7c83f56f15))

# [5.8.0](https://github.com/hexonet/php-sdk/compare/v5.7.0...v5.8.0) (2020-04-06)


### Features

* **phar:** create and upload phar/phar.gz archives in release process ([696d4a8](https://github.com/hexonet/php-sdk/commit/696d4a871c16675132b49e05f5fb26e4a900ad60))

# [5.7.0](https://github.com/hexonet/php-sdk/compare/v5.6.1...v5.7.0) (2020-04-03)


### Features

* **apiclient:** allow to specify additional libraries via setUserAgent ([7f9cf7c](https://github.com/hexonet/php-sdk/commit/7f9cf7c79a0400eb0c7a03176c8946eea4a2c13a))

## [5.6.1](https://github.com/hexonet/php-sdk/compare/v5.6.0...v5.6.1) (2020-04-02)


### Bug Fixes

* **security:** replace passwords whereever they could be used for output ([9e97123](https://github.com/hexonet/php-sdk/commit/9e9712315697e513860474fe01d02730a68666f7))

# [5.6.0](https://github.com/hexonet/php-sdk/compare/v5.5.1...v5.6.0) (2020-04-02)


### Features

* **response:** added getCommandPlain (getting used command in plain text) ([c3992a4](https://github.com/hexonet/php-sdk/commit/c3992a48aa9e83c4eeeff3fff34cee018ef20ef5))

## [5.5.1](https://github.com/hexonet/php-sdk/compare/v5.5.0...v5.5.1) (2020-04-02)


### Bug Fixes

* **namespace:** review namespace usages ([509e988](https://github.com/hexonet/php-sdk/commit/509e9882e8412c4097ca988668383194b53a8537))

# [5.5.0](https://github.com/hexonet/php-sdk/compare/v5.4.2...v5.5.0) (2020-04-01)


### Features

* **logger:** possibility to override debug mode's default logging mechanism. See README.md ([680c70e](https://github.com/hexonet/php-sdk/commit/680c70e888b2d8ce9cdac03530954c0c379ef04c))

## [5.4.2](https://github.com/hexonet/php-sdk/compare/v5.4.1...v5.4.2) (2020-04-01)


### Bug Fixes

* **auto-versioning:** fixed broken version auto-update process ([58103b9](https://github.com/hexonet/php-sdk/commit/58103b95435dc7822df516aa49e6491fd2d545d2))

## [5.4.1](https://github.com/hexonet/php-sdk/compare/v5.4.0...v5.4.1) (2020-04-01)


### Bug Fixes

* **messaging:** return a specific error template in case code or description are missing ([59119c3](https://github.com/hexonet/php-sdk/commit/59119c31f16a269e24b07609f5ee4364628e3321))

# [5.4.0](https://github.com/hexonet/php-sdk/compare/v5.3.0...v5.4.0) (2020-04-01)


### Bug Fixes

* **response:** fixed placeholder replacements ([4e188dd](https://github.com/hexonet/php-sdk/commit/4e188dd393f57a0013a8512b482d1a3d96683324))


### Features

* **response:** possibility of placeholder vars in standard responses to improve error details ([1d0a017](https://github.com/hexonet/php-sdk/commit/1d0a0170134232f9a83a006dd8979cee9f3d0d4b))

# [5.3.0](https://github.com/hexonet/php-sdk/compare/v5.2.0...v5.3.0) (2020-03-31)


### Features

* **apiclient:** support the `High Performance Proxy Setup`. see README.md ([90c73ab](https://github.com/hexonet/php-sdk/commit/90c73ab84225a29a1a17fb6fa346bf22932de121))

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

#!/bin/bash
phpcs --standard=PHPCompatibility -q -n --colors --runtime-set testVersion 5.6 src || exit 1;
phpcs --standard=PHPCompatibility -q -n --colors --runtime-set testVersion 7.2 src || exit 1;
phpcs --standard=PHPCompatibility -q -n --colors --runtime-set testVersion 7.3 src || exit 1;

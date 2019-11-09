lint: lint-assets lint-php

lint-assets: lint-css lint-js lint-scss

lint-assets-fix: lint-css lint-js-fix lint-scss

lint-css:
	npm run lint:css

lint-js:
	npm run lint:js

lint-js-fix:
	npm run lint:js:fix

lint-php: lint-phpmd lint-phpcs

lint-phpmd:
	./vendor/bin/phpmd web/app,config text phpmd.xml --suffixes php

lint-phpcs:
	./vendor/bin/phpcs -p -s -v --standard=phpcs.xml --extensions=php ./web/app ./config

lint-scss:
	npm run lint:scss

.PHONY: lint lint-assets lint-assets-fix lint-css lint-js lint-js-fix lint-php lint-phpmd lint-phpcs lint-scss

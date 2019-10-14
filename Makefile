lint: lint-assets lint-php

lint-assets: lint-css lint-js lint-scss

lint-assets-fix: lint-css lint-js-fix lint-scss

lint-css:
	yarn --cwd $(PWD)/src lint:css

lint-js:
	yarn --cwd $(PWD)/src lint:js

lint-js-fix:
	yarn --cwd $(PWD)/src lint:js:fix

lint-php: lint-phpmd lint-phpcs

lint-phpmd:
	./vendor/bin/phpmd web/app,config text phpmd.xml --suffixes php

lint-phpcs:
	./vendor/bin/phpcs -p -s -v --standard=phpcs.xml --extensions=php ./web/app ./config

lint-scss:
	yarn --cwd $(PWD)/src lint:scss

.PHONY: lint lint-assets lint-assets-fix lint-css lint-js lint-js-fix lint-php lint-phpmd lint-phpcs lint-scss

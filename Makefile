lint: lint-php

lint-php: lint-phpmd lint-wpcs

lint-phpmd:
	./vendor/bin/phpmd web/app,config text phpmd.xml --suffixes php

lint-wpcs:
	./vendor/bin/phpcs -p -s -v --standard=phpcs.xml --extensions=php ./web/app ./config

.PHONY: lint lint-php lint-phpmd lint-wpcs

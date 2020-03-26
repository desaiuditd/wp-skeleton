#!/bin/bash

set -ex

if [ ! -z $LANDO_MOUNT ]; then

	if ! $(wp core is-installed); then
		# Defaults
		CMS_EMAIL="john.doe@example.com"
		CMS_PASSWORD="password"
		CMS_TITLE="WP Skeleton"
		CMS_URL="https://wp-skeleton.lndo.site"
		CMS_USER="admin"

		# To install and configure WordPress
		wp core install \
			--url="${CMS_URL}" \
			--title="${CMS_TITLE}" \
			--admin_user="${CMS_USER}" \
			--admin_password="${CMS_PASSWORD}" \
			--admin_email="${CMS_EMAIL}" \
			--skip-email || ( echo 'Error while installing WordPress' && exit 1 )
	fi

	# Activate plugins
	wp plugin activate --all || ( echo 'Error while activating plugins' && exit 1 )

fi

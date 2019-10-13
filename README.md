# wp-skeleton
A skeleton repo as a starter for WordPress websites using Bedrock. https://roots.io/bedrock/

# [Bedrock](https://roots.io/bedrock/)

Bedrock is a modern WordPress stack that helps you get started with the best development tools and project structure.

Much of the philosophy behind Bedrock is inspired by the [Twelve-Factor App](http://12factor.net/) methodology including the [WordPress specific version](https://roots.io/twelve-factor-wordpress/).

## Features

* Better folder structure
* Dependency management with [Composer](https://getcomposer.org)
* Easy WordPress configuration with environment specific files
* Environment variables with [Dotenv](https://github.com/vlucas/phpdotenv)
* Autoloader for mu-plugins (use regular plugins as mu-plugins)
* Enhanced security (separated web root and secure passwords with [wp-password-bcrypt](https://github.com/roots/wp-password-bcrypt)

## Bedrock Documentation

Bedrock documentation is available at [https://roots.io/bedrock/docs/](https://roots.io/bedrock/docs/).

# Prerequisite

## NodeJS

Install NodeJS via nodenv - Node Version Manager. https://github.com/nodenv/nodenv

Follow the instructions from here - https://github.com/nodenv/nodenv#installation

### Mac

```bash
brew install nodenv
```

### Linux

For Linux/Ubuntu, to install nodenv, follow the instructions from here - https://github.com/nodenv/nodenv#basic-github-checkout

## Composer

Refer this installation guide for Composer - https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos

First, download the `composer.phar`

And then install it globally on your machine.

```bash
mv composer.phar /usr/local/bin/composer
```

## Yarn

### Mac

```bash
brew install yarn
```

### Linux

Follow the instructions from here - https://yarnpkg.com/en/docs/install#debian-stable

```bash
curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
sudo apt-get update && sudo apt-get install yarn
```

# Local Development

- Install EasyEngine
    - Mac: `brew install easyengine`
	- Linux: `wget -qO ee rt.cx/ee4 && sudo bash ee`
- Create a new site in EasyEngine
    - `ee site create example.test --type=wp --public-dir=web --cache --local-db --with-local-redis`
    - Note down `DB_NAME`, `DB_USER`, `DB_PASSWORD`. We will need these later.
    - Note down `DB_ROOT_PASSWORD`, `WP_USERNAME`, `WP_PASSWORD` just for information.
- Delete the `htdocs` folder under easyengine installation.
    - `rm -rf ~/easyengine/sites/example.test/app/htdocs/*`
- Deploy this git repository under `htdocs` folder.
- Create `.env` file for the project.
    - `cp .env.example .env`
    - Replace values which we noted earlier. `DB_NAME`, `DB_USER`, `DB_PASSWORD`.
    - Set DB_HOST as `db:3306` for easyengine.
	- Set WP_HOME as `http://example.test`
	- Generate secretes with [WordPress salts generator](https://roots.io/salts.html) provided by Roots.
		- `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`
- Access WordPress admin at `http://example.test/wp/wp-admin/`

# Themes

Add theme(s) in `web/app/themes/` as you would for a normal WordPress site.

Also, override the `WP_DEFAULT_THEME` constant to set the theme directory in `config/application.php`

Add below line

```php
Config::define( 'WP_DEFAULT_THEME', Config::get( 'CONTENT_DIR' ) . '/themes' );
```

after the custom content directory is defined.

```php
/**
 * Custom Content Directory
 */
Config::define( 'CONTENT_DIR', '/app' );
Config::define( 'WP_CONTENT_DIR', $webroot_dir . Config::get( 'CONTENT_DIR' ) );
Config::define( 'WP_CONTENT_URL', Config::get( 'WP_HOME' ) . Config::get( 'CONTENT_DIR' ) );
```

# Plugins

Add plugin(s) in `web/app/plugins/` as you would for a normal WordPress site.

Also, whitelist the plugin path in `.gitignore` to get it tracked in Git.

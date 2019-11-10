# wp-skeleton
A skeleton repo as a starter for WordPress websites using Bedrock. https://roots.io/bedrock/

# Bedrock

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

# How to use this skeleton

```bash
mkdir my-wordpress-website
cd my-wordpress-website
wget -qO wps bit.ly/wp-skeleton && bash wps
rm -rf wps
```

# Prerequisite

## Homebrew

Install Homebrew in order to install nodenv.

### MacOS

```bash
/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
```

Follow the instructions from here - https://docs.brew.sh/Installation

### Ubuntu

```bash
sh -c "$(curl -fsSL https://raw.githubusercontent.com/Linuxbrew/install/master/install.sh)"
echo "eval \$($(brew --prefix)/bin/brew shellenv)" >>~/.profile
eval $($(brew --prefix)/bin/brew shellenv)
```

Follow the instructions from here - https://docs.brew.sh/Homebrew-on-Linux

## NodeJS

Install NodeJS via nodenv - Node Version Manager. https://github.com/nodenv/nodenv

Follow the instructions from here - https://github.com/nodenv/nodenv#installation

```bash
brew install nodenv
```

## Composer

Refer this installation guide for Composer - https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos

First, download the `composer.phar`

And then install it globally on your machine.

```bash
mv composer.phar /usr/local/bin/composer
```

# Local Development

- Install composer dependencies. `composer install`
- Install npm package dependencies. `npm install`
- Install [Local by Flywheel](https://localbyflywheel.com/).
- Create a new site in Local by Flywheel. [Refer](https://roots.io/guides/local-bedrock-development-with-local-by-flywheel/)
- Delete everything inside the `public` folder under Local Sites.
	- `rm -rf ~/Local\ Sites/example/app/public/*`
- Deploy this git repository under `app` folder.
	- `~/Local\ Sites/example/app/`
- Create `.env` file for the project.
	- `cp .env.example .env`
	- Change these values, if needed. `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `DB_HOST`. (Optional, in case of default setup with Local by Flywheel)
	- Set WP_HOME as `https://example.test`
	- Generate secretes with [WordPress salts generator](https://roots.io/salts.html) provided by Roots.
		- `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`
- Restart the site from Local by Flywheel.
- Access WordPress admin at `https://example.test/wp/wp-admin/`

# Themes

Add theme(s) in `public/app/themes/` as you would for a normal WordPress site.

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

Add plugin(s) in `public/app/mu-plugins/` as you would for a normal WordPress site.

Also, whitelist the plugin in `.gitignore` file, so that you can commit the files into the repo.

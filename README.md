# wp-skeleton
A skeleton repo as a starter for WordPress websites using [Bedrock](https://roots.io/bedrock/) and [Lando](https://lando.dev/).

Project not maintained actively.

![Build](https://github.com/desaiuditd/wp-skeleton/workflows/Build/badge.svg)

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

# Lando

Lando vastly simplifies local development and DevOps so you can focus on what's important;
delivering value to your clients and customers. And it's free and Open Source.

## Lando Documentation

Lando documentation is available at [https://docs.lando.dev/](https://docs.lando.dev/).

# How to use this skeleton

```bash
mkdir my-wordpress-website
cd my-wordpress-website
wget -qO wps bit.ly/wp-skeleton && bash wps
rm -rf wps
```

# Prerequisite

## MacOS

### Homebrew

```bash
/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
```

Follow the instructions from here - https://docs.brew.sh/Installation

### Docker

```bash
brew cask install docker
```

### Lando

```bash
brew cask install lando
```

This will eventually install Docker as well, as a dependency, if it's already not installed.

Follow the instructions from here - https://docs.lando.dev/basics/installation.html#macos

Enable the SSL in local.

```bash
sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain ~/.lando/certs/lndo.site.pem
```

## Ubuntu

### Docker

Install Docker Desktop (Community Edition) by following instructions from its official website https://docs.docker.com/install/linux/docker-ce/ubuntu/.

```bash
# Update the package manager.
sudo apt-get update

# Install dependencies.
sudo apt-get install \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg-agent \
    software-properties-common \
	-y

# Add Docker GPG Key.
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

# Verify Docker GPG Key.
sudo apt-key fingerprint 0EBFCD88

# Add Docker repo.
sudo add-apt-repository \
   "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
   $(lsb_release -cs) \
   stable"

# Update the package manager.
sudo apt-get update

# Install docker
sudo apt-get install docker-ce docker-ce-cli containerd.io -y
```

### Lando

Follow the instructions from [here](https://docs.lando.dev/basics/installation.html#linux) and install Lando.

- Download the `*.deb` file of the latest version from the [Github releases page](https://github.com/lando/lando/releases).
- Run the required package installation command for your os eg `sudo dpkg -i lando-stable.deb`.
	- Note that you may also be able to just double click on the package and install via your distributions "Software Center" or equivalent.
- Enable the SSL in local.

```bash
sudo cp -r ~/.lando/certs/lndo.site.pem /usr/local/share/ca-certificates/lndo.site.pem
sudo cp -r ~/.lando/certs/lndo.site.crt /usr/local/share/ca-certificates/lndo.site.crt
sudo update-ca-certificates
```

# Local Development

- Create `.env` file for the project.
	- `cp .env.example .env`
	- Change these values, if needed. `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `DB_HOST`, `WP_HOME`. (Optional, in case of default setup with Lando)
	- Generate secretes with [WordPress salts generator](https://roots.io/salts.html) provided by Roots.
		- `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`
- Run `lando start`. Initial start may take some time.

# Local URLs

- WordPress Front-end: https://wp-skeleton.lndo.site
- WordPress Admin: https://wp-skeleton.lndo.site/wp/wp-admin/
- phpMyAdmin: https://db-wp-skeleton.lndo.site

# SSH into Containers

- WordPress App Container: `lando ssh -s appserver`
- Node Container for building assets: `lando ssh -s assets`
- Database Container: `lando ssh -s database`

# Check Linting in local.

- Lint JS/CSS/SCSS files in local: `lando npm run lint`
- Lint PHP files in local (phpcs): `lando ssh -s appserver -c "./vendor/bin/phpcs -p -s -v --standard=phpcs.xml --extensions=php ./web/app ./config"`
- Scan phpmd in local: `lando ssh -s appserver -c "./vendor/bin/phpmd web/app,config text phpmd.xml --suffixes php"`

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

Add plugin(s) in `web/app/mu-plugins/` as you would for a normal WordPress site.

Also, whitelist the plugin in `.gitignore` file, so that you can commit the files into the repo.

# ToDO

- husky/lint-staged and pre-commit hooks are probably not working.

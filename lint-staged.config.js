module.exports = {
	'*.css': [ 'stylelint' ],
	'*.js': [ 'eslint webpack.config.js' ],
	'*.php': [ './vendor/bin/phpcs -p -s -v --standard=phpcs.xml --extensions=php' ],
	'*.scss': [ 'stylelint --syntax scss' ],
};

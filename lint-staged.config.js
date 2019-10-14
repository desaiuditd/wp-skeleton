module.exports = {
	'*.css':  [ 'stylelint' ],
	'*.js':   [ 'eslint webpack.config.babel.js' ],
	'*.scss': [ 'stylelint --syntax scss' ],
};

module.exports = {
	'*.css':  [ 'stylelint' ],
	'*.js':   [ 'eslint webpack.config.js' ],
	'*.scss': [ 'stylelint --syntax scss' ],
};

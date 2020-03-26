module.exports = {
	// TODO: Enable this when we start adding css files.
	// '*.css': [ 'stylelint' ],
	'*.js': [ 'eslint webpack.config.js' ],
	'*.scss': [ 'stylelint --syntax scss' ],
};

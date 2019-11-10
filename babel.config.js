module.exports = {
	presets: [
		'@babel/preset-env',
		[
			'@babel/preset-react',
			{
				pragma: 'wp.element.createElement',
				pragmaFrag: 'wp.element.Fragment',
			},
		],
	],
	plugins: [
		[
			'@babel/plugin-proposal-class-properties',
			{ loose: true },
		],
		'@babel/plugin-proposal-nullish-coalescing-operator',
		'@babel/plugin-proposal-optional-chaining',
		'babel-plugin-styled-components',
	],
};

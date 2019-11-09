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
	],
};

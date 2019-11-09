const fs = require( 'fs' );
const merge = require( 'webpack-merge' );
const path = require( 'path' );
const webpack = require( 'webpack' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

// The base directory, an absolute path, for resolving entry points.
const context = path.resolve( __dirname, 'web/app/mu-plugins' );

/*
 * Bundled files map.
 *
 * Example:
 * 'example-asset': './example/asset/bundle/path/js/script',
 */
const bundleMap = {};

module.exports = function ( env, argv ) {
	const isDev = argv.mode === 'development';

	let config = {
		context,
		entry: bundleMap,
		output: {
			path: path.resolve( __dirname, 'web/dist' ),
			// The filename of the entry chunk as relative path inside the output.path directory.
			filename: '[name].js',
		},
		devtool: isDev ? 'source-map' : 'hidden-source-map',
		resolve: { extensions: [ '.js', '.jsx', '.css', '.scss' ] },
		module: {
			rules: [ {
				test: /\.jsx?$/,
				loader: 'babel-loader',
				query: {
					presets: [ '@babel/preset-env' ],
					compact: isDev ? false : true,
				},
				exclude: path.resolve( __dirname, '/node_modules/' ),
			}, {
				test: /\.(png|jpg|jpeg|gif|svg|woff|woff2|eot|ttf)$/,
				loader: 'url-loader',
				query: {
					name: '[name].[ext]',
					limit: 10000,
				},
			} ],
		},
		optimization: {
			minimize: ! isDev,
			namedModules: ! isDev,
			namedChunks: ! isDev,
		},
		performance: {
			assetFilter: assetFilename => {
				return ! ( /\.map$/.test( assetFilename ) );
			},
		},
		plugins: [
			new CopyWebpackPlugin( [ {
				from: '*/assets/**/+(fonts|images)/**',
				test: /\\.(jpeg|jpg|png|svg|woff|woff2)$/,
				to: '.',
			} ] ),
			new CopyWebpackPlugin( [ {
				from: './ffx-external-scripts/scripts/**',
				test: /\\.(css|js)$/,
				to: '.',
			} ] ),
		],
	};

	if ( isDev ) {
		const StyleLintPlugin = require( 'stylelint-webpack-plugin' );

		config = merge( config, {
			module: {
				rules: [ {
					test: /\.s?css$/,
					use: [
						{ loader: 'style-loader' },
						{
							loader: 'css-loader',
							options: {
								importLoaders: 1,
								sourceMap: true,
							},
						},
						{ loader: 'postcss-loader' },
						{ loader: 'sass-loader' },
					],
				} ],
			},
			plugins: [
				new StyleLintPlugin( { syntax: 'scss' } ),
			],
			devServer: {
				host: 'ink-authoring-local.ffx.io',
				port: 8080,
				inline: false,
				hot: false,
				watchOptions: { poll: false },
				disableHostCheck: true,
				https: {
					key: fs.readFileSync( path.resolve( __dirname + '/../../ssl/server.key' ) ),
					cert: fs.readFileSync( path.resolve( __dirname + '/../../ssl/server.crt' ) ),
				},
			},
			performance: {
				maxAssetSize: 1000000, // 1 mB.
				maxEntrypointSize: 1000000,
			},
		} );
	} else {
		const loaderOptionsPluginConfig = {
			minimize: true,
			debug: false,
		};

		const plugins = [
			new MiniCssExtractPlugin( '[name].css' ),
			new webpack.LoaderOptionsPlugin( loaderOptionsPluginConfig ),
		];

		config = merge( config, {
			module: {
				rules: [ {
					test: /\.s?css$/,
					use: [
						{ loader: MiniCssExtractPlugin.loader },
						{
							loader: 'css-loader',
							options: {
								importLoaders: 1,
								sourceMap: true,
							},
						},
						{ loader: 'postcss-loader' },
						{ loader: 'sass-loader' },
					],
				} ],
			},
			plugins,
		} );
	}

	return config;
};

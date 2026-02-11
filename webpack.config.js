var path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

// change these variables to fit your project
const outputPath = './assets';
const entryPoints = {
	//admin: ['./src/js/admin.js', './src/scss/admin.scss'],
	//frontend: ['./src/js/frontend.js','./src/scss/frontend.scss']
	frontend: ['./src/js/frontend.js','./src/scss/frontend.scss']
};

// Rules
const rules = [
	{
		test: /\.scss$/i,
		use: [
			MiniCssExtractPlugin.loader,
			'css-loader',
			'postcss-loader',
			{
				loader: 'sass-loader',
				options: {
					implementation: require('sass'), // IMPORTANT
					sassOptions: {
						quietDeps: true, // stops @import deprecation from breaking build
					},
				},
			},
		],
	},
	{
		test: /\.(jpg|jpeg|png|gif|woff|woff2|eot|ttf|svg)$/i,
		use: 'url-loader?limit=1024'
	},
	{
		exclude: /node_modules/,
		test: /\.jsx?$/,
		loader: 'babel-loader',
		options: {
			presets: ["@babel/preset-env"]
		}
	}
];

// Development
const devConfig = {
	entry: entryPoints,
	output: {
		path: path.resolve(__dirname, outputPath),
		filename: 'js/[name].js',
		chunkFilename: 'js/modules/dev/[name].js',
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: 'css/[name].css',
		}),
	],
	module: {
		rules: rules
	},
	devtool: 'source-map',

};

// Production
const prodConfig = {
	entry: entryPoints,
	output: {
		path: path.resolve(__dirname, outputPath),
		filename: 'js/[name].min.js',
		chunkFilename: 'js/modules/[name].js',
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: 'css/[name].min.css',
		}),
	],
	module: {
		rules: rules
	},
	optimization: {
		chunkIds: 'named',
	},

};

// Exports
module.exports = (env, argv) => {
	switch (argv.mode) {
		case 'production':
			return prodConfig;
		default:
			return devConfig;
	}
}
module.exports = {
	input: 'src/main.js',
	output: 'dist/main.bundle.js',
	namespace: 'BX.Odva',
	plugins: {
		babel: {
			minified: true,
			presets: ['@babel/preset-env']
		}
	}
};
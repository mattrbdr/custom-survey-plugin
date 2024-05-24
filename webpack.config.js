const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
    ...defaultConfig,
    entry: path.resolve(__dirname, 'src/blocks/index.js'),
    output: {
        path: path.resolve(__dirname, 'build'),
        filename: 'index.js',
    },
    resolve: {
        fallback: {
            "path": require.resolve("path-browserify")
        }
    }
};

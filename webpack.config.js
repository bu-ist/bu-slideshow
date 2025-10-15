// From https://developer.wordpress.org/news/2023/04/how-webpack-and-wordpress-packages-interact/

// Import the original config from the @wordpress/scripts package.
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );


// Import the helper to find and generate the entry points in the src directory
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' );


// Add a new entry point by extending the Webpack config.
module.exports = {
   ...defaultConfig,
   entry: {
       ...getWebpackEntryPoints(),
       admin: './src/admin-page/index.js',
   },
};
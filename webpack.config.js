const defaults = require('@wordpress/scripts/config/webpack.config');
const FilterEntryOutputPlugin = require('filter-entry-output-plugin')

let entry = {
    'angular-app': './assets/admin/src/angular-app/app.js',
    //'react-app': './assets/admin/src/react-app/index.js',
};

let output = {
    filename: '[name].js',
    path: __dirname + '/assets/admin/js',
};


defaults.entry = entry;
defaults.output = output;

const filterOutput = new FilterEntryOutputPlugin({
    test: /angular-app|react-app/,
    remove: /\.asset.php$/,
})

defaults.plugins.push(filterOutput);

module.exports = { ...defaults }
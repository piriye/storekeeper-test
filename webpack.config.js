const path = require('path');
const CopyPlugin = require('copy-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const webpack = require('webpack');

global.__rootdir = __dirname;
global.__appdir = path.join(__dirname, 'app');
global.__appscriptdir = path.join(__dirname, 'app', 'scripts');
global.__distdir = path.join(__dirname, 'public');

module.exports = async (env) => {
    env = env || {};

    const webpackConfig = {
        context: path.join(__dirname, '/js'),
        entry: './index.js',
        output: {
            filename: '[name].[contenthash].js',
            chunkFilename: 'chunk[id].[contenthash].js',
            path: path.resolve(__dirname, 'public'),
            crossOriginLoading: 'anonymous',
        },
        resolve: {
            extensions: ['*', '.hbs', '.js', '.json', '.css', '.less'],
            modules: [
                path.resolve(__dirname, 'js'),
                'node_modules',
            ],
        },
        plugins: []
    };


    webpackConfig.plugins.push(new webpack.SourceMapDevToolPlugin({
        filename: '[file].map',
    }));


    webpackConfig.plugins.push(
        new CopyPlugin({
            patterns: [
                { from: "favicon.ico" },
            ],
        }),
    );

    webpackConfig.plugins.push(new HtmlWebpackPlugin({
        title: 'StoreKeeper Fullstack Assessment',
        template: 'index.ejs',
        inject: 'body',
        minify: !env.development,
        templateParameters: {
            isDevelopment: !!env.development,
        },
    }));

    webpackConfig.devServer = {
        compress: false,
        liveReload: true,
        port: 8001,
        allowedHosts: 'all'
    };

    return webpackConfig;
};
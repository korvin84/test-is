const path = require('path');
const webpack = require("webpack");

const entryPoint = {
    app: "./src/js/app.js"
};

// Путь к публичной части
const jsOutputPath = "../../public/assets";

// относительно `outputPath`
const jsOutputTpl = "scripts/[name].js";

const providePlugin = {
    "$": "jquery",
    "jQuery": "jquery",
    "waitMe": "../../node_modules/waitMe",
};

//относительно `outputPath`
const cssOutputTpl = "styles/[name].css";
const cssOutputTplExtra = "styles/[id].css";

const JSLoader = {
    test: /\.js$/,
    exclude: /node_modules/,
    use: {
        loader: 'babel-loader',
        options: {
            presets: ["@babel/env"]
        }
    }
};

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const SassLoader = {
    test: /\.scss$/,
    exclude: /node_modules/,
    use: [
        {
            loader: MiniCssExtractPlugin.loader
        },
        {
            loader: "css-loader"
        },
        {
            loader: "resolve-url-loader"
        },
        {
            loader: "sass-loader",
            options: {
                sourceMap: true,
                outputStyle: 'compressed',
                implementation: require("sass"),
                fiber: require('fibers')
            }
        }
    ]
};

const CssExtractPlugin = new MiniCssExtractPlugin({
    filename: cssOutputTpl,
    chunkFilename: cssOutputTplExtra
});

module.exports = {
    entry: entryPoint,
    module: {
        rules: [
            JSLoader,
            SassLoader
        ]
    },
    plugins: [
        new webpack.ProvidePlugin(providePlugin),
        CssExtractPlugin
    ],
    output: {
        path: path.resolve(__dirname, jsOutputPath),
        filename: jsOutputTpl
    }
};
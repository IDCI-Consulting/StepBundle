var webpack = require('webpack');
var path    = require('path');
var UglifyJsPlugin = require("uglifyjs-webpack-plugin");

module.exports = {
  entry: {
    'load-step-editors': './Resources/public/js/editor/src/entrypoint.js',
    'display-maps': './Resources/public/js/map/src/entrypoint.js'
  },
  output: {
    path: __dirname + '/Resources/public/js/editor/dist',
    filename: '[name].js',
    chunkFilename: '[name].async.js',
    publicPath: "/bundles/idcistep/js/editor/dist/"
  },
  resolve: {
    alias: {
      'vue': 'vue/dist/vue.esm.js',
      'ExtraFormBundle': path.resolve(
        __dirname,
        'vendor/idci/extra-form-bundle/IDCI/Bundle/ExtraFormBundle/Resources/public/js/editor/src/'
      ),
      'StepBundle': path.resolve(
        __dirname,
        'Resources/public/js/editor/src/'
      )
    }
  },
  externals: {
    jquery: 'jQuery',
    V: 'V'
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        use: 'vue-loader'
      },
      {
        test: /\.css$/,
        use: [
          'style-loader',
          'css-loader'
        ]
      }
    ]
  }
};

if (process.env.NODE_ENV === 'production') {
  module.exports.plugins = (module.exports.plugins || []).concat([
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"production"'
      }
    }),
    new UglifyJsPlugin({
      uglifyOptions: {
        compress: {
          warnings: false
        }
      }
    }),
    new webpack.LoaderOptionsPlugin({
      minimize: true
    })
  ])
}

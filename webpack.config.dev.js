const gettext = require('gettext-parser')
const fs = require('fs')
const glob = require('glob')
const path = require('path')
const wpPot = require('wp-pot')
const AssetsPlugin = require('assets-webpack-plugin')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const ESLintPlugin = require('eslint-webpack-plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const StyleLintPlugin = require('stylelint-webpack-plugin')
const postcss = require('postcss')

const themeDir = 'wp-content/themes/_ws/'
const themeJSON = require('./' + themeDir + 'theme.json')

class TranslationPlugin {
  apply(compiler) {
    compiler.hooks.done.tap('Translation Plugin', stats => {
      console.log('Generating .pot file')
      wpPot({
        destFile: themeDir + 'languages/_ws.pot',
        domain: '_ws',
        src: [themeDir + '**/*.php', themeDir + 'src/**/*.js']
      })
      fs.readFile(themeDir + 'languages/_ws.po', function read(err, data) {
        if (err) {
          return
        }
        console.log('Generating .mo file')
        const output = gettext.mo.compile(gettext.po.parse(data))
        fs.writeFileSync(themeDir + 'languages/_ws.mo', output)
      })
    })
  }
}

function generateCSS(root, options) {
  let output = ''

  const colorVariables = themeJSON.global.colors.map(color => `$${color.slug}:${color.color};`)
  output += colorVariables.join('')
  const colors = themeJSON.global.colors.map(color => `${color.slug}:$${color.slug}`)
  output += `$colors:(${colors.join(',')});`

  const gradientVariables = themeJSON.global.gradients.map(gradient => `$${gradient.slug}:${gradient.gradient};`)
  output += gradientVariables.join('')
  const gradients = themeJSON.global.gradients.map(gradient => `${gradient.slug}:${gradient.gradient}`)
  output += `$gradients:(${gradients.join(',')});`

  root.prepend(output)
}

module.exports = (env, argv) => {
  const plugins = [
    new CleanWebpackPlugin(),
    new AssetsPlugin({
      filename: 'assets.json',
      fullPath: false,
      path: path.resolve(__dirname, themeDir + 'dist')
    }),
    new MiniCssExtractPlugin({
      filename: '[name].[contenthash].min.css'
    })
  ]
  if (env && env.lint) {
    plugins.push(
      new StyleLintPlugin({
        configFile: '.stylelintrc',
        files: themeDir + 'src/**/*.css',
        syntax: 'scss'
      }),
      new ESLintPlugin({
        context: path.resolve(__dirname, themeDir),
        files: [
          'src/**/*.js',
          'blocks/**/*.js'
        ]
      })
    )
  }
  return {
    mode: 'development',
    devtool: 'cheap-module-source-map',
    resolve: {
      alias: {
        Components: path.resolve(__dirname, themeDir + 'src/js/admin/components'),
        Css: path.resolve(__dirname, themeDir + 'src/css'),
        Modules: path.resolve(__dirname, themeDir + 'src/js/front/modules'),
        Theme: path.resolve(__dirname, themeDir + 'theme.json'),
        Utilities: path.resolve(__dirname, themeDir + 'src/js/front/modules/utilities.js')
      }
    },
    entry: {
      'front-js': path.resolve(__dirname, themeDir + 'src/js/front/front.js'),
      'front-css': path.resolve(__dirname, themeDir + 'src/css/front/front.css'),
      'admin-css': [
        path.resolve(__dirname, themeDir + 'src/css/admin/admin.css'),
        ...glob.sync(path.resolve(__dirname, themeDir + 'blocks/**/admin.css'))
      ],
      'blocks-js': path.resolve(__dirname, themeDir + 'src/js/admin/blocks.js'),
      'options-js': path.resolve(__dirname, themeDir + 'src/js/admin/options.js'),
      ...glob.sync(path.resolve(__dirname, themeDir + 'blocks/*/front.js')).reduce((acc, path) => {
        const parts = path.split('/')
        const entry = 'front-js-' + parts[parts.length - 2]
        acc[entry] = path
        return acc
      }, {}),
      ...glob.sync(path.resolve(__dirname, themeDir + 'blocks/*/front.css')).reduce((acc, path) => {
        const parts = path.split('/')
        const entry = 'front-css-' + parts[parts.length - 2]
        acc[entry] = path
        return acc
      }, {})
    },
    plugins: plugins,
    watchOptions: {
      ignored: [themeDir + 'dist/**', 'node_modules/**']
    },
    module: {
      rules: [
        // All JS
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: [
            {
              loader: 'babel-loader',
              options: {
                presets: [
                  '@babel/preset-env',
                  '@babel/preset-react'
                ]
              }
            }
          ]
        },
        // Frontend CSS
        {
          test: /\.css$/,
          include: [path.resolve(themeDir + 'src/css/front'), path.resolve(themeDir + 'blocks')],
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: 'css-loader',
              options: { importLoaders: 1 }
            },
            {
              loader: 'postcss-loader',
              options: {
                postcssOptions: {
                  parser: 'postcss-syntax',
                  plugins: [
                    'postcss-import',
                    postcss.plugin('prepend', options => {
                      return (root, result) => {
                        generateCSS(root, options)
                      }
                    })(),
                    ['precss', { autoprefixer: { grid: true } }],
                    'postcss-hexrgba',
                    'cssnano'
                  ]
                }
              }
            }
          ]
        },
        // Backend CSS
        {
          test: /\.css$/,
          include: [path.resolve(themeDir + 'src/css/admin')],
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: 'css-loader',
              options: { importLoaders: 1 }
            },
            {
              loader: 'postcss-loader',
              options: {
                postcssOptions: {
                  parser: 'postcss-syntax',
                  plugins: [
                    'postcss-import',
                    postcss.plugin('prepend', options => {
                      return (root, result) => {
                        generateCSS(root, options)
                      }
                    })(),
                    'precss',
                    'postcss-hexrgba',
                    'cssnano'
                  ]
                }
              }
            }
          ]
        }
      ]
    },
    output: {
      filename: '[name].[contenthash].min.js',
      path: path.resolve(__dirname, themeDir + 'dist')
    }
  }
}

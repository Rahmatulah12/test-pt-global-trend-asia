const path = require("path");
module.exports = {
  transpileDependencies: [
    'vuetify'
  ],
  publicPath: '/',
  lintOnSave: false,
  runtimeCompiler: true,
  configureWebpack:{
    resolve: {
      symlinks: false,
    },
  },
  outputDir: path.resolve(__dirname, "../public"),
   devServer: {
      host: 'localhost',
       port: 9090
   }
}

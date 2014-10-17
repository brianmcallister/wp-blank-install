({
  appDir: '../content/themes/THEME_NAME/assets/js',
  baseUrl: './',
  mainConfigFile: '../content/themes/THEME_NAME/assets/js/main.js',
  
  optimizeCss: false,
  
  preserveLicenseComments: false,
  removeCombined: true,
  
  modules: [
    {
      name: 'main',
      include: ['templates']
    }
  ]
})
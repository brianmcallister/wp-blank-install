require 'psych'

config = Psych.load(File.read('config/theme.yml'))
theme = "content/themes/#{config[:theme][:name]}"
assets = "#{theme}/assets"

guard :coffeescript,
  input: "#{assets}/coffee",
  output: "#{assets}/js",
  all_on_start: true,
  error_to_js: true

guard :compass,
  compile_on_start: true

guard :ejs,
  input: "#{assets}/templates",
  output: "#{assets}/js/templates.js"

guard :livereload do
  watch %r{#{assets}/(css|js)/.+\.(css|js)}
  watch %r{#{assets}/(img|fonts)/.*\.(.*)}
  watch %r{#{theme}/.*\.php}
end
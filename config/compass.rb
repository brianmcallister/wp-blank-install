require 'psych'
require 'susy'
require 'sass-globbing'

config = ::Psych.load(File.read('config/theme.yml'))
theme = "content/themes/#{config[:theme][:name]}"

http_path  = '/'
asset_path = "#{theme}/assets"
compile_path = (environment == :production) ? "build/#{asset_path}" : asset_path

sass_dir        = "#{asset_path}/sass"

css_dir         = "#{compile_path}/css"
images_dir      = "#{compile_path}/img"
javascripts_dir = "#{compile_path}/js"
fonts_dir       = "#{compile_path}/fonts"

output_style = (environment == :production) ? :compressed : :expanded
relative_assets = true

sass_options = {
  :custom => {
    images_dir: images_dir,
  }
}

if environment == :production
  asset_cache_buster do |http_path, real_path|
    pathname = Pathname.new(http_path)
    file_hash = Digest::MD5.file(real_path).hexdigest[0...7]
    new_path = "#{pathname.dirname}/#{pathname.basename(pathname.extname)}-#{file_hash}#{pathname.extname}"
    
    {:path => new_path, :query => nil}
  end
end

module Sass::Script::Functions
  # Custom Sass function to get a list of images in a directory.
  # https://gist.github.com/brianmcallister/5824102
  #
  # path - Directory in which to get svg images. Defaults to a blank string.
  #
  # Returns a list of images.
  def get_svg_images(path = Sass::Script::String.new(''))
    assert_type path, :String
  
    files = []
  
    dir = if path.value === ''
      "#{@options[:custom][:images_dir]}/*.svg"
    else
      "#{@options[:custom][:images_dir]}/#{path.value}/*.svg"
    end
  
    Dir.glob(dir).each do |f|
      # Make sure that there's also a .png version of the .svg file. We need
      # the .png to measure images sizes.
      if File.exists? f.gsub('.svg', '.png')
        files << Sass::Script::String.new(File.basename(f).gsub!('.svg', ''))
      end
    end

    return Sass::Script::List.new(files, :comma)
  end
  declare :get_svg_images, %w(:path)
end
require 'bundler/setup'
require 'rake'
require 'guard'

revision = `git rev-parse HEAD`[0...7]
Rake::TaskManager.record_task_metadata = true

config = Psych.load(File.read('config/theme.yml'))
theme = config[:theme][:name]

desc 'Build the site.'
task :build => %w(build:lint_coffee build:lint_sass build:clean
  build:create_dirs build:copy build:sass build:coffee build:templates
  build:optimize build:images)

namespace :build do
  desc 'Remove the old build directory.'
  task :clean do |task|
    msg 'Build step', task.full_comment
    FileUtils.rm_r 'build', force: true, verbose: true
  end
  
  desc 'Create the needed build directories.'
  task :create_dirs do |task|
    msg 'Build step', task.full_comment
    FileUtils.mkdir 'build', verbose: true
    FileUtils.mkdir_p "build/content/themes/#{theme}/assets", verbose: true
    FileUtils.mkdir "build/content/themes/#{theme}/assets/css", verbose: true
    FileUtils.mkdir "build/content/themes/#{theme}/assets/js", verbose: true
    FileUtils.mkdir 'build/content/uploads', verbose: true

    # Create the REVISION file.
    File.open('build/REVISION', 'w') do |f|
      f.write revision
    end
    
    # Composer.
    system 'composer install'
  end
  
  desc 'Copy the files that need to be copied into the build.'
  task :copy => %w(clean create_dirs) do |task|
    msg 'Build step', task.full_comment
    # Main directories.
    FileUtils.cp_r %w(wp vendor index.php wp-config.php), 'build/',
      verbose: true
      
    # Assets.
    FileUtils.cp_r %W(content/themes/#{theme}/assets/img),
      "build/content/themes/#{theme}/assets/", verbose: true
    FileUtils.cp_r Dir.glob("content/themes/#{theme}/*.{php,png,css}"),
      "build/content/themes/#{theme}/", verbose: true
        
    # Plugins.      
    FileUtils.cp_r %w(content/plugins), 'build/content/', verbose: true
  end
  
  desc 'Lint the Sass.'
  task :lint_sass do |task|
    system "bundle exec scss-lint --config=config/scss-lint.yml --exclude=content/themes/#{theme}/assets/sass/third-party/** content/themes/#{theme}/assets/sass"
  end
  
  desc 'Compile Sass.'
  task :sass => %w(clean create_dirs copy) do |task|
    msg 'Build step', task.full_comment
    
    system "bundle exec compass compile --force -e production"

    # Rename files.
    # First, rename all the CSS files with hashes.
    assets = Dir.glob %W(build/content/themes/#{theme}/assets/css/*)

    assets.each do |asset|
      FileUtils.mv asset, get_path_with_hash(asset), verbose: true
    end
    
    # Next, rename all the image assets with hashes.
    Dir.glob("build/content/themes/#{theme}/assets/img/**/*.{png,gif,jpg,svg}").each do |f|
      FileUtils.mv f, get_path_with_hash(f), verbose: true
    end
  end

  desc 'Lint the CoffeeScript.'
  task :lint_coffee do |task|
    msg 'Build step', task.full_comment
    
    require 'coffeelint'
    
    report = Coffeelint.run_test_suite("content/themes/#{theme}/assets/coffee",
      :config_file => 'config/coffeelint.json')
      
    if !report
      raise 'Coffeelint failed!'
    end
  end
  
  desc 'Compile CoffeeScript.'
  task :coffee do |task|
    msg 'Build step', task.full_comment
    Guard.setup
    Guard.plugin('coffeescript').run_all
  end
  
  desc 'Compile templates.'
  task :templates do |task|
    msg 'Build step', task.full_comment
    Guard.setup
    Guard.plugin('ejs').run_all
  end
  
  desc 'Optimize JavaScript.'
  task :optimize => %w(create_dirs copy coffee) do |task|
    msg 'Build step', task.full_comment
    
    dir = "build/content/themes/#{theme}/assets/js/#{revision}"
    FileUtils.mkdir_p dir, verbose: true
        
    system "node lib/r.js -o config/app.build.js dir=#{dir} optimize=uglify2"
    
    # # Clean up.
    FileUtils.rm_r %W(#{dir}/build.txt)
  end
  
  desc 'Optimize images.'
  task :images => %w(create_dirs copy) do |task|
    msg 'Build step', task.full_comment
    
    require 'image_optim'

    puts
    puts "Attempting to optimize images. If you get an error here, its likely
because you haven't installed all of the utilities. See
https://github.com/toy/image_optim for instructions."
    puts
  
    files = Dir.glob("build/content/themes/#{theme}/assets/img/*.{png,gif,jpg,svg}")
    # Don't bother with svgo, it mangles too many images.
    image_optim = ImageOptim.new(pngout: false, svgo: false)
    image_optim.optimize_images!(files)
  end
end

namespace :deploy do
  require 'psych'
  
  config = Psych.load(File.read('config/deploy.yml'))
  
  desc 'Deploy the site to staging.'
  task :staging do |task|
    msg 'Deploy step', task.full_comment
    
    stage = config[:staging]
    stage[:exclude] = %w(.htaccess local-config.php content/uploads)
    
    if File.directory?('build')
      puts
      print 'A build already exists. Would you like to deploy the existing build? [y/n]: '
      resp = $stdin.gets.chomp
      
      if resp != 'y' and resp != 'n' and resp != ''
        raise 'Deploy cancelled.'
      end
        
      if resp == 'n'
        Rake::Task['build'].invoke
      end
    end
    
    system get_rsync_opts 'build', stage
  end
  
  desc 'Deploy the site to production.'
  task :production => %w(build) do |task|
    msg 'Deploy step', task.full_comment
    opts = get_rsync_opts(config['build_dir'], config['production'])
    puts opts.inspect
  end
end

# Public: Get a filename path with the md5 hash included.
#
# path - Path to convert.
#
# Example
#
#   get_path_with_hash('assets/css/style.css')
#   #=> 'assets/css/style-abcd567.css'
#
# Returns the converted path.
def get_path_with_hash(path)
  pathname = Pathname.new(path)
  file_hash = Digest::MD5.file(path).hexdigest[0...7]
  "#{pathname.dirname}/#{pathname.basename(pathname.extname)}-#{file_hash}#{pathname.extname}"
end

# Public: Send a message to STDOUT.
#
# prefix - Prefix to the message.
# msg - Message to print.
#
# Returns nothing.
def msg(prefix, msg)
  puts
  together = "\e[33m#{prefix}:\e[0m \e[32m#{msg}\e[0m"
  puts together
  puts '=' * "#{prefix}: #{msg}".length
end

# Public: Get the deploy options for rsync.
#
# build_dir - Directory where the built site is.
# deploy_config - Object of deploy config options. Should support:
#                 user: SSH username.
#                 host: Host to deploy to.
#                 directory: Directory on the server to deploy to.
#
# Returns a string rsync command.
def get_rsync_opts(build_dir, deploy_config)
  opts = %W{rsync -az --progress --delete}
  
  if deploy_config.include? :port
    opts << "--rsh='ssh -p#{deploy_config[:port]}'"
  end
  
  if deploy_config.include? :exclude
    deploy_config[:exclude].each do |file|
      opts << "--exclude '#{file}'"
    end
  end
  
  opts << "#{build_dir}/ #{deploy_config[:user]}@#{deploy_config[:host]}:#{deploy_config[:directory]}/"
  opts.join ' '
end
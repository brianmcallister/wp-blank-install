# TheBetes.Org

### Getting started.

Make sure you have Ruby, PHP, and Node.js installed.

Make sure you have [Bundler](http://bundler.io/) installed. Next, install all
the gems with `bundle`.

Make sure you have [Composer](https://getcomposer.org/) installed. Next,
install all the packages with `composer install`.

After that, copy `local-config-sample.php` to `local-config.php` and fill out
your database connection details.

Point Apache at the application root and you should be ready to go.

### Compiling Assets.

Start Guard with `bundle exec guard`.

### Building.

Run `bundle exec rake build` to build the entire site. The entire build process
will run on your local machine, and will create a build at `build/`

Take a look at the `Rakefile` to see the various available tasks.

### Deploying.

Copy `deploy-sample.yml` to `deploy.yml`. Fill in all the relevant values.

Server information resides with *Marina Tsaplina <<thebetesorg@gmail.com>>*

Staging: `bundle exec rake deploy:staging`. Production: `bundle exec rake
deploy:production`.

Current environments: Staging:
[http://staging.thebetes.org](http://staging.thebetes.org) Production:
[http://thebetes.org](http://thebetes.org)

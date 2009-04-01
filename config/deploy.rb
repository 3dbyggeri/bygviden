# enable the multistage plugin, see: http://weblog.jamisbuck.org/2007/7/23/capistrano-multistage
set :stages, %w(local qa production) # defaults to just staging and production
require 'capistrano/ext/multistage'

set :repository, "git@github.com:3dbyggeri/bygviden.git"

set :user, "bygviden" # log in as
set :runner, "bygviden" # run processes as

namespace :deploy do
  desc "Deploy the newest version"
  task :default do
    update
  end

  desc "Clone the git repository into the webroot and run migrations."
  task :first do
    run "git clone -q #{repository} #{deploy_to}"
    config_database
    migrate
  end

  desc "[internal] Pull a new version of the master branch"
  task :update do
    run "cd #{deploy_to}; git pull origin master"
  end

  desc "Run pending migrations"
  task :migrate, :roles => :db, :only => { :primary => true } do
    run "cd #{deploy_to}; rake BYGVIDEN_ENV=#{env} db:migrate"
  end

  desc "Deploy the newest version and run any pending migrations"
  task :migrations do
    update
    migrate
  end

  desc "Configure the database on the remote server"
  task :config_database, :roles => :app do
    set :remote_database_password, proc { Capistrano::CLI.password_prompt("#{env.capitalize} database password: ") }

    buffer = YAML::load_file('config/database.yml')
    # first get rid of uneeded configurations
    buffer.delete('development')
    case env
    when 'qa'
      buffer.delete('production')
    when 'production'
      buffer.delete('qa')
    end

    # then populate deployed environment element
    buffer[env]['adapter'] = "mysql"
    buffer[env]['database'] = remote_database
    buffer[env]['username'] = remote_database_username
    buffer[env]['password'] = remote_database_password
    buffer[env]['host'] = remote_database_server

    put YAML::dump(buffer), "#{deploy_to}/config/database.yml"
  end

  desc "Load data into the database"
  task :data, :roles => :db, :only => { :primary => true } do
    begin
      file
    rescue
      puts "Aborting... Remember to supply the -s parameter file=[filename]"
      exit 0
    end
    run "cd #{deploy_to}; rake BYGVIDEN_ENV=#{env} db:data:load file=#{file}"
  end
end
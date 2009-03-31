namespace :db do
  task :load_config => :bygviden_env do
    require 'active_record'
    ActiveRecord::Base.configurations = YAML::load(File.open('/Users/watson/code/bygviden/config/database.yml'))
    ActiveRecord::Base.logger = Logger.new(STDERR)
  end

  task :environment => :load_config do
    ActiveRecord::Base.establish_connection(ActiveRecord::Base.configurations[BYGVIDEN_ENV]) 
  end

  namespace :create do
    desc 'Create all the local databases defined in config/database.yml'
    task :all => :load_config do
      ActiveRecord::Base.configurations.each_value do |config|
        # Skip entries that don't have a database key, such as the first entry here:
        #
        #  defaults: &defaults
        #    adapter: mysql
        #    username: root
        #    password:
        #    host: localhost
        #
        #  development:
        #    database: blog_development
        #    <<: *defaults
        next unless config['database']
        # Only connect to local databases
        local_database?(config) { create_database(config) }
      end
    end
  end

  desc 'Create the database defined in config/database.yml for the current BYGVIDEN_ENV'
  task :create => :load_config do
    create_database(ActiveRecord::Base.configurations[BYGVIDEN_ENV])
  end

  def create_database(config)
    begin
      if config['adapter'] =~ /sqlite/
        if File.exist?(config['database'])
          $stderr.puts "#{config['database']} already exists"
        else
          begin
            # Create the SQLite database
            ActiveRecord::Base.establish_connection(config)
            ActiveRecord::Base.connection
          rescue
            $stderr.puts $!, *($!.backtrace)
            $stderr.puts "Couldn't create database for #{config.inspect}"
          end
        end
        return # Skip the else clause of begin/rescue    
      else
        ActiveRecord::Base.establish_connection(config)
        ActiveRecord::Base.connection
      end
    rescue
      case config['adapter']
      when 'mysql'
        @charset   = ENV['CHARSET']   || 'utf8'
        @collation = ENV['COLLATION'] || 'utf8_general_ci'
        begin
          ActiveRecord::Base.establish_connection(config.merge('database' => nil))
          ActiveRecord::Base.connection.create_database(config['database'], :charset => (config['charset'] || @charset), :collation => (config['collation'] || @collation))
          ActiveRecord::Base.establish_connection(config)
        rescue
          $stderr.puts "Couldn't create database for #{config.inspect}, charset: #{config['charset'] || @charset}, collation: #{config['collation'] || @collation} (if you set the charset manually, make sure you have a matching collation)"
        end
      when 'postgresql'
        @encoding = config[:encoding] || ENV['CHARSET'] || 'utf8'
        begin
          ActiveRecord::Base.establish_connection(config.merge('database' => 'postgres', 'schema_search_path' => 'public'))
          ActiveRecord::Base.connection.create_database(config['database'], config.merge('encoding' => @encoding))
          ActiveRecord::Base.establish_connection(config)
        rescue
          $stderr.puts $!, *($!.backtrace)
          $stderr.puts "Couldn't create database for #{config.inspect}"
        end
      end
    else
      $stderr.puts "#{config['database']} already exists"
    end
  end

  namespace :drop do
    desc 'Drops all the local databases defined in config/database.yml'
    task :all => :load_config do
      ActiveRecord::Base.configurations.each_value do |config|
        # Skip entries that don't have a database key
        next unless config['database']
        # Only connect to local databases
        local_database?(config) { drop_database(config) }
      end
    end
  end

  desc 'Drops the database for the current BYGVIDEN_ENV'
  task :drop => :load_config do
    config = ActiveRecord::Base.configurations[BYGVIDEN_ENV || 'development']
    begin
      drop_database(config)
    rescue Exception => e
      puts "Couldn't drop #{config['database']} : #{e.inspect}"
    end
  end

  def local_database?(config, &block)
    if %w( 127.0.0.1 localhost ).include?(config['host']) || config['host'].blank?
      yield
    else
      puts "This task only modifies local databases. #{config['database']} is on a remote host."
    end
  end


  desc "Migrate the database through scripts in db/migrate and update db/schema.rb by invoking db:schema:dump. Target specific version with VERSION=x. Turn off output with VERBOSE=false."
  task :migrate => :environment do
    ActiveRecord::Migration.verbose = ENV["VERBOSE"] ? ENV["VERBOSE"] == "true" : true
    ActiveRecord::Migrator.migrate("db/migrate/", ENV["VERSION"] ? ENV["VERSION"].to_i : nil)
    Rake::Task["db:schema:dump"].invoke if ActiveRecord::Base.schema_format == :ruby
  end

  namespace :migrate do
    desc  'Rollbacks the database one migration and re migrate up. If you want to rollback more than one step, define STEP=x. Target specific version with VERSION=x.'
    task :redo => :environment do
      if ENV["VERSION"]
        Rake::Task["db:migrate:down"].invoke
        Rake::Task["db:migrate:up"].invoke
      else
        Rake::Task["db:rollback"].invoke
        Rake::Task["db:migrate"].invoke
      end
    end

    desc 'Resets your database using your migrations for the current environment'
    task :reset => ["db:drop", "db:create", "db:migrate"]

    desc 'Runs the "up" for a given migration VERSION.'
    task :up => :environment do
      version = ENV["VERSION"] ? ENV["VERSION"].to_i : nil
      raise "VERSION is required" unless version
      ActiveRecord::Migrator.run(:up, "db/migrate/", version)
      Rake::Task["db:schema:dump"].invoke if ActiveRecord::Base.schema_format == :ruby
    end

    desc 'Runs the "down" for a given migration VERSION.'
    task :down => :environment do
      version = ENV["VERSION"] ? ENV["VERSION"].to_i : nil
      raise "VERSION is required" unless version
      ActiveRecord::Migrator.run(:down, "db/migrate/", version)
      Rake::Task["db:schema:dump"].invoke if ActiveRecord::Base.schema_format == :ruby
    end
  end

  desc 'Rolls the schema back to the previous version. Specify the number of steps with STEP=n'
  task :rollback => :environment do
    step = ENV['STEP'] ? ENV['STEP'].to_i : 1
    ActiveRecord::Migrator.rollback('db/migrate/', step)
    Rake::Task["db:schema:dump"].invoke if ActiveRecord::Base.schema_format == :ruby
  end

  desc 'Drops and recreates the database from db/schema.rb for the current environment.'
  task :reset => ['db:drop', 'db:create', 'db:schema:load']

  desc "Retrieves the charset for the current environment's database"
  task :charset => :environment do
    config = ActiveRecord::Base.configurations[BYGVIDEN_ENV || 'development']
    case config['adapter']
    when 'mysql'
      ActiveRecord::Base.establish_connection(config)
      puts ActiveRecord::Base.connection.charset
    when 'postgresql'
      ActiveRecord::Base.establish_connection(config)
      puts ActiveRecord::Base.connection.encoding
    else
      puts 'sorry, your database adapter is not supported yet, feel free to submit a patch'
    end
  end

  desc "Retrieves the collation for the current environment's database"
  task :collation => :environment do
    config = ActiveRecord::Base.configurations[BYGVIDEN_ENV || 'development']
    case config['adapter']
    when 'mysql'
      ActiveRecord::Base.establish_connection(config)
      puts ActiveRecord::Base.connection.collation
    else
      puts 'sorry, your database adapter is not supported yet, feel free to submit a patch'
    end
  end

  desc "Retrieves the current schema version number"
  task :version => :environment do
    puts "Current version: #{ActiveRecord::Migrator.current_version}"
  end

  desc "Raises an error if there are pending migrations"
  task :abort_if_pending_migrations => :environment do
    if defined? ActiveRecord
      pending_migrations = ActiveRecord::Migrator.new(:up, 'db/migrate').pending_migrations

      if pending_migrations.any?
        puts "You have #{pending_migrations.size} pending migrations:"
        pending_migrations.each do |pending_migration|
          puts '  %4d %s' % [pending_migration.version, pending_migration.name]
        end
        abort %{Run "rake db:migrate" to update your database then try again.}
      end
    end
  end

  namespace :schema do
    desc "Create a db/schema.rb file that can be portably used against any DB supported by AR"
    task :dump => :environment do
      require 'active_record/schema_dumper'
      File.open(ENV['SCHEMA'] || "#{BYGVIDEN_ROOT}/db/schema.rb", "w") do |file|
        ActiveRecord::SchemaDumper.dump(ActiveRecord::Base.connection, file)
      end
      Rake::Task["db:schema:dump"].reenable
    end

    desc "Load a schema.rb file into the database"
    task :load => :environment do
      file = ENV['SCHEMA'] || "#{BYGVIDEN_ROOT}/db/schema.rb"
      load(file)
    end
  end

  namespace :structure do
    desc "Dump the database structure to a SQL file"
    task :dump => :environment do
      abcs = ActiveRecord::Base.configurations
      case abcs[BYGVIDEN_ENV]["adapter"]
      when "mysql", "oci", "oracle"
        ActiveRecord::Base.establish_connection(abcs[BYGVIDEN_ENV])
        File.open("#{BYGVIDEN_ROOT}/db/#{BYGVIDEN_ENV}_structure.sql", "w+") { |f| f << ActiveRecord::Base.connection.structure_dump }
      when "postgresql"
        ENV['PGHOST']     = abcs[BYGVIDEN_ENV]["host"] if abcs[BYGVIDEN_ENV]["host"]
        ENV['PGPORT']     = abcs[BYGVIDEN_ENV]["port"].to_s if abcs[BYGVIDEN_ENV]["port"]
        ENV['PGPASSWORD'] = abcs[BYGVIDEN_ENV]["password"].to_s if abcs[BYGVIDEN_ENV]["password"]
        search_path = abcs[BYGVIDEN_ENV]["schema_search_path"]
        search_path = "--schema=#{search_path}" if search_path
        `pg_dump -i -U "#{abcs[BYGVIDEN_ENV]["username"]}" -s -x -O -f db/#{BYGVIDEN_ENV}_structure.sql #{search_path} #{abcs[BYGVIDEN_ENV]["database"]}`
        raise "Error dumping database" if $?.exitstatus == 1
      when "sqlite", "sqlite3"
        dbfile = abcs[BYGVIDEN_ENV]["database"] || abcs[BYGVIDEN_ENV]["dbfile"]
        `#{abcs[BYGVIDEN_ENV]["adapter"]} #{dbfile} .schema > db/#{BYGVIDEN_ENV}_structure.sql`
      when "sqlserver"
        `scptxfr /s #{abcs[BYGVIDEN_ENV]["host"]} /d #{abcs[BYGVIDEN_ENV]["database"]} /I /f db\\#{BYGVIDEN_ENV}_structure.sql /q /A /r`
        `scptxfr /s #{abcs[BYGVIDEN_ENV]["host"]} /d #{abcs[BYGVIDEN_ENV]["database"]} /I /F db\ /q /A /r`
      when "firebird"
        set_firebird_env(abcs[BYGVIDEN_ENV])
        db_string = firebird_db_string(abcs[BYGVIDEN_ENV])
        sh "isql -a #{db_string} > #{BYGVIDEN_ROOT}/db/#{BYGVIDEN_ENV}_structure.sql"
      else
        raise "Task not supported by '#{abcs["test"]["adapter"]}'"
      end

      if ActiveRecord::Base.connection.supports_migrations?
        File.open("#{BYGVIDEN_ROOT}/db/#{BYGVIDEN_ENV}_structure.sql", "a") { |f| f << ActiveRecord::Base.connection.dump_schema_information }
      end
    end
  end
end

def drop_database(config)
  case config['adapter']
  when 'mysql'
    ActiveRecord::Base.establish_connection(config)
    ActiveRecord::Base.connection.drop_database config['database']
  when /^sqlite/
    FileUtils.rm(File.join(BYGVIDEN_ROOT, config['database']))
  when 'postgresql'
    ActiveRecord::Base.establish_connection(config.merge('database' => 'postgres', 'schema_search_path' => 'public'))
    ActiveRecord::Base.connection.drop_database config['database']
  end
end

def session_table_name
  ActiveRecord::Base.pluralize_table_names ? :sessions : :session
end

def set_firebird_env(config)
  ENV["ISC_USER"]     = config["username"].to_s if config["username"]
  ENV["ISC_PASSWORD"] = config["password"].to_s if config["password"]
end

def firebird_db_string(config)
  FireRuby::Database.db_string_for(config.symbolize_keys)
end

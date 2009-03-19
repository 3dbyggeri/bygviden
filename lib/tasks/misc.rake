task :rails_env do
  unless defined? RAILS_ENV
    RAILS_ENV = ENV['RAILS_ENV'] ||= 'development'
  end
end
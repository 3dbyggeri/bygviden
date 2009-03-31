task :bygviden_env do
  unless defined? BYGVIDEN_ENV
    BYGVIDEN_ENV = ENV['BYGVIDEN_ENV'] ||= 'development'
  end
end
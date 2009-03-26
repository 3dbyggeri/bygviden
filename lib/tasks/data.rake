namespace :db do
  namespace :data do 
    desc 'Load SQL file (tip: put sql files in db/data) - Currently only works with MySQL'
    task :load => :environment do
      raise "Usage: rake db:data:load file=[filename]" unless ENV.member? 'file'

      conf = ActiveRecord::Base.configurations[RAILS_ENV]
      user = conf['username']
      pass = conf['password']
      host = conf['host']
      database = conf['database']

      auth = "-u #{user}" + (pass.nil? ? '' : " -p#{pass}")
      puts `mysql #{auth} -h #{host} #{database} < #{ENV['file']}`
    end
  end
end
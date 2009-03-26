namespace :db do
  namespace :data do 
    desc 'Load SQL file (tip: put sql files in db/data)'
    task :load => :environment do
      raise "Usage: rake db:data:load file=[filename]" unless ENV.member? 'file'

      f = File.new ENV['file']
      ActiveRecord::Base.connection.execute f.read
    end
  end
end
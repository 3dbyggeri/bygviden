set :env, 'qa'

set :remote_database_server, 'db02'
set :remote_database, 'bygviden_qa'
set :remote_database_username, 'bygviden_qa'

set :deploy_to, "/usr/home/bygviden/bygviden_qa"
set :app_url, "qa.bygviden.dk"

role :app, "qa.bygviden.dk"
role :web, "qa.bygviden.dk"
role :db,  "qa.bygviden.dk", :primary => true

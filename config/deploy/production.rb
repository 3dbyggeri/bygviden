set :env, 'production'

set :remote_database_server, 'db02'
set :remote_database, 'bygviden_production'
set :remote_database_username, 'bygviden_prod'

set :deploy_to, "/usr/home/bygviden/bygviden_prod"
set :app_url, "www.bygviden.dk"

role :app, "www.bygviden.dk"
role :web, "www.bygviden.dk"
role :db,  "www.bygviden.dk", :primary => true

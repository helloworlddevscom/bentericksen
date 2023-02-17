# Set the deployment directory on the target hosts.
set :deploy_to, "/var/www/sites/virtual/bentericksen-dev"
set :current_path, "#{deploy_to}/current"

# Path to artisan 
set :artisan, "cd #{current_path} ; php #{app_root}/artisan"

# The path to cachetool
set :cachetool, "#{deploy_to}/#{shared_dir}/cachetool.phar"

# The hostnames to deploy to.
role :web, "stg01-bentericksen.ec2.metaltoad.net"

set :gateway, "mgt02-mtm.ec2.metaltoad.net"

# Specify one of the web servers to use for database backups or updates.
role :db, "stg01-bentericksen.ec2.metaltoad.net", :primary => true

# The username on the target system, if different from your local username
ssh_options[:user] = "deploy"
ssh_options[:forward_agent] = true

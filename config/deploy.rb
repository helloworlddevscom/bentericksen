 # The project name. (one word: no spaces, dashes, or underscores)
set :application, "bentericksen"

# Set the repository type and location to deploy from.
set :scm, :none
set :deploy_via, :copy
set :repository, "."

# Set the database passwords that we'll use for maintenance. Probably only used
# during setup.
set(:db_root_pass) { '-p' + Capistrano::CLI.password_prompt("Production Root MySQL password: ") }
set(:db_pass) { random_password }

# The subdirectory within the repo containing the DocumentRoot.
set :doc_root, "httpdocs"
set :app_root, "laravel"

# Set the shared and writable directories
set :shared_children, [
  'bootstrap/cache',
  'storage/app',
  'storage/bentericksen',
  'storage/debugbar',
  'storage/framework',
  'storage/logs'
]
set :writable_dirs, :shared_children

ssh_options[:user] = 'deploy'
ssh_options[:forward_agent] = true

# Multistage support - see config/deploy/[STAGE].rb for specific configs
set :default_stage, "dev"
set :stages, %w(dev staging feature prod)

before 'multistage:ensure' do
  # Set the branch to the current stage, unless it's been overridden
  if !exists?(:branch)
    set :branch, stage
  end
end

# Generally don't need sudo for this deploy setup
set :use_sudo, false

# This allows the sudo command to work if you do need it
default_run_options[:pty] = true

# Override these in your stage files if your web server group is something other than apache
set :httpd_group, 'apache'

# Run database migrations
after "deploy:cacheclear",
  "deploy:web:updb",
  "deploy:restart"

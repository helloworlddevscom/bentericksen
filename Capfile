# TODO: Cap db:push should support rollback or backup in some way
# TODO: Better handling of database creation - perhaps make the deploy user a database admin?

load 'deploy' if respond_to?(:namespace) # cap2 differentiator
Dir['vendor/plugins/*/recipes/*.rb'].each { |plugin| load(plugin) }
load 'config/deploy.rb'

require 'capistrano/ext/multistage'
require 'dotenv'

namespace :deploy do
  desc "Prepares one or more servers for deployment."
  task :setup, :roles => :web, :except => { :no_release => true } do
    dirs = [deploy_to, releases_path, shared_path]
    shared_children.each do |child_dir|
      dirs += [shared_path + '/#{child_dir}']
    end
    dirs += %w(system).map { |d| File.join(shared_path, d) }
    run "mkdir -m 0775 -p #{dirs.join(' ')}"
    # add setgid bit, so that files/ contents are always in the httpd group
    writable_dirs.each do |child_dir|
      run "chmod 2775 #{shared_path}/#{child_dir}"
      run "chgrp #{httpd_group} #{shared_path}/#{child_dir}"
    end
  end

  desc "Create local .env in shared"
  task :create_env, :roles => :web do
    configuration = <<-EOF
APP_ENV=#{stage}
APP_DEBUG=false
APP_KEY=#{random_password(32)}

DB_HOST=localhost
DB_DATABASE=#{short_name()}
DB_USERNAME=#{tiny_name()}
DB_PASSWORD=#{db_pass}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=smtp
EOF
      
    put configuration, "#{deploy_to}/#{shared_dir}/.env"
  end

  desc "link file dirs and the .env to the shared copy"
  task :symlink_files, :roles => :web do
    # symlink webroot
    run "ln -nfs #{deploy_to}/current/#{doc_root} #{release_path}/webroot"
    # link settings file
    run "ln -nfs #{deploy_to}/#{shared_dir}/.env #{release_path}/#{app_root}/.env"
    # remove any link or directory that was exported from SCM, and link to remote app filesystem
    shared_children.each do |child_dir|
      run "rm -rf #{release_path}/#{app_root}/#{child_dir}"
      run "ln -nfs #{deploy_to}/#{shared_dir}/#{child_dir} #{release_path}/#{app_root}/#{child_dir}"
    end
  end

  # desc '[internal] Touches up the released code.'
  task :finalize_update, :except => { :no_release => true } do
    run "chmod -R g+w #{release_path}"
    #run "chmod 644 #{release_path}/#{app_root}/.env"
  end

  desc "Flush the cache system."
  task :cacheclear, :roles => :db, :only => { :primary => true } do
    # Clear application cache
    run "#{artisan} cache:clear"
    # Clear and rebuild the config cache
    run "#{artisan} config:clear"
    run "#{artisan} config:cache"
    # Clear the view cache
    run "#{artisan} view:clear"
  end

  # Restart PHP, the Zend OPcache doesn't notice symlink swaps
  task :restart, :roles => :web do
    sudo "/sbin/service php73-php-fpm restart"
  end

  namespace :web do
    desc "Set application to online."
    task :enable do
      run "#{artisan} up"
    end

    desc "Set application to off-line."
    task :disable do
      run "#{artisan} down"
    end

    desc "Run database migration scripts"
    task :updb do
      run "#{artisan} migrate --force --no-interaction"
    end
  end

  # Each of the following tasks are Rails specific. They're removed.
  task :migrate do
  end

  task :migrations do
  end

  task :cold do
  end

  task :start do
  end

  task :stop do
  end

  after "deploy:setup",
    "deploy:create_env",
    "db:create"

  after "deploy:update_code",
    "deploy:symlink_files"

  after "deploy",
    "deploy:cacheclear",
    "deploy:cleanup"

end

namespace :db do
  desc "Download a backup of the database(s) from the given stage."
  task :down, :roles => :db, :only => { :primary => true } do
    run_locally "mkdir -p database"
    filename = "default_#{stage}.sql.gz"

    temp = "/tmp/#{release_name}_#{application}_#{filename}"
    run "touch #{temp} && chmod 600 #{temp}"

    # Remote environment info
    env_data = capture("cat #{deploy_to}/#{shared_dir}/.env")
    info = get_env_info(env_data)

    run "mysqldump -u #{info["DB_USERNAME"]} -h #{info["DB_HOST"]} -p#{info["DB_PASSWORD"]} #{info["DB_DATABASE"]} | gzip > #{temp}"
    download("#{temp}", "database/#{filename}", :via=> :scp)
    run "rm #{temp}"
  end

  desc "Download and apply a backup of the database(s) from the given stage."
  task :pull, :roles => :db, :only => { :primary => true } do
    filename = "default_#{stage}.sql.gz"

    # Local environment info
    env_data = run_locally "cat #{app_root}/.env"
    info = get_env_info(env_data)

    run_locally "gunzip -c database/#{filename} | mysql -u#{info["DB_USERNAME"]} -h #{info["DB_DEPLOY_HOST"]} -p#{info["DB_PASSWORD"]} #{info["DB_DATABASE"]}"
  end

  desc "Dump a local copy of the database to database/local_dump.sql file."
  task :dump, :roles => :db, :only => { :primary => true } do
    run_locally "mkdir -p database"
    filename = "local_dump.sql.gz"

    # Local environment info
    env_data = run_locally "cat #{app_root}/.env"
    info = get_env_info(env_data)

    run_locally "mysqldump -u #{info["DB_USERNAME"]} -h #{info["DB_HOST"]} -p#{info["DB_PASSWORD"]} #{info["DB_DATABASE"]} | gzip > database/#{filename}"
  end

  desc "Upload database(s) to the given stage."
  task :push, :roles => :db, :only => { :primary => true } do
    filename = "default_#{stage}.sql.gz"
    temp = "/tmp/#{release_name}_#{application}_#{filename}"
    run "touch #{temp} && chmod 600 #{temp}"
    upload("database/#{filename}", "#{temp}", :via=> :scp)

    # Remote environment info
    env_data = capture("cat #{deploy_to}/#{shared_dir}/.env")
    info = get_env_info(env_data)

    run "gunzip -c #{temp} | mysql -u #{info["DB_USERNAME"]} -h #{info["DB_HOST"]} -p#{info["DB_PASSWORD"]} #{info["DB_DATABASE"]}"
    run "rm #{temp}"
  end

  desc "Create database"
  task :create, :roles => :db, :only => { :primary => true } do
    # Create and gront privs to the new db user
    create_sql = "CREATE DATABASE IF NOT EXISTS \\\`#{short_name()}\\\` ;
                  GRANT ALL ON \\\`#{short_name()}\\\`.* TO '#{tiny_name()}'@'localhost' IDENTIFIED BY '#{db_pass}';
                  FLUSH PRIVILEGES;"
    run "mysql -u root #{db_root_pass} -e \"#{create_sql}\""
    puts "Using pass: #{db_pass}"
  end

  after "db:push", "deploy:cacheclear"
  before "db:pull", "db:down"
end

namespace :files do
  desc "Download a backup of the shared file directories from the given stage."
  task :pull, :roles => :web do
    shared_children.each do |child_dir|
      if exists?(:gateway)
        run_locally("rsync --recursive --times --omit-dir-times --chmod=ugo=rwX --rsh='ssh -A #{ssh_options[:user]}@#{gateway} ssh  #{ssh_options[:user]}@#{find_servers(:roles => :web).first.host}' --compress --human-readable --progress :#{deploy_to}/#{shared_dir}/#{child_dir}/ #{app_root}/#{child_dir}/")
      else
        run_locally("rsync --recursive --times --omit-dir-times --chmod=ugo=rwX --rsh=ssh --compress --human-readable --progress #{ssh_options[:user]}@#{find_servers(:roles => :web).first.host}:#{deploy_to}/#{shared_dir}/#{child_dir}/ #{app_root}/#{child_dir}/")
      end
    end
  end

  desc "Push a backup of the shared file directories from the given stage."
  task :push, :roles => :web do
    shared_children.each do |child_dir|
      if exists?(:gateway)
        run_locally("rsync --recursive --times --omit-dir-times --chmod=ugo=rwX --rsh='ssh -A #{ssh_options[:user]}@#{gateway} ssh  #{ssh_options[:user]}@#{find_servers(:roles => :web).first.host}' --compress --human-readable --progress #{app_root}/#{child_dir}/ :#{deploy_to}/#{shared_dir}/#{child_dir}/")
      else
        run_locally("rsync --recursive --times --omit-dir-times --chmod=ugo=rwX --rsh=ssh --compress --human-readable --progress #{app_root}/#{child_dir}/ #{ssh_options[:user]}@#{find_servers(:roles => :web).first.host}:#{deploy_to}/#{shared_dir}/#{child_dir}/")
      end
    end
  end
end

def short_name(domain=nil)
  return "#{application}_#{stage}_#{domain}".gsub('.', '_')[0..63] if domain && domain != 'default'
  return "#{application}_#{stage}".gsub('.', '_')[0..63]
end

def tiny_name(domain=nil)
  return "#{application[0..5]}_#{stage.to_s[0..2]}_#{domain[0..4]}".gsub('.', '_') if domain && domain != 'default'
  return "#{application[0..11]}_#{stage.to_s[0..2]}".gsub('.', '_')
end

def random_password(size = 16)
  chars = (('A'..'Z').to_a + ('a'..'z').to_a + ('0'..'9').to_a) - %w(i o 0 1 l 0)
  (1..size).collect{|a| chars[rand(chars.size)] }.join
end

def get_env_info(data=nil)
  return Dotenv::Parser.call(data)
end

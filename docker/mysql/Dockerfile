FROM mysql:5.7-debian

RUN apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 467B942D3A79BD29

RUN apt-get update && apt-get install -y ruby-full netcat

RUN gem install net-ssh -v 6.1.0

RUN gem install capistrano -v 2.15.5

RUN gem install capistrano-deploy-strategy-archive

RUN gem install dotenv

RUN mkdir -p /workspace/database

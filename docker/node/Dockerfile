FROM node:14

RUN apt-get update

RUN apt-get install -y libnss3 libgconf-2-4 wget

RUN wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb

RUN apt install -y ./google-chrome-stable_current_amd64.deb

RUN npm install -g maildev

RUN npm install -g --force npx
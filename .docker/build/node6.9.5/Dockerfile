# FROM node:6.9.5
#
# RUN apt-get update
# RUN apt-get install -y ruby ruby-dev
# RUN gem install sass
# 
# RUN npm install --global eslint bower gulp && echo '{ "allow_root": true }' > /root/.bowerrc
# 
# WORKDIR /usr/src/app

FROM ubuntu:16.04

RUN apt-get update
RUN apt-get install -y curl xvfb chromium-browser firefox

# FROM markadams/chromium-xvfb

WORKDIR /usr/src/app

RUN curl -sL https://deb.nodesource.com/setup_7.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists

RUN apt-get update
RUN apt-get install -y ruby ruby-dev
RUN gem install sass

RUN npm install --global eslint bower gulp && echo '{ "allow_root": true }' > /root/.bowerrc

RUN mv /usr/bin/chromium-browser /usr/bin/chromium
ADD xvfb-chromium /usr/bin/xvfb-chromium
RUN chmod +x /usr/bin/xvfb-chromium
RUN ln -s /usr/bin/xvfb-chromium /usr/bin/google-chrome
RUN ln -s /usr/bin/xvfb-chromium /usr/bin/chromium-browser

RUN mv /usr/bin/firefox /usr/bin/firefox-browser
ADD xvfb-firefox /usr/bin/xvfb-firefox
RUN chmod +x /usr/bin/xvfb-firefox
RUN ln -s /usr/bin/xvfb-firefox /usr/bin/firefox
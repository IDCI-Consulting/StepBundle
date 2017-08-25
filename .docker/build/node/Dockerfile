FROM node:8.4.0-stretch

RUN apt-get update
RUN apt-get install -y curl xvfb chromium chromium-l10n firefox-esr ruby ruby-dev
RUN gem install sass

RUN npm install --global gulp eslint bower && \
        echo '{ "allow_root": true }' > /root/.bowerrc

# Add Chromium and Firefox with xvfb for karma tests

ADD xvfb-chromium /usr/bin/xvfb-chromium
RUN chmod +x /usr/bin/xvfb-chromium
ENV CHROMIUM_BIN=/usr/bin/xvfb-chromium

ADD xvfb-firefox /usr/bin/xvfb-firefox
RUN chmod +x /usr/bin/xvfb-firefox
ENV FIREFOX_BIN=/usr/bin/xvfb-firefox

WORKDIR /usr/src/app

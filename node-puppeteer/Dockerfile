FROM node:20-alpine
ARG NODE_ENV=development

WORKDIR /opt/
COPY package*.json ./
RUN npm config set fetch-retry-maxtimeout 600000 -g && npm install --legacy-peer-deps
ENV PATH /opt/node_modules/.bin:$PATH

WORKDIR /opt/app
COPY . .
RUN chown -R node:node /opt/app
USER node


RUN ["npm", "run", "build:ts"]

EXPOSE 3000
ENV NODE_ENV=${NODE_ENV}
CMD ["npm", "run", "server"]

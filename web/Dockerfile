FROM node:latest
WORKDIR /app
COPY package*.json ./
RUN yarn
COPY ./ .
RUN rm -rf dist
RUN yarn build
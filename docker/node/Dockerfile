ARG NODE_VERSION=20.9.0

FROM node:${NODE_VERSION}-slim

RUN yarn set version berry && yarn config set --home enableTelemetry 0

WORKDIR /app
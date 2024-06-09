# syntax=docker/dockerfile:1

ARG NODE_VERSION=20.14
ARG PNPM_VERSION=9.2.0

FROM node:${NODE_VERSION}-slim

RUN --mount=type=cache,target=/root/.npm \
    npm install -g pnpm@${PNPM_VERSION}

WORKDIR /app

USER node

EXPOSE 5173
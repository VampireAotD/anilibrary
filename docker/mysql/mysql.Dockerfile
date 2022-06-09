FROM mysql:8.0

ARG uid=1000
ARG gid=1000

RUN groupadd -g $gid user && useradd -lm -u $uid -g $gid user

USER user

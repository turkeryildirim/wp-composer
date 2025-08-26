FROM redis:8-alpine

COPY ./redis.conf /usr/local/etc/redis/redis.conf

RUN mkdir -p /data /var/log/redis /var/run/ && \
    chown -R redis:redis /data /var/log/redis

EXPOSE 6379

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD redis-cli ping || exit 1

USER redis

CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]

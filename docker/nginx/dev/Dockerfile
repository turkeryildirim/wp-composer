FROM nginx:1.25-alpine

RUN apk add --no-cache curl

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./default.conf /etc/nginx/conf.d/default.conf
COPY ./init.sh /init.sh
RUN chmod +x /init.sh

RUN mkdir -p /var/log/nginx && \
    ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80 443

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost/health || exit 1

ENTRYPOINT ["/init.sh"]

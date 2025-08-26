FROM axllent/mailpit:latest

EXPOSE 1025 8025

HEALTHCHECK \
  --interval=30s \
  --timeout=10s \
  --start-period=30s \
  --retries=3 \
  CMD ["/mailpit", "readyz"]

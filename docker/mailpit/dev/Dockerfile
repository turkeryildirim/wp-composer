FROM axllent/mailpit:latest

EXPOSE 1025 8025

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost:8025/api/v1/messages || exit 1

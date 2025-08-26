#!/bin/bash

set -e

# load .env
if [ -f /var/www/.env ]; then
  set -o allexport
  while IFS= read -r line; do
      line="${line%%[$'\r']}"
      case "$line" in \#*|"") continue ;; esac
      export "$line"
  done < <(grep -v '^\s*#' /var/www/.env)
  set +o allexport
fi

echo "🚀 Starting PHP service initialization..."

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    printf "${GREEN}[INFO]${NC} %s\n" "$1"
}

print_warning() {
    printf "${YELLOW}[WARNING]${NC} %s\n" "$1"
}

print_error() {
    printf "${RED}[ERROR]${NC} %s\n" "$1"
}

print_step() {
    printf "${BLUE}[STEP]${NC} %s\n" "$1"
}

wait_for_services() {
    print_step "Waiting for external services..."

    print_status "Waiting for MySQL at $MYSQL_HOST:${MYSQL_PORT:-3306}..."
    timeout=60
    while ! nc -z $MYSQL_HOST ${MYSQL_PORT:-3306} && [ $timeout -gt 0 ]; do
        sleep 1
        timeout=$((timeout - 1))
    done

    if [ $timeout -eq 0 ]; then
        print_error "MySQL connection timeout!"
        exit 1
    fi
    print_status "🎉 MySQL is ready!"

    print_status "Waiting for Redis at $REDIS_HOST:${REDIS_PORT:-6379}..."
    timeout=60
    while ! nc -z $REDIS_HOST ${REDIS_PORT:-6379} && [ $timeout -gt 0 ]; do
        sleep 1
        timeout=$((timeout - 1))
    done

    if [ $timeout -eq 0 ]; then
        print_warning "Redis connection timeout! Continuing anyway..."
    else
        print_status "🎉 Redis is ready!"
    fi
}

install_dependencies() {
    print_step "Installing Composer dependencies..."
    print_status "Installing Composer packages..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
    print_status "🎉 Composer packages installed successfully!"
}

generate_env_file() {
    print_step "Checking env file..."

    if [ ! -f ".env" ]; then
        print_warning ".env file not found, copying from .env.example..."
        if [ -f ".env.example" ]; then
            cp .env.example .env
            print_status "🎉 .env created"
        fi
    fi

   print_status ".env found, skipping."
}

prepare_directories() {
    print_step "Preparing directories..."

    if [ ! -d "logs" ]; then
        mkdir -p logs
        print_status "logs directory created."
    else
        print_status "logs directory already exists."
    fi

    if [ ! -d "app/uploads" ]; then
        mkdir -p app/uploads
        print_status "uploads directory created."
    else
        print_status "uploads directory already exists."
    fi

    if [ "$CLEAR_LOGS" = "true" ]; then
        print_step "Clearing log files..."
        find logs -type f -name "*.log" -delete
        print_status "🎉 Log files cleared."
    fi

    print_status "🎉 Directory preparation completed!"
}

setup_wordpress() {
    cd /var/www
    print_step "Checking WordPress installation..."
    if ! ./vendor/bin/wp core is-installed --allow-root; then
        print_status "WordPress not installed. Starting installation..."

        ./vendor/bin/wp core install \
          --url="${SITE_DOMAIN}/wordpress" \
          --title="$SITE_TITLE" \
          --admin_user="$SITE_ADMIN_USER" \
          --admin_password="$SITE_ADMIN_PASSWORD" \
          --admin_email="$SITE_ADMIN_EMAIL" \
          --skip-email \
          --allow-root

        print_status "🎉 WordPress installed successfully!"
    else
        print_status "WordPress already installed, skipping."
    fi
}


main() {
    print_status "Starting initialization..."

    wait_for_services
    install_dependencies
    generate_env_file
    prepare_directories
    setup_wordpress

    print_status "✅ Initialization completed successfully!"
    print_status "🐘 Starting PHP-FPM..."
}

main

if [ $# -gt 0 ]; then
    print_status "Executing: $@"
    exec "$@"
else
    exec php-fpm
fi

#!/bin/sh
set -e

echo "🚀 Starting PHP service initialization..."

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo "${BLUE}[STEP]${NC} $1"
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

setup_directories() {
    print_step "Setting up directories..."

    mkdir -p logs

    print_status "🎉 Directories setup completed!"
}

clear_logs() {
    if [ "$CLEAR_LOGS" = "true" ]; then
        print_step "Clearing log files..."
        find logs -type f -name "*.log" -delete
        print_status "🎉 Log files cleared."
    fi
}

setup_wordpress() {
    cd /var/www/wordpress
    local WP_CONFIG="/var/www/wp-config.php"

    print_step "Checking WordPress installation..."

    [ ! -f wp-config.php ] && ln -s ../wp-config.php wp-config.php

    if ! ../vendor/bin/wp core is-installed --allow-root; then
        print_status "WordPress not installed. Starting installation..."

        ../vendor/bin/wp core install \
          --url="$WP_SITEURL" \
          --title="$WP_TITLE" \
          --admin_user="$WP_ADMIN_USER" \
          --admin_password="$WP_ADMIN_PASSWORD" \
          --admin_email="$WP_ADMIN_EMAIL" \
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
    setup_directories
    clear_logs
    #setup_wordpress

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

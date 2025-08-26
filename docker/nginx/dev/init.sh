#!/bin/sh
set -e

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

echo "🚀 Starting Nginx service..."

print_step "Waiting for php.local:9000 service to be ready..."

until nc -z php.local 9000; do
  print_warning "PHP service is not ready. Rechecking..."
  sleep 10
done

print_status "PHP service is ready."
print_step "Starting Nginx"

exec nginx -g "daemon off;"

#!/bin/bash

# Install dependencies
composer install

# Create .env file
cp .env.example .env

# Generate app key
php artisan key:generate

# Create database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Launch Tests
php artisan test

# Serve the application
php artisan serve

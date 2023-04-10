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

# Launch Tests
php artisan test

# Seed database
php artisan db:seed

# Serve the application
php artisan serve

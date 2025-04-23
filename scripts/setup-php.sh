#!/bin/bash

check_app_key() {
  if grep -q "^APP_KEY=$" ".env"; then
    echo "APP_KEY is empty, generating a new key"
    php artisan key:generate
  fi
}

load_phpbrew() {
  if test -f ".phpbrewrc"; then
    if test -f ~/.phpbrew/bashrc; then
      echo "Loading ~/.phpbrew/bashrc"
      source ~/.phpbrew/bashrc
    fi
    echo "Loading .phpbrewrc"
    source .phpbrewrc
  fi
}

load_phpbrew
composer install
check_app_key

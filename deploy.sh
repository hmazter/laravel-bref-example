#!/usr/bin/env bash

# npm run production
# composer install --no-dev

aws s3 cp public/css/app.css s3://wb-laravel-bref-assets-dev/css/app.css --acl=public-read
aws s3 cp public/js/app.js s3://wb-laravel-bref-assets-dev/js/app.js --acl=public-read

serverless deploy -v

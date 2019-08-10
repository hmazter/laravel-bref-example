#!/usr/bin/env bash

aws s3 cp public/css/app.css s3://wb-laravel-bref-assets/css/app.css --acl=public-read
aws s3 cp public/js/app.js s3://wb-laravel-bref-assets/js/app.js --acl=public-read

serverless deploy -v

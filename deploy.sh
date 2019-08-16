#!/usr/bin/env bash

# npm run production
# composer install --no-dev

# sync all assets from the oublic forlder to S3 (that is served by CloudFront)
aws s3 sync public/ s3://wb-laravel-bref-assets-dev/ --acl=public-read --delete --exclude "index.php" --exclude "mix-manifest.json"

serverless deploy -v

# Migrate database after successful deploy
aws lambda invoke  --function-name laravel-bref-dev-artisan --payload '{"cli": "migrate --force"}' migrate.log
cat migrate.log

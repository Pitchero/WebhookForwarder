### GoCardless Webhook Forwarder

**Initial Setup**

Basic PHP LAMBDA + BREF project to forward GoCardless webhooks to different environments.

- Fill out your GoCardless webhook creds within your `.env` file
- Fill out your Webhook URLS within your `.receivers` file

```
cp .env.sample .env
cp .receivers.sample .receivers
```

**Dependencies**

Ensure you have PHP + Composer Installed, then run:

```
composer install
```

Install serverless for deploying

```
npm i -g serverless
```

Install and configure the Serverless Framework

```
npm i -g serverless
```

Configure your AWS creds for deployment

```
serverless config credentials —provider aws —key <key> —secret <secret>
```


Test this endpoint locally ( requires PHP to be installed )

```
php -S localhost:8088
```

Deploy

```serverless deploy```

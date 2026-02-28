# ContentPulse Shopify App

[![CI](https://github.com/contentpulseio/contentpulse-shopify/actions/workflows/ci.yml/badge.svg)](https://github.com/contentpulseio/contentpulse-shopify/actions/workflows/ci.yml)

Sync AI-generated content from ContentPulse to Shopify stores. Create and update blog articles, sync product descriptions, and keep your Shopify content in sync with ContentPulse's AI-powered content pipeline.

## Requirements

- PHP 8.2 or higher
- Shopify Partner account
- ContentPulse API credentials

## Installation

Install via Composer:

```bash
composer require contentpulse/shopify-app
```

## Configuration

### ContentPulse API

Set the following environment variables:

- `CONTENTPULSE_API_URL` - Your ContentPulse API base URL (e.g. `https://api.contentpulse.io`)
- `CONTENTPULSE_API_KEY` - Your ContentPulse API key

### Shopify

Configure your Shopify app credentials in the Shopify Partner Dashboard:

- App API key
- App API secret key
- Access scopes for blogs and products

## Architecture

| Component | Purpose |
|-----------|---------|
| `Services/ContentPulseBridge.php` | The glue between the ContentPulse SDK and Shopify. Fetches content from the API, transforms it, and pushes to Shopify. |
| `Services/Shopify/ArticleAdapter.php` | Shopify blog article operations: create, update, and upsert articles. |
| `Services/Shopify/ProductAdapter.php` | Product description updates and sync. |

## Features

- **Blog article creation and updates** - Sync ContentPulse articles to Shopify blogs
- **Product description sync** - Update product descriptions from ContentPulse content
- **ContentPulse ID tracking** - Metafields store ContentPulse content IDs for reliable upserts
- **Multi-shop support** - Handle multiple Shopify stores
- **Webhook handling** - React to ContentPulse webhooks for real-time sync

## Testing

Run the test suite:

```bash
./vendor/bin/phpunit
```

## License

MIT

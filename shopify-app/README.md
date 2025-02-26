# Store Locator Shopify App

This Shopify app allows merchants to manage their store locations and display them on a map.

## Setup Instructions

1. Create a new Shopify app in your Shopify Partner account
2. Copy `.env.example` to `.env` and fill in your Shopify API credentials
3. Install dependencies:
   ```bash
   npm install
   ```
4. Start the development server:
   ```bash
   npm run dev
   ```

## Features

- Secure authentication with Shopify
- Add and manage store locations
- View store locations in a table format
- Each merchant can only view and manage their own store locations
- Automatic login when accessing through Shopify admin

## Environment Variables

- `SHOPIFY_API_KEY`: Your Shopify API key
- `SHOPIFY_API_SECRET`: Your Shopify API secret
- `MONGODB_URI`: MongoDB connection string
- `HOST`: Your app's host URL
- `SCOPES`: Required Shopify API scopes

## Deployment

1. Set up a MongoDB database
2. Deploy the application to your hosting provider
3. Update the app URLs in your Shopify Partner dashboard
4. Install the app on your Shopify store

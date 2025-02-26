require('dotenv').config();
const express = require('express');
const { Shopify } = require('@shopify/shopify-api');
const mongoose = require('mongoose');

const app = express();
const port = process.env.PORT || 3000;

// Shopify configuration
const shopify = new Shopify({
  apiKey: process.env.SHOPIFY_API_KEY,
  apiSecretKey: process.env.SHOPIFY_API_SECRET,
  scopes: ['read_products', 'write_products'],
  hostName: process.env.HOST.replace(/https:\/\//, ''),
  apiVersion: '2023-10',
  isEmbeddedApp: true
});

// MongoDB connection
mongoose.connect(process.env.MONGODB_URI, {
  useNewUrlParser: true,
  useUnifiedTopology: true
});

// Store schema
const StoreSchema = new mongoose.Schema({
  shopDomain: String,
  accessToken: String,
  locations: [{
    name: String,
    address: String,
    lat: Number,
    lng: Number,
    description: String
  }]
});

const Store = mongoose.model('Store', StoreSchema);

// Middleware to verify Shopify requests
const verifyShopify = async (req, res, next) => {
  try {
    const session = await Shopify.Auth.validateAuthCallback(
      req,
      res,
      req.query
    );
    req.shopifySession = session;
    next();
  } catch (error) {
    res.status(401).send('Unauthorized');
  }
};

// Auth routes
app.get('/auth', async (req, res) => {
  const authRoute = await Shopify.Auth.beginAuth(
    req,
    res,
    req.query.shop,
    '/auth/callback',
    false
  );
  res.redirect(authRoute);
});

app.get('/auth/callback', async (req, res) => {
  try {
    const session = await Shopify.Auth.validateAuthCallback(
      req,
      res,
      req.query
    );
    
    // Save store information
    await Store.findOneAndUpdate(
      { shopDomain: session.shop },
      { 
        shopDomain: session.shop,
        accessToken: session.accessToken
      },
      { upsert: true }
    );

    res.redirect(`/app?shop=${session.shop}`);
  } catch (error) {
    res.status(500).send('Error during auth');
  }
});

// API Routes
app.get('/api/locations', verifyShopify, async (req, res) => {
  try {
    const store = await Store.findOne({ shopDomain: req.query.shop });
    res.json(store.locations || []);
  } catch (error) {
    res.status(500).json({ error: 'Error fetching locations' });
  }
});

app.post('/api/locations', verifyShopify, async (req, res) => {
  try {
    const store = await Store.findOne({ shopDomain: req.query.shop });
    store.locations.push(req.body);
    await store.save();
    res.json(store.locations);
  } catch (error) {
    res.status(500).json({ error: 'Error saving location' });
  }
});

app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});

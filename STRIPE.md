# Stripe Local Dev Setup

1) re-run composer
2) run migrations & seeds (see readme in laravel/bentericksen/Payment/readme.md)
3) Set Stripe ENV’s (STRIPE_KEY and STRIPE_SECRET)
4) Make sure loopback is setup locally:   (in /etc/hosts  127.0.0.1 hrdirector.localhost)
5) Launch ngrok for webhooks (available via homebrew)
6) Configure webhooks on stripe (create new endpoint with ngrok domain).   
7) Update ENV for webhook (from Stripe webhook dashboard):  STRIPE_WEBHOOK_SECRET
8) Try to create subscriptions for any user that has business assigned!

# Regression Test Sheet

- https://drive.google.com/file/d/1gGkPbVegAJF5--LPvT3oLmnWh44tFGat/view?usp=sharing

# Stripe Config Object

- http://hrdirector.localhost/payment/setup

# Stripe Credit Card Testing

```
4242 4242 4242 4242
```

# Bent Stripe Integration

Includes integration of the Stripe Customer, Credit Card, Bank Account, Product, and Subscription APIs.

##### Stripe API Documentation

- [Customer API](https://stripe.com/docs/api/customers)
- [Credit Card API](https://stripe.com/docs/api/cards)
- [Bank Account API](https://stripe.com/docs/api/customer_bank_accounts)
- [Product API](https://stripe.com/docs/api/products)
- [Subscription API](https://stripe.com/docs/api/subscriptions)

##### Laravel Cashier Documentation

- [https://laravel.com/docs/7.x/billing](https://laravel.com/docs/7.x/billing)
- [https://github.com/laravel/cashier-stripe](https://github.com/laravel/cashier-stripe)

---
#### Installation:

```bash
composer install
```
```bash
php artisan migrate
```
```bash
php artisan db:seed --class=StripeProductsTableSeeder
```
```bash
php artisan db:seed --class=StripePricesTableSeeder
```
---

#### Request Body Samples:
```javascript
// Create a card:
var obj = {'data': {'source': 'token_here'}};

// Update a card:
var obj = {'data': {'exp_month': 08, 'exp_year': 2022}};
```
```javascript
// Create an account:
var obj = {'data': {'source': 'btok_1HOz5h2eZvKYlo2CTh7gEZ6u'}};

// Update an account:
var obj = {'data': {'metadata': {'order_id': '999999'}}};

// Verify an account:
var obj = {'data': {'amounts': [33,78]}};
```
```javascript
// Create a product:
var obj = {'data': {'name': 'Blue Special', 'description': 'A shiny glimmering product - deluxe model'}};

// Update a product:
var obj = {'data': {'metadata': {'prod_id': '1111', 'status': 'test deluxe' }}};
```
```javascript
// Update a subscription:
var obj = {'data': {'metadata': {'sub_id': '2222', 'status': 'test' }}};
```
```javascript
// Create a customer:
var obj = {'data': {'description': 'This is a test customer', 'name': 'Test Customer'}};

// Update a customer:
var obj = {'data': {'metadata': {'test_id': '555', 'status': 'test555' }}};
```

---
#### Unit Test Commands:
```bash
# Credit Card API
php artisan test --filter="PaymentCardTest" --env=testing

# Bank Account API
php artisan test --filter="PaymentAccountTest" --env=testing

# Product API
php artisan test --filter="PaymentProductTest" --env=testing

# Subscription API
php artisan test --filter="PaymentSubscriptionTest" --env=testing

# Customer API
php artisan test --filter="PaymentCustomerTest" --env=testing
```

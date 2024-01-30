# JustBetter Customer Pricing

Magento 2 module that enables prices for specific customers.

## Features

- Allows for storing customer specific prices in the database via the API
- Sets the customer specific prices on category and product pages
- Sets the customer specific prices in the cart and checkout

## Installation

```shell
composer require justbetter/magento2-customer-pricing
bin/magento setup:upgrade
```

## How does it work?

Magento 2 provides a final price event (`catalog_product_get_final_price`) in which we can override the product price in the checkout / cart.

For the product and category pages we hook into the `catalog_product_collection_load_after` to set the final price of the products.

We store the customer prices in a database table called `customer_pricing`.

## Updating Prices

This module provides an API endpoint for updating prices.

Make a POST request to: `customer-pricing/{sku}` with the following payload:
```json
{
  "customerPrices": [
    {
      "customer_id": 10,
      "quantity": 10,
      "price": 9.99
    },
    {
      "customer_id": 20,
      "quantity": 1,
      "price": 8.99
    }
  ]
}
```

## Current Limitations

Due to the simplicity of this module there are several limitations.
We accept non-breaking PR's to accept functionality.

### Website / store specific pricing

This module currently does not support pricing for specific stores / websites because we do not store a website/store id with each price.

### No adding/updating in the Magento 2 backend

It is currently only possible to view and delete customer specific prices in the backend.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Vincent Boon](https://github.com/VincentBean)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


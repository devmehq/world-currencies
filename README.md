# World Currencies

[![npm version](https://badge.fury.io/js/@devmehq%2Fworld-currencies.svg)](https://www.npmjs.com/package/@devmehq/world-currencies)
[![PyPI version](https://badge.fury.io/py/world-currencies.svg)](https://pypi.org/project/world-currencies/)
[![Gem Version](https://badge.fury.io/rb/devmehq-world-currencies.svg)](https://rubygems.org/gems/devmehq-world-currencies)
[![Packagist Version](https://img.shields.io/packagist/v/devmehq/world-currencies.svg)](https://packagist.org/packages/devmehq/world-currencies)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![CI](https://github.com/devmehq/world-currencies/actions/workflows/test.yml/badge.svg)](https://github.com/devmehq/world-currencies/actions/workflows/test.yml)

A comprehensive, multi-language library providing detailed information about world currencies. Access currency data including ISO codes, symbols, native names, denominations, and more across JavaScript/TypeScript, Python, Ruby, and PHP.

## Features

- 🌍 **Complete Coverage**: All 150+ world currencies with detailed information
- 🌐 **Native Names**: Currency names in their native languages and scripts
- 💱 **Rich Metadata**: ISO codes, symbols, decimal digits, rounding rules
- 💵 **Denominations**: Information about banknotes and coins in circulation
- 🏳️ **Country Mapping**: Countries where each currency is used
- 📦 **Multi-Platform**: Available for JavaScript/TypeScript, Python, Ruby, and PHP
- 🔄 **Consistent API**: Similar interface across all supported languages
- 📊 **JSON Data Source**: Single source of truth for all implementations

## Installation

### JavaScript/TypeScript (npm)

```bash
npm install @devmehq/world-currencies
```

```javascript
const currencies = require('@devmehq/world-currencies');
// or
import currencies from '@devmehq/world-currencies';
```

### Python (pip)

```bash
pip install world-currencies
```

```python
from world_currencies import currencies
```

### Ruby (gem)

```bash
gem install devmehq-world-currencies
```

```ruby
require 'world_currencies'
```

### PHP (Composer)

```bash
composer require devmehq/world-currencies
```

```php
use DevMe\WorldCurrencies\Currencies;
```

## Usage Examples

### JavaScript/TypeScript

```javascript
import { getCurrency, getAllCurrencies, getCurrenciesByCountry } from '@devmehq/world-currencies';

// Get specific currency
const usd = getCurrency('USD');
console.log(usd);
// {
//   name: 'US Dollar',
//   name_native: 'US Dollar',
//   symbol: '$',
//   symbol_native: '$',
//   decimal_digits: 2,
//   countries: ['US', 'AS', 'BQ', ...],
//   ...
// }

// Get all currencies
const allCurrencies = getAllCurrencies();

// Get currencies for a specific country
const countryCurrencies = getCurrenciesByCountry('US');
```

### Python

```python
from world_currencies import get_currency, get_all_currencies, get_currencies_by_country

# Get specific currency
usd = get_currency('USD')
print(usd['name'])  # 'US Dollar'
print(usd['symbol'])  # '$'

# Get all currencies
all_currencies = get_all_currencies()

# Get currencies for a specific country
us_currencies = get_currencies_by_country('US')
```

### Ruby

```ruby
require 'world_currencies'

# Get specific currency
usd = WorldCurrencies.get_currency('USD')
puts usd['name']  # 'US Dollar'
puts usd['symbol']  # '$'

# Get all currencies
all_currencies = WorldCurrencies.get_all_currencies

# Get currencies for a specific country
us_currencies = WorldCurrencies.get_currencies_by_country('US')
```

### PHP

```php
use DevMe\WorldCurrencies\Currencies;

$currencies = new Currencies();

// Get specific currency
$usd = $currencies->getCurrency('USD');
echo $usd['name'];  // 'US Dollar'
echo $usd['symbol'];  // '$'

// Get all currencies
$allCurrencies = $currencies->getAllCurrencies();

// Get currencies for a specific country
$usCurrencies = $currencies->getCurrenciesByCountry('US');
```

## Currency Data Structure

Each currency object contains the following information:

```json
{
  "name": "United States Dollar",
  "name_native": "US Dollar",
  "name_plural": "US dollars",
  "name_plural_native": "US Dollars",
  "iso": {
    "code": "USD",
    "number": "840"
  },
  "symbol": "$",
  "symbol_native": "$",
  "decimal_digits": 2,
  "rounding": 0,
  "units": {
    "major": {
      "name": "dollar",
      "symbol": "$"
    },
    "minor": {
      "name": "cent",
      "symbol": "¢",
      "majorValue": 0.01
    }
  },
  "banknotes": {
    "frequent": ["$1", "$5", "$10", "$20", "$50", "$100"],
    "rare": ["$2"]
  },
  "coins": {
    "frequent": ["1¢", "5¢", "10¢", "25¢"],
    "rare": ["50¢", "$1"]
  },
  "countries": ["US", "AS", "BQ", "IO", "EC", "SV", "GU", "HT", "MH", "FM", "MP", "PW", "PA", "PR", "TL", "TC", "VI", "UM"]
}
```

## API Reference

### Core Functions

All implementations provide these core functions:

| Function | Description | Parameters | Returns |
|----------|-------------|------------|---------|
| `getCurrency(code)` | Get currency by ISO code | `code: string` | Currency object or null |
| `getAllCurrencies()` | Get all currencies | None | Object/Dict of all currencies |
| `getCurrenciesByCountry(country)` | Get currencies used in a country | `country: string` | Array of currency codes |
| `getCurrencyBySymbol(symbol)` | Find currencies by symbol | `symbol: string` | Array of currency objects |
| `getCurrenciesByDecimalDigits(digits)` | Find currencies by decimal digits | `digits: number` | Array of currency objects |

### Available Currency Codes

The library includes all ISO 4217 currency codes, including but not limited to:

- **Major Currencies**: USD, EUR, GBP, JPY, CNY, CHF, CAD, AUD
- **Regional Currencies**: XAF, XCD, XOF, XPF (monetary unions)
- **Crypto-friendly**: Many currencies used in crypto exchanges
- **Historical**: Recently redenominated currencies with their current codes

## Data Source

The currency data is maintained in a single JSON file (`data/world-currencies.json`) that serves as the source of truth for all language implementations. This ensures consistency across all platforms.

## Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Development Setup

1. Clone the repository:
```bash
git clone https://github.com/devmehq/world-currencies.git
cd world-currencies
```

2. Install dependencies for your language:

**JavaScript/TypeScript:**
```bash
npm install
npm run build
npm test
```

**Python:**
```bash
pip install -e .[dev]
pytest tests/python
```

**Ruby:**
```bash
bundle install
bundle exec rspec
```

**PHP:**
```bash
composer install
composer test
```

## Testing

All implementations include comprehensive test suites. Run tests with:

- JavaScript: `npm test`
- Python: `pytest`
- Ruby: `bundle exec rspec`
- PHP: `composer test`

## Deployment

The library is automatically published to all package managers when a new version tag is pushed:

```bash
git tag v1.0.0
git push origin v1.0.0
```

This triggers GitHub Actions workflows that publish to:
- NPM (@devmehq/world-currencies)
- PyPI (world-currencies)
- RubyGems (devmehq-world-currencies)
- Packagist (devmehq/world-currencies)

## License

MIT License - see [LICENSE](LICENSE) file for details.

## Support

- 📧 Email: support@devme.com
- 🐛 Issues: [GitHub Issues](https://github.com/devmehq/world-currencies/issues)
- 💬 Discussions: [GitHub Discussions](https://github.com/devmehq/world-currencies/discussions)
- 💖 Sponsor: [GitHub Sponsors](https://github.com/sponsors/devmehq)

## Related Projects

- [DevMe SDK JS](https://github.com/devmehq/devme-sdk-js) - JavaScript SDK for DevMe API
- [DevMe SDK Python](https://github.com/devmehq/devme-sdk-python) - Python SDK for DevMe API
- [DevMe SDK Ruby](https://github.com/devmehq/devme-sdk-ruby) - Ruby SDK for DevMe API
- [DevMe SDK PHP](https://github.com/devmehq/devme-sdk-php) - PHP SDK for DevMe API

## Credits

Created and maintained by [DevMe](https://devme.com).

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes.

---

<p align="center">Made with ❤️ by <a href="https://devme.com">DevMe</a></p>
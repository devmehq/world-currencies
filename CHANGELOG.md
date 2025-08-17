# Changelog

All notable changes to the World Currencies library will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial release of the World Currencies library
- Support for 150+ world currencies with comprehensive data
- Multi-language support: TypeScript/JavaScript, Python, Ruby, and PHP
- Currency data includes:
  - Names (standard and native language)
  - Plural forms (standard and native)
  - ISO 4217 codes and numeric codes
  - Currency symbols (standard and native)
  - Decimal digits and rounding rules
  - Flag codes (ISO 3166-1 alpha-2)
  - Denominations (banknotes and coins)
  - Countries where each currency is used
  - Major and minor unit information

### Features
- **TypeScript/JavaScript**: Full TypeScript support with type definitions
- **Python**: Functional API and object-oriented Currency class
- **Ruby**: Module methods and Currency class with dynamic accessors
- **PHP**: Currencies service class and Currency entity with full getter methods

### API Methods
- `getCurrency(code)` - Get currency by ISO code
- `getAllCurrencies()` - Get all currencies
- `getCurrenciesByCountry(countryCode)` - Get currencies used in a country
- `getCurrencyBySymbol(symbol)` - Find currencies by symbol
- `getCurrenciesByDecimalDigits(digits)` - Find currencies by decimal places
- `getCurrencyCodes()` - Get all ISO currency codes
- `searchCurrencies(searchTerm)` - Search currencies by name

### Infrastructure
- GitHub Actions workflows for automated releases to:
  - NPM (@devmehq/world-currencies)
  - PyPI (world-currencies)
  - RubyGems (devmehq-world-currencies)
  - Packagist (devmehq/world-currencies)
- Comprehensive test suites for all languages
- Renovate configuration for dependency management

### Data Format
- All field names use camelCase convention
- Flag codes for visual representation
- Native language names and symbols
- Accurate plural forms
- Complete ISO compliance

## [1.0.0] - TBD

### Notes
- First stable release
- Production-ready for all supported languages
- Comprehensive currency coverage
- Full test coverage

## Version History

### Pre-release Development
- Project initialization and structure setup
- Implementation of core functionality across all languages
- Test suite development
- CI/CD pipeline configuration
- Documentation and examples

---

## Upgrade Guide

### From snake_case to camelCase (Breaking Change)
If you're accessing the JSON data directly, note that all field names have been changed from snake_case to camelCase:

**Before:**
```json
{
  "decimal_digits": 2,
  "symbol_native": "$",
  "name_plural": "US dollars"
}
```

**After:**
```json
{
  "decimalDigits": 2,
  "symbolNative": "$",
  "namePlural": "US dollars"
}
```

All language-specific implementations handle this change internally through their APIs.

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Maintained by

[DevMe](https://dev.me) - Building developer tools for the modern web.

[Unreleased]: https://github.com/devmehq/world-currencies/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/devmehq/world-currencies/releases/tag/v1.0.0
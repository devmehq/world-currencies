<?php

namespace DevMe\WorldCurrencies;

use JsonException;

/**
 * World Currencies Library
 * A comprehensive library providing currency information for all world currencies
 */
class Currencies
{
    /**
     * @var array<string, array> Currency data
     */
    private array $currencies;

    /**
     * Constructor - loads currency data
     * @throws JsonException If JSON parsing fails
     */
    public function __construct()
    {
        $this->loadCurrencies();
    }

    /**
     * Load currency data from JSON file
     * @throws JsonException If JSON parsing fails
     */
    private function loadCurrencies(): void
    {
        $dataPath = __DIR__ . '/../../data/world-currencies.json';
        $jsonContent = file_get_contents($dataPath);
        
        if ($jsonContent === false) {
            throw new \RuntimeException('Failed to load currency data file');
        }

        $this->currencies = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Get a specific currency by its ISO code
     * @param string $code ISO 4217 currency code (e.g., 'USD', 'EUR')
     * @return array|null Currency array or null if not found
     */
    public function getCurrency(string $code): ?array
    {
        $upperCode = strtoupper($code);
        return $this->currencies[$upperCode] ?? null;
    }

    /**
     * Get all currencies
     * @return array<string, array> Array containing all currencies keyed by ISO code
     */
    public function getAllCurrencies(): array
    {
        return $this->currencies;
    }

    /**
     * Get currencies used in a specific country
     * @param string $countryCode ISO 3166-1 alpha-2 country code (e.g., 'US', 'GB')
     * @return array<string> Array of currency codes used in the country
     */
    public function getCurrenciesByCountry(string $countryCode): array
    {
        $countryCurrencies = [];
        $upperCountryCode = strtoupper($countryCode);

        foreach ($this->currencies as $code => $currency) {
            if (isset($currency['countries']) && in_array($upperCountryCode, $currency['countries'], true)) {
                $countryCurrencies[] = $code;
            }
        }

        return $countryCurrencies;
    }

    /**
     * Find currencies by symbol
     * @param string $symbol Currency symbol (e.g., '$', '€')
     * @return array<array> Array of currencies with the given symbol
     */
    public function getCurrencyBySymbol(string $symbol): array
    {
        $matchingCurrencies = [];

        foreach ($this->currencies as $currency) {
            if (($currency['symbol'] ?? '') === $symbol || 
                ($currency['symbol_native'] ?? '') === $symbol) {
                $matchingCurrencies[] = $currency;
            }
        }

        return $matchingCurrencies;
    }

    /**
     * Find currencies by decimal digits
     * @param int $digits Number of decimal digits
     * @return array<array> Array of currencies with the given decimal digits
     */
    public function getCurrenciesByDecimalDigits(int $digits): array
    {
        $matchingCurrencies = [];

        foreach ($this->currencies as $currency) {
            if (($currency['decimal_digits'] ?? -1) === $digits) {
                $matchingCurrencies[] = $currency;
            }
        }

        return $matchingCurrencies;
    }

    /**
     * Get currency codes
     * @return array<string> Array of all ISO currency codes
     */
    public function getCurrencyCodes(): array
    {
        return array_keys($this->currencies);
    }

    /**
     * Search currencies by name
     * @param string $searchTerm Search term to match against currency names
     * @return array<array> Array of currencies matching the search term
     */
    public function searchCurrencies(string $searchTerm): array
    {
        $lowerSearchTerm = strtolower($searchTerm);
        $matchingCurrencies = [];

        foreach ($this->currencies as $currency) {
            if (str_contains(strtolower($currency['name'] ?? ''), $lowerSearchTerm) ||
                str_contains(strtolower($currency['name_native'] ?? ''), $lowerSearchTerm) ||
                str_contains(strtolower($currency['name_plural'] ?? ''), $lowerSearchTerm) ||
                str_contains(strtolower($currency['name_plural_native'] ?? ''), $lowerSearchTerm)) {
                $matchingCurrencies[] = $currency;
            }
        }

        return $matchingCurrencies;
    }

    /**
     * Get currencies for multiple countries
     * @param array<string> $countryCodes Array of ISO 3166-1 alpha-2 country codes
     * @return array<string, array<string>> Array of currency codes keyed by country code
     */
    public function getCurrenciesForCountries(array $countryCodes): array
    {
        $result = [];

        foreach ($countryCodes as $countryCode) {
            $result[$countryCode] = $this->getCurrenciesByCountry($countryCode);
        }

        return $result;
    }

    /**
     * Check if a currency code exists
     * @param string $code ISO 4217 currency code
     * @return bool True if the currency exists
     */
    public function currencyExists(string $code): bool
    {
        return isset($this->currencies[strtoupper($code)]);
    }

    /**
     * Get currencies by ISO number
     * @param string $number ISO 4217 numeric code
     * @return array|null Currency array or null if not found
     */
    public function getCurrencyByIsoNumber(string $number): ?array
    {
        foreach ($this->currencies as $currency) {
            if (isset($currency['iso']['number']) && $currency['iso']['number'] === $number) {
                return $currency;
            }
        }

        return null;
    }
}
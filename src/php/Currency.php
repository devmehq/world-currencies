<?php

namespace DevMe\WorldCurrencies;

/**
 * Currency class for object-oriented access to currency data
 */
class Currency
{
    private string $code;
    private array $data;
    private Currencies $currencies;

    /**
     * Constructor
     * @param string $code ISO 4217 currency code
     * @throws \InvalidArgumentException If currency code is not found
     */
    public function __construct(string $code)
    {
        $this->currencies = new Currencies();
        $this->code = strtoupper($code);
        
        $currencyData = $this->currencies->getCurrency($this->code);
        if ($currencyData === null) {
            throw new \InvalidArgumentException("Currency code '{$code}' not found");
        }
        
        $this->data = $currencyData;
    }

    /**
     * Get currency code
     * @return string ISO 4217 currency code
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get currency name
     * @return string Currency name
     */
    public function getName(): string
    {
        return $this->data['name'] ?? '';
    }

    /**
     * Get native currency name
     * @return string Native currency name
     */
    public function getNameNative(): string
    {
        return $this->data['name_native'] ?? '';
    }

    /**
     * Get plural currency name
     * @return string Plural currency name
     */
    public function getNamePlural(): string
    {
        return $this->data['name_plural'] ?? '';
    }

    /**
     * Get native plural currency name
     * @return string Native plural currency name
     */
    public function getNamePluralNative(): string
    {
        return $this->data['name_plural_native'] ?? '';
    }

    /**
     * Get currency symbol
     * @return string Currency symbol
     */
    public function getSymbol(): string
    {
        return $this->data['symbol'] ?? '';
    }

    /**
     * Get native currency symbol
     * @return string Native currency symbol
     */
    public function getSymbolNative(): string
    {
        return $this->data['symbol_native'] ?? '';
    }

    /**
     * Get decimal digits
     * @return int Number of decimal digits
     */
    public function getDecimalDigits(): int
    {
        return $this->data['decimal_digits'] ?? 2;
    }

    /**
     * Get rounding
     * @return float Rounding value
     */
    public function getRounding(): float
    {
        return $this->data['rounding'] ?? 0;
    }

    /**
     * Get ISO data
     * @return array ISO code and number
     */
    public function getIso(): array
    {
        return $this->data['iso'] ?? ['code' => $this->code, 'number' => ''];
    }

    /**
     * Get ISO number
     * @return string ISO 4217 numeric code
     */
    public function getIsoNumber(): string
    {
        return $this->data['iso']['number'] ?? '';
    }

    /**
     * Get units information
     * @return array Units data
     */
    public function getUnits(): array
    {
        return $this->data['units'] ?? [];
    }

    /**
     * Get banknotes information
     * @return array Banknotes data
     */
    public function getBanknotes(): array
    {
        return $this->data['banknotes'] ?? ['frequent' => [], 'rare' => []];
    }

    /**
     * Get coins information
     * @return array Coins data
     */
    public function getCoins(): array
    {
        return $this->data['coins'] ?? ['frequent' => [], 'rare' => []];
    }

    /**
     * Get countries using this currency
     * @return array<string> Array of country codes
     */
    public function getCountries(): array
    {
        return $this->data['countries'] ?? [];
    }

    /**
     * Check if currency is used in a specific country
     * @param string $countryCode ISO 3166-1 alpha-2 country code
     * @return bool True if currency is used in the country
     */
    public function isUsedInCountry(string $countryCode): bool
    {
        return in_array(strtoupper($countryCode), $this->getCountries(), true);
    }

    /**
     * Format an amount in this currency
     * @param float $amount Amount to format
     * @param bool $useNativeSymbol Use native symbol instead of standard symbol
     * @return string Formatted amount
     */
    public function formatAmount(float $amount, bool $useNativeSymbol = false): string
    {
        $symbol = $useNativeSymbol ? $this->getSymbolNative() : $this->getSymbol();
        $decimals = $this->getDecimalDigits();
        
        return $symbol . ' ' . number_format($amount, $decimals);
    }

    /**
     * Get all currency data as array
     * @return array Complete currency data
     */
    public function toArray(): array
    {
        return array_merge(['code' => $this->code], $this->data);
    }

    /**
     * Magic getter for accessing currency properties
     * @param string $name Property name
     * @return mixed Property value
     * @throws \InvalidArgumentException If property doesn't exist
     */
    public function __get(string $name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        
        throw new \InvalidArgumentException("Property '{$name}' does not exist");
    }

    /**
     * Check if property exists
     * @param string $name Property name
     * @return bool True if property exists
     */
    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    /**
     * String representation of the currency
     * @return string Currency string representation
     */
    public function __toString(): string
    {
        return $this->getName() . ' (' . $this->code . ')';
    }
}
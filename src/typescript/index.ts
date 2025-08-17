import * as currenciesData from '../../data/world-currencies.json';

export interface Currency {
  name: string;
  nameNative: string;
  namePlural: string;
  namePluralNative: string;
  iso: {
    code: string;
    number: string;
  };
  symbol: string;
  symbolNative: string;
  decimalDigits: number;
  rounding: number;
  flagCode: string | null;
  units: {
    major: {
      name: string;
      symbol: string;
    };
    minor: {
      name: string;
      symbol: string;
      majorValue: number;
    };
  };
  banknotes: {
    frequent: string[];
    rare: string[];
  };
  coins: {
    frequent: string[];
    rare: string[];
  };
  countries: string[];
}

export type CurrenciesData = Record<string, Currency>;

const currencies: CurrenciesData = currenciesData as CurrenciesData;

/**
 * Get a specific currency by its ISO code
 * @param code - ISO 4217 currency code (e.g., 'USD', 'EUR')
 * @returns Currency object or null if not found
 */
export function getCurrency(code: string): Currency | null {
  return currencies[code.toUpperCase()] || null;
}

/**
 * Get all currencies
 * @returns Object containing all currencies keyed by ISO code
 */
export function getAllCurrencies(): CurrenciesData {
  return currencies;
}

/**
 * Get currencies used in a specific country
 * @param countryCode - ISO 3166-1 alpha-2 country code (e.g., 'US', 'GB')
 * @returns Array of currency codes used in the country
 */
export function getCurrenciesByCountry(countryCode: string): string[] {
  const countryCurrencies: string[] = [];
  const upperCountryCode = countryCode.toUpperCase();
  
  for (const [code, currency] of Object.entries(currencies)) {
    if (currency.countries.includes(upperCountryCode)) {
      countryCurrencies.push(code);
    }
  }
  
  return countryCurrencies;
}

/**
 * Find currencies by symbol
 * @param symbol - Currency symbol (e.g., '$', '€')
 * @returns Array of currencies with the given symbol
 */
export function getCurrencyBySymbol(symbol: string): Currency[] {
  const matchingCurrencies: Currency[] = [];
  
  for (const currency of Object.values(currencies)) {
    if (currency.symbol === symbol || currency.symbolNative === symbol) {
      matchingCurrencies.push(currency);
    }
  }
  
  return matchingCurrencies;
}

/**
 * Find currencies by decimal digits
 * @param digits - Number of decimal digits
 * @returns Array of currencies with the given decimal digits
 */
export function getCurrenciesByDecimalDigits(digits: number): Currency[] {
  const matchingCurrencies: Currency[] = [];
  
  for (const currency of Object.values(currencies)) {
    if (currency.decimalDigits === digits) {
      matchingCurrencies.push(currency);
    }
  }
  
  return matchingCurrencies;
}

/**
 * Get currency codes
 * @returns Array of all ISO currency codes
 */
export function getCurrencyCodes(): string[] {
  return Object.keys(currencies);
}

/**
 * Search currencies by name
 * @param searchTerm - Search term to match against currency names
 * @returns Array of currencies matching the search term
 */
export function searchCurrencies(searchTerm: string): Currency[] {
  const lowerSearchTerm = searchTerm.toLowerCase();
  const matchingCurrencies: Currency[] = [];
  
  for (const currency of Object.values(currencies)) {
    if (
      currency.name.toLowerCase().includes(lowerSearchTerm) ||
      currency.nameNative.toLowerCase().includes(lowerSearchTerm) ||
      currency.namePlural.toLowerCase().includes(lowerSearchTerm) ||
      currency.namePluralNative.toLowerCase().includes(lowerSearchTerm)
    ) {
      matchingCurrencies.push(currency);
    }
  }
  
  return matchingCurrencies;
}

// Default export
export default {
  getCurrency,
  getAllCurrencies,
  getCurrenciesByCountry,
  getCurrencyBySymbol,
  getCurrenciesByDecimalDigits,
  getCurrencyCodes,
  searchCurrencies,
  currencies
};
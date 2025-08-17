import {
  getCurrency,
  getAllCurrencies,
  getCurrenciesByCountry,
  getCurrencyBySymbol,
  getCurrenciesByDecimalDigits,
  getCurrencyCodes,
  searchCurrencies,
  Currency
} from '../../src/typescript';

describe('World Currencies Library', () => {
  describe('getCurrency', () => {
    test('should return USD currency data', () => {
      const usd = getCurrency('USD');
      expect(usd).toBeDefined();
      expect(usd?.name).toBe('US Dollar');
      expect(usd?.symbol).toBe('$');
      expect(usd?.decimal_digits).toBe(2);
    });

    test('should handle lowercase currency code', () => {
      const eur = getCurrency('eur');
      expect(eur).toBeDefined();
      expect(eur?.name).toBe('Euro');
    });

    test('should return null for invalid currency code', () => {
      const invalid = getCurrency('XXX');
      expect(invalid).toBeNull();
    });
  });

  describe('getAllCurrencies', () => {
    test('should return all currencies', () => {
      const currencies = getAllCurrencies();
      expect(currencies).toBeDefined();
      expect(Object.keys(currencies).length).toBeGreaterThan(150);
      expect(currencies['USD']).toBeDefined();
      expect(currencies['EUR']).toBeDefined();
    });
  });

  describe('getCurrenciesByCountry', () => {
    test('should return USD for United States', () => {
      const currencies = getCurrenciesByCountry('US');
      expect(currencies).toContain('USD');
    });

    test('should return EUR for Germany', () => {
      const currencies = getCurrenciesByCountry('DE');
      expect(currencies).toContain('EUR');
    });

    test('should handle lowercase country code', () => {
      const currencies = getCurrenciesByCountry('gb');
      expect(currencies).toContain('GBP');
    });

    test('should return empty array for invalid country', () => {
      const currencies = getCurrenciesByCountry('XX');
      expect(currencies).toEqual([]);
    });
  });

  describe('getCurrencyBySymbol', () => {
    test('should find currencies with $ symbol', () => {
      const currencies = getCurrencyBySymbol('$');
      expect(currencies.length).toBeGreaterThan(0);
      
      const currencyCodes = currencies.map(c => c.iso.code);
      expect(currencyCodes).toContain('USD');
    });

    test('should find EUR by € symbol', () => {
      const currencies = getCurrencyBySymbol('€');
      expect(currencies.length).toBeGreaterThan(0);
      expect(currencies[0].iso.code).toBe('EUR');
    });

    test('should return empty array for non-existent symbol', () => {
      const currencies = getCurrencyBySymbol('###');
      expect(currencies).toEqual([]);
    });
  });

  describe('getCurrenciesByDecimalDigits', () => {
    test('should find currencies with 0 decimal digits', () => {
      const currencies = getCurrenciesByDecimalDigits(0);
      expect(currencies.length).toBeGreaterThan(0);
      
      const jpyFound = currencies.some(c => c.iso.code === 'JPY');
      expect(jpyFound).toBe(true);
    });

    test('should find currencies with 2 decimal digits', () => {
      const currencies = getCurrenciesByDecimalDigits(2);
      expect(currencies.length).toBeGreaterThan(50);
      
      const usdFound = currencies.some(c => c.iso.code === 'USD');
      expect(usdFound).toBe(true);
    });

    test('should find currencies with 3 decimal digits', () => {
      const currencies = getCurrenciesByDecimalDigits(3);
      expect(currencies.length).toBeGreaterThan(0);
      
      const kwdFound = currencies.some(c => c.iso.code === 'KWD');
      expect(kwdFound).toBe(true);
    });
  });

  describe('getCurrencyCodes', () => {
    test('should return array of currency codes', () => {
      const codes = getCurrencyCodes();
      expect(Array.isArray(codes)).toBe(true);
      expect(codes.length).toBeGreaterThan(150);
      expect(codes).toContain('USD');
      expect(codes).toContain('EUR');
      expect(codes).toContain('GBP');
    });
  });

  describe('searchCurrencies', () => {
    test('should find currencies by name', () => {
      const currencies = searchCurrencies('dollar');
      expect(currencies.length).toBeGreaterThan(0);
      
      const usdFound = currencies.some(c => c.iso.code === 'USD');
      expect(usdFound).toBe(true);
    });

    test('should find currencies by native name', () => {
      const currencies = searchCurrencies('يورو');
      expect(currencies.length).toBeGreaterThan(0);
    });

    test('should be case insensitive', () => {
      const currencies1 = searchCurrencies('EURO');
      const currencies2 = searchCurrencies('euro');
      expect(currencies1.length).toBe(currencies2.length);
    });

    test('should return empty array for no matches', () => {
      const currencies = searchCurrencies('xyzabc123');
      expect(currencies).toEqual([]);
    });
  });

  describe('Currency data structure', () => {
    test('should have complete USD data', () => {
      const usd = getCurrency('USD');
      expect(usd).toBeDefined();
      
      // Check required fields
      expect(usd?.name).toBeDefined();
      expect(usd?.name_native).toBeDefined();
      expect(usd?.name_plural).toBeDefined();
      expect(usd?.name_plural_native).toBeDefined();
      expect(usd?.symbol).toBeDefined();
      expect(usd?.symbol_native).toBeDefined();
      expect(usd?.decimal_digits).toBeDefined();
      expect(usd?.rounding).toBeDefined();
      expect(usd?.countries).toBeDefined();
      expect(Array.isArray(usd?.countries)).toBe(true);
      
      // Check ISO data
      expect(usd?.iso).toBeDefined();
      expect(usd?.iso.code).toBe('USD');
      expect(usd?.iso.number).toBeDefined();
      
      // Check units
      expect(usd?.units).toBeDefined();
      expect(usd?.units.major).toBeDefined();
      expect(usd?.units.minor).toBeDefined();
      
      // Check banknotes and coins
      expect(usd?.banknotes).toBeDefined();
      expect(usd?.banknotes.frequent).toBeDefined();
      expect(usd?.banknotes.rare).toBeDefined();
      expect(usd?.coins).toBeDefined();
      expect(usd?.coins.frequent).toBeDefined();
      expect(usd?.coins.rare).toBeDefined();
    });
  });
});
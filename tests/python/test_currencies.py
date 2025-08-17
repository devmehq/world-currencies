import pytest
import sys
from pathlib import Path

# Add src/python to path
sys.path.insert(0, str(Path(__file__).parent.parent.parent / 'src' / 'python'))

from world_currencies import (
    get_currency,
    get_all_currencies,
    get_currencies_by_country,
    get_currency_by_symbol,
    get_currencies_by_decimal_digits,
    get_currency_codes,
    search_currencies,
    Currency
)


class TestGetCurrency:
    def test_get_usd_currency(self):
        usd = get_currency('USD')
        assert usd is not None
        assert usd['name'] == 'US Dollar'
        assert usd['symbol'] == '$'
        assert usd['decimal_digits'] == 2
    
    def test_handle_lowercase_code(self):
        eur = get_currency('eur')
        assert eur is not None
        assert eur['name'] == 'Euro'
    
    def test_return_none_for_invalid_code(self):
        invalid = get_currency('XXX')
        assert invalid is None


class TestGetAllCurrencies:
    def test_return_all_currencies(self):
        currencies = get_all_currencies()
        assert currencies is not None
        assert len(currencies) > 150
        assert 'USD' in currencies
        assert 'EUR' in currencies


class TestGetCurrenciesByCountry:
    def test_return_usd_for_us(self):
        currencies = get_currencies_by_country('US')
        assert 'USD' in currencies
    
    def test_return_eur_for_germany(self):
        currencies = get_currencies_by_country('DE')
        assert 'EUR' in currencies
    
    def test_handle_lowercase_country_code(self):
        currencies = get_currencies_by_country('gb')
        assert 'GBP' in currencies
    
    def test_return_empty_for_invalid_country(self):
        currencies = get_currencies_by_country('XX')
        assert currencies == []


class TestGetCurrencyBySymbol:
    def test_find_currencies_with_dollar_symbol(self):
        currencies = get_currency_by_symbol('$')
        assert len(currencies) > 0
        currency_codes = [c['iso']['code'] for c in currencies]
        assert 'USD' in currency_codes
    
    def test_find_eur_by_euro_symbol(self):
        currencies = get_currency_by_symbol('€')
        assert len(currencies) > 0
        assert currencies[0]['iso']['code'] == 'EUR'
    
    def test_return_empty_for_nonexistent_symbol(self):
        currencies = get_currency_by_symbol('###')
        assert currencies == []


class TestGetCurrenciesByDecimalDigits:
    def test_find_currencies_with_0_decimals(self):
        currencies = get_currencies_by_decimal_digits(0)
        assert len(currencies) > 0
        jpy_found = any(c['iso']['code'] == 'JPY' for c in currencies)
        assert jpy_found is True
    
    def test_find_currencies_with_2_decimals(self):
        currencies = get_currencies_by_decimal_digits(2)
        assert len(currencies) > 50
        usd_found = any(c['iso']['code'] == 'USD' for c in currencies)
        assert usd_found is True
    
    def test_find_currencies_with_3_decimals(self):
        currencies = get_currencies_by_decimal_digits(3)
        assert len(currencies) > 0
        kwd_found = any(c['iso']['code'] == 'KWD' for c in currencies)
        assert kwd_found is True


class TestGetCurrencyCodes:
    def test_return_array_of_currency_codes(self):
        codes = get_currency_codes()
        assert isinstance(codes, list)
        assert len(codes) > 150
        assert 'USD' in codes
        assert 'EUR' in codes
        assert 'GBP' in codes


class TestSearchCurrencies:
    def test_find_currencies_by_name(self):
        currencies = search_currencies('dollar')
        assert len(currencies) > 0
        usd_found = any(c['iso']['code'] == 'USD' for c in currencies)
        assert usd_found is True
    
    def test_find_currencies_by_native_name(self):
        currencies = search_currencies('Euro')
        assert len(currencies) > 0
    
    def test_case_insensitive_search(self):
        currencies1 = search_currencies('EURO')
        currencies2 = search_currencies('euro')
        assert len(currencies1) == len(currencies2)
    
    def test_return_empty_for_no_matches(self):
        currencies = search_currencies('xyzabc123')
        assert currencies == []


class TestCurrencyDataStructure:
    def test_complete_usd_data(self):
        usd = get_currency('USD')
        assert usd is not None
        
        # Check required fields
        assert 'name' in usd
        assert 'name_native' in usd
        assert 'name_plural' in usd
        assert 'name_plural_native' in usd
        assert 'symbol' in usd
        assert 'symbol_native' in usd
        assert 'decimal_digits' in usd
        assert 'rounding' in usd
        assert 'countries' in usd
        assert isinstance(usd['countries'], list)
        
        # Check ISO data
        assert 'iso' in usd
        assert usd['iso']['code'] == 'USD'
        assert 'number' in usd['iso']
        
        # Check units
        assert 'units' in usd
        assert 'major' in usd['units']
        assert 'minor' in usd['units']
        
        # Check banknotes and coins
        assert 'banknotes' in usd
        assert 'frequent' in usd['banknotes']
        assert 'rare' in usd['banknotes']
        assert 'coins' in usd
        assert 'frequent' in usd['coins']
        assert 'rare' in usd['coins']


class TestCurrencyClass:
    def test_create_currency_object(self):
        currency = Currency('USD')
        assert currency.code == 'USD'
        assert currency.name == 'US Dollar'
        assert currency.symbol == '$'
    
    def test_currency_attributes(self):
        currency = Currency('EUR')
        assert currency.name_native == 'Euro'
        assert currency.decimal_digits == 2
        assert isinstance(currency.countries, list)
    
    def test_currency_string_representation(self):
        currency = Currency('GBP')
        assert str(currency) == 'British Pound (GBP)'
        assert 'GBP' in repr(currency)
    
    def test_currency_to_dict(self):
        currency = Currency('JPY')
        data = currency.to_dict()
        assert data['code'] == 'JPY'
        assert data['name'] == 'Japanese Yen'
    
    def test_invalid_currency_raises_error(self):
        with pytest.raises(ValueError, match="Currency code 'XXX' not found"):
            Currency('XXX')
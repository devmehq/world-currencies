"""
World Currencies Library
A comprehensive library providing currency information for all world currencies
"""

import json
import os
from typing import Dict, List, Optional, Any
from pathlib import Path

__version__ = "1.0.0"
__all__ = [
    'get_currency',
    'get_all_currencies',
    'get_currencies_by_country',
    'get_currency_by_symbol',
    'get_currencies_by_decimal_digits',
    'get_currency_codes',
    'search_currencies',
    'currencies'
]

# Load currency data
_data_path = Path(__file__).parent.parent.parent.parent / 'data' / 'world-currencies.json'
with open(_data_path, 'r', encoding='utf-8') as f:
    currencies: Dict[str, Dict[str, Any]] = json.load(f)


def get_currency(code: str) -> Optional[Dict[str, Any]]:
    """
    Get a specific currency by its ISO code
    
    Args:
        code: ISO 4217 currency code (e.g., 'USD', 'EUR')
    
    Returns:
        Currency dictionary or None if not found
    """
    return currencies.get(code.upper())


def get_all_currencies() -> Dict[str, Dict[str, Any]]:
    """
    Get all currencies
    
    Returns:
        Dictionary containing all currencies keyed by ISO code
    """
    return currencies


def get_currencies_by_country(country_code: str) -> List[str]:
    """
    Get currencies used in a specific country
    
    Args:
        country_code: ISO 3166-1 alpha-2 country code (e.g., 'US', 'GB')
    
    Returns:
        List of currency codes used in the country
    """
    country_currencies = []
    upper_country_code = country_code.upper()
    
    for code, currency in currencies.items():
        if upper_country_code in currency.get('countries', []):
            country_currencies.append(code)
    
    return country_currencies


def get_currency_by_symbol(symbol: str) -> List[Dict[str, Any]]:
    """
    Find currencies by symbol
    
    Args:
        symbol: Currency symbol (e.g., '$', '€')
    
    Returns:
        List of currencies with the given symbol
    """
    matching_currencies = []
    
    for currency in currencies.values():
        if currency.get('symbol') == symbol or currency.get('symbolNative') == symbol:
            matching_currencies.append(currency)
    
    return matching_currencies


def get_currencies_by_decimal_digits(digits: int) -> List[Dict[str, Any]]:
    """
    Find currencies by decimal digits
    
    Args:
        digits: Number of decimal digits
    
    Returns:
        List of currencies with the given decimal digits
    """
    matching_currencies = []
    
    for currency in currencies.values():
        if currency.get('decimalDigits') == digits:
            matching_currencies.append(currency)
    
    return matching_currencies


def get_currency_codes() -> List[str]:
    """
    Get currency codes
    
    Returns:
        List of all ISO currency codes
    """
    return list(currencies.keys())


def search_currencies(search_term: str) -> List[Dict[str, Any]]:
    """
    Search currencies by name
    
    Args:
        search_term: Search term to match against currency names
    
    Returns:
        List of currencies matching the search term
    """
    lower_search_term = search_term.lower()
    matching_currencies = []
    
    for currency in currencies.values():
        if (lower_search_term in currency.get('name', '').lower() or
            lower_search_term in currency.get('nameNative', '').lower() or
            lower_search_term in currency.get('namePlural', '').lower() or
            lower_search_term in currency.get('namePluralNative', '').lower()):
            matching_currencies.append(currency)
    
    return matching_currencies


class Currency:
    """Currency class for object-oriented access"""
    
    def __init__(self, code: str):
        """
        Initialize a Currency object
        
        Args:
            code: ISO 4217 currency code
        
        Raises:
            ValueError: If currency code is not found
        """
        currency_data = get_currency(code)
        if not currency_data:
            raise ValueError(f"Currency code '{code}' not found")
        
        self.code = code.upper()
        self._data = currency_data
    
    def __getattr__(self, name: str) -> Any:
        """Get currency attribute"""
        if name in self._data:
            return self._data[name]
        raise AttributeError(f"Currency has no attribute '{name}'")
    
    def __repr__(self) -> str:
        """String representation"""
        return f"Currency(code='{self.code}', name='{self._data.get('name')}')"
    
    def __str__(self) -> str:
        """String conversion"""
        return f"{self._data.get('name')} ({self.code})"
    
    def to_dict(self) -> Dict[str, Any]:
        """Convert to dictionary"""
        return {**self._data, 'code': self.code}
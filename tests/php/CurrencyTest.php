<?php

namespace DevMe\WorldCurrencies\Tests;

use PHPUnit\Framework\TestCase;
use DevMe\WorldCurrencies\Currency;
use InvalidArgumentException;

class CurrencyTest extends TestCase
{
    public function testConstructor(): void
    {
        $currency = new Currency('USD');
        $this->assertEquals('USD', $currency->getCode());
        $this->assertEquals('US Dollar', $currency->getName());
        $this->assertEquals('$', $currency->getSymbol());
    }

    public function testConstructorWithLowercase(): void
    {
        $currency = new Currency('eur');
        $this->assertEquals('EUR', $currency->getCode());
        $this->assertEquals('Euro', $currency->getName());
    }

    public function testConstructorThrowsExceptionForInvalidCode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Currency code 'XXX' not found");
        new Currency('XXX');
    }

    public function testGetters(): void
    {
        $currency = new Currency('GBP');
        
        $this->assertEquals('GBP', $currency->getCode());
        $this->assertEquals('British Pound', $currency->getName());
        $this->assertEquals('British Pound', $currency->getNameNative());
        $this->assertEquals('British pounds', $currency->getNamePlural());
        $this->assertEquals('British Pounds', $currency->getNamePluralNative());
        $this->assertEquals('£', $currency->getSymbol());
        $this->assertEquals('£', $currency->getSymbolNative());
        $this->assertEquals(2, $currency->getDecimalDigits());
        $this->assertEquals(0, $currency->getRounding());
        
        $iso = $currency->getIso();
        $this->assertEquals('GBP', $iso['code']);
        $this->assertNotEmpty($currency->getIsoNumber());
        
        $this->assertIsArray($currency->getUnits());
        $this->assertIsArray($currency->getBanknotes());
        $this->assertIsArray($currency->getCoins());
        $this->assertIsArray($currency->getCountries());
    }

    public function testIsUsedInCountry(): void
    {
        $currency = new Currency('EUR');
        
        $this->assertTrue($currency->isUsedInCountry('DE'));
        $this->assertTrue($currency->isUsedInCountry('FR'));
        $this->assertTrue($currency->isUsedInCountry('it')); // Test lowercase
        $this->assertFalse($currency->isUsedInCountry('US'));
        $this->assertFalse($currency->isUsedInCountry('GB'));
    }

    public function testFormatAmount(): void
    {
        $currency = new Currency('USD');
        $formatted = $currency->formatAmount(1234.56);
        $this->assertEquals('$ 1,234.56', $formatted);
        
        $formattedNative = $currency->formatAmount(1234.56, true);
        $this->assertEquals('$ 1,234.56', $formattedNative);
        
        // Test currency with 0 decimals
        $jpy = new Currency('JPY');
        $formattedJpy = $jpy->formatAmount(1234.56);
        $this->assertEquals('¥ 1,235', $formattedJpy);
        
        // Test currency with 3 decimals
        $kwd = new Currency('KWD');
        $formattedKwd = $kwd->formatAmount(1234.567);
        $this->assertEquals('KD 1,234.567', $formattedKwd);
    }

    public function testToArray(): void
    {
        $currency = new Currency('CHF');
        $array = $currency->toArray();
        
        $this->assertIsArray($array);
        $this->assertEquals('CHF', $array['code']);
        $this->assertEquals('Swiss Franc', $array['name']);
        $this->assertEquals('CHF', $array['symbol']);
    }

    public function testMagicGetter(): void
    {
        $currency = new Currency('AUD');
        
        $this->assertEquals('Australian Dollar', $currency->name);
        $this->assertEquals('A$', $currency->symbol);
        $this->assertEquals(2, $currency->decimal_digits);
        $this->assertIsArray($currency->countries);
    }

    public function testMagicGetterThrowsExceptionForInvalidProperty(): void
    {
        $currency = new Currency('CAD');
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property 'nonexistent' does not exist");
        $currency->nonexistent;
    }

    public function testIsset(): void
    {
        $currency = new Currency('INR');
        
        $this->assertTrue(isset($currency->name));
        $this->assertTrue(isset($currency->symbol));
        $this->assertTrue(isset($currency->decimal_digits));
        $this->assertFalse(isset($currency->nonexistent));
    }

    public function testToString(): void
    {
        $currency = new Currency('JPY');
        $this->assertEquals('Japanese Yen (JPY)', (string)$currency);
        
        $currency2 = new Currency('SEK');
        $this->assertEquals('Swedish Krona (SEK)', (string)$currency2);
    }

    public function testCompleteDataStructure(): void
    {
        $currency = new Currency('USD');
        
        // Test all data is accessible
        $this->assertNotEmpty($currency->getName());
        $this->assertNotEmpty($currency->getNameNative());
        $this->assertNotEmpty($currency->getNamePlural());
        $this->assertNotEmpty($currency->getNamePluralNative());
        $this->assertNotEmpty($currency->getSymbol());
        $this->assertNotEmpty($currency->getSymbolNative());
        $this->assertIsInt($currency->getDecimalDigits());
        $this->assertIsNumeric($currency->getRounding());
        
        // Test flag code
        $flagCode = $currency->getFlagCode();
        $this->assertTrue($flagCode === null || is_string($flagCode));
        
        $units = $currency->getUnits();
        $this->assertArrayHasKey('major', $units);
        $this->assertArrayHasKey('minor', $units);
        
        $banknotes = $currency->getBanknotes();
        $this->assertArrayHasKey('frequent', $banknotes);
        $this->assertArrayHasKey('rare', $banknotes);
        
        $coins = $currency->getCoins();
        $this->assertArrayHasKey('frequent', $coins);
        $this->assertArrayHasKey('rare', $coins);
        
        $this->assertNotEmpty($currency->getCountries());
    }
}
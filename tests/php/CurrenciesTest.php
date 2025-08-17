<?php

namespace DevMe\WorldCurrencies\Tests;

use PHPUnit\Framework\TestCase;
use DevMe\WorldCurrencies\Currencies;
use DevMe\WorldCurrencies\Currency;

class CurrenciesTest extends TestCase
{
    private Currencies $currencies;

    protected function setUp(): void
    {
        $this->currencies = new Currencies();
    }

    public function testGetCurrency(): void
    {
        // Test USD currency
        $usd = $this->currencies->getCurrency('USD');
        $this->assertNotNull($usd);
        $this->assertEquals('US Dollar', $usd['name']);
        $this->assertEquals('$', $usd['symbol']);
        $this->assertEquals(2, $usd['decimalDigits']);

        // Test lowercase code
        $eur = $this->currencies->getCurrency('eur');
        $this->assertNotNull($eur);
        $this->assertEquals('Euro', $eur['name']);

        // Test invalid code
        $invalid = $this->currencies->getCurrency('XXX');
        $this->assertNull($invalid);
    }

    public function testGetAllCurrencies(): void
    {
        $allCurrencies = $this->currencies->getAllCurrencies();
        $this->assertIsArray($allCurrencies);
        $this->assertGreaterThan(150, count($allCurrencies));
        $this->assertArrayHasKey('USD', $allCurrencies);
        $this->assertArrayHasKey('EUR', $allCurrencies);
    }

    public function testGetCurrenciesByCountry(): void
    {
        // Test US
        $usCurrencies = $this->currencies->getCurrenciesByCountry('US');
        $this->assertContains('USD', $usCurrencies);

        // Test Germany
        $deCurrencies = $this->currencies->getCurrenciesByCountry('DE');
        $this->assertContains('EUR', $deCurrencies);

        // Test lowercase
        $gbCurrencies = $this->currencies->getCurrenciesByCountry('gb');
        $this->assertContains('GBP', $gbCurrencies);

        // Test invalid country
        $invalidCurrencies = $this->currencies->getCurrenciesByCountry('XX');
        $this->assertEmpty($invalidCurrencies);
    }

    public function testGetCurrencyBySymbol(): void
    {
        // Test $ symbol
        $dollarCurrencies = $this->currencies->getCurrencyBySymbol('$');
        $this->assertNotEmpty($dollarCurrencies);
        
        $currencyCodes = array_map(fn($c) => $c['iso']['code'], $dollarCurrencies);
        $this->assertContains('USD', $currencyCodes);

        // Test € symbol
        $euroCurrencies = $this->currencies->getCurrencyBySymbol('€');
        $this->assertNotEmpty($euroCurrencies);
        $this->assertEquals('EUR', $euroCurrencies[0]['iso']['code']);

        // Test non-existent symbol
        $invalidCurrencies = $this->currencies->getCurrencyBySymbol('###');
        $this->assertEmpty($invalidCurrencies);
    }

    public function testGetCurrenciesByDecimalDigits(): void
    {
        // Test 0 decimals
        $zeroDecimals = $this->currencies->getCurrenciesByDecimalDigits(0);
        $this->assertNotEmpty($zeroDecimals);
        
        $jpyFound = false;
        foreach ($zeroDecimals as $currency) {
            if ($currency['iso']['code'] === 'JPY') {
                $jpyFound = true;
                break;
            }
        }
        $this->assertTrue($jpyFound);

        // Test 2 decimals
        $twoDecimals = $this->currencies->getCurrenciesByDecimalDigits(2);
        $this->assertGreaterThan(50, count($twoDecimals));
        
        $usdFound = false;
        foreach ($twoDecimals as $currency) {
            if ($currency['iso']['code'] === 'USD') {
                $usdFound = true;
                break;
            }
        }
        $this->assertTrue($usdFound);

        // Test 3 decimals
        $threeDecimals = $this->currencies->getCurrenciesByDecimalDigits(3);
        $this->assertNotEmpty($threeDecimals);
        
        $kwdFound = false;
        foreach ($threeDecimals as $currency) {
            if ($currency['iso']['code'] === 'KWD') {
                $kwdFound = true;
                break;
            }
        }
        $this->assertTrue($kwdFound);
    }

    public function testGetCurrencyCodes(): void
    {
        $codes = $this->currencies->getCurrencyCodes();
        $this->assertIsArray($codes);
        $this->assertGreaterThan(150, count($codes));
        $this->assertContains('USD', $codes);
        $this->assertContains('EUR', $codes);
        $this->assertContains('GBP', $codes);
    }

    public function testSearchCurrencies(): void
    {
        // Test search by name
        $dollarCurrencies = $this->currencies->searchCurrencies('dollar');
        $this->assertNotEmpty($dollarCurrencies);
        
        $usdFound = false;
        foreach ($dollarCurrencies as $currency) {
            if ($currency['iso']['code'] === 'USD') {
                $usdFound = true;
                break;
            }
        }
        $this->assertTrue($usdFound);

        // Test case insensitive
        $euro1 = $this->currencies->searchCurrencies('EURO');
        $euro2 = $this->currencies->searchCurrencies('euro');
        $this->assertEquals(count($euro1), count($euro2));

        // Test no matches
        $noMatches = $this->currencies->searchCurrencies('xyzabc123');
        $this->assertEmpty($noMatches);
    }

    public function testGetCurrenciesForCountries(): void
    {
        $countryCurrencies = $this->currencies->getCurrenciesForCountries(['US', 'GB', 'DE']);
        
        $this->assertArrayHasKey('US', $countryCurrencies);
        $this->assertArrayHasKey('GB', $countryCurrencies);
        $this->assertArrayHasKey('DE', $countryCurrencies);
        
        $this->assertContains('USD', $countryCurrencies['US']);
        $this->assertContains('GBP', $countryCurrencies['GB']);
        $this->assertContains('EUR', $countryCurrencies['DE']);
    }

    public function testCurrencyExists(): void
    {
        $this->assertTrue($this->currencies->currencyExists('USD'));
        $this->assertTrue($this->currencies->currencyExists('eur')); // Test lowercase
        $this->assertFalse($this->currencies->currencyExists('XXX'));
    }

    public function testGetCurrencyByIsoNumber(): void
    {
        $usd = $this->currencies->getCurrencyByIsoNumber('840');
        $this->assertNotNull($usd);
        $this->assertEquals('USD', $usd['iso']['code']);

        $eur = $this->currencies->getCurrencyByIsoNumber('978');
        $this->assertNotNull($eur);
        $this->assertEquals('EUR', $eur['iso']['code']);

        $invalid = $this->currencies->getCurrencyByIsoNumber('999');
        $this->assertNull($invalid);
    }

    public function testCurrencyDataStructure(): void
    {
        $usd = $this->currencies->getCurrency('USD');
        $this->assertNotNull($usd);

        // Check required fields
        $this->assertArrayHasKey('name', $usd);
        $this->assertArrayHasKey('nameNative', $usd);
        $this->assertArrayHasKey('namePlural', $usd);
        $this->assertArrayHasKey('namePluralNative', $usd);
        $this->assertArrayHasKey('symbol', $usd);
        $this->assertArrayHasKey('symbolNative', $usd);
        $this->assertArrayHasKey('decimalDigits', $usd);
        $this->assertArrayHasKey('rounding', $usd);
        $this->assertArrayHasKey('countries', $usd);
        $this->assertIsArray($usd['countries']);

        // Check ISO data
        $this->assertArrayHasKey('iso', $usd);
        $this->assertEquals('USD', $usd['iso']['code']);
        $this->assertArrayHasKey('number', $usd['iso']);

        // Check units
        $this->assertArrayHasKey('units', $usd);
        $this->assertArrayHasKey('major', $usd['units']);
        $this->assertArrayHasKey('minor', $usd['units']);

        // Check banknotes and coins
        $this->assertArrayHasKey('banknotes', $usd);
        $this->assertArrayHasKey('frequent', $usd['banknotes']);
        $this->assertArrayHasKey('rare', $usd['banknotes']);
        $this->assertArrayHasKey('coins', $usd);
        $this->assertArrayHasKey('frequent', $usd['coins']);
        $this->assertArrayHasKey('rare', $usd['coins']);
    }
}
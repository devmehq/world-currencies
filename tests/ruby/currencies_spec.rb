# frozen_string_literal: true

require 'rspec'
require_relative '../../src/ruby/world_currencies'

RSpec.describe WorldCurrencies do
  describe '.get_currency' do
    it 'returns USD currency data' do
      usd = WorldCurrencies.get_currency('USD')
      expect(usd).not_to be_nil
      expect(usd['name']).to eq('US Dollar')
      expect(usd['symbol']).to eq('$')
      expect(usd['decimalDigits']).to eq(2)
    end

    it 'handles lowercase currency code' do
      eur = WorldCurrencies.get_currency('eur')
      expect(eur).not_to be_nil
      expect(eur['name']).to eq('Euro')
    end

    it 'returns nil for invalid currency code' do
      invalid = WorldCurrencies.get_currency('XXX')
      expect(invalid).to be_nil
    end
  end

  describe '.get_all_currencies' do
    it 'returns all currencies' do
      currencies = WorldCurrencies.get_all_currencies
      expect(currencies).not_to be_nil
      expect(currencies.keys.length).to be > 150
      expect(currencies).to have_key('USD')
      expect(currencies).to have_key('EUR')
    end
  end

  describe '.get_currencies_by_country' do
    it 'returns USD for United States' do
      currencies = WorldCurrencies.get_currencies_by_country('US')
      expect(currencies).to include('USD')
    end

    it 'returns EUR for Germany' do
      currencies = WorldCurrencies.get_currencies_by_country('DE')
      expect(currencies).to include('EUR')
    end

    it 'handles lowercase country code' do
      currencies = WorldCurrencies.get_currencies_by_country('gb')
      expect(currencies).to include('GBP')
    end

    it 'returns empty array for invalid country' do
      currencies = WorldCurrencies.get_currencies_by_country('XX')
      expect(currencies).to eq([])
    end
  end

  describe '.get_currency_by_symbol' do
    it 'finds currencies with $ symbol' do
      currencies = WorldCurrencies.get_currency_by_symbol('$')
      expect(currencies.length).to be > 0
      
      currency_codes = currencies.map { |c| c['iso']['code'] }
      expect(currency_codes).to include('USD')
    end

    it 'finds EUR by € symbol' do
      currencies = WorldCurrencies.get_currency_by_symbol('€')
      expect(currencies.length).to be > 0
      expect(currencies[0]['iso']['code']).to eq('EUR')
    end

    it 'returns empty array for non-existent symbol' do
      currencies = WorldCurrencies.get_currency_by_symbol('###')
      expect(currencies).to eq([])
    end
  end

  describe '.get_currencies_by_decimal_digits' do
    it 'finds currencies with 0 decimal digits' do
      currencies = WorldCurrencies.get_currencies_by_decimal_digits(0)
      expect(currencies.length).to be > 0
      
      jpy_found = currencies.any? { |c| c['iso']['code'] == 'JPY' }
      expect(jpy_found).to be true
    end

    it 'finds currencies with 2 decimal digits' do
      currencies = WorldCurrencies.get_currencies_by_decimal_digits(2)
      expect(currencies.length).to be > 50
      
      usd_found = currencies.any? { |c| c['iso']['code'] == 'USD' }
      expect(usd_found).to be true
    end

    it 'finds currencies with 3 decimal digits' do
      currencies = WorldCurrencies.get_currencies_by_decimal_digits(3)
      expect(currencies.length).to be > 0
      
      kwd_found = currencies.any? { |c| c['iso']['code'] == 'KWD' }
      expect(kwd_found).to be true
    end
  end

  describe '.get_currency_codes' do
    it 'returns array of currency codes' do
      codes = WorldCurrencies.get_currency_codes
      expect(codes).to be_a(Array)
      expect(codes.length).to be > 150
      expect(codes).to include('USD')
      expect(codes).to include('EUR')
      expect(codes).to include('GBP')
    end
  end

  describe '.search_currencies' do
    it 'finds currencies by name' do
      currencies = WorldCurrencies.search_currencies('dollar')
      expect(currencies.length).to be > 0
      
      usd_found = currencies.any? { |c| c['iso']['code'] == 'USD' }
      expect(usd_found).to be true
    end

    it 'finds currencies by native name' do
      currencies = WorldCurrencies.search_currencies('Euro')
      expect(currencies.length).to be > 0
    end

    it 'is case insensitive' do
      currencies1 = WorldCurrencies.search_currencies('EURO')
      currencies2 = WorldCurrencies.search_currencies('euro')
      expect(currencies1.length).to eq(currencies2.length)
    end

    it 'returns empty array for no matches' do
      currencies = WorldCurrencies.search_currencies('xyzabc123')
      expect(currencies).to eq([])
    end
  end

  describe 'Currency data structure' do
    it 'has complete USD data' do
      usd = WorldCurrencies.get_currency('USD')
      expect(usd).not_to be_nil
      
      # Check required fields
      expect(usd['name']).not_to be_nil
      expect(usd['nameNative']).not_to be_nil
      expect(usd['namePlural']).not_to be_nil
      expect(usd['namePluralNative']).not_to be_nil
      expect(usd['symbol']).not_to be_nil
      expect(usd['symbolNative']).not_to be_nil
      expect(usd['decimalDigits']).not_to be_nil
      expect(usd['rounding']).not_to be_nil
      expect(usd['countries']).to be_a(Array)
      
      # Check ISO data
      expect(usd['iso']).not_to be_nil
      expect(usd['iso']['code']).to eq('USD')
      expect(usd['iso']['number']).not_to be_nil
      
      # Check units
      expect(usd['units']).not_to be_nil
      expect(usd['units']['major']).not_to be_nil
      expect(usd['units']['minor']).not_to be_nil
      
      # Check banknotes and coins
      expect(usd['banknotes']).not_to be_nil
      expect(usd['banknotes']['frequent']).to be_a(Array)
      expect(usd['banknotes']['rare']).to be_a(Array)
      expect(usd['coins']).not_to be_nil
      expect(usd['coins']['frequent']).to be_a(Array)
      expect(usd['coins']['rare']).to be_a(Array)
    end
  end
end

RSpec.describe WorldCurrencies::Currency do
  describe '#initialize' do
    it 'creates a currency object' do
      currency = WorldCurrencies::Currency.new('USD')
      expect(currency.code).to eq('USD')
      expect(currency.name).to eq('US Dollar')
      expect(currency.symbol).to eq('$')
    end

    it 'handles lowercase code' do
      currency = WorldCurrencies::Currency.new('eur')
      expect(currency.code).to eq('EUR')
      expect(currency.name).to eq('Euro')
    end

    it 'raises error for invalid currency code' do
      expect { WorldCurrencies::Currency.new('XXX') }.to raise_error(ArgumentError, /Currency code 'XXX' not found/)
    end
  end

  describe 'attribute access' do
    let(:currency) { WorldCurrencies::Currency.new('GBP') }

    it 'provides access to currency attributes' do
      expect(currency.nameNative).to eq('British Pound')
      expect(currency.decimalDigits).to eq(2)
      expect(currency.countries).to be_a(Array)
    end

    it 'responds to attribute methods' do
      expect(currency.respond_to?(:name)).to be true
      expect(currency.respond_to?(:symbol)).to be true
      expect(currency.respond_to?(:nonexistent)).to be false
    end
  end

  describe 'string representations' do
    let(:currency) { WorldCurrencies::Currency.new('JPY') }

    it 'has a string representation' do
      expect(currency.to_s).to eq('Japanese Yen (JPY)')
    end

    it 'has an inspect representation' do
      expect(currency.inspect).to include('JPY')
      expect(currency.inspect).to include('Japanese Yen')
    end
  end

  describe '#to_h' do
    let(:currency) { WorldCurrencies::Currency.new('CHF') }

    it 'converts to hash' do
      hash = currency.to_h
      expect(hash['code']).to eq('CHF')
      expect(hash['name']).to eq('Swiss Franc')
      expect(hash['symbol']).to eq('CHF')
    end

    it 'supports to_hash alias' do
      hash = currency.to_hash
      expect(hash['code']).to eq('CHF')
    end
  end
end
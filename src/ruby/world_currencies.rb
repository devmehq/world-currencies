# frozen_string_literal: true

require 'json'
require 'pathname'

# WorldCurrencies module provides comprehensive currency information
module WorldCurrencies
  VERSION = '1.0.0'

  class << self
    # Load currency data
    def currencies
      @currencies ||= load_currencies
    end

    # Get a specific currency by its ISO code
    # @param code [String] ISO 4217 currency code (e.g., 'USD', 'EUR')
    # @return [Hash, nil] Currency hash or nil if not found
    def get_currency(code)
      currencies[code.to_s.upcase]
    end

    # Get all currencies
    # @return [Hash] Hash containing all currencies keyed by ISO code
    def get_all_currencies
      currencies
    end

    # Get currencies used in a specific country
    # @param country_code [String] ISO 3166-1 alpha-2 country code (e.g., 'US', 'GB')
    # @return [Array<String>] Array of currency codes used in the country
    def get_currencies_by_country(country_code)
      country_currencies = []
      upper_country_code = country_code.to_s.upcase

      currencies.each do |code, currency|
        if currency['countries']&.include?(upper_country_code)
          country_currencies << code
        end
      end

      country_currencies
    end

    # Find currencies by symbol
    # @param symbol [String] Currency symbol (e.g., '$', '€')
    # @return [Array<Hash>] Array of currencies with the given symbol
    def get_currency_by_symbol(symbol)
      matching_currencies = []

      currencies.each_value do |currency|
        if currency['symbol'] == symbol || currency['symbol_native'] == symbol
          matching_currencies << currency
        end
      end

      matching_currencies
    end

    # Find currencies by decimal digits
    # @param digits [Integer] Number of decimal digits
    # @return [Array<Hash>] Array of currencies with the given decimal digits
    def get_currencies_by_decimal_digits(digits)
      matching_currencies = []

      currencies.each_value do |currency|
        if currency['decimal_digits'] == digits
          matching_currencies << currency
        end
      end

      matching_currencies
    end

    # Get currency codes
    # @return [Array<String>] Array of all ISO currency codes
    def get_currency_codes
      currencies.keys
    end

    # Search currencies by name
    # @param search_term [String] Search term to match against currency names
    # @return [Array<Hash>] Array of currencies matching the search term
    def search_currencies(search_term)
      lower_search_term = search_term.to_s.downcase
      matching_currencies = []

      currencies.each_value do |currency|
        if currency['name']&.downcase&.include?(lower_search_term) ||
           currency['name_native']&.downcase&.include?(lower_search_term) ||
           currency['name_plural']&.downcase&.include?(lower_search_term) ||
           currency['name_plural_native']&.downcase&.include?(lower_search_term)
          matching_currencies << currency
        end
      end

      matching_currencies
    end

    private

    def load_currencies
      data_path = Pathname.new(__dir__).parent.join('data', 'world-currencies.json')
      JSON.parse(File.read(data_path))
    end
  end

  # Currency class for object-oriented access
  class Currency
    attr_reader :code

    # Initialize a Currency object
    # @param code [String] ISO 4217 currency code
    # @raise [ArgumentError] If currency code is not found
    def initialize(code)
      @code = code.to_s.upcase
      @data = WorldCurrencies.get_currency(@code)
      
      raise ArgumentError, "Currency code '#{code}' not found" unless @data
    end

    # Get currency attribute
    def method_missing(method_name, *args, &block)
      if @data.key?(method_name.to_s)
        @data[method_name.to_s]
      else
        super
      end
    end

    # Check if method exists
    def respond_to_missing?(method_name, include_private = false)
      @data.key?(method_name.to_s) || super
    end

    # String representation
    def to_s
      "#{@data['name']} (#{@code})"
    end

    # Inspect representation
    def inspect
      "#<WorldCurrencies::Currency code=\"#{@code}\" name=\"#{@data['name']}\">"
    end

    # Convert to hash
    def to_h
      @data.merge('code' => @code)
    end
    alias to_hash to_h
  end
end
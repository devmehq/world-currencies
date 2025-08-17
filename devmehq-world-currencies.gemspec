# frozen_string_literal: true

Gem::Specification.new do |spec|
  spec.name          = 'devmehq-world-currencies'
  spec.version       = '1.0.0'
  spec.authors       = ['DevMe']
  spec.email         = ['support@dev.me']

  spec.summary       = 'A comprehensive library providing currency information for all world currencies'
  spec.description   = 'Access detailed information about world currencies including ISO codes, symbols, native names, and more. Part of the DevMe ecosystem.'
  spec.homepage      = 'https://github.com/devmehq/world-currencies'
  spec.license       = 'MIT'

  spec.required_ruby_version = '>= 2.7.0'

  spec.metadata['allowed_push_host'] = 'https://rubygems.org'
  spec.metadata['homepage_uri'] = spec.homepage
  spec.metadata['source_code_uri'] = 'https://github.com/devmehq/world-currencies'
  spec.metadata['changelog_uri'] = 'https://github.com/devmehq/world-currencies/blob/main/CHANGELOG.md'
  spec.metadata['bug_tracker_uri'] = 'https://github.com/devmehq/world-currencies/issues'
  spec.metadata['documentation_uri'] = 'https://github.com/devmehq/world-currencies#readme'
  spec.metadata['funding_uri'] = 'https://github.com/sponsors/devmehq'

  # Specify which files should be added to the gem when it is released.
  # The `git ls-files -z` loads the files in the RubyGem that have been added into git.
  spec.files = Dir.chdir(File.expand_path(__dir__)) do
    `git ls-files -z`.split("\x0").reject do |f|
      (f == __FILE__) || f.match(%r{\A(?:(?:test|spec|features)/|\.(?:git|travis|circleci)|appveyor)})
    end
  end
  spec.bindir        = 'exe'
  spec.executables   = spec.files.grep(%r{\Aexe/}) { |f| File.basename(f) }
  spec.require_paths = ['src/ruby']

  # Runtime dependencies
  spec.add_runtime_dependency 'json', '>= 2.0'

  # Development dependencies
  spec.add_development_dependency 'bundler', '~> 2.0'
  spec.add_development_dependency 'rake', '~> 13.0'
  spec.add_development_dependency 'rspec', '~> 3.12'
  spec.add_development_dependency 'rubocop', '~> 1.50'
  spec.add_development_dependency 'rubocop-rspec', '~> 2.20'
  spec.add_development_dependency 'simplecov', '~> 0.22'
  spec.add_development_dependency 'yard', '~> 0.9'
end
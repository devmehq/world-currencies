module.exports = {
  preset: 'ts-jest',
  testEnvironment: 'node',
  roots: ['<rootDir>/tests/typescript'],
  testMatch: ['**/*.test.ts'],
  collectCoverageFrom: [
    'src/typescript/**/*.ts',
    '!src/typescript/**/*.d.ts',
  ],
  coverageDirectory: 'coverage',
  coverageReporters: ['text', 'lcov', 'html'],
};
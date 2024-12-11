module.exports = {
  extends: [
    "turbo",
    "eslint:recommended",
    "plugin:@typescript-eslint/recommended",
    "plugin:@typescript-eslint/eslint-recommended",
    "prettier"
  ],
  plugins: ["@typescript-eslint"],
  parser: "@typescript-eslint/parser",
  env: {
    browser: true,
    node: true
  },
  parserOptions: {
    sourceType: "module",
    ecmaVersion: 2022
  },
  rules: {
    "no-unused-vars": "off",
    "@typescript-eslint/no-unused-vars": "error",
    "react/no-unescaped-entities": "off",
    semi: ["error", "always"],
    quotes: ["error", "double"]
  }
};

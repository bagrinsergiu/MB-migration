# Package config

### Is intended for share config between all internal packages such as :

_This configuration can be extended, or override_

# Usage:

### 1. Add this package as devDependencies in package.json:

```JSON
{
  "devDependencies": {
    "config": "*"
  }
}
```

### 2. In any package you can extend from the basic rules, for example:

#### Prettier

_.prettierrc.js_

```js
module.exports = {
  ...require("config/prettier.config")
  // ... your custom rules.
};
```

### Eslint Config

_.eslintrc.js_

```js
module.exports = {
  root: true,
  extends: ["config/eslint-config"]
  // ... your custom rules.
};
```

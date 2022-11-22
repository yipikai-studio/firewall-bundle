# Firewall Bundle

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![Latest Stable Version](https://img.shields.io/packagist/v/yipikai/firewall-bundle.svg)](https://packagist.org/packages/yipikai/firewall-bundle)
[![Total Downloads](https://poser.pugx.org/yipikai/firewall-bundle/downloads.svg)](https://packagist.org/packages/yipikai/firewall-bundle)
[![License](https://poser.pugx.org/yipikai/firewall-bundle/license.svg)](https://packagist.org/packages/yipikai/firewall-bundle)

## Install bundle

You can install it with Composer:

```
composer require yipikai/firewall-bundle
```

## Documentation

```yaml
yipikai_firewall:
  uri:                        "URI"
  token:
    public:                   "PUBLIC_KEY"
    private:                  "PRIVATE_KEY"
    salt:                     "SALT"
  persist_in_session:
    enabled:                  true
    seconds:                  10
  filters:
    environnement:
      dev:
        enabled:              true
        redirect:             "https://yipikai.studio"
      prod:
        enabled:              false
        redirect:             "https://yipikai.studio"
    path:
      list:
        - /path-with-firewall
      redirect:               "https://yipikai.studio"
```




## Commit Messages

The commit message must follow the [Conventional Commits specification](https://www.conventionalcommits.org/).
The following types are allowed:

* `update`: Update
* `fix`: Bug fix
* `feat`: New feature
* `docs`: Change in the documentation
* `spec`: Spec change
* `test`: Test-related change
* `perf`: Performance optimization

Examples:

    update : Something

    fix: Fix something

    feat: Introduce X

    docs: Add docs for X

    spec: Z disambiguation

## License and Copyright
See licence file

## Credits
Created by [Matthieu Beurel](https://www.mbeurel.com). Sponsored by [Yipikai Studio](https://yipikai.studio).
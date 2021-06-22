# DuxRavel 应用安装器

composer.json
```
{
    "name": "duxphp/duxravel-appname",
    "type":"duxravel-app",
    "description": "appname为应用名称",
    "authors": [
        {
            "name": "duxphp"
        }
    ],
    "license": "MIT",
    "require": {
        "php": ">=7.4"
    },
    "autoload": {
        "psr-4": {
            "Modules\\": "src/"
        }
    },
    "extra": {
        "duxravel": {
            "type": "app",
            "name": "appname"
        }
    }
}

```

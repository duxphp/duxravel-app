# DuxRavel 应用安装器

应用开发请设置 composer.json 基础模板
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
    "extra": {
        "duxravel": {
            "type": "app",
            "name": "appname"
        }
    }
}

```

# LocoLaravelExport

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
![License][ico-license]

A drop in solution for exporting translations from https://localise.biz - A translation management tool to Laravel 5 project.

## Installation

Via Composer

``` bash
$ composer require mineschan/loco-laravel-export
```

If you like to have custom configurations

``` bash
$ php artisan vendor:publish --provider="mineschan\LocoLaravelExport\LocoLaravelExportServiceProvider"
```

## Usage: Export

####Step 1

Add your Export Key as `LOCO_EXPORT_API_KEY` in `.env`


####Step 2
Save all available languages to resources/lang directory.

``` bash
$ php artisan localise:export
```

####Done!

---

By default the package export all available languages from your Loco porjects, if you like to export only 
part of them you can specify using arguments.

``` bash
$ php artisan localise:export en zh-Hants 
```

You can pass them one by one, or you can simply pass `zh` for all `zh` locale. e.g. `zh-Hant`, `zh-TW` 



#####Export in Live servers
Just like `artisan:migrate`. If you run `localise:export` on non-local environment, confirmation will be needed.

You can pass `-f` or `--force` to make it silent. Helpful if you want to include this to your CI/CD flow.  

``` bash
$ php artisan localise:export -f
```

## Usage: Get string

LocoLaravelExport saves string array files from Localise.biz to `resources/lang/{lang}/loco.php` by default.

This package provides a helper function `loco()` to help you retrieve your saved strings with this package easily.

```
loco('your_key');
```

Alternatively, you can still use Laravel `__()` helper like so `__('loco.{your_string_key}')`.


## Configurable options

Option | default |Explanation
------- | ------- | ------
api_key* | (null) | Export Key of Loco project
lang_filename | loco | Default file name after export & save.
locales | [] | Default languages to export if you do not specific any.
download|  |Achieve download options.
export  |  |Export options.

## Contributions

Pull request is welcomed!

This is my first composer package, please do support me by giving me stars.


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Author

- [MineS Chan][link-author]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/mineschan/loco-laravel-export.svg?style=flat
[ico-downloads]: https://img.shields.io/packagist/dt/mineschan/loco-laravel-export.svg?style=flat
[ico-travis]: https://img.shields.io/travis/mineschan/loco-laravel-export/master.svg?style=flat
[ico-styleci]: https://styleci.io/repos/12345678/shield
[ico-license]: https://img.shields.io/github/license/mineschan/LocoLaravelExport.svg?style=flat

[link-packagist]: https://packagist.org/packages/mineschan/loco-laravel-export
[link-downloads]: https://packagist.org/packages/mineschan/loco-laravel-export
[link-travis]: https://travis-ci.org/mineschan/loco-laravel-export
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/mineschan
[link-contributors]: ../../contributors

# [Composer Based Installer for WordPress](https://github.com/turkeryildirim/wp-composer/)

This installer makes possible to manage installation of WordPress, themes and plugins by Composer. It also includes WordPress Coding Standarts checker and fixer.

## How to use

Just run `composer install` then make changes in `.env` file in the root folder and then start regular WordPress installation process from your browser.

## Installation structure

```shell
/                         # → Root of your web site
├── app/                  # → Main directory for regular `wp-content` folder
├── composer.json         # → Installer
├── composer.lock         # → Composer lock file (never edit)
├── vendor/               # → Composer packages (never edit) 
├── wordpress/            # → WordPress package (never edit) 
├── index.php             # → WordPress application bootsrapper (never edit) 
└── .env.sample           # → Sample enviroment file (never edit)
└── phpcs.xml             # → WordPress coding stadarts file. (never edit)
└── wp-config.php         # → WordPress configuration file (no need to edit most of the time)
└── .env                  # → Enviroment file (You should edit this one)
```

## Notes
Do not create `wp-config.php` file inside `wordpress` folder.

## License
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

## Contributing

Contributions are welcome from everyone.


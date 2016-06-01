# Code Climate PHP_CodeSniffer Engine

`codeclimate-phpcodesniffer` is a Code Climate engine that wraps the [PHP_Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) static analysis tool. You can run it on your command line using the Code Climate CLI, or on our hosted analysis platform.

PHP_CodeSniffer helps you detect violations of a defined coding standard.

### Installation

1. If you haven't already, [install the Code Climate CLI](https://github.com/codeclimate/codeclimate).
2. Run `codeclimate engines:enable phpcodesniffer`. This command both installs the engine and enables it in your `.codeclimate.yml` file.
3. You're ready to analyze! Browse into your project's folder and run `codeclimate analyze`.

###Config Options

Format the values for these config options per the [PHP_CodeSniffer documentation](https://github.com/squizlabs/PHP_CodeSniffer).

* file_extensions - This is where you can configure the file extensions for the files that you want PHP_CodeSniffer to analyze.
* standard - This is the list of standards that you want PHP_CodeSniffer to use while analyzing your files. The [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards#standards-subsets) are supported.
* ignore_warnings - You can hide warnings, and only report errors with this option.
* encoding - By default, PHPCS uses ISO-8859-1. Use this to change it to your encoding, e.g. UTF-8.

###Sample Config

    exclude_paths:
     - "/examples/**/*"
    engines:
      phpcodesniffer:
        enabled: true
        config:
          file_extensions: "php,inc,lib"
          standard: "PSR1,PSR2"
          ignore_warnings: true
          encoding: utf-8
    ratings:
      paths:
      - "**.php"

### Need help?

For help with PHP_CodeSniffer, [check out their documentation](https://github.com/squizlabs/PHP_CodeSniffer).

If you're running into a Code Climate issue, first look over this project's [GitHub Issues](https://github.com/squizlabs/PHP_CodeSniffer/issues), as your question may have already been covered. If not, [go ahead and open a support ticket with us](https://codeclimate.com/help).

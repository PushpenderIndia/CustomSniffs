# rtSniffs
Written Additional PHPCS Rules for Detecting Code Quality Issues &amp; Even Automatically fixing it using PHPCBF

## Installation (If Installed phpcs using composer)
1. Clone this repo
```
git clone https://github.com/pushpenderindia/rtSniffs ~/.composer/vendor/squizlabs/php_codesniffer/src/Standards/rtSniffs 
```
2. Check whether `rtSniffs` is getting detected by `phpcs`
```
phpcs -i
```
2.1 Expected Output (Should contain rtSniffs):
```
➜  rtSniffs git:(main) ✗ phpcs -i
The installed coding standards are MySource, PEAR, PSR1, PSR2, PSR12, Squiz, Zend, rtSniffs, WordPress-VIP-Go, WordPressVIPMinimum, PHPCompatibility, PHPCompatibilityParagonieRandomCompat, PHPCompatibilityParagonieSodiumCompat, PHPCompatibilityWP, Modernize, NormalizedArrays, Universal, PHPCSUtils, VariableAnalysis, WordPress, WordPress-Core, WordPress-Docs and WordPress-Extra
```

## Usage
1. Detecting Code Quality Issues in current directory
```
phpcs --standard=rtSniffs .
```
2. Fixing Issues using `phpcbf`
```
phpcs --standard=rtSniffs .
```

## NOTE
- rtSniffs is in developmental stage, so use `phpcs` command only, don't rely on `phpcbf` for autofix.

## Current Coding Sniffs 
- [X] `Sniffs.Commenting.MissingReturnTagSniff`: Checks if docblock contains `@return` Type
- [X] `Sniffs.Spacing.ExcessiveBlankLineSniff`: Detects more than one consecutive blank line.
- [X] `Sniffs.Spacing.MissingSpaceBeforeCommentSniff`: Checking if there is a space before start of single line comment.

## TODO
- [ ] ValidateFunctionReturnType: Validate the function return type and if exceptions are not handled then throw warning

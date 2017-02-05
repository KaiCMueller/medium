[![Build Status](https://travis-ci.org/KaiCMueller/medium.svg?branch=master)](https://travis-ci.org/KaiCMueller/medium)

A PHP library for reading the latest posts from a Medium.com feed

I built this library for showing my latest Medium posts on my web profile. As this might be interesting to others, I provide it to the public. This library is currently under development and has a limited feature set. Feel free to contact me for any bugs or contributions.

## Current features: 

* Get up to ten latest Medium posts with title, preview text, url, date and tags
* Built in file cache and possibility to inject own cache

## Usage

```php

$medium = new \KaiCMueller\Medium\Medium(
    [
        'user' => '@yourUsername',
        'cacheTime' => 600, // an optional cache lifetime
        'useFileCache' => false, // de-/activate the optional build in file cache
        'fileCachePath' => '/path/to/cacheFile' // optional file path for internal file cache
    ],
    $cache // optional parameter to inject own cache class implementing \KaiCMueller\Medium\Cache\CacheInterface
);

foreach ($medium->getPosts() as $post) {
    echo $post->getTitle();
}

```

## Install

The recommended way to install is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

In your composer.json file add the following requirement

```json
"require": {
    "kaicmueller/medium": "~1.0"
}
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

You can then later update using composer:

 ```bash
composer.phar update
 ```
 
 
## Versions

* 1.0.1 - Adjusting Readme for correct versioning
* 1.0.0 - First release version

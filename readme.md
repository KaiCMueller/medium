A PHP library for reading the latest posts from a Medium.com feed

I built this library for showing my latest Medium posts on my web profile. As this might be interesting to others, I provide it to the public. This library is currently in beta status and has a limited feature set. Feel free to contact me for any bugs or contributions.

## Current features: 

* Get up to ten latest Medium posts with title, preview text, url, date and tags
* Built in file cache and possibility to inject own cache

## Usage

```php


$config = [
  'user' => '@yourUsername',
  'cacheTime' => 600, // an optional cache lifetime
  'useFileCache' => false, // de-/activate the optional build in file cache
  'fileCachePath' => '/path/to/cacheFile' // optional file path for internal file cache
];

$medium = new Medium($config); 

foreach ($medium->getPosts() as $post) {
    echo $post->getTitle();
}

// using your own cache
// it has to implement the methods defined in
// KaiCMueller\Medium\Cache\CacheInterface

$myOwnCache = new MyOwnCache();

$medium = new Medium($config, $myOwnCache); 


```

## Install

The recommended way to install Guzzle is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version:

```bash
php composer.phar require KaiCMueller/medium
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

You can then later update Guzzle using composer:

 ```bash
composer.phar update
 ```
 
 
## Versions

* 0.1 - Beta - Initial Version
# Watch files for changes with fswatch and ReactPHP.

**Table of contents**

* [Installation](#installation)
* [Usage](#usage)
* [Change data](#change-data)

## Installation

Library requires [fswatch](https://github.com/emcrisostomo/fswatch) - a cross-platform file change
 monitor with multiple environments.

You can install this package like this:

```bash
composer global seregazhuk/reactphp-fswatch
```

## Usage

First of all, you create a *watcher* object providing a loop and fswatch command that 
you are going to run. For example if you want to listen to changes inside `src` directory:

```php
$loop = \React\EventLoop\Factory::create();
$watcher = new \Seregazhuk\ReactFsWatch\FsWatch('src', $loop,);
$watcher->run();

$loop->run();
``` 

Once there are changes in the directory the watcher fires `change` event that contains an
instance of `Change` object. To detect change you can listen to this event and
handle the event object:

```php
$watcher->on('change', static function (Change $event) {
    $type = $event->isFile() ? 'File' : 'Dir';
    echo $type . ': ' . $event->file() . ' was changed' . PHP_EOL;
});
```

Also, you can use a helper method `onChange`:

```php
$watcher->onChange($callable);
```

To stop listening for filesytem use method `stop()`:

```php
$watcher->stop();
```

## Change data

On every change you receive `Change` object that contains different details about 
an event that happened in the filesystem. It has the following helper methods to examine the change:

 - `file()` - filename or a directory that has changed.
 - `isFile()` - whether a file was changed or not.
 - `isDir()` - whether a directory was changed or not.
 - `isSymbolicLink` - whether a symbolic link was changed or not.
 - `isLink()` - the object link count has changed.
 - `noOp()` - no changed were detected.
 - `attributeModified()` - the object’s attribute has changed.
 - `ownerModified()` - the object’s owner has changed.
 - `created()` - the object has been created.
 - `removed()` - the object has been removed.
 - `renamed()` - the object has been renamed.
 - `updated()` - the object has been updated. 

# License

MIT [http://rem.mit-license.org](http://rem.mit-license.org)

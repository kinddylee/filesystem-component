# [![Build Status](https://travis-ci.org/sonrisa/filesystem-component.png?branch=master)](https://travis-ci.org/sonrisa/filesystem-component) FileSystem Component

## 1. Installation
Add the following to your `composer.json` file :

```
"sonrisa/filesystem-component":"dev-master"
```

## 2. Description
The FileSystem component allows file and directory manipulation under the File System.

Compression and decompression is available for both files (Gzip and Zip) and folder (Zip).

## 3. Methods:
All actions available in this component have been split into the 2 possible entities, `File` and `Folder`.

### 3.1. File class

#### Methods available:
- File::isReadable($filePath);
- File::isWritable($filePath);
- File::isHidden($path);
- File::isLink($path);
- File::getExtension($filePath);
- File::getModificationDate($filePath);
- File::size($filePath, $format = false, $precision = 2);
- File::exists($filePath);
- File::read($filePath);
- File::write($filePath, $data, $mode = null);
- File::append($filePath, $data);
- File::chmod($filePath, $mode);
- File::delete($filePath);
- File::rename($filePath,$newFileName,$overwrite=false);
- File::copy($filePath,$newFilePath,$overwrite=false);
- File::move($filePath,$destinationFolder,$overwrite=false);
- File::touch($filePath,$time='',$accessTime='');
- File::gzip($filePath, $newFileName, $overwrite=false, $param="1");
- File::gunzip ($filePath, $destinationPath,$overwrite=false);
- File::zip($filePath, $newFileName, $overwrite=false);
- File::unzip($filePath, $destinationPath, $overwrite=false)
- File::softSymLink($original, $alias);
- File::hardSymLink($original, $alias);
- File::mimeType($path);
- File::userOwner($path);
- File::groupOwner($path);


#### Usage:
```php
<?php
use \Sonrisa\Component\FileSystem\File as File;

File::copy('hello/world.txt','goodbye/moon.txt');
File::exists('goodbye/moon.txt');
```

### 3.2. Folder class
#### Methods available:
- Folder::isReadable($path);
- Folder::isWritable($path);
- Folder::isLink($path);
- Folder::isHidden($path);
- Folder::getModificationDate($path);
- Folder::size($filePath, $format = false, $precision = 2);
- Folder::copy($filePath,$newFilePath,$overwrite=false);
- Folder::move($filePath,$newFilePath);
- Folder::exists($path);
- Folder::create($path);
- Folder::delete($path);
- Folder::rename($path,$newName);
- Folder::touch($path,$time='',$accessTime='');
- Folder::chmod($path, $mode);
- Folder::zip($filePath, $newFileName, $overwrite=false);
- Folder::unzip($filePath, $newFileName, $overwrite=false);
- Folder::softSymLink($original, $alias);
- Folder::hardSymLink($original, $alias);
- Folder::softSymLink($original, $alias);
- Folder::hardSymLink($original, $alias);

#### Usage:
```php
<?php
use \Sonrisa\Component\FileSystem\Folder as Folder;

Folder::softSymLink('path/to/src','source');
Folder::isLink('source');
```


## 4. Fully tested.
Testing has been done using PHPUnit and [Travis-CI](https://travis-ci.org). All code has been tested to be compatible from PHP 5.3 up to PHP 5.5 and [Facebook's PHP Virtual Machine: HipHop](http://hiphop-php.com).


## 5.To do:
While the current class is stable and usable, new features will be added eventually for even better file system support.

- Add functions to retrieve data from files:
    - http://www.php.net/manual/en/function.stat.php

- Add functions to retrieve data from directories:
    - Total number of files in the directory.
    - Total number of files with of a certain type or extension.
    - Largest file in the directory.
    - Last modified file in the directory.   
    - List all directory files sorted by file size.
    - List all directory files sorted by modification date.    

- Add new functionalities to existing functions

    - Folder::copy($path,$destinationPath,$overwrite=false); 
        Adding an overwrite flag means, first, read all files in path, read all files in destination file. If there are coincidences, and overwrite == false, throw exception.

    -  Folder::move($path,$destinationPath,$overwrite=false); 
        Adding an overwrite flag means, first, read all files in path, read all files in destination file. If there are coincidences, and overwrite == false, throw exception.

## 6. Author
Nil Portugués Calderó
 - <contact@nilportugues.com>
 - http://nilportugues.com

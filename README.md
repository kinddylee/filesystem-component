# [![Build Status](https://travis-ci.org/sonrisa/filesystem-component.png?branch=master)](https://travis-ci.org/sonrisa/filesystem-component) FileSystem Component

## 1. Description
The FileSystem component allows file and directory manipulation under the File System.

Compression is available for both files and folder using Zip and GZip for files.

## 2. Methods:
All actions available in this component have been split into the 2 possible entities, `File` and `Folder`.

### 2.1. File class

#### Methods available:
- File::isReadable($filePath);
- File::isWritable($filePath);
- File::getExtension($filePath);
- File::getModificationDate($filePath);
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
- File::isLink($path);
- File::mimeType($path);
- File::userOwner($path);
- File::groupOwner($path);

#### Usage:
```
<?php
use \NilPortugues\Component\FileSystem\File as File;

File::copy('hello/world.txt','goodbye/moon.txt');
File::exists('goodbye/moon.txt');

?>
```

### 2.2. Folder class
#### Methods available:
- Folder::isReadable($path);
- Folder::isWritable($path);
- Folder::getModificationDate($path);
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
- Folder::isLink($path);
- Folder::softSymLink($original, $alias);
- Folder::hardSymLink($original, $alias);
- Folder::isLink($path);

#### Usage:
```
use \NilPortugues\Component\FileSystem\Folder as Folder;

Folder::softSymLink('path/to/src','source');
Folder::isLink('source');
```


## 3. Fully tested.
Testing has been done using PHPUnit and Travis-CI. All code has been tested to be compatible from PHP 5.3 up to PHP 5.5.


## 4.To do:
While the current class is stable and usable, new features will be added eventually for even better file system support.

- Add functions to retrieve data from files:
    - Get file size in a human friendly way.
    - Check if file is hidden.
    - http://www.php.net/manual/en/function.stat.php

- Add functions to retrieve data from directories:
    - Total number of files in the directory.
    - Total number of files with of a certain type or extension.
    - Total directory file size.
    - Largest file in the directory.
    - List all directory files sorted by file size.
    - Last modified file  in the directory.
    - List all directory files sorted by modification date.    

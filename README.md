# [![Build Status](https://travis-ci.org/sonrisa/filesystem-component.png?branch=master)](https://travis-ci.org/sonrisa/filesystem-component) FileSystem Component

## 1. Description
The FileSystem component allows file and directory manipulation under the File System.

Compression is available for both files and folder using Zip and GZip for files.

## 2. Methods:
All actions available in this component have been split into the 2 possible entities, `File` and `Folder`.

### 2.1. File class

#### Methods available:
- isReadable($filePath);
- isWritable($filePath);
- getExtension($filePath);
- getModificationDate($filePath);
- exists($filePath);
- read($filePath);
- write($filePath, $data, $mode = null);
- append($filePath, $data);
- chmod($filePath, $mode);
- delete($filePath);
- rename($filePath,$newFileName,$overwrite=false);
- copy($filePath,$newFilePath,$overwrite=false);
- move($filePath,$destinationFolder,$overwrite=false);
- touch($filePath,$time='',$accessTime='');
- gzip($filePath, $newFileName, $overwrite=false, $param="1");
- gunzip ($filePath, $destinationPath,$overwrite=false);
- zip($filePath, $newFileName, $overwrite=false);
- unzip($filePath, $destinationPath, $overwrite=false)
- softSymLink($original, $alias);
- hardSymLink($original, $alias);
- isLink($path);

#### Usage:
```
$file = new \NilPortugues\Component\FileSystem\File();

$file->copy('hello/world.txt','goodbye/moon.txt');
$file->exists('goodbye/moon.txt');

```

### 2.2. Folder class
#### Methods available:
- isReadable($path);
- isWritable($path);
- getModificationDate($path);
- copy($filePath,$newFilePath,$overwrite=false);
- move($filePath,$newFilePath);
- exists($path);
- create($path);
- delete($path);
- rename($path,$newName);
- touch($path,$time='',$accessTime='');
- chmod($path, $mode);
- zip($filePath, $newFileName, $overwrite=false);
- unzip($filePath, $newFileName, $overwrite=false);
- softSymLink($original, $alias);
- hardSymLink($original, $alias);
- isLink($path);
- softSymLink($original, $alias);
- hardSymLink($original, $alias);
- isLink($path);

#### Usage:
```
$folder = new \NilPortugues\Component\FileSystem\Folder();

$folder->softSymLink('path/to/src','source');
$folder->isLink('source');
```


## 3. Fully tested.
Testing has been done using PHPUnit and Travis-CI. All code has been tested to be compatible from PHP 5.3 up to PHP 5.5.


## 4.To do:
While the current class is stable and usable, new features will be added eventually for even better file system support.

- Add functions to retrieve data from files:
    - Get file size in a human friendly way.
    - Get file mime type.
    - Check if file is hidden.
    - Get file owner.
    - Get file group owner.

- Add functions to retrieve data from directories:
    - Total number of files in the directory.
    - Total number of files with of a certain type or extension.
    - Total directory file size.
    - Largest file in the directory.
    - List all directory files sorted by file size.
    - Last modified file  in the directory.
    - List all directory files sorted by modification date.
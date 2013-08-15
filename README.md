# [![Build Status](https://travis-ci.org/sonrisa/filesystem-component.png?branch=master)](https://travis-ci.org/sonrisa/filesystem-component) FileSystem Component


## 1. Description
The FileSystem Component for SonrisaCMS allows working with the operating system's file system.

## 2. Methods:
All actions available in this component have been split into the 3 possible entities, `File`, `Folder` and the `FileSystem` itself.

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

#### Usage:
```
$file = new \NilPortugues\Component\FileSystem\File();
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

#### Usage:
```
$folder = new \NilPortugues\Component\FileSystem\Folder();
```

### 2.3. FileSystem
#### Methods available:

#### Usage:
```
$folder = new \NilPortugues\Component\FileSystem\FileSystem();
```


## 3. Fully tested.
Testing has been done using PHPUnit and Travis-CI. All code has been tested to be compatible from PHP 5.3 up to PHP 5.5.


## 4.To do:
While the current class is stable and usable, new features will be added eventually for even better file system support.

- Add functions to retrieve data from files:
    - Get file size in a human friendly way.
    - Get file mime type.

- Add functions to retrieve data from directories:
    - Total number of files in the directory.
    - Total number of files with of a certain type or extension.
    - Total directory file size.
    - Largest file in the directory.
    - List all directory files sorted by file size.
    - Last modified file  in the directory.
    - List all directory files sorted by modification date.
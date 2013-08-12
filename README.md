# [![Build Status](https://travis-ci.org/sonrisa/filesystem-component.png?branch=master)](https://travis-ci.org/sonrisa/filesystem-component) FileSystem Component

The FileSystem Component for SonrisaCMS allows working with the operating system's file system.

All actions available in this component have been split into the 3 possible entities, `File`, `Folder` and the `FileSystem` itself.

## 1. File

### Methods available: 
- getExtension($filePath);
- getModificationDate($filePath);
- exists($filePath);
- isReadable($filePath);
- isWritable($filePath);
- read($filePath);
- write($filePath, $data, $mode = null);
- append($filePath, $data);
- chmod($filePath, $mode);
- delete($filePath);
- rename($filePath,$newFileName,$overwrite=false);
- touch($filePath,$time='',$accessTime='');
- gzip($in, $out, $overwrite=false, $param="1");
- gunzip ($in, $out,$overwrite=false);

### Usage:
```
$file = new NilPortugues\Component\FileSystem\File();
```

## 2. Folder
### Methods available:

### Usage:
```
$folder = new NilPortugues\Component\FileSystem\Folder();
```

## 3. FileSystem
### Methods available:

### Usage:
```
$folder = new NilPortugues\Component\FileSystem\FileSystem();
```
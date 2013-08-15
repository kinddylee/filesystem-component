# [![Build Status](https://travis-ci.org/sonrisa/filesystem-component.png?branch=master)](https://travis-ci.org/sonrisa/filesystem-component) FileSystem Component

The FileSystem Component for SonrisaCMS allows working with the operating system's file system.

All actions available in this component have been split into the 3 possible entities, `File`, `Folder` and the `FileSystem` itself.

## 1. File

### Methods available:
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

### Usage:
```
$file = new \NilPortugues\Component\FileSystem\File();
```

## 2. Folder
### Methods available:
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

### Usage:
```
$folder = new \NilPortugues\Component\FileSystem\Folder();
```

## 3. FileSystem
### Methods available:

### Usage:
```
$folder = new \NilPortugues\Component\FileSystem\FileSystem();
```
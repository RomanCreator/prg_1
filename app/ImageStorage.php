<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 26.07.16
 * Time: 16:04
 *
 * Данный класс рабоатет с файлами изображений для любых сущностей
 * Определяет структуру хранения данных, а так же делает миниатюры изображений
 */

namespace App;


use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Image;
use Storage;

class ImageStorage {

    /* Диск по умолчанию где делать миниатюры и хранить оригиналы */
    public static $defaultDisk = 'public';

    /* Кодировка имен файлов по умолчанию */
    protected static $defaultEncodingName = 'UTF-8';

    /* Путь к директории с файлами вычисляется автоматически в конструкторе */
    protected $pathToDir = '';

    public function __construct(Model $class = null) {
        if (is_null($class)) {
            throw new ImageStorageException('Не задан класс-держатель изображений');
        }

        $this->pathToDir = str_replace("\\",'-', get_class($class)).'/'.$class->id.'/';

        if (!Storage::disk($this::$defaultDisk)->exists($this->pathToDir)) {
            Storage::disk($this::$defaultDisk)->makeDirectory($this->pathToDir);
        }
    }

    /**
     * Сохраняет изображения в неймспейсе
     *
     * @param $uploadedFiles array
     * @param $nameSpace
     */
    public function save ($uploadedFiles, $nameSpace) {
        if (!Storage::disk($this::$defaultDisk)->exists($this->pathToDir.$nameSpace)) {
            Storage::disk($this::$defaultDisk)->makeDirectory($this->pathToDir.$nameSpace);
        }

        /**
         * @var $uploadedFile UploadedFile
         */
        foreach ($uploadedFiles as $uploadedFile) {
            /*Тут добавить проверку на наличие такого же файла по имени*/
            $name = urlencode($uploadedFile->getClientOriginalName());

            $info = pathinfo($name);

            $baseName = basename($name,'.'.$info['extension']);
            $extension = $info['extension'];
            $nameIsFree = false;
            $nameIterator = '';
            while (!$nameIsFree) {
                if (Storage::disk($this::$defaultDisk)->exists($this->pathToDir.$nameSpace.'/'.$baseName.$nameIterator.'.'.$extension)) {
                    if ($nameIterator == '') {
                        $nameIterator = 1;
                    } else {
                        $nameIterator++;
                    }
                } else {
                    $nameIsFree = true;
                    if ($nameIterator != '') {
                        $name = $baseName.$nameIterator.'.'.$extension;
                    }
                }
            }

            Storage::disk($this::$defaultDisk)->put(
                $this->pathToDir.$nameSpace.'/'.$name,
                file_get_contents($uploadedFile->getRealPath())
            );
        }
    }

    /**
     * Возвращает список всех url путей файлов находящихся в неймспейсе
     * @param $nameSpace
     * @return array
     */
    public function get ($nameSpace, $urlPathType=true) {
        $files = Storage::disk($this::$defaultDisk)->files($this->pathToDir.$nameSpace);
        $filesArray = [];

        foreach ($files as $file) {
            if ($urlPathType) {
                $filesArray[] = Storage::disk($this::$defaultDisk)->url($file);
            } else {
                $filesArray[] = $file;
            }
        }

        return $filesArray;
    }

    /**
     * Возвращает массив кропированных изображений находящихся в неймспейсе
     * если миниатюра изображения уже создана, то просто возвращает url пути
     * @param $namespace
     * @param int $width
     * @param int $height
     * @return array
     */
    public function getCropped ($namespace, $width=300, $height=300) {
        $files = $this->get($namespace, false);
        $croppedFiles = [];
        foreach ($files as $file) {
            list($baseName, $extension) = $this->getFileNameAndExtension($file);

            if (!Storage::disk($this::$defaultDisk)->exists($this->pathToDir.$namespace.'/'.$baseName.'_derived_'.$width.'x'.$height.'.'.$extension)) {
                Image::make(Storage::disk('public')->get($this->pathToDir.$namespace.'/'.$baseName.'.'.$extension))->crop($width,$height)->save(storage_path('app/'.$this::$defaultDisk.'/'.$this->pathToDir.$namespace.'/'.$baseName.'_derived_'.$width.'x'.$height.'.'.$extension));

            }

            $croppedFiles[] = Storage::disk($this::$defaultDisk)->url($this->pathToDir.$namespace.'/'.$baseName.'_derived_'.$width.'x'.$height.'.'.$extension);
        }

        return $croppedFiles;
    }


    /**
     * Возвращает имя файла и его расширение в виде массива
     * @param $fullNameOfFile
     * @return mixed
     */
    private function getFileNameAndExtension ($fullNameOfFile) {
        $info = pathinfo($fullNameOfFile);
        $baseName = basename($fullNameOfFile,'.'.$info['extension']);
        $extension = $info['extension'];

        $name = [];
        $name[] = $baseName;
        $name[] = $extension;

        return $name;
    }



}

class ImageStorageException extends Exception {
}
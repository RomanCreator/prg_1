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
            Storage::disk($this::$defaultDisk)->put(
                $this->pathToDir.$nameSpace.'/'.$name,
                file_get_contents($uploadedFile->getRealPath())
            );
        }
    }

    /**
     * @param $nameSpace
     * @return array
     */
    public function get ($nameSpace, $command = null, $param = null) {
        $files = Storage::disk($this::$defaultDisk)->files($this->pathToDir.$nameSpace);
        $filesArray = [];
        dd($files);
        /*
        foreach ($files as $file) {
            $elem = [];
            $elem['url'] = $file->
        }

        return $filesArray;*/
    }

}

class ImageStorageException extends Exception {
}
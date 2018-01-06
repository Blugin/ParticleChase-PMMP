<?php

namespace presentkim\particlechase\util;

class Translation{

    /** @var string[string] */
    private static $lang = [];

    /** @param string $filename */
    public static function load(string $filename) : void{
        self::$lang = yaml_parse_file($filename);
    }

    /** @param resource $resource */
    public static function loadFromResource($resource) : void{
        if (is_resource($resource)) {
            self::$lang = yaml_parse(stream_get_contents($resource));
        }
    }

    /**
     * @param string $filename
     *
     * @return bool Returns TRUE on
     *              success.
     */
    public static function save(string $filename) : bool{
        $path = dirname($filename);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return yaml_emit_file($filename, self::$lang, YAML_UTF8_ENCODING);
    }

    /**
     * @param string   $strId
     * @param string[] $params
     *
     * @return string
     */
    public static function translate(string $strId, string ...$params) : string{
        if (isset(self::$lang[$strId])) {
            $value = self::$lang[$strId];
            if (is_array($value)) {
                $value = $value[array_rand($value)];
            }
            if (is_string($value)) {
                return empty($params) ? $value : strtr($value, listToPairs($params));
            } else {
                return "$strId is not string";
            }
        }
        return "Undefined \$strId : $strId";
    }

    /**
     * @param string $strId
     *
     * @return string[] | null
     */
    public static function getArray(string $strId) : ?array{
        if (isset(self::$lang[$strId])) {
            $value = self::$lang[$strId];
            return is_array($value) ? $value : null;
        }
        return null;
    }
}
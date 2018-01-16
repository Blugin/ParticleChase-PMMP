<?php

namespace presentkim\particlechase\util;

use presentkim\particlechase\ParticleChaseMain as Plugin;

class Translation{

    /** @var string[string] */
    private static $lang = [];

    /** @var string[string] */
    private static $default = [];

    /**
     * @param string $filename
     * @param bool   $default
     */
    public static function load(string $filename, boolean $default = false){
        if ($default) {
            self::$default = yaml_parse_file($filename);
        } else {
            self::$lang = yaml_parse_file($filename);
        }
    }

    /**
     * @param resource $resource
     * @param bool     $default
     */
    public static function loadFromResource($resource, boolean $default = false){
        if (is_resource($resource)) {
            if ($default) {
                self::$default = yaml_parse(stream_get_contents($resource));
            } else {
                self::$lang = yaml_parse(stream_get_contents($resource));
            }
        }
    }

    /**
     * @param string   $strId
     * @param string[] $params
     *
     * @return string
     */
    public static function translate(string $strId, string ...$params){
        if (isset(self::$lang[$strId])) {
            $value = self::$lang[$strId];
        } elseif (isset(self::$default[$strId])) {
            Plugin::getInstance()->getLogger()->warning("get $strId from default");
            $value = self::$default[$strId];
        } else {
            Plugin::getInstance()->getLogger()->warning("get $strId failed");
            return "Undefined strId : $strId";
        }

        if (is_array($value)) {
            $value = $value[array_rand($value)];
        }
        if (is_string($value)) {
            return empty($params) ? $value : strtr($value, listToPairs($params));
        } else {
            return "$strId is not string";
        }
    }

    /**
     * @param string $strId
     *
     * @return string[] | null
     */
    public static function getArray(string $strId){
        if (isset(self::$lang[$strId])) {
            $value = self::$lang[$strId];
        } elseif (isset(self::$default[$strId])) {
            Plugin::getInstance()->getLogger()->warning("get $strId from default");
            $value = self::$default[$strId];
        } else {
            Plugin::getInstance()->getLogger()->warning("get $strId failed");
            return null;
        }
        return is_array($value) ? $value : null;
    }
}
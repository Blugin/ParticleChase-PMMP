<?php

namespace presentkim\particlechase\util;

class Utils{

    /**
     * @param string $str
     * @param array  $strs
     *
     * @return bool
     */
    public static function in_arrayi(string $str, array $strs) : bool{
        foreach ($strs as $key => $value) {
            if (strcasecmp($str, $value) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Object[] $list
     *
     * @return string[]
     */
    public static function listToPairs(array $list) : array{
        $pairs = [];
        $size = sizeOf($list);
        for ($i = 0; $i < $size; ++$i) {
            $pairs["{%$i}"] = $list[$i];
        }
        return $pairs;
    }

    /**
     * @param string        $str
     * @param int|null      $default = null
     * @param \Closure|null $filter
     *
     * @return int|null
     */
    public static function toInt(string $str, int $default = null, \Closure $filter = null) : ?int{
        if (is_numeric($str)) {
            $i = (int) $str;
        } elseif (is_numeric($default)) {
            $i = $default;
        } else {
            return null;
        }
        if (!$filter) {
            return $i;
        } elseif ($result = $filter($i)) {
            return $result === -1 ? $default : $i;
        } else {
            return null;
        }
    }
}
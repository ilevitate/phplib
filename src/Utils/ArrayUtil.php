<?php
/**
 * Created by PhpStorm.
 * User: iLevitate
 * Date: 2018/10/11
 * Time: 11:10
 */

namespace Utils;

class ArrayUtil
{
    /**
     * 生成无限极树形结构数组
     * @param $arr
     * @return array
     */
    public static function makeTree($arr)
    {
        $refer = array();
        $tree = array();
        foreach ($arr as $k => $v) {
            $refer[$v['id']] = &$arr[$k];  //创建主键的数组引用
        }

        foreach ($arr as $k => $v) {
            $pid = $v['pid'];   //获取当前分类的父级id
            if ($pid == 0) {
                $tree[] = &$arr[$k];    //顶级栏目
            } else {
                if (isset($refer[$pid])) {
                    $refer[$pid]['subcat'][] = &$arr[$k];    //如果存在父级栏目，则添加进父级栏目的子栏目数组中
                }
            }
        }

        return $tree;
    }

    /**
     * 对象转数组,使用get_object_vars返回对象属性组成的数组
     * @param $obj
     * @return array
     */
    public static function objectToArray($obj)
    {
        $arr = is_object($obj) ? get_object_vars($obj) : $obj;
        if (is_array($arr)) {
            return array_map(__FUNCTION__, $arr);
        } else {
            return $arr;
        }
    }

    /**
     * 数组转对象
     * @param $arr
     * @return object
     */
    public static function arrayToObject($arr)
    {
        if (is_array($arr)) {
            return (object)array_map(__FUNCTION__, $arr);
        } else {
            return $arr;
        }
    }


    /**
     * 将二维数组的子数组的指定字段的值作为子数组的key
     * @param $arr
     * @param $field
     * @return array
     */
    public static function useFieldAsKey($arr, $field)
    {
        if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
            return $arr;
        }
        $re = [];
        foreach ($arr as $v) {
            if (!isset($v[$field])) {
                continue;
            }
            $re[$v[$field]] = $v;
        }
        return $re;
    }

    /**
     * 将数组的值作为key
     * @param $arr
     * @return array
     */
    public static function useValAsKey($arr)
    {
        if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
            return $arr;
        }
        $re = [];
        foreach ($arr as $v) {
            $re[$v] = $v;
        }
        return $re;
    }

    /**
     * 使用二维数组的子数组的某个字段作为value
     * @param $arr
     * @param $field
     * @return array
     */
    public static function useFieldAsVal($arr, $field)
    {
        if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
            return $arr;
        }
        $re = [];
        foreach ($arr as $k => $v) {
            $re[$k] = $v[$field];
        }
        return $re;
    }

    /**
     * 获取二维数组的子数组中的某个字段的集合
     * @param $arr
     * @param $childName
     * @return array
     */
    public static function getChildrenFields($arr, $childName)
    {
        $firstChild = current($arr);
        if (!is_array($arr) || !count($arr) || (!is_array($firstChild) && !is_object($firstChild))) {
            return [];
        }
        $children = [];
        foreach ($arr as $k => $v) {
            if (is_array($v) || is_object($v)) {
                $val = is_object($v) ? $v->$childName : arrayGet($v, $childName, '');
                $children[$k] = is_numeric($val) ? (float)$val : $val;
            }
        }
        return $children;
    }

    /**
     * 获取二维数组的指定key的子数组集合
     * @param $arr
     * @param $keys
     * @return array|bool
     */
    public static function getChildrenArrays($arr, $keys)
    {
        if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
            return false;
        }
        $children = [];
        foreach ($arr as $k => $v) {
            if (in_array($k, $keys)) {
                $children[$k] = $v;
            }
        }
        return $children;
    }

    /**
     * 合并多维数组
     * @param $arr
     * @return array|bool
     */
    public static function unionChildrenArrays($arr)
    {
        if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
            return false;
        }
        $children = [];
        foreach ($arr as $k => $v) {
            $children = array_merge($children, $v);
        }
        return $children;
    }

    /**
     * 将二维数组按某个字段分组
     * @param $arr
     * @param $field
     * @return array|bool
     */
    public static function departByField($arr, $field)
    {
        if (!is_array($arr) || !count($arr) || !is_array(current($arr))) {
            return false;
        }
        $re = [];
        foreach ($arr as $k => $v) {
            if (is_array(current($v))) {
                continue;
            }
            $re[$v[$field]][$k] = $v;
        }
        return $re;
    }

    /**
     * 取数组中指定key的元素组合
     * @param array $arr
     * @param array $keys
     * @return array
     */
    public static function arrayOnly(array $arr, array $keys)
    {
        foreach ($arr as $k => $v) {
            if (!in_array($k, $keys)) {
                unset($arr[$k]);
            }
        }
        return $arr;
    }

    /**
     * @param $array
     * @param $fields
     * @param bool $skipExists
     * @return array
     */
    public static function getFields($array, $fields, $skipExists = false)
    {
        $result = [];

        if ($skipExists) {
            foreach ($fields as $field) {
                if (array_key_exists($field, $array)) {
                    $result[$field] = $array[$field];
                }
            }
        } else {
            foreach ($fields as $field) {
                $result[$field] = static::getValue($array, $field);
            }
        }

        return $result;
    }

    /**
     * @param $array
     * @param $name
     * @param bool $keepKeys
     * @return array
     */
    public static function getColumn($array, $name, $keepKeys = true)
    {
        $result = [];
        if ($keepKeys) {
            foreach ($array as $k => $element) {
                $result[$k] = static::getValue($element, $name);
            }
        } else {
            foreach ($array as $element) {
                $result[] = static::getValue($element, $name);
            }
        }

        return $result;
    }

    /**
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public static function getValue($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array)) {
            // this is expected to fail if the property does not exist, or __get() is not implemented
            // it is not reliably possible to check whether a property is accessable beforehand
            return $array->$key;
        } elseif (is_array($array)) {
            return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }

    /**
     * @param $array
     * @param $key
     * @param array $groups
     * @return array
     */
    public static function index($array, $key, $groups = [])
    {
        $result = [];
        $groups = (array)$groups;

        foreach ($array as $element) {
            $lastArray = &$result;

            foreach ($groups as $group) {
                $value = static::getValue($element, $group);
                if (!array_key_exists($value, $lastArray)) {
                    $lastArray[$value] = [];
                }
                $lastArray = &$lastArray[$value];
            }

            if ($key === null) {
                if (!empty($groups)) {
                    $lastArray[] = $element;
                }
            } else {
                $value = static::getValue($element, $key);
                if ($value !== null) {
                    if (is_float($value)) {
                        $value = (string)$value;
                    }
                    $lastArray[$value] = $element;
                }
            }
            unset($lastArray);
        }

        return $result;
    }

}
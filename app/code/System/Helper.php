<?php
/**
 * System helper service
 *
 * @category  TimetableTool
 * @package   TimetableTool_System
 * @author    Paul Shunkow
 * @copyright 2014 Paul Shunkow
 */
namespace System;

use System\Libs\Object;

class Helper extends Object
{
    /**
     * Path to application base dir
     *
     * @var string
     */
    static private $_appRoot;

    /**
     * Return base app dir
     *
     * @return string
     */
    static public function getBaseDir()
    {
        if (self::$_appRoot) {
            return self::$_appRoot;
        }

        $appRoot = realpath(dirname(''));

        if (is_dir($appRoot) and is_readable($appRoot)) {
            self::$_appRoot = $appRoot;
            return self::$_appRoot;
        } else {
            die($appRoot . ' is not a directory or not readable by this user');
        }
    }

    /**
     * Return absolute path to folder
     *
     * @param  array $relatedPath Related path as array
     * @return string
     */
    static public function getDirAbsolutePath($relatedPath)
    {
        $absolutePath = self::getBaseDir();
        foreach ($relatedPath as $part) {
            $absolutePath .= DIRECTORY_SEPARATOR . $part;
        }
        $absolutePath .= DIRECTORY_SEPARATOR;
        return $absolutePath;
    }
    /**
     * Convert input array into JSON string
     *
     * @param array $inputArray
     *
     * @return string
     */
    static public function JSONEncode($inputArray)
    {
        $outString = json_encode($inputArray);
        return $outString;
    }

    /**
     * Convert input JSON string into array
     *
     * @param      $inputString
     * @param bool $likeArray
     *
     * @return mixed
     */
    static public function JSONDecode($inputString, $likeArray = false)
    {
        $outArray = json_decode($inputString, $likeArray);
        return $outArray;
    }
}
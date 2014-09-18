<?php
/**
 * System Autoloader
 *
 * @category  TimetableTool
 * @package   TimetableTool
 * @author    Paul Shunkow
 * @copyright 2014 Paul Shunkow
 */
class Autoloader
{
    /**
     * System autoloader
     *
     * @param string $class Class name
     */
    static public function autoload($class)
    {
        $classFilePath  = realpath(dirname(''));
        $classPathParts = explode('\\', $class);
        $classFilePath .= DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'src';


        for ($i = 0; $i < count($classPathParts); $i++) {
            $classFilePath .= DIRECTORY_SEPARATOR . $classPathParts[$i];
        }

        $classFilePath .= '.php';

        if (is_file($classFilePath)) {
            require_once $classFilePath;
        }else{

        }
    }
}
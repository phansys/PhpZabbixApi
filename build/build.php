<?php
/**
 * @file    build.php
 *
 * @brief   PHP script to build the PhpZabbixApi class(es).
 *
 * This file is part of PhpZabbixApi.
 *
 * PhpZabbixApi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhpZabbixApi is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhpZabbixApi.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   GNU General Public License
 * @author      confirm IT solutions GmbH, Rathausstrase 14, CH-6340 Baar
 */


/*
 * Load required files.
 */

    require __DIR__.'/config.inc.php';
    require __DIR__.'/replacePlaceholders.func.php';

/*
 * Define some pathes and do some sanity checks for existence of the pathes.
 */

    if(!is_dir(PATH_ZABBIX))
        die('ERROR: Zabbix path "'.PATH_ZABBIX.'" is not a directory! Please check the PATH_ZABBIX configuration constant.');

    // load Zabbix internal constants, to access ZABBIX_API_VERSION
    require PATH_ZABBIX.'/include/defines.inc.php';

    /**
     * @brief   Path to the API.php class file of the Zabbix PHP front-end.
     *
     * This class file will be used, to determine all available API classes.
     */

    define('PATH_ZABBIX_API_CLASS_FILE', PATH_ZABBIX.'/include/classes/api/API.php');

    if(!file_exists(PATH_ZABBIX_API_CLASS_FILE))
        die('ERROR: API class file "'.PATH_ZABBIX_API_CLASS_FILE.'" not found! Please check the PATH_ZABBIX_API_CLASS_FILE configuration constant');

    /**
     * @brief   Path to the api/classes/ directory of the Zabbix PHP front-end.
     *
     * This directory and the contained class files will be used, to determine all
     * available methods for each API class.
     */

    if(version_compare(ZABBIX_API_VERSION, '2.4') >= 0)
        define('PATH_ZABBIX_API_CLASSES_DIRECTORY', PATH_ZABBIX.'/include/classes/api/services');
    else
        define('PATH_ZABBIX_API_CLASSES_DIRECTORY', PATH_ZABBIX.'/api/classes');

    if(!is_dir(PATH_ZABBIX_API_CLASSES_DIRECTORY))
        die('ERROR: API class directory "'.PATH_ZABBIX_API_CLASSES_DIRECTORY.'" not found!');

/*
 * Initialize.
 */

    // send HTTP header
    header('Content-type: text/plain; charset=utf-8');

    // set template placeholders
    $templatePlaceholders = array(
        'CLASSNAME_CONCRETE' => CLASSNAME_CONCRETE,
        'CLASSNAME_EXCEPTION' => CLASSNAME_EXCEPTION,
        'FILENAME_CONCRETE'  => FILENAME_CONCRETE,
        'FILENAME_EXCEPTION'  => FILENAME_EXCEPTION,
    );

/*
 * Create class-map class.
 *
 * Create a new class and extend it from the origin Zabbix classes, so that we
 * can fetch the class map directly from the Zabbix classes without defining
 * it here.
 *
 * There are some differences between the Zabbix versions:
 *
 *  < 2.4:  The class map is stored as a static property directly in the
 *          origin API class.
 *
 *  >= 2.4: The class map is stored as an instance property in the
 *          origin CApiServiceFactory class.
 */

    // load API
    require PATH_ZABBIX_API_CLASS_FILE;

    // create new class to fetch class map for API classes
    if(version_compare(ZABBIX_API_VERSION, '2.4') >= 0)
    {
        require PATH_ZABBIX.'/include/classes/core/CRegistryFactory.php';
        require PATH_ZABBIX.'/include/classes/api/CApiServiceFactory.php';
        require PATH_ZABBIX.'/include/classes/api/CApiService.php';

        class ZabbixApiClassMap extends CApiServiceFactory
        {
            public function getClassMap()
            {
                $classMap = $this->objects;
                return $classMap;
            }
        }
    }
    else
    {
        require PATH_ZABBIX.'/include/classes/api/CZBXAPI.php';
        class ZabbixApiClassMap extends API
        {
            public function getClassMap()
            {
                return self::$classMap;
            }
        }
    }

/*
 * Register SPL autoloader.
 *
 * The API class files always inherit from other classes. Most of the classes
 * inherit from the CZBXAPI class, but there are a bunch of classes which
 * are extended by other API classes.
 *
 * So that we don't have to "follow" the right order on loading API class files,
 * we're register an API autoloader right here.
 *
 * Later the get_class_methods() function will automatically invoke this
 * autoloader.
 */

    function __autoload($className)
    {
        require PATH_ZABBIX_API_CLASSES_DIRECTORY.'/'.$className.'.php';
    }

/*
 * Build API array.
 *
 * Just loop through all available API classes and find all defined methods in
 * these classes.
 */

    // initialze API array
    $apiArray = array();

    // Create new instance for API class map.
    $apiClassMap = new ZabbixApiClassMap();

    // loop through class map
    foreach($apiClassMap->getClassMap() as $resource => $class)
    {
        // add resource to API array
        $apiArray[$resource] = array();

        // create new reflection class
        $reflection = new ReflectionClass($class);

        // loop through defined methods
        foreach($reflection->getMethods(ReflectionMethod::IS_PUBLIC & ~ReflectionMethod::IS_STATIC) as $method)
        {
            // add action to API array
            if( $method->class != 'CZBXAPI'
                && !($resource == 'user' && $method->name == 'login')
                && !($resource == 'user' && $method->name == 'logout')
                && !$method->isConstructor()
                && !$method->isDestructor()
                && !$method->isAbstract()
            )
                $apiArray[$resource][] = $method->name;
        }

    }

/*
 * Build concrete template.
 */

    // get template
    if(!$template = file_get_contents(PATH_TEMPLATES.'/concrete.tpl.php'))
        die();

    // fetch API method block
    preg_match('/(.*)<!START_API_CONSTANT>(.*)<!END_API_CONSTANT>(.*)<!START_API_METHOD>(.*)<!END_API_METHOD>(.*)/s', $template, $matches);

    // sanity check
    if(count($matches) != 6)
        die('Template "'.PATH_TEMPLATES.'"/concrete.tpl.php parsing failed!');

    $defines = file_get_contents(PATH_ZABBIX.'/include/defines.inc.php');
    preg_match_all('{^define\(\'(?<constant_names>[^\']+)\',\s*(?!\);)(?<constant_values>.+)\)\;.*\n}m', $defines, $constantsArray);

    // initialize variable for API constants
    $apiConstants = '';

    $blacklistedConstants = array('HTTPS', 'ZBX_FONTPATH');

    // build API constants
    foreach($constantsArray['constant_names'] as $k => $name)
    {
        if (in_array($name, $blacklistedConstants, true)) {
            continue;
        }
        $value = $constantsArray['constant_values'][$k];

        foreach($constantsArray['constant_names'] as $declaredName) {
            if (false !== strpos($value, $declaredName)) {
                $value = \preg_replace('#\b'.$declaredName.'\b#', 'self::'.$declaredName, $value);
            }
        }
        $constantPlaceholders = array(
            'PHP_CONST_NAME' => $name,
            'PHP_CONST_VALUE' => $value,
        );
        $apiConstants .= replacePlaceholders($matches[2], $constantPlaceholders);
    }

    // initialize variable for API methods
    $apiMethods = '';

    $anonymousFunctions = [
        'apiinfo.version',
    ];

    // build API methods
    foreach($apiArray as $resource => $actions)
    {
        foreach($actions as $action)
        {
            $apiMethod = $resource.'.'.$action;
            $methodPlaceholders = array(
                'API_METHOD' => $apiMethod,
                'PHP_METHOD' => $resource.ucfirst($action),
                'IS_AUTHENTICATION_REQUIRED' => in_array($apiMethod, $anonymousFunctions, true) ? 'false' : 'true',
            );
            $apiMethods .= replacePlaceholders($matches[4], $methodPlaceholders);
        }
    }

    // build file content
    $fileContent = replacePlaceholders($matches[1].$apiConstants.$matches[3].$apiMethods.$matches[5], $templatePlaceholders);

    // write abstract class
    if(!file_put_contents(PATH_BUILD.'/'.FILENAME_CONCRETE, $fileContent))
        die();

    echo 'BUILT: concrete class file "'.PATH_BUILD.'/'.FILENAME_CONCRETE.'"'."\n";

/*
 * Build exception template.
 */
        // get template
        if(!$template = file_get_contents(PATH_TEMPLATES.'/exception.tpl.php'))
            die();

        // build file content
        $fileContent = replacePlaceholders($template, $templatePlaceholders);

        // write abstract class
        if(!file_put_contents(PATH_BUILD.'/'.FILENAME_EXCEPTION, $fileContent))
            die();

        echo 'BUILT: exception class file "'.PATH_BUILD.'/'.FILENAME_EXCEPTION.'"'."\n";

?>

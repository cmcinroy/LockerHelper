<?php
/**
 * Configuration class
 * Used for managing runtime configuration.
 *
 * Borrowed extensively from CakePHP(tm) Configuration design.
 */
namespace LockerHelper;

class Config
{
    /**
     * Array of values currently stored in Configure.
     *
     * @var array
     */
    protected static $_values = [
        'debug' => false
    ];

    /**
     * Flag to track whether or not ini_set exists.
     *
     * @return void
     */
    protected static $_hasIniSet = null;

    /**
     * Used to store a dynamic variable in Configure.
     *
     * Usage:
     * ```
     * Configure::write('One.key1', 'value of the Configure::One[key1]');
     * Configure::write(['One.key1' => 'value of the Configure::One[key1]']);
     * Configure::write('One', [
     *     'key1' => 'value of the Configure::One[key1]',
     *     'key2' => 'value of the Configure::One[key2]'
     * ]);
     *
     * Configure::write([
     *     'One.key1' => 'value of the Configure::One[key1]',
     *     'One.key2' => 'value of the Configure::One[key2]'
     * ]);
     * ```
     *
     * @param string|array $config The key to write, can be a dot notation value.
     * Alternatively can be an array containing key(s) and value(s).
     * @param mixed $value Value to set for var
     * @return bool True if write was successful
     */
    public static function write($config, $value = null)
    {
        if (!is_array($config)) {
            $config = [$config => $value];
        }

        static::$_values = $config;
        //foreach ($config as $name => $value) {
        //    var_dump($config);
        //    echo $name;
        //    echo $value;
        //    static::$_values = Hash::insert(static::$_values, $name, $value);
        //}

        if (isset($config['debug'])) {
            if (static::$_hasIniSet === null) {
                static::$_hasIniSet = function_exists('ini_set');
            }
            if (static::$_hasIniSet) {
                ini_set('display_errors', $config['debug'] ? 1 : 0);
            }
        }
        return true;
    }

    /**
     * Used to read information stored in Configure. It's not
     * possible to store `null` values in Configure.
     *
     * Usage:
     * ```
     * Configure::read('Name'); will return all values for Name
     * Configure::read('Name.key'); will return only the value of Configure::Name[key]
     * ```
     *
     * @param string|null $var Variable to obtain. Use '.' to access array elements.
     * @return mixed Value stored in configure, or null.
     */
    public static function read($var = null)
    {
        if ($var === null) {
            return static::$_values;
        }
        $var = explode('.', $var);
        return self::getValue($var, static::$_values);
    }

    /**
     * Returns true if given variable is set in Configure.
     *
     * @param string $var Variable name to check for
     * @return bool True if variable is there
     */
    public static function check($var)
    {
        if (empty($var)) {
            return false;
        }
        return static::read($var) !== null;
    }

    /**
     * Loads stored configuration information from a config file.
     *
     * @param string $key name of configuration resource to load.
     * @return bool False if file not found, true if load successful.
     */
    public static function load($key)
    {
        $file = static::_getFilePath($key, true);

        $return = include $file;
        if (is_array($return)) {
            return static::write($return);
        }
    }

    /**
     * Converts the provided $data into a string of PHP code that can
     * be used saved into a file and loaded later.
     *
     * @param string $key The identifier to write to.
     * @param array $data Data to dump.
     * @return bool Success
     */
    //public function dumpFile($key, array $data)
    //{
    //    $contents = '<?php' . "\n" . 'return ' . var_export($data, true) . ';';
    //
    //    $filename = static::_getFilePath($key);
    //    return file_put_contents($filename, $contents) > 0;
    //}

    /**
     * Get file path
     *
     * @param string $key The identifier to write to.
     * @param bool $checkExists Whether to check if file exists. Defaults to false.
     * @return string Full file path
     * @throws \Exception When files don't exist or when
     *  files contain '..' as this could lead to abusive reads.
     */
    protected static function _getFilePath($key, $checkExists = false)
    {
        if (strpos($key, '..') !== false) {
            throw new \Exception('Cannot load/dump configuration files with ../ in them.');
        }

        $file = $key;

        if (!$checkExists || is_file($file)) {
            return $file;
        }

        if (is_file(realpath($file))) {
            return realpath($file);
        }

        throw new \Exception(sprintf('Could not load configuration file: %s', $file));
    }

    /**
     * Navigate through a config array looking for a particular index
     * @param array $index The index sequence we are navigating down
     * @param array $value The portion of the config array to process
     * @return mixed
     * Borrowed from https://gist.github.com/treffynnon/563670
     */
    private static function getValue($index, $value) {
        if(is_array($index) and
           count($index)) {
            $current_index = array_shift($index);
        }
        if(is_array($index) and
           count($index) and
           is_array($value[$current_index]) and
           count($value[$current_index])) {
            return self::getValue($index, $value[$current_index]);
        } else {
            return $value[$current_index];
        }
    }
}
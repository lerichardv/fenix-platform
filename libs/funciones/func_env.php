<?php
/**
 * Simple Pure PHP .env Loader
 * Loads key-value pairs from .env into getenv(), $_ENV, and $_SERVER.
 */

if (!function_exists('load_env')) {
    function load_env($path = null)
    {
        static $loaded = false;
        if ($loaded && $path === null) {
            return;
        }

        if ($path === null) {
            // Find .env by starting at root of project
            $projectRoot = realpath(__DIR__ . '/../..');
            if ($projectRoot && file_exists($projectRoot . '/.env')) {
                $path = $projectRoot . '/.env';
            } else {
                // Fallback: search upward
                $dir = __DIR__;
                while ($dir && $dir !== '/' && $dir !== '.') {
                    if (file_exists($dir . '/.env')) {
                        $path = $dir . '/.env';
                        break;
                    }
                    $parent = dirname($dir);
                    if ($parent === $dir) {
                        break;
                    }
                    $dir = $parent;
                }
            }
        }

        if (!$path || !file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines or comments
            if ($line === '' || $line[0] === '#' || (strlen($line) >= 2 && $line[0] === '/' && $line[1] === '/')) {
                continue;
            }

            // Find key=value delimiter
            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }

            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            // Strip enclosing quotes if present
            if (strlen($value) >= 2) {
                $first = $value[0];
                $last = substr($value, -1);
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            // Convert common string representations
            switch (strtolower($value)) {
                case 'true':
                case '(true)':
                    $val = true;
                    break;
                case 'false':
                case '(false)':
                    $val = false;
                    break;
                case 'null':
                case '(null)':
                    $val = null;
                    break;
                case 'empty':
                case '(empty)':
                    $val = '';
                    break;
                default:
                    $val = $value;
                    break;
            }

            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $val;
            }
            if (!array_key_exists($key, $_SERVER)) {
                $_SERVER[$key] = $val;
            }
            if (is_scalar($val)) {
                putenv("{$key}={$val}");
            }
        }

        $loaded = true;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }

        if (array_key_exists($key, $_SERVER)) {
            return $_SERVER[$key];
        }

        $val = getenv($key);
        if ($val !== false) {
            return $val;
        }

        return $default;
    }
}

// Automatically load on include
load_env();

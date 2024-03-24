<?php
namespace Utils;

class Env {
    public static function load() {
        if (!file_exists('.env')) {
            return false;
        }

        $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0 || $line === '') {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }

        return true;
    }

    public static function base_url($path = '') {
        $base_path = rtrim($_ENV['BASE_PATH'], '/'); 
        $formattedPath = ltrim($path, '/'); 
        
        if (substr($formattedPath, 0, strlen($base_path)) !== $base_path) {
            return $base_path . '/' . $formattedPath;
        } else {
            return '/' . $formattedPath; 
        }
    }
    
}

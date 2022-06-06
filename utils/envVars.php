<?php
/**
 * Gets the environment variables. If the utils/.env exists, get environment
 * variables from .env. Otherwise, get variables from getenv function
 * @param array $vars The required environment variable names.
 * @return array An associative array containing the environment variable name=>value
 */
function getEnvVars(array $vars): array
{
    if (file_exists(__DIR__ . '/.env')) {
        $envVars = parse_ini_file('.env');
        if (!$envVars) return [];
        return $envVars;
    }
    $envVars = [];
    foreach ($vars as $var) {
        $envVars[$var] = getenv($var);
    }
    return $envVars;
}

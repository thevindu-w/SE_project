<?php
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

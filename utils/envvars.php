<?php
function getEnvVars(array $vars): array
{
    if (file_exists(__DIR__ . '/.env')) {
        $envvars = parse_ini_file('.env');
        if (!$envvars) return [];
        return $envvars;
    }
    $envvars = [];
    foreach ($vars as $var) {
        $envvars[$var] = getenv($var);
    }
    return $envvars;
}

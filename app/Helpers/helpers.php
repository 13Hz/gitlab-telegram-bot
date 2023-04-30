<?php

if (!function_exists('get_sqlite_db_path')) {
    function get_sqlite_db_path(string $databaseName): string
    {
        $path = sys_get_temp_dir()."/$databaseName";
        if (!file_exists($path)) {
            touch($path);
        }

        return $path;
    }
}

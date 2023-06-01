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

if (!function_exists('declOfNum')) {
    /**
     * Функция склонения числительных в русском языке
     *
     * @param int $number Число которое нужно просклонять
     * @param array $titles Массив слов для склонения ['%d комментарий', '%d комментария', '%d комментариев']
     * @return string
     */
    function declOfNum(int $number, array $titles): string
    {
        $cases = [2, 0, 1, 1, 1, 2];
        $format = $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];

        return sprintf($format, $number);
    }
}

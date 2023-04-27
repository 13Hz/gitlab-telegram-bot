<?php

namespace App\Models\Core;

class InlineKeyboard extends \Longman\TelegramBot\Entities\InlineKeyboard
{
    public function __construct($rows)
    {
        $data = $this->createFromRows($rows);
        parent::__construct($data);

        $this->{$this->getKeyboardType()} = array_filter($this->{$this->getKeyboardType()});
    }

    private function createFromRows($args): array
    {
        $keyboardType = $this->getKeyboardType();

        foreach ($args as &$arg) {
            !is_array($arg) && $arg = [$arg];
        }
        unset($arg);

        $data = reset($args);

        if ($fromData = array_key_exists($keyboardType, (array) $data)) {
            $args = $data[$keyboardType];

            if (!is_array($args)) {
                $args = [];
            }
        }

        $newKeyboard = [];
        foreach ($args as $row) {
            $newKeyboard[] = $this->parseRow($row);
        }

        if (!empty($newKeyboard)) {
            if (!$fromData) {
                $data = [];
            }
            $data[$keyboardType] = $newKeyboard;
        }

        return $data ?: [];
    }
}

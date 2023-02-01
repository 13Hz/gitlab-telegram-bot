<?php

namespace App\Models;

class InlineKeyboard extends \Longman\TelegramBot\Entities\InlineKeyboard {
    public function __construct($rows)
    {
        $data = $this->createFromRows($rows);
        parent::__construct($data);

        $this->{$this->getKeyboardType()} = array_filter($this->{$this->getKeyboardType()});
    }

    private function createFromRows($args): array
    {
        $keyboard_type = $this->getKeyboardType();

        foreach ($args as &$arg) {
            !is_array($arg) && $arg = [$arg];
        }
        unset($arg);

        $data = reset($args);

        if ($from_data = array_key_exists($keyboard_type, (array) $data)) {
            $args = $data[$keyboard_type];

            if (!is_array($args)) {
                $args = [];
            }
        }

        $new_keyboard = [];
        foreach ($args as $row) {
            $new_keyboard[] = $this->parseRow($row);
        }

        if (!empty($new_keyboard)) {
            if (!$from_data) {
                $data = [];
            }
            $data[$keyboard_type] = $new_keyboard;
        }

        return $data ?: [];
    }
}

<?php

namespace Sun\Settings\DTO;

class SettingDTO
{
    private $value;
    private $localeValue;

    public function __construct($value = null, $localeValue = null)
    {
        $this->value = $value;
        $this->localeValue = $localeValue;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getLocaleValue()
    {
        return $this->localeValue;
    }

    public function setLocaleValue($localeValue): self
    {
        $this->localeValue = $localeValue;
        return $this;
    }

    public static function createFromData(array $data): self
    {
        $value = $data['value'] ?? null;
        $localeValue = $data['locale_value'] ?? null;

        $value = $value ? json_decode($value, true) : null;
        $localeValue = $localeValue ? json_decode($localeValue, true) : null;

        return new static($value, $localeValue);
    }
}

<?php

namespace Sun\Settings\DTO;

use JsonSerializable;

class SettingWithDefaultDTO implements JsonSerializable
{
    private ?SettingDTO $setting;
    private $default;

    public function __construct(?SettingDTO $setting, $default = null)
    {
        $this->setting = $setting;
        $this->default = $default;
    }

    public function getSetting(): ?SettingDTO
    {
        return $this->setting;
    }

    public function setSetting(?SettingDTO $setting): self
    {
        $this->setting = $setting;
        return $this;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($default): self
    {
        $this->default = $default;
        return $this;
    }

    public function getPrioritySetting($currentDefault = null)
    {
        $value = $this->setting ? $this->setting->getValue() : null;
        $localeValue = $this->setting ? $this->setting->getLocaleValue() : null;
        return $localeValue ?? $value ?? $currentDefault ?? $this->default;
    }

    public function jsonSerialize()
    {
        $value = $this->setting ? $this->setting->getValue() : null;
        $localeValue = $this->setting ? $this->setting->getLocaleValue() : null;

        return [
            'value' => $value ?? $this->default,
            'locale_value' => $localeValue,
        ];
    }
}

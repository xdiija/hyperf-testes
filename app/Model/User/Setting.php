<?php declare(strict_types=1);

namespace App\Model\User;

use Hyperf\DbConnection\Model\Model;

class Setting extends Model
{
    private string $key;
    private string $value;
    private string $visibility;

    public function getKey(): string
    {
        return $this->key ?? '';
    }

    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    public function getValue(): string
    {
        return $this->value ?? '';
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function getVisibility(): string
    {
        return $this->visibility ?? '';
    }

    public function setVisibility(?string $visibility): void
    {
        $this->visibility = $visibility;
    }

    public static function fromArray($array){

        $setting = new Setting();
        $setting->setKey($array['key']);
        $setting->setValue($array['value']);
        $setting->setVisibility($array['visibility']);
        return $setting;

    }

}
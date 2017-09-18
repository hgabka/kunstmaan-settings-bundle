<?php

namespace Hgabka\KunstmaanSettingsBundle\Choices;

use Hgabka\KunstmaanExtensionBundle\Choices\ConstantsChoiceList;

class SettingTypes extends ConstantsChoiceList
{
    const STR = 'str';
    const INT = 'int';
    const BOOL = 'bool';
    const EMAIL = 'email';
    const FLOAT = 'float';

    public static function getI18nPrefix()
    {
        return 'hgabka_kuma_settings.types.';
    }
}

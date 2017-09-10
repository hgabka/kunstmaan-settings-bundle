<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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

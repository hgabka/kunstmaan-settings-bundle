<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanSettingsBundle\Validator;

use Symfony\Component\Validator\Constraint;

class Setting extends Constraint
{
    public $name;
    public $comparator = '=';

    public function validatedBy()
    {
        return SettingValidator::class;
    }
}

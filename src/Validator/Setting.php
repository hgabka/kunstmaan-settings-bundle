<?php
/**
 * Created by PhpStorm.
 * User: sfhun
 * Date: 2017.09.02.
 * Time: 8:04
 */

namespace Hgabka\KunstmaanSettingsBundle\Validator;

use Symfony\Component\Validator\Constraint;

class SystemSetting extends Constraint
{
    public $name;
    public $comparator = '=';

    public function validatedBy()
    {
        return SettingValidator::class;
    }
}

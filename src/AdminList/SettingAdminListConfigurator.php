<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanSettingsBundle\AdminList;

use Doctrine\ORM\EntityManager;
use Hgabka\KunstmaanSettingsBundle\Entity\Setting;
use Hgabka\KunstmaanSettingsBundle\Form\SettingAdminType;
use Hgabka\KunstmaanSettingsBundle\Helper\SettingsManager;
use Kunstmaan\AdminBundle\Helper\Security\Acl\AclHelper;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractDoctrineORMAdminListConfigurator;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * The admin list configurator for Setting.
 */
class SettingAdminListConfigurator extends AbstractDoctrineORMAdminListConfigurator
{
    /** @var AuthorizationChecker */
    private $authChecker;

    /** @var SettingsManager */
    private $settingsManager;

    /**
     * @param EntityManager $em        The entity manager
     * @param AclHelper     $aclHelper The acl helper
     */
    public function __construct(EntityManager $em, AuthorizationChecker $authChecker, SettingsManager $manager, AclHelper $aclHelper = null)
    {
        parent::__construct($em, $aclHelper);
        $this->setAdminType(new SettingAdminType($authChecker, $manager));
        $this->authChecker = $authChecker;
        $this->settingsManager = $manager;
    }

    /**
     * Configure the visible columns.
     */
    public function buildFields()
    {
        $this->addField('name', 'Név', true);
        $this->addField('description', 'Leírás', true);
        $this->addField('value', 'Érték', true, 'HgabkaKunstmaanSettingsBundle:Field:value_field.html.twig');
    }

    /**
     * Build filters for admin list.
     */
    public function buildFilters()
    {
    }

    /**
     * Get bundle name.
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'HgabkaKunstmaanSettingsBundle';
    }

    /**
     * Get entity name.
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'Setting';
    }

    /**
     * @return bool
     */
    public function canAdd()
    {
        return $this->authChecker->isGranted('ROLE_SUPER_ADMIN');
    }

    /**
     * @param array|object $item
     *
     * @return bool
     */
    public function canDelete($item)
    {
        return $this->authChecker->isGranted('ROLE_SUPER_ADMIN');
    }

    public function getListTitle()
    {
        return 'Rendszerbeállítások';
    }

    /**
     * Returns edit title.
     *
     * @return null|string
     */
    public function getEditTitle()
    {
        return 'Beállítás szerkesztése';
    }

    /**
     * Returns new title.
     *
     * @return null|string
     */
    public function getNewTitle()
    {
        return 'Új beállítás';
    }
}

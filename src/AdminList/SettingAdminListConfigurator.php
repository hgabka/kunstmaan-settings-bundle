<?php
/**
 * Created by PhpStorm.
 * User: sfhun
 * Date: 2017.09.02.
 * Time: 19:27
 */

namespace Hgabka\KunstmaanSettingsBundle\AdminList;

use Doctrine\ORM\EntityManager;
use Hgabka\KunstmaanSettingsBundle\Entity\Setting;
use Hgabka\KunstmaanSettingsBundle\Form\SettingAdminType;
use Hgabka\KunstmaanSettingsBundle\Helper\SettingsManager;
use Kunstmaan\AdminBundle\Helper\Security\Acl\AclHelper;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractDoctrineORMAdminListConfigurator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * The admin list configurator for Setting
 */
class SettingAdminListConfigurator extends AbstractDoctrineORMAdminListConfigurator
{
    /** @var AuthorizationChecker  */
    private $authChecker;

    /** @var  SettingsManager */
    private $settingsManager;

    /**
     * @param EntityManager $em The entity manager
     * @param AclHelper $aclHelper The acl helper
     */
    public function __construct(EntityManager $em, AuthorizationChecker $authChecker, SettingsManager $manager, AclHelper $aclHelper = null)
    {
        parent::__construct($em, $aclHelper);
        $this->setAdminType(new SettingAdminType($authChecker, $manager));
        $this->authChecker = $authChecker;
        $this->settingsManager = $manager;
    }

    /**
     * Configure the visible columns
     */
    public function buildFields()
    {
        $this->addField('name', 'Név', true);
        $this->addField('description', 'Leírás', true);
        $this->addField('value', 'Érték', true);
    }

    /**
     * Build filters for admin list
     */
    public function buildFilters()
    {
    }

    /**
     * Get bundle name
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'HgabkaKunstmaanSettingsBundle';
    }

    /**
     * Get entity name
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
}

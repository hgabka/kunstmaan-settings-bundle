<?php

namespace Hgabka\KunstmaanSettingsBundle\Controller;

use Hgabka\KunstmaanSettingsBundle\AdminList\SettingAdminListConfigurator;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AdminListConfiguratorInterface;
use Kunstmaan\AdminListBundle\Controller\AdminListController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * The admin list controller for Setting.
 */
class SettingAdminListController extends AdminListController
{
    /**
     * @var AdminListConfiguratorInterface
     */
    private $configurator;

    /**
     * @return AdminListConfiguratorInterface
     */
    public function getAdminListConfigurator()
    {
        if (!isset($this->configurator)) {
            $this->configurator = new SettingAdminListConfigurator(
                $this->getEntityManager(),
                $this->get('security.authorization_checker'),
                $this->get('hgabka_kunstmaan_settings.settings_manager'),
                $this->container->getParameter('hgabka_kunstmaan_settings.editor_role')
            );
        }

        return $this->configurator;
    }

    /**
     * The index action.
     *
     * @Route("/", name="hgabkakunstmaansettingsbundle_admin_setting")
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted($this->container->getParameter('hgabka_kunstmaan_settings.editor_role'));

        return parent::doIndexAction($this->getAdminListConfigurator(), $request);
    }

    /**
     * The add action.
     *
     * @Route("/add", name="hgabkakunstmaansettingsbundle_admin_setting_add")
     * @Method({"GET", "POST"})
     *
     * @return array
     */
    public function addAction(Request $request)
    {
        return parent::doAddAction($this->getAdminListConfigurator(), null, $request);
    }

    /**
     * The edit action.
     *
     * @param int $id
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="hgabkakunstmaansettingsbundle_admin_setting_edit")
     * @Method({"GET", "POST"})
     *
     * @return array
     */
    public function editAction(Request $request, $id)
    {
        return parent::doEditAction($this->getAdminListConfigurator(), $id, $request);
    }

    /**
     * The edit action.
     *
     * @param int $id
     *
     * @Route("/{id}", requirements={"id" = "\d+"}, name="hgabkakunstmaansettingsbundle_admin_setting_view")
     * @Method({"GET"})
     *
     * @return array
     */
    public function viewAction(Request $request, $id)
    {
        return parent::doViewAction($this->getAdminListConfigurator(), $id, $request);
    }

    /**
     * The delete action.
     *
     * @param int $id
     *
     * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="hgabkakunstmaansettingsbundle_admin_setting_delete")
     * @Method({"GET", "POST"})
     *
     * @return array
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::doDeleteAction($this->getAdminListConfigurator(), $id, $request);
    }

    /**
     * The export action.
     *
     * @param string $_format
     *
     * @Route("/export.{_format}", requirements={"_format" = "csv|xlsx"}, name="hgabkakunstmaansettingsbundle_admin_setting_export")
     * @Method({"GET", "POST"})
     *
     * @return array
     */
    public function exportAction(Request $request, $_format)
    {
        return parent::doExportAction($this->getAdminListConfigurator(), $_format, $request);
    }
}

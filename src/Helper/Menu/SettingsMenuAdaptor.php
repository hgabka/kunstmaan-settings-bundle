<?php

namespace Hgabka\KunstmaanSettingsBundle\Helper\Menu;

use Kunstmaan\AdminBundle\Helper\Menu\MenuAdaptorInterface;
use Kunstmaan\AdminBundle\Helper\Menu\MenuBuilder;
use Kunstmaan\AdminBundle\Helper\Menu\MenuItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SettingsMenuAdaptor implements MenuAdaptorInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /** @var string */
    protected $editorRole;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, string $editorRole)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->editorRole = $editorRole;
    }

    /**
     * In this method you can add children for a specific parent, but also remove and change the already created children.
     *
     * @param MenuBuilder   $menu      The MenuBuilder
     * @param MenuItem[]    &$children The current children
     * @param null|MenuItem $parent    The parent Menu item
     * @param Request       $request   The Request
     */
    public function adaptChildren(MenuBuilder $menu, array &$children, MenuItem $parent = null, Request $request = null)
    {
        if (null === $parent || !$this->authorizationChecker->isGranted($this->editorRole)) {
            return;
        } elseif ('KunstmaanAdminBundle_settings' === $parent->getRoute()) {
            $menuItem = new MenuItem($menu);
            $menuItem
                ->setRoute('hgabkakunstmaansettingsbundle_admin_setting')
                ->setUniqueId('setting')
                ->setLabel('Rendszerbeállítások')
                ->setParent($parent);
            if (0 === stripos($request->attributes->get('_route'), $menuItem->getRoute())) {
                $menuItem->setActive(true);
                $parent->setActive(true);
            }

            array_unshift($children, $menuItem);
        } elseif ('setting' == $parent->getUniqueId()) {
            $this->addMenuForSubRoute($menu, $parent, 'Új beállítás', 'hgabkakunstmaansettingsbundle_admin_setting_add', $children, $request);
            $this->addMenuForSubRoute($menu, $parent, 'Beállítás szerkesztése', 'hgabkakunstmaansettingsbundle_admin_setting_edit', $children, $request);
        }
    }

    protected function addMenuForSubRoute($menu, $parent, $label, $route, &$children, $request)
    {
        $menuItem = new MenuItem($menu);
        $menuItem->setUniqueId($route);
        $menuItem->setRoute($route);
        $menuItem->setLabel($label)->setAppearInNavigation(false)->setParent($parent);
        if (0 === stripos($request->attributes->get('_route'), $menuItem->getRoute())) {
            $menuItem->setActive(true);
        }

        $children[] = $menuItem;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: sfhun
 * Date: 2017.09.02.
 * Time: 19:36
 */

namespace Hgabka\KunstmaanSettingsBundle\Helper\Menu;

use Kunstmaan\AdminBundle\Helper\Menu\MenuAdaptorInterface;
use Kunstmaan\AdminBundle\Helper\Menu\MenuBuilder;
use Kunstmaan\AdminBundle\Helper\Menu\MenuItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SettingsMenuAdaptor  implements MenuAdaptorInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * In this method you can add children for a specific parent, but also remove and change the already created children
     *
     * @param MenuBuilder   $menu      The MenuBuilder
     * @param MenuItem[]    &$children The current children
     * @param MenuItem|null $parent    The parent Menu item
     * @param Request       $request   The Request
     */
    public function adaptChildren(MenuBuilder $menu, array &$children, MenuItem $parent = null, Request $request = null)
    {
        if (is_null($parent) || !$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return;
        } elseif ('KunstmaanAdminBundle_settings' == $parent->getRoute()) {
            $menuItem = new MenuItem($menu);
            $menuItem
                ->setRoute('hgabkakunstmaansettingsbundle_admin_setting')
                ->setUniqueId('setting')
                ->setLabel('Rendszerbeállítások')
                ->setParent($parent);
            if (stripos($request->attributes->get('_route'), $menuItem->getRoute()) === 0) {
                $menuItem->setActive(true);
                $parent->setActive(true);
            }
            $children[] = $menuItem;
        }
    }
}
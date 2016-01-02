<?php

namespace MonitorWebBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    /** @var FactoryInterface */
    protected $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        $menu->addChild('Dashboard', [
            'route' => 'monitor_web.dashboard.show',
            'routeParameters' => [],
            'attributes' => ['class' => 'nav_main__item'],
            'linkAttributes' => ['class' => 'nav_main__item-link'],
        ]);

        $menu->addChild('Настройки', [
            'route' => 'monitor_web.settings.show',
            'routeParameters' => [],
            'attributes' => ['class' => 'nav_main__item'],
            'linkAttributes' => ['class' => 'nav_main__item-link'],
        ]);

        $menu->addChild('Профиль', [
            'route' => 'monitor_web.profile.show',
            'routeParameters' => [],
            'attributes' => ['class' => 'nav_main__item'],
            'linkAttributes' => ['class' => 'nav_main__item-link'],
        ]);

        $menu->addChild('Помощь', [
            'route' => 'monitor_web.static_page.help.show',
            'routeParameters' => [],
            'attributes' => ['class' => 'nav_main__item'],
            'linkAttributes' => ['class' => 'nav_main__item-link'],
        ]);

        $menu->addChild('Выход', [
            'route' => 'fos_user_security_logout',
            'routeParameters' => [],
            'attributes' => ['class' => 'nav_main__item'],
            'linkAttributes' => ['class' => 'nav_main__item-link'],
        ]);

        return $menu;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createLeftMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-sidebar');
        $menu->addChild('Test', [
            'route' => 'monitor_web.profile.show',
            'routeParameters' => [],
        ]);
        $menu->addChild('Test2', [
            'route' => 'monitor_web.static_page.help.show',
            'routeParameters' => [],
        ]);

        return $menu;
    }
}

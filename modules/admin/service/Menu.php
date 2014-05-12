<?php

namespace Admin\Service;

class Menu extends \KF\Lib\Module\Service {

    /**
     * @var MenuItem
     */
    public $serviceMenuItem;

    public function __construct() {
        $this->_model = '\Admin\Model\Menu';
        $this->serviceMenuItem = new MenuItem();
    }

    public function saveFromJsTree(&$row) {
        try {
            $jsonItems = $row['menu'];
            unset($row['menu']);
            
            // Save Menu
            $saved = parent::save($row);
            
            $childsDeleted = $this->serviceMenuItem->delete(['menu' => $row['cod']]);
            
            // Save MenuItems
            $row['menu'] = $this->arrayFromJsTree([$jsonItems], ['save' => 'true', 'menu' => $row['cod']]);

            return $saved;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function arrayFromJsTree($menu = [], $options = [], $parent = null) {
        try {
            $menuFull = [];

            foreach ($menu as $i => $item) {
                if (isset($options['save']) && $options['save']) {
                    $row = ['name' => $item['text'], 'menu' => $options['menu']];
                    if ($parent) {
                        $row['parent'] = $parent;
                    }
                    
                    if(isset($item['data']) && isset($item['data']['link']) && $item['data']['link']) {
                        $row['link'] = $item['data']['link'];
                        $menuFull[$i]['link'] = $item['data']['link'];
                    }
                    
                    if ($this->serviceMenuItem->save($row)) {
                        $i = $row['cod'];
                        $menuFull[$i]['cod'] = $i;
                    }

                    $menuFull[$i]['name'] = $item['text'];
                    $menuFull[$i]['children'] = [];
                    
                    $options['menu-saved'] = $menuFull;

                    if (isset($item['children'])) {
                        if (isset($options['save']) && $options['save'] && isset($row['cod'])) {
                            $options = array_merge($options, ['parent' => $row['cod']]);
                        }
                        $menuFull[$i]['children'] = $this->arrayFromJsTree($item['children'], $options, $i);
                    }
                }
            }

            return $menuFull;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function arrayFromDb($menuDb = []) {
        try {
            $menu = [];
            $options = [];
            foreach ($menuDb as $item) {
                $menuItem = ['cod' => $item['cod'], 'name' => $item['name'], 'children' => [], 'data' => ['link' => $item['link']]];
                $options['found'] = false;
                $this->array_addChildToParent($menuItem, $item['parent'], $menu, $options);
            }
            return $menu;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function array_addChildToParent($child, $parentCod, &$menu, &$options = []) {
        try {
            if (isset($options['root']) && $options['root'] && $parentCod && isset($options['found']) && !$options['found']) {
                if (isset($menu['cod'])) {
                    if ($menu['cod'] === $parentCod) {
                        $options['found'] = true;
                        $menu['children'][$child['cod']] = $child;
                    }
                }
                if (isset($menu['children']) && !$options['found']) {
                    foreach ($menu['children'] as &$item) {
                        $this->array_addChildToParent($child, $parentCod, $item, $options);
                    }
                }
            } else {
                if (!$parentCod) {
                    $menu = $child;
                    $options['root'] = true;
                    $options['found'] = false;
                }
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}

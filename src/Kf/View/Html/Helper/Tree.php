<?php

namespace Kf\View\Html\Helper;

class Tree extends Helper {

    public function __invoke($data) {
        try {
            return self::render($data);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function render($data) {
        try {
            $rootFound = false;
            $menu = '';
            self::parseMenu([$data], $menu, $rootFound);
            return $menu;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function parseMenu($data, &$menu, &$rootFound) {
        try {
            foreach ($data as $item) {
                if (!$rootFound) {
                    $menu.= " <li data-jstree='{\"opened\": true}' data-menu-root='true' id='root'><a id='root-item' href='#'>{$item['name']}</a>";
                    $rootFound = true;
                } else {
                    $icon = 'file';
                    $data = '';
                    
                    if(isset($item['children']) && count($item['children'])) {
                        $icon = 'folder-close';
                    }
                    
                    if(isset($item['data']) && isset($item['data']['link']) && $item['data']['link']) {
                        $data.= " data-link='{$item['data']['link']}'";
                    }
                    
                    $menu.= " <li data-jstree='{\"icon\": \"glyphicon glyphicon-{$icon} glyphicon-tree-use\"}' {$data} id='{$item['cod']}'><a href='#'>{$item['name']}</a>";
                }
                if (isset($item['children']) && count($item['children'])) {
                    $menu.= "<ul>";
                    self::parseMenu($item['children'], $menu, $rootFound);
                    $menu.= "</ul>";
                }
                $menu.= "</li> ";
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}

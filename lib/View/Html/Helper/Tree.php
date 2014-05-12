<?php

namespace KF\Lib\View\Html\Helper;

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
            self::parseMenu([$data], $menu);
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
                    if(isset($item['children']) && count($item['children'])) {
                        $icon = 'folder-close';
                    }
                    $menu.= " <li data-jstree='{\"icon\": \"glyphicon glyphicon-{$icon} glyphicon-tree-use\"}' id='{$item['cod']}'><a href='#'>{$item['name']}</a>";
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

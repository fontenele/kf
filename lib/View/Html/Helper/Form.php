<?php

namespace KF\Lib\View\Html\Helper;

class Form extends Helper {

    public function __invoke() {
        return $this;
    }

    public function openTag($id = null, $attrs = []) {
        $return = "<form ";
        if($id) {
            $return.= "id='{$id}' ";
        }
        if(count($attrs)) {
            foreach($attrs as $attr => $value) {
                $return.= "{$attr}='{$value}' ";
            }
        }
        return "{$return}>";
    }
    
    public function closeTag() {
        return '</form>';
    }
    
    public function submit() {
        return new \KF\Lib\View\Html\Button('btn-search', 'Pesquisar', ['class' => 'btn-primary']);
    }

}

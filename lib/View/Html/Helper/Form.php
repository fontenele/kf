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
    
    public function submit($label = 'Enviar', $id = 'btn-submit') {
        return new \KF\Lib\View\Html\Button($id, $label, ['class' => 'btn-primary']);
    }

}

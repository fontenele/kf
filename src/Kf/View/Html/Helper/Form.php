<?php

namespace KF\View\Html\Helper;

class Form extends Helper {

    public function __invoke() {
        return $this;
    }

    public function openTag($id = null, $attrs = []) {
        $return = "<form ";
        if ($id) {
            $return.= "id='{$id}' ";
        }
        if (count($attrs)) {
            foreach ($attrs as $attr => $value) {
                $return.= "{$attr}='{$value}' ";
            }
        }
        return "{$return}>";
    }

    public function closeTag() {
        return '</form>';
    }

    public function submit($label = 'Enviar', $id = 'btn-submit') {
        return new \KF\View\Html\Button($id, $label, ['class' => 'btn-primary']);
    }

    public function submitSave($label = 'Enviar', $id = 'btn-save') {
        $content = Glyphicon::get('ok') . ' &nbsp;' . $label;
        return \KF\View\Html\Button::create($id, $label, ['class' => 'btn-primary'])->setContent($content);
    }

    public function submitSearch($label = 'Pesquisar', $id = 'btn-search') {
        $content = Glyphicon::get('search') . ' &nbsp;' . $label;
        return \KF\View\Html\Button::create($id, $label, ['class' => 'btn-primary'])->setContent($content);
    }

}

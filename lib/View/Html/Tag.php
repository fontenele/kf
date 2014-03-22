<?php

namespace KF\Lib\View\Html;

class Tag extends \KF\Lib\System\ArrayObject {

    /**
     * @var string
     */
    protected $tag;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $label;

    /**
     * @var boolean
     */
    public $closeTagAfter = false;

    public function __construct($tag, $name, $label = null) {
        try {
            $this->tag = $tag;
            $this->name = $name;
            $this->label = $label;
            $this->class = 'form-control';
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function __toString() {
        try {
            return ( $this->label ? $this->label() : '' ) . $this->render(true);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function label($class = null, $hidden = false) {
        try {
            if ($hidden) {
                $class.= ' sr-only';
            }
            return "<label class='control-label {$class}' for='{$this->name}'>{$this->label}</label>";
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function render($value = null, $static = false) {
        try {
            $this->value = $value ? $value : $this->value;
            if ($static) {
                return "<p class='form-control-static'>{$this->value}</p>";
            }
            $html = "<{$this->tag} ";
            foreach ($this as $attr => $value) {
                $html.= "{$attr}='{$value}' ";
            }
            $html.= $this->closeTagAfter ? ">{$this->content}</{$this->tag}>" : " />";
            return $html;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}

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
     * @var array
     */
    public $class = [];

    /**
     * @var boolean
     */
    public $closeTagAfter = false;

    public function __construct($tag, $name, $label = null) {
        try {
            $this->tag = $tag;
            $this->name = $name;
            $this->label = $label;
            $this->addClass('form-control');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function __toString() {
        try {
            return ( $this->label ? $this->label() : '' ) . $this->render();
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

    public function addClass($classname) {
        try {
            $this->class[$classname] = $classname;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function removeClass($classname) {
        try {
            if ($this->hasClass($classname)) {
                unset($this->class[$classname]);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function hasClass($classname) {
        try {
            return isset($this->class[$classname]);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function render($value = null, $static = false) {
        try {
            if (\KF\Kernel::$request->post->offsetGet($this->name)) {
                $this->setValue(\KF\Kernel::$request->post->offsetGet($this->name));
            }
            if ($value) {
                $this->setValue($value);
            }
            if ($static) {
                return "<p class='form-control-static'>{$this->value}</p>";
            }
            $html = "<{$this->tag} ";

            $html.= "class='";
            foreach ($this->class as $className) {
                $html.= "{$className} ";
            }
            $html.= "' ";

            foreach ($this as $attr => $value) {
                $html.= "{$attr}='{$value}' ";
            }

            $html.= $this->closeTagAfter ? ">{$this->content}</{$this->tag}>" : " />";
            return $html;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function formGroup($class = null, $labelClass = null, $componentClass = null) {
        try {
            $return = "<div class='form-group {$class}'>";
            $return.= $this->label($labelClass);
            $return.= "<div class='{$componentClass}'>";
            $return.= $this->render();
            $return.= "</div>";
            $return.= "</div>";
            return $return;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}

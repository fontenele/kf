<?php

namespace Kf\View\Html;

class Tag extends \Kf\System\ArrayObject {

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
            $this->data = [];
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
            $classname = explode(' ', $classname);
            foreach ($classname as $_classname) {
                $this->class[$_classname] = $_classname;
            }
            return $this;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function removeClass($classname) {
        try {
            if ($this->hasClass($classname)) {
                unset($this->class[$classname]);
            }
            return $this;
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
            if (\Kf\Kernel::$request->post->offsetGet($this->name)) {
                $this->setValue(\Kf\Kernel::$request->post->offsetGet($this->name));
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
                if (is_array($value)) {
                    foreach ($value as $subattr => $subvalue) {
                        $html.= "{$attr}-{$subattr}='{$subvalue}' ";
                    }
                } else {
                    $html.= "{$attr}='{$value}' ";
                }
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

    public function getTitle() {
        return $this->title;
    }

    public function getRequired() {
        return $this->required;
    }

    public function getPlaceholder() {
        return $this->placeholder;
    }

    public function getValue() {
        if ($this instanceof Select) {
            return $this->getSelected();
        }
        return $this->value;
    }

    /**
     * @param string $title
     * @return \Kf\View\Html\Input
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @param bool $required
     * @return \Kf\View\Html\Input
     */
    public function setRequired($required) {
        if (!$required) {
            $this->offsetUnset('required');
        } else {
            $this->required = $required;
        }
        return $this;
    }

    /**
     * @param string $placeholder
     * @return \Kf\View\Html\Input
     */
    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @param string $value
     * @return \Kf\View\Html\Tag
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function addData($param, $value = null) {
        $data = $this->data;
        $data[$param] = $value;
        $this->data = $data;
        return $this;
    }

    public function getData($param) {
        return isset($this->data[$param]) ? $this->data[$param] : null;
    }

    public function attr($attr, $val) {
        if ($val) {
            $this->{$attr} = $val;
            return $this;
        } else {
            return $this->{$attr};
        }
    }

}

<?php
// https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/HTML5/HTML5_element_list
// https://developer.mozilla.org/en-US/docs/Web/HTML/Element?redirectlocale=en-US&redirectslug=HTML%2FElement
define('ELEMENTS_ROOT_ELEMENT', 'html');
define('ELEMENTS_DOCUMENT_METADATA', 'head|title|base|link|meta|stype');
define('ELEMENTS_SCRIPTING', 'script|noscript');
define('ELEMENTS_SECTIONS', 'body|section|nav|article|aside|h1|h2|h3|h4|h5|h6|header|footer|address|main');
define('ELEMENTS_GROUPING_CONTENT', 'p|hr|pre|blockquote|ol|ul|li|dl|dt|dd|figure|figcaption|div');
define('ELEMENTS_TEXTLEVEL_SEMANTICS', 'a|em|strong|small|s|cite|q|dfn|abbr|data|time|code|var|samp|kbd|sub|sup|i|b|u|mark|ruby|rt|rp|bdi|bdo|span|br|wbr');
define('ELEMENTS_EDITS', 'ins|del');
define('ELEMENTS_EMBEDDED_CONTENT', 'img|iframe|embed|object|video|audio|source|track|canvas|map|area|svg|math');
define('ELEMENTS_TABULAR_DATA', 'table|caption|colgroup|col|tbody|thead|tfoot|tr|td|th');
define('ELEMENTS_FORMS', 'form|fieldset|legend|label|input|button|select|datalist|optgroup|option|textarea|keygen|output|progress|meter');
define('ELEMENTS_INTERACTIVE_ELEMENTS', 'details|summary|command|menu');
define('ELEMENTS_VOID', 'area|base|br|col|command|embed|hr|img|input|keygen|link|meta|param|source|track|wbr');
define('ELEMENTS', ELEMENTS_ROOT_ELEMENT . '|' . ELEMENTS_DOCUMENT_METADATA . '|' . ELEMENTS_SCRIPTING . '|' . ELEMENTS_SECTIONS . '|' . ELEMENTS_GROUPING_CONTENT . '|' . ELEMENTS_TEXTLEVEL_SEMANTICS . '|' . ELEMENTS_EDITS . '|' . ELEMENTS_EMBEDDED_CONTENT . '|' . ELEMENTS_TABULAR_DATA . '|' . ELEMENTS_FORMS . '|' . ELEMENTS_INTERACTIVE_ELEMENTS);

class HtmlElementException extends Exception {}

class HtmlElement {
    protected $_elementName;
    protected $_parent;
    protected $_innerHtml;
    protected $_attr = array();

    public function __construct($elementName) {
        if (!preg_match('/^(' . ELEMENTS . ')$/', $elementName)) {
            throw new HtmlElementException('Invalid element');
        }
        $this->_elementName = $elementName;
    }

    public function attr($attr, $value=null) {
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                $this->_attr[$key] = $value;
            }
        } else {
            $this->_attr[$attr] = $value;
        }
        return $this;
    }

    // @todo validate if element can set value
    public function value($value='') {
        $this->_attr['value'] = $value;
        return $this;
    }

    public function innerHtml($innerHtml='') {
        $this->_innerHtml = $innerHtml;
        return $this;
    }

    public function toHtml($addVoidElementSlash=false) {
        $element = '<' . $this->_elementName;
        foreach ($this->_attr as $attr => $value) {
            if (false === $value) {
                continue;
            } else if (!empty($attr)) {
                $element .= ' ' . $attr . '="' . $value . '"';
            } else {
                $element .= ' ' . $value;
            }
        }

        $innerHtml = '';
        if ($this->_innerHtml instanceof HtmlElement) {
            $innerHtml .= $this->_innerHtml->toHtml($addVoidElementSlash);
        } elseif (is_array($this->_innerHtml)) {
            foreach ($this->_innerHtml as &$el) {
                $innerHtml .= $el->toHtml($addVoidElementSlash);
            }
        } else {
            $innerHtml .= $this->_innerHtml;
        }
        
        if (!preg_match('/^(' . ELEMENTS_VOID . ')$/', $this->_elementName)) {
            $element .=  '>' . $innerHtml . '</' . $this->_elementName . '>';
        } else {
            if ($addVoidElementSlash) {
                $element .= ' /';
            }
            $element .= '>';
        }

        return $element;
    }
}

class HtmlGen {
    protected $_elements = array();
    protected $_current;

    public function __construct($type='html') {
        $this->_type = $type;
    }

    public function __call($name, $arguments) {
        return call_user_func_array(array(&$this->_current, $name), $arguments);
    }

    public function getCurrent() {
        return $this->_current;
    }

    public function element($element) {
        $this->_elements[] = new HtmlElement($element);
        $this->_current = end($this->_elements);
        return $this;
    }

    public function toHtml() {
        $addVoidElementSlash = $this->_type == 'xhtml';
        $html = '';
        foreach ($this->_elements as &$element) {
            $html .= $element->toHtml($addVoidElementSlash);
        }
        return $html;
    }


}
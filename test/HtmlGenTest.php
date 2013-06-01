<?php
require 'HtmlGen.php';
class HtmlGenTest extends PHPUnit_Framework_TestCase {
    public function testCreateElementOnConstructor() {
        $html = new HtmlGen();
        $html->element('html');
        $this->assertEquals($html->toHtml(), '<html></html>');
    }

    public function testCreateElement() {
        $html = new HtmlGen();
        $current = $html->element('html');
        $this->assertEquals($html->toHtml(), '<html></html>');
    }

    public function testCreateInvalidElement() {
        $this->setExpectedException('HtmlElementException');
        $html = new HtmlGen();
        $html->element('THIS_IS_NOT_VALID_ELEMENT');
    }

    public function testCreateElementWithAttr() {
        $html = new HtmlGen();
        $html->element('div')->attr('id', 'myId');
        $this->assertEquals($html->toHtml(), '<div id="myId"></div>');
    }

    public function testCreateElementWithArrayOfAttr() {
        $html = new HtmlGen();
        $html->element('div')->attr(array('id' => 'myId', 'class' => 'list', 'data-ng-repeat'));
        $this->assertEquals($html->toHtml(), '<div id="myId" class="list" data-ng-repeat></div>');
    }

    public function testCreateVoidElement() {
        $html = new HtmlGen();
        $html->element('br');
        $this->assertEquals($html->toHtml(), '<br>');
    }

    public function testCreateXhtmlVoidElement() {
        $html = new HtmlGen('xhtml');
        $html->element('br');
        $this->assertEquals($html->toHtml(), '<br />');
    }

    public function testCreateElementWithStringInnerHtml() {
        $html = new HtmlGen();
        $html->element('div')->innerHtml('<p>Teste</p>');
        $this->assertEquals($html->toHtml(), '<div><p>Teste</p></div>');
    }

    public function testCreateElementWithHtmlElementInnerHtml() {
        $html = new HtmlGen();
        $html->element('div')->innerHtml(new HtmlElement('p'));
        $this->assertEquals($html->toHtml(), '<div><p></p></div>');
    }

    public function testCreateVoidElementWithHtmlElementInnerHtml() {
        $html = new HtmlGen();
        $html->element('div')->innerHtml(new HtmlElement('br'));
        $this->assertEquals($html->toHtml(), '<div><br></div>');
    }

    public function testCreateXhtmlVoidElementWithHtmlElementInnerHtml() {
        $html = new HtmlGen('xhtml');
        $html->element('div')->innerHtml(new HtmlElement('br'));
        $this->assertEquals($html->toHtml(), '<div><br /></div>');
    }

    public function testCreateSiblingElements() {
        $html = new HtmlGen();
        $html
            ->element('div')
            ->element('div')
                ->innerHtml(new HtmlElement('br'));

        $this->assertEquals($html->toHtml(), '<div></div><div><br></div>');
    }

    public function testCreateXhtmlSiblingElements() {
        $html = new HtmlGen('xhtml');
        $html
            ->element('area')
            ->element('div')
            ->element('div')
                ->innerHtml(new HtmlElement('br'));

        $this->assertEquals($html->toHtml(), '<area /><div></div><div><br /></div>');
    }

    public function testCreateTableElement() {
        $html = new HtmlGen();
        $thead = new HtmlElement('thead');
        $tbody = new HtmlElement('tbody');

        $tr1 = new HtmlElement('tr');
        $tr1_th1 = new HtmlElement('th');
        $tr1_th2 = new HtmlElement('th');
        
        $tr2 = new HtmlElement('tr');
        $tr2_td1 = new HtmlElement('td');
        $tr2_td2 = new HtmlElement('td');

        $html->element('table')
            ->innerHtml(
                array(
                    $thead->innerHtml(
                        $tr1->innerHtml(array($tr1_th1->innerHtml('a'), $tr1_th2->innerHtml('b')))
                    ),
                    $tbody->innerHtml(array(
                        $tr2->attr('class', 'tr2')->innerHtml(array($tr2_td1, $tr2_td2)),
                    ))
                )
            );

        $this->assertEquals($html->toHtml(), '<table><thead><tr><th>a</th><th>b</th></tr></thead><tbody><tr class="tr2"><td></td><td></td></tr></tbody></table>');
    }
}
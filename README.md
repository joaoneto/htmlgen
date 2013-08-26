# htmlgen
htmlgen is a PHP class helper to generate HTML tags.
This class NOT uses the [DOMDocument](http://www.php.net/manual/class.domdocument.php) class, it just implements a fast interface to create HTML elements.
The use of this class should be in a higher level of abstraction.
We may use with xhtml and html, but there is still no XML schema validation, only the elements that will be validated with enserram `<element />` to xhtml and html to `<element>` common.

## Examples

```php
require 'HtmlGen.php';
$html = new htmlgen();
$html->element('html');
```
Should return `<html></html>`

```php
require 'HtmlGen.php';
$html = new HtmlGen();
$html->element('div')->attr('id', 'myId');
```
Should return `<div id="myId"></div>`

```php
$html = new HtmlGen('xhtml');
$html->element('br');
```
Should return `<br />`

```php
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
```
Should return

```html
    <table>
        <thead>
            <tr>
                <th>a</th>
                <th>b</th>
            </tr>
        </thead>
        <tbody>
            <tr class="tr2">
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
```

## License
The MIT License (MIT)

Copyright (c) 2013 João Pinto Neto

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

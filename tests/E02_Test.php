<?php
require_once 'PruebasHTML.php';

class E02_Test extends PruebasHTML {
    const DIR = 'E02' .DIRECTORY_SEPARATOR;
    const ARCHIVO = self::DIR . 'index.html';

    private $sistemas = array(
        array('Chrome', self::DIR . 'chrome.html', 'img/chrome.png'),
        array('Linux', self::DIR . 'linux.html', 'img/linux.jpg'),
        array('Windows', self::DIR . 'windows.html', 'img/windows.jpg'),
        array('Mac', self::DIR . 'mac.html', 'img/mac.gif')
    );

    public function testSolucionCorrectaSistemas(){

        foreach ($this->sistemas as $s) {
            $sistema    = $s[0];
            $archivo    = $s[1];
            $imagen     = $s[2];

            $this->estructuraCorrectaDocumentoHTML($this->root . $archivo);

            $str = str_ireplace(self::DOC_TYPE, '', file_get_contents($this->root . $archivo));

            $doc = new DOMDocument();

            libxml_use_internal_errors(true);
            $doc->loadHTML($str);

            $this->assertIsObject($doc, "No se pudo leer la estructura del documento ({$archivo}), revisa que sea un documento HTML válido");

            $img = $doc->getElementsByTagName('img');

            $this->assertEquals(1, count($img), "({$archivo}) Debe haber 1 elemento <img>");

            $src = trim($img[0]->getAttribute('src'));
            $this->assertNotEmpty(trim($src), "({$archivo}) Falta el atributo src o no tiene valor en la etiqueta <img>");
            $this->assertEquals($imagen, $src, "({$archivo}) La ruta de la imagen es incorrecta");

            $src = trim($img[0]->getAttribute('alt'));
            $this->assertNotEmpty(trim($src), "({$archivo}) Falta el atributo alt o no tiene valor en la etiqueta <img>");
            $this->assertEqualsIgnoringCase($sistema, $src, "({$archivo}) El texto alternavito de la imagen no es el correcto ($sistema)");

            $a = $doc->getElementsByTagName('a');

            $this->assertEquals(2, count($a), "({$archivo}) Deben haber 2 elementos <a>");

            $href = trim($a[1]->getAttribute('href'));

            $this->assertNotEmpty($href, "({$archivo}) Atributo href del hipervínculo no establecido");
            $this->assertNotFalse(filter_var($href, FILTER_VALIDATE_URL), "({$archivo}) El hipervínculo a Wikipedia no es válido (no olvides agregar http:// o https://)");
            $this->assertStringContainsStringIgnoringCase('wikipedia.org/', $href, "({$archivo}) El hipervínculo no lleva a Wikipedia");

            $target = trim($a[1]->getAttribute('target'));
            $this->assertNotEmpty($target, "({$archivo}) Atributo target no establecido");
            $this->assertEquals($target, '_blank', "({$archivo}) Atributo target no está establecido correctamente");

            $title = trim($a[1]->getAttribute('title'));
            $this->assertNotEmpty($title, "({$archivo}) Atributo title no establecido");
            $this->assertEqualsIgnoringCase($title, 'Ver en Wikipedia', "({$archivo}) Atributo title no está establecido correctamente");
        }

    }

}
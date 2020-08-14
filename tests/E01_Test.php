<?php
require_once 'PruebasHTML.php';

class E01_Test extends PruebasHTML {
    const DIR = 'E01' .DIRECTORY_SEPARATOR;
    const ARCHIVO = self::DIR . 'index.html';

    public function testSolicionCorrecta(){
        $archivo = $this->root . self::ARCHIVO;

        $this->estructuraCorrectaDocumentoHTML($archivo);

        $str = str_ireplace(self::DOC_TYPE, '', file_get_contents($archivo));

        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($str);

        $this->assertIsObject($doc, 'No se pudo leer la estructura del documento, revisa que sea un documento HTML válido');

        $h1 = $doc->getElementsByTagName('h1');

        $this->assertEquals(1, count($h1), 'Debe haber 1 encabezado h1');
        $this->assertEquals('Himno Nacional Mexicano', $h1[0]->nodeValue);

        ///////////////////////////////////////////////////////

        $h2 = $doc->getElementsByTagName('h2');
        $this->assertEquals(1, count($h2), 'Debe haber 1 encabezado h2');
        $this->assertEquals('Letra', trim($h2[0]->nodeValue));

        ///////////////////////////////////////////////////////

        $h3 = $doc->getElementsByTagName('h3');

        $this->assertEquals(3, count($h3), 'Deben haber 3 encabezado h3');
        $this->assertEquals('Coro:', trim($h3[0]->nodeValue));
        $this->assertEquals('Estrofa I:', trim($h3[1]->nodeValue));
        $this->assertEquals('Estrofa V:', trim($h3[2]->nodeValue));


        ///////////////////////////////////////////////////////

        $this->assertEquals(5, count($doc->getElementsByTagName('p')), 'Deben haber 5 párrafos');

        ///////////////////////////////////////////////////////

        $b = $doc->getElementsByTagName('b');

        $this->assertEquals(1, count($b), 'Debe haber 1 elemento <b> (negritas)');
        $this->assertEquals('Himno Nacional Mexicano', trim($b[0]->nodeValue), 'Texto destacado con negritas incorrecto');

        ///////////////////////////////////////////////////////

        $u = $doc->getElementsByTagName('u');

        $this->assertEquals(1, count($u), 'Debe haber 1 elemento <u> (subrayado)');
        $this->assertEquals('símbolos patrios', trim($u[0]->nodeValue), 'Texto destacado con itálica incorrecto');

        ///////////////////////////////////////////////////////

        $i = $doc->getElementsByTagName('i');

        $this->assertEquals(3, count($i), 'Deben haber 3 elementos <i> (itálicas)');

        ///////////////////////////////////////////////////////

        $br = $doc->getElementsByTagName('br');

        $this->assertEquals(19, count($br), 'Deben haber 19 elementos <br> (saltos de línea)');
    }

}
<?php

use PHPUnit\Framework\TestCase;

class PruebasHTML extends TestCase {
    protected $root = '';
    protected const DOC_TYPE = '<!doctype html>';

    public function setUp():void{
        $this->root = str_replace('tests', '', __DIR__);
    }

    protected function estructuraCorrectaDocumentoHTML($archivo){
        $nombre = basename($archivo);

        $this->assertFileExists($archivo, 'Archivo '.$archivo.' no existe o no se puede leer');

        $file    = file($archivo);
        $count      = count($file);

        $this->assertGreaterThan(0, $count, 'El archivo ' . $nombre . ' está vacío');

        $this->assertStringContainsStringIgnoringCase(self::DOC_TYPE, trim($file[0]), "($nombre) Falta la Declaración del Tipo de Documento (DTD) HTML5");
        $this->assertStringContainsString('<html', trim($file[1]), "($nombre) Falta la etiqueta de apertura <html> seguida de la DTD");
        $this->assertStringContainsString('<head>', trim($file[2]), "($nombre) Falta la etiqueta de apertura <head> seguida de <html>");
        $this->assertStringContainsStringIgnoringCase('<meta charset="UTF-8">', trim($file[3]), "($nombre) Falta la etiqueta <meta charset=\"UTF-8\"> seguida de <head>");

        $cierreHead = false;
        $posCierreHead = 0;
        for($i = 0; $i < $count; $i++){
            if($i > 3){
                if( strstr($file[$i], '</head>') ){
                    $cierreHead = true;
                    $posCierreHead = $i;
                    break;
                }
            }
        }

        $this->assertTrue($cierreHead, "($nombre) No se encontró la etiqueta de </head>");
        $this->assertStringContainsString('<body>', $file[$posCierreHead + 1], "($nombre) No se encontró la etiqueta de <body> después de </head>");
        $this->assertStringContainsString('</body>', $file[$count - 2], "($nombre) Falta la etiqueta de cierre </body> antes de </html>");
        $this->assertStringContainsString('</html>', $file[$count - 1], "($nombre) Falta la etiqueta de cierre </html> al final del archivo");
    }
}

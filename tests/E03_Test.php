<?php
require_once 'PruebasHTML.php';

class E03_Test extends PruebasHTML
{
    const DIR = 'E03' . DIRECTORY_SEPARATOR;
    const ARCHIVO = self::DIR . 'index.html';

    public function testSolucionCorrectaSistemas(){
        $archivo = self::ARCHIVO;
        $this->estructuraCorrectaDocumentoHTML( $this->root. $archivo );

        $str = str_ireplace(self::DOC_TYPE, '', file_get_contents( $this->root . $archivo));

        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($str);

        $this->assertIsObject($doc, "No se pudo leer la estructura del documento ({$archivo}), revisa que sea un documento HTML válido");

        $inputs = $doc->getElementsByTagName('input');
        $select = $doc->getElementsByTagName('select');
        $options = $doc->getElementsByTagName('option');
        $textarea = $doc->getElementsByTagName('textarea');
        $fieldsets = $doc->getElementsByTagName('fieldset');
        $legends = $doc->getElementsByTagName('legend');
        $forms = $doc->getElementsByTagName('form');
        $labels = $doc->getElementsByTagName('label');

        $this->assertEquals(9, count($inputs), 'Deben haber 9 elementos <input>');

        $input_nombre   = null;
        $input_email    = null;
        $input_password = null;
        $input_radios   = array();
        $input_checkbox = array();
        $input_submit   = null;

        foreach ($inputs as $input){
            switch (trim($input->getAttribute('type'))){
                case 'text':
                    $input_nombre = $input;
                    break;
                case 'email':
                    $input_email = $input;
                    break;
                case 'password':
                    $input_password = $input;
                    break;
                case 'submit':
                    $input_submit = $input;
                    break;
                case 'radio':
                    $input_radios[] = $input;
                    break;
                case 'checkbox':
                    $input_checkbox[] = $input;
                    break;
            }
        }



        $this->assertNotNull($input_nombre, 'No se encontró el input de tipo texto');
        $this->assertNotEmpty(trim($input_nombre->getAttribute('name')), 'El campo para el nombre no tiene el atributo name o está vacío');
        $this->assertEquals('nombre', trim($input_nombre->getAttribute('name')), 'El atributo name del campo nombre no tiene el nombre correcto');



        $this->assertNotEmpty(trim($input_nombre->getAttribute('placeholder')), 'El campo para el nombre no tiene el atributo placeholder o está vacío');
        $this->assertEqualsIgnoringCase('nombre', trim($input_nombre->getAttribute('placeholder')), 'El atributo placeholder del campo nombre no tiene el nombre correcto (Nombre)');


        $this->assertTrue($input_nombre->hasAttribute('required'), 'El campo para el nombre no está establecido como obligatorio');


        ///////////////////////////////////////////////////////

        $this->assertNotNull($input_email, 'No se encontró el input de tipo email');
        $this->assertNotEmpty(trim($input_email->getAttribute('name')),  'El campo para el e-mail no tiene el atributo name o está vacío');
        $this->assertEquals('email', trim($input_email->getAttribute('name')), 'El atributo name del campo email no tiene el nombre correcto');

        $this->assertNotEmpty(trim($input_email->getAttribute('placeholder')),  'El campo para el e-mail no tiene el atributo placeholder o está vacío');
        $this->assertEqualsIgnoringCase('e-mail', trim($input_email->getAttribute('placeholder')), 'El atributo placeholder del campo email no tiene el nombre correcto (E-mail)');

        $this->assertTrue($input_email->hasAttribute('required'), 'El campo para el e-mail no está establecido como obligatorio');

        ////////////////////////////////////////////////////////

        $this->assertNotNull($input_password, 'No se encontró el input de tipo password');
        $this->assertNotEmpty(trim($input_password->getAttribute('name')),  'El campo para la contraseña no tiene el atributo name o está vacío');
        $this->assertEquals('password', trim($input_password->getAttribute('name')), 'El atributo name del campo contraseña no tiene el nombre correcto');

        $this->assertNotEmpty(trim($input_password->getAttribute('placeholder')),  'El campo para la contraseña no tiene el atributo placeholder o está vacío');
        $this->assertEqualsIgnoringCase('contraseña', trim($input_password->getAttribute('placeholder')), 'El atributo placeholder del campo contraseña no tiene el nombre correcto (Contraseña)');

        $this->assertTrue($input_password->hasAttribute('required'), 'El campo para la contraseña no está establecido como obligatorio');

        ////////////////////////////////////////////////////////

        $this->assertEquals(2, count($input_radios), 'Deben haber 2 inputs de tipo radio');

        $validos = 0;
        $hombre_mujer = 0;
        $marcado = 0;
        foreach($input_radios as $radio){
            $name   = trim($radio->getAttribute('name'));
            $value  = trim($radio->getAttribute('value'));
            $checked  = trim($radio->getAttribute('checked'));

            if($name == 'sexo'){
                $validos++;
            }

            if(!empty($checked)){
                $marcado++;
            }

            if(($value == 'hombre' || $value == 'h') || ($value == 'mujer' || $value == 'm')){
                $hombre_mujer++;
            }
        }

        $this->assertEquals(2, $validos, 'Los 2 input de tipo radio deben tener el mismo valor en su atributo name (sexo)');
        $this->assertEquals(2, $hombre_mujer, 'El atributo value de alguno de los radios es incorrecto');
        $this->assertEquals(1, $marcado, 'Solo uno de los radios para el dato de sexo debe estar marcado');

        ////////////////////////////////////////////////////////

        $this->assertEquals(3, count($input_checkbox), 'Deben haber 2 inputs de tipo checkbox');


        $nombre_correcto = 0;
        $politica = 0;
        $espectaculos = 0;
        $tecnologia = 0;
        foreach($input_checkbox as $checkbox){
            $name   = trim($checkbox->getAttribute('name'));
            $value  = trim($checkbox->getAttribute('value'));

            if($name == 'noticias[]'){
                $nombre_correcto++;
            }

            if($value == 'Noticias de política'){
                $politica++;
            }

            if($value == 'Noticias de espectáculos'){
                $espectaculos++;
            }

            if($value == 'Noticias de tecnología'){
                $tecnologia++;
            }
        }

        $this->assertEquals(3, $nombre_correcto, 'Los 3 checkboxes deben tener el mismo nombre (noticias[])');
        $this->assertEquals(1, $politica, 'El valor de un solo checkbox debe ser Noticias de política');
        $this->assertEquals(1, $espectaculos, 'El valor de un solo checkbox debe ser Noticias de espectáculos');
        $this->assertEquals(1, $tecnologia, 'El valor de un solo checkbox debe ser Noticias de tecnología');


        /////////////////////////////////////////////////////////////

        $this->assertEquals(1, count($select), 'Debe haber 1 elemento <select>');
        $this->assertEquals(2, count($options), 'Deben haber 2 elementos <option>');

        $portal = 0;
        $email = 0;
        foreach($options as $option){
            $value  = trim($option->getAttribute('value'));

            if($value == 'Desde el portal'){
                $portal++;
            }

            if($value == 'Enviar a mi e-mail'){
                $email++;
            }
        }

        $this->assertEquals(1, $portal, 'El valor de un solo <option> debe ser Desde el portal');
        $this->assertEquals(1, $email, 'El valor de un solo <option> debe ser Enviar a mi e-mail');

        /////////////////////////////////////////////////////////////

        $this->assertEquals(1, count($textarea), 'Debe haber 1 elemento <textarea>');

        $this->assertNotEmpty(trim($textarea[0]->getAttribute('name')), 'El campo para la observación no tiene el atributo name o está vacío');

        /////////////////////////////////////////////////////////////

        $this->assertNotNull($input_submit, 'No se encontró el input de tipo submit');
        $this->assertNotEmpty(trim($input_submit->getAttribute('value')), 'El botón Enviar no tiene el atributo value o está vacío');
        $this->assertEquals('Enviar', trim($input_submit->getAttribute('value')), 'El atributo value del botón no tiene el valor correcto');


        /////////////////////////////////////////////////////////////

        $this->assertEquals(2, count($fieldsets), 'Deben haber 2 elementos <fieldset>');
        $this->assertEquals(2, count($legends), 'Deben haber 2 elementos <legend>');

        $personales = 0;
        $servicio = 0;

        foreach ($legends as $legend){
            if($legend->nodeValue == 'Datos personales'){
                $personales++;
            }

            if($legend->nodeValue == 'Servicio de noticias'){
                $servicio++;
            }
        }

        $this->assertEquals(1, $personales, 'El texto de un <legend> debe ser Datos personales');
        $this->assertEquals(1, $servicio, 'El texto de un <legend> debe ser Servicio de noticias');


        /////////////////////////////////////////////////////////////

        $this->assertEquals(1, count($forms), 'Deben habe 1 elemento <form>');
        $this->assertNotEmpty(trim($forms[0]->getAttribute('method')), 'El formulario no tiene el atributo method o está vacío');
        $this->assertNotEmpty(trim($forms[0]->getAttribute('action')), 'El formulario no tiene el atributo action o está vacío');

        $this->assertEquals('get', trim(strtolower($forms[0]->getAttribute('method'))), 'El formulario debe utilizar el método GET');
        $this->assertEquals('http://servicios.ver.ucc.mx/procesar.php', trim($forms[0]->getAttribute('action')), 'El formulario envía los datos al destino equivocado');


        /////////////////////////////////////////////////////////////

        $this->assertEquals(10, count($labels), 'Cantidad incorrecta de elementos <label>');

    }
}
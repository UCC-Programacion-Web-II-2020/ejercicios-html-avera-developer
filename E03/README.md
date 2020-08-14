# Instrucciones

Crea un nuevo documento HTML con un formulario que permita registrar nuevos usuarios de una revista digital. El formulario debe contener lo siguiente:

* 1 elementos input de tipo _text_ para capturar el nombre del usuario.
* 1 elemento input de tipo _email_ para el correo electrónico del usuario.
* 1 elemento input de tipo _password_ para la contraseña de acceso al portal.
* 2 elementos radio button para la selección del sexo. Nota, un radio button debe aparecer activo por default.
* 3 elementos checkbox para indicar el tipo de noticias a ofrecer:
  * Noticias de política
  * Noticias de espectáculos
  * Noticias de tecnología
* 1 elemento select que permita seleccionar el método de entrega de las noticias. Las opciones a mostrar son:
  * Desde el portal
  * Enviar a mi e-mail
* 1 elemento textarea para registrar alguna observación que tenga el usuario. Se sugiere sea de 30 columnas de ancho por 5 renglones de alto.
* 1 elemento input de tipo submit con la leyenda "Enviar"
* Elementos fieldset y legend para crear 2 apartados, uno con el texto "Datos personales", el otro con el texto "Servicio de noticias" (ver imagen al final).
## Consideraciones técnicas
* El formulario debe enviar los datos utilizando el método GET, a la URL http://servicios.ver.ucc.mx/procesar.php
* Cada elemento debe tener su propia etiqueta (elemento label).
* Dale nombre a cada campo (atributo name)
* Los campos para el Nombre, E-mail y Contraseña deben ser obligatorios.
* Agrega un texto de ayuda (placeholder) para los campos Nombre, E-mail y Contraseña.
* Los valores para los elementos checkbox, radio button deben ser los mismos que las etiquetas.
* Los valores para los elementos option del elemento select deben ser los mismos a su correspondiente leyenda.
### Resultado final
![](formulario_de_registro.jpg?raw=true, "Resultado final")

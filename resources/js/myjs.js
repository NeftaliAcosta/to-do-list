$(document).ready(function(){

});
// la e lo mismo que event
$('#formCrearUsuario').submit(function(e) {
	//detenemos cualquier accion que se vaya a realizar el formulario en el metodo post
	e.preventDefault();

	//empezamos a realizar la validacion de los formularios

	//alert("Enviando datos a sistema");

	//variable formulario contiene todo el contenido del formulario. Es llamado por medio del id
	var formulario = $(this);
	var formularioSerializado = formulario.serializeArray();
	//agregar un nuevo valor al array serializado
	formularioSerializado.push({name: 'accion', value:"crearUsuario"});

	//tiene la misma funcion que var_dump
	console.log(formularioSerializado);

	$.ajax({
		url: 'http://projectinit.local/ajax.php',
		type: 'POST',
		data: formularioSerializado,
		beforeSend: function(){
			$('.loader').show();
			//alert('Espera se esta enviando.......');
			
		},
		success: function(response){
			//alert('Ya se envio el dato');
			//console.log(response);

		},
		complete: function(){
			$('.loader').hide();
			$('.alerta').show();

			//alert('Usuario registrado.');
			
			//console.log(location);
			location.reload();
	
		}

	});


});


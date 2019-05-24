

function addUser() {

	var name = $('input[name="username"]').val();
	var email = $('input[name="email"]').val();
	var password = $('input[name="password"]').val();
	var type = $('select[name="type"]').val();
	var csrf_name = $('input[name="csrf_name"]').val();
	var csrf_value = $('input[name="csrf_value"]').val();
	var user_id = $('input[name="edited_user_id"]').val();

	if(name == '') {
		alert('Informe um nome para o usuário.');
		$('.form-name').addClass('has-error');
		return false;
	}else{
		$('.form-name').removeClass('has-error');
	}

	if(email == '') {
		alert('Informe um email de acesso para o usuário.');
		$('.form-email').addClass('has-error');
		return false;
	}else{
		$('.form-email').removeClass('has-error');
	}

	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if( ! re.test(email)) {
  	    alert('Informe um email válido.');
	    $('.form-email').addClass('has-error');
	    return false;
	}

	if(password == '') {
		alert('Informe uma senha.');
		$('.form-password').addClass('has-error');
		return false;
	}else{
		$('.form-password').removeClass('has-error');
	}

	// submit a form
	var data = {id:user_id, name:name, email:email, password:password, type:type, csrf_name:csrf_name, csrf_value:csrf_value}

	$.post("/admin/usuarios", data)
  	.done(function( data ) {

  		var data = JSON.parse(data);
  		if(data.success == true) {
  			location.reload();
  		}
  		if(data.success == false) {
  			alert('Usuário já existente');
  			return false;
  		}
  	});
}

function openUserToEdit(id) {

	$.get("/api/get_user_by_id/" + id, function() {

	})
	.done(function(data) {

		$('input[name="edited_user_id"]').val(data[0].id);
		$('input[name="username"]').val(data[0].name);
		$('input[name="email"]').val(data[0].email);
		$('input[name="password"]').val();
		$('select[name="type"]').val();
		$('#exampleModal').modal();
		
	})
	.fail(function() {
		alert('Ocorreu um erro.');
  	})
}

function removeUser(id) {
	if(confirm('Deseja remove este usuário?')) {

		var csrf_name = $('input[name="csrf_name"]').val();
		var csrf_value = $('input[name="csrf_value"]').val();
		var data = {id:id, csrf_name:csrf_name, csrf_value:csrf_value}

		$.post("/admin/usuarios/remove", data)
	  	.done(function( data ) {

	  		var data = JSON.parse(data);
	  		if(data.success == true) {
	  			location.reload();
	  		}
	  		if(data.success == false) {
	  			alert('Este usuário não pode ser removido');
	  			return false;
	  		}
	  	});

	}
}

$(document).ready(function() {
	$('button[name="adduser"]').click(function() {
		addUser();
	});
});


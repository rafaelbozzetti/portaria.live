


function openmodal_add_visitor() {

	if($('input[name="visitor_name"]').val() != "") {
		$('input[name="add_visitor_name"]').val( $('input[name="visitor_name"]').val() );
	}
    $('#exampleModal').modal();
}

function add_visitor() {

	var visitor_name = $('input[name="add_visitor_name"]').val();
	var visitor_document = $('input[name="add_visitor_document"]').val();
	var service_provider = $('select[name="add_service_provider"]').val();
	var img = $('input[name="img"]').val();

	if(visitor_name == '') {
		alert('Preencha o nome do visitante.');
		$('.form-name').addClass('has-error');
		return false;
	}else{
		$('.form-name').removeClass('has-error');
	}

	if(visitor_document == '') {
		alert('Preencha o documento do visitante.');
		$('.form-document').addClass('has-error');
		return false;
	}else{
		$('.form-document').removeClass('has-error');
	}


	var model = $('input[name="vehicle[0][model]"]').val();
	var plate = $('input[name="vehicle[0][plate]"]').val();
	var color = $('input[name="vehicle[0][color]"]').val();
	var type = $('input[name="vehicle[0][type]"]').val();
	
	var data = { visitor_name:visitor_name, visitor_document:visitor_document, service_provider: service_provider,  
				 model: model, plate:plate, color:color, type:type, img:img };

	$.post("/api/add_visitor", data)
  	.done(function( data ) {

  		if(data.success == true) {
  			$('input[name="visitor_id"]').val(data.data['id']);
  			$('input[name="visitor_name"]').val(data.data['name']);

			$('input[name="add_visitor_name"]').val('');
			$('input[name="add_visitor_document"]').val('');
			$('input[name="vehicle[0][model]"]').val('');
			$('input[name="vehicle[0][plate]"]').val('');
			$('input[name="vehicle[0][color]"]').val('');
			$('input[name="vehicle[0][type]"]').val('');

  			$('#exampleModal').hide();
  		}
  		if(data.success == false) {
  			alert('Erro ao adiciUsuário já existente');
  			return false;
  		}
  	});

}


$('#add_visitor').click(function() {
	add_visitor();
});

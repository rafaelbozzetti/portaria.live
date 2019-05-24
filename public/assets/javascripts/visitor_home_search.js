
function visitor_search() {

	var search = $('input[name="visitor_name"]').val();

	var data = {search: search}

	$.post("/api/get_visitor_by_name", data)
  	.done(function( data ) {

      var data = JSON.parse(data);

      if(data.length > 0) {
        var html = '';
        $.each(data, function(k,v) {
          html += '<li class="item-visit" ><div style="padding:5px 10px;cursor:pointer;" onclick="visitor_select(\''+v.id+'\', \''+v.name+'\', \''+v.service_provider+'\');"><b>'+v.name+'</b> Doc: '+v.document+' '+ (v.service_provider == 1 ? '<b>(Prestador de Serviço)</b>' : '') +'</div ></li>';
        });

        $('.visitor-item-list').html(html);
        $('.visitor-item-list').show();
      }
  	});	
}

function visitor_select(id, name, service_provider) {
  $('input[name="visitor_name"]').val(name + (service_provider == 1 ? ' (Prestador de Serviço)' : '') );
  $('input[name="visitor_id"]').val(id);
  $('.visitor-item-list').hide();
}


function visitor_register() {

  var block = $('select[name="block"]').val();
  var unit = $('select[name="unit"]').val();
  var visitor = $('input[name="visitor_id"]').val();
  var visitor_name  = $('input[name="visitor_name"]').val();

  if(unit == "" || block == "") {
    alert('Selecione um Bloco e uma Unidade');
    return false;
  }

  if(visitor == '') {
    alert('Procure ou Cadastre o Visitante');
    return false;    
  }

  $('#reg_visitor_name').html(visitor_name);

  $.get("/api/get_unit_address/" + unit, function() {

  })
  .done(function(data) {
      $('#visitaModal').modal('show');
      $('#reg_unit_and_block').html(data.unit['name'] + ' - '+ data.block['name']); 
  })
  .fail(function() {
      alert('Ocorreu um erro.');
  });

}


function register_visitor(type) {

  var block = $('select[name="block"]').val();
  var unit = $('select[name="unit"]').val();
  var visitor = $('input[name="visitor_id"]').val();
  var visitor_name  = $('input[name="visitor_name"]').val();
  var person = $('input[name="person_id"]').val();

  if(unit == "" || block == "") {
    alert('Selecione um Bloco e uma Unidade');
    return false;
  }

  if(visitor == '') {
    alert('Procure ou Cadastre o Visitante');
    return false;
  }

  if(person == '') {
    alert('Selecione um morador que será responsável pela autorização');
    return false;
  }

  // submit a form
  var data = {unit:unit, visitor:visitor, person:person, type:type}

  $.post("/api/register_visitor", data)
    .done(function( data ) {

      if(data.success == true) {
        location.reload();
      }
      if(data.success == false) {
        alert('Erro ao registrar visita');
        return false;
      }
    });
}

function selectPerson(id) {
  $('.list-person-'+id).addClass('btn-default');
  $('input[name="person_id"]').val(id);
}

$('input[name="visitor_name"]').keyup(function() {
	if($('input[name="visitor_name"]').val().length > 1) {
		visitor_search();
	}
});

$('input[name="visitor_name"]').focus(function() {
  $('.visitor-item-list').show();
});
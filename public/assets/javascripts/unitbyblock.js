

function mount_block_and_units(block_id, unit_id) {


	$.get("/api/get_blocks", function() {

	})
	.done(function(data) {

		var html = '<option value="">Selecione</option>';
	  	$.each(data, function(k,v) {
	  		if(v.id == block_id) {
	  			html += '<option selected value="' + v.id + '"> '+ v.name +' </option>';
	  		}else{
	  			html += '<option value="' + v.id + '"> '+ v.name +' </option>';
	  		}	  		
	  	});

		$('select[name="block"]').html(html);
	    $('select[name="block"]').multiselect({
	      enableFiltering: true,
	      filterPlaceholder: 'Procurar',
	      buttonWidth: '100%',
	      maxHeight: 300,
	      nonSelectedText: 'Selecione',
	      buttonClass: 'btn btn-secundary input-lg input-block',
	      templates: {
		      li: '<li class="list-block"><a><label></label></a></li>'
	      },
          onDropdownShow: function(event) {
              $('.visitor-item-list').hide();
          }
	    });
	});


	$.get("/api/get_units_by_block/" + block_id, function() {

	})
	.done(function(data) {

		var html = '<option value="">Selecione</option>';
	  	$.each(data, function(k,v) {
	  		if(v.id == unit_id) {
	  			html += '<option selected value="' + v.id + '"> '+ v.name +' </option>';
	  		}else{
	  			html += '<option value="' + v.id + '"> '+ v.name +' </option>';
	  		}	  		
	  	});

		$('select[name="unit"]').html(html);

	    $('select[name="unit"]').multiselect({
	      enableFiltering: true,
	      filterPlaceholder: 'Procurar',
	      buttonWidth: '100%',
	      maxHeight: 300,
	      nonSelectedText: 'Selecione',
	      buttonClass: 'btn btn-secundary input-lg input-block',
	      templates: {
		      li: '<li class="list-block"><a><label></label></a></li>'
	      },
          onDropdownShow: function(event) {
              $('.visitor-item-list').hide();
          }
	    });

	})
	.fail(function() {
		alert('Ocorreu um erro.');
  	})
}


function get_units_by_block() {

	var block_id = $('select[name="block"]').val();

	$.get("/api/get_units_by_block/" + block_id, function() {

	})
	.done(function(data) {

	    $('select[name="unit"]').multiselect('destroy');

		var html = '<option value="">Selecione</option>';

	  	$.each(data, function(k,v) {
	  		html += '<option value="' + v.id + '"> '+ v.name +' </option>';			
	  	});

		$('select[name="unit"]').html(html);

	    $('select[name="unit"]').multiselect({
	      enableFiltering: true,
	      filterPlaceholder: 'Procurar',
	      buttonWidth: '100%',
	      maxHeight: 300,
	      nonSelectedText: 'Selecione',
	      buttonClass: 'btn btn-secundary input-lg input-block',
	      templates: {
		      li: '<li class="list-block"><a><label></label></a></li>'
	      },
          onDropdownShow: function(event) {
              $('.visitor-item-list').hide();
          }
	    });

		$('select[name="block"]').multiselect('destroy');

	    $('select[name="block"]').multiselect({
	      enableFiltering: true,
	      filterPlaceholder: 'Procurar',
	      buttonWidth: '100%',
	      maxHeight: 300,
	      nonSelectedText: 'Selecione',
	      buttonClass: 'btn btn-secundary input-lg input-block',
	      templates: {
		      li: '<li class="list-block"><a><label></label></a></li>'
	      },
          onDropdownShow: function(event) {
              $('.visitor-item-list').hide();
          }
	    });

	})
	.fail(function() {
		alert('Ocorreu um erro.');
  	})
}


function get_units_person() {

	var unit_id = $('select[name="unit"]').val();
	
	$.get("/api/get_units_person/" + unit_id, function() {

	})
	.done(function(data) {
		var html = '<div class="alert alert-info"> Não há moradores nessa unidade.</div>';

		if(data.length > 0) {			
			html = '';
		  	$.each(data, function(k,v) {
				html += '	<a href="#" class="list-group-item list-person-'+v.id+'" onclick="selectPerson('+v.id+');">';
                html += '      <h4 class="list-group-item-heading">'+v.name+'</h4>';
                html += '      <p class="list-group-item-text">Fones: ';
                if(v.phones != undefined) {
                	if(v.phones[0] != undefined) {
                		html +=  v.phones[0];		
                	}
                	if(v.phones[1] != undefined) {
                		html +=  ' - ' + v.phones[1];
                	}
                }else{
                	html +=  '-';
                }
            	html += '</p>';                
                html += '    </a>';
		  	});
		}	 
		$('.person-item-list').html(html);

	})
	.fail(function() {
		alert('Ocorreu um erro.');
  	})


}

function addPhoneItem() {
	var num = document.getElementById("phone-list").childElementCount;
	var qhtml =  '<div class="phone-item-'+num+'">';
	qhtml +=      '<div class="col-sm-10" style="padding:5px 0px 5px 0px;">';
	qhtml +=         '<input type="text" name="phone['+num+']" class="form-control input-lg">';
	qhtml +=     '</div>';
	qhtml +=     '<div class="col-sm-2" style="padding:5px 0px 5px 5px;">';
	qhtml +=         '<button type="button" class="btn btn-sm phone-item-rm-0 btn-secundary" onclick="removePhoneItem('+num+');"><i class="fa fa-trash-o"></i></button>';
	qhtml +=         '<button type="button" class="btn btn-sm phone-item-add-0 btn-secundary" style="margin-left:3px;" onclick="addPhoneItem();"><i class="fa fa-plus"></i></button>';
	qhtml +=     '</div>';
	qhtml += '</div>';
	$('#phone-list').append(qhtml);
}

function removePhoneItem(id) {
	var num = document.getElementById("phone-list").childElementCount;
	if(num > 1) {
		$('.phone-item-'+id).remove();	
	}
}
function addVehicleItem() {
	var num = document.getElementById("vehicle-list").childElementCount;
	var qhtml =  '<div class="vehicle-item-'+num+'">';
		qhtml += '	<div class="col-sm-10" style="padding:15px 0px 5px 0px;display:inline;" >';
		qhtml += '	    <div class="radio-custom radio-primary checkbox-inline">';
		qhtml += '	        <input type="radio" name="vehicle['+num+'][type]" value="1" id="radioExample1'+num+'" checked>';
		qhtml += '	        <label for="radioExample1'+num+'"><b>Carro</b></label>';
		qhtml += '	    </div>';
		qhtml += '	    <div class="radio-custom radio-primary checkbox-inline">';
		qhtml += '	        <input type="radio" name="vehicle['+num+'][type]" value="2" id="radioExample2'+num+'">';
		qhtml += '	        <label for="radioExample2'+num+'"><b>Moto</b></label>';
		qhtml += '	    </div>';
		qhtml += '	    <div class="radio-custom radio-primary checkbox-inline">';
		qhtml += '	        <input type="radio" name="vehicle['+num+'][type]" value="3" id="radioExample3'+num+'">';
		qhtml += '	        <label for="radioExample3'+num+'"><b>Utilitário</b></label>';
		qhtml += '	    </div>';
		qhtml += '	    <div class="radio-custom radio-primary checkbox-inline">';
		qhtml += '	        <input type="radio" name="vehicle['+num+'][type]" value="4" id="radioExample4'+num+'">';
		qhtml += '	        <label for="radioExample4'+num+'"><b>Caminhão</b></label>';
		qhtml += '	    </div>';
		qhtml += '	</div>';

		qhtml += '	<div class="col-sm-2" style="padding:14px 0px 0px 0px;">';
		qhtml += '	    <button type="button" class="btn btn-sm phone-item-rm-0 btn-secundary" onclick="removeVehicleItem('+num+');"><i class="fa fa-trash-o"></i></button>';
		qhtml += '	    <button type="button" class="btn btn-sm phone-item-add-0 btn-secundary" style="margin-left:3px;" onclick="addVehicleItem();"><i class="fa fa-plus"></i></button>';
		qhtml += '	</div>';
		qhtml += '	';
		qhtml += '	<div class="col-sm-10" style="padding:5px 0px 5px 0px;">';
		qhtml += '	    <label>Modelo:</label>';
		qhtml += '	    <input type="text" name="vehicle['+num+'][model]" class="form-control input-sm">';
		qhtml += '	</div>';
		qhtml += '	<div class="col-sm-10" style="padding:0px">';
		qhtml += '	    <div class="col-sm-6" style="padding:0px 0px 5px 0px;">';
		qhtml += '	        <label>Placa:</label>';
		qhtml += '	        <input type="text" name="vehicle['+num+'][plate]" class="form-control input-sm">';
		qhtml += '	    </div>';
		qhtml += '	    <div class="col-sm-6" style="padding:0px 0px 5px 5px;">';
		qhtml += '	        <label>Cor:</label>';
		qhtml += '	        <input type="text" name="vehicle['+num+'][color]" class="form-control input-sm">';
		qhtml += '	    </div>';
		qhtml += '	</div>';
	    qhtml += '</div>'; 
	$('#vehicle-list').append(qhtml);
}

function removeVehicleItem(id) {
	var num = document.getElementById("vehicle-list").childElementCount;
	if(num > 1) {
		$('.vehicle-item-'+id).remove();	
	}
}


$(document).ready(function() {
	if(document.getElementById("vehicle-list")) {
		if(document.getElementById("vehicle-list").childElementCount == 0 ) {
			addVehicleItem();	
		}			
	}
});
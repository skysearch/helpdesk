/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function somar(){
	cons = (+$('input[name="construida"]').val()); 
	acons = (+$('input[name="aconstruir"]').val());
	reg = (+$('input[name="regularizada"]').val());
	areg = (+$('input[name="aregularizar"]').val());
	dem = (+$('input[name="ademolir"]').val());
	des = (+$('input[name="desmontavel"]').val());
	if(validarSoma(cons, acons, reg, areg, dem, des))
		if((((cons+acons)-dem)+des)>0)
			$("#total").val(((cons+acons)-dem)+des);
}

function validarSoma(cons, acons, reg, areg, dem, des){
	if(reg>cons){
		alert("A area regularizada nao pode ser maior do que a area construida");
		$('input[name="regularizada"]').next(".exemplo").html("A area regularizada nao pode ser maior do que a area construida");
		$('input[name="regularizada"]').val("").focus();
		return false;
	}
	if((cons+acons)<dem){
		alert("A area a demolir nao pode ser maior que a construir + construida");
		$('input[name="ademolir"]').next(".exemplo").html("A area a demolir nao pode ser maior que a construir + construida");
		$('input[name="ademolir"]').val("").focus();
		return false;
	}
	else {
		if(areg>((cons+acons)-dem)){
			alert("A area a regularizar nao pode ser maior do que (construida + a construir) - a demolir");
			$('input[name="aregularizar"]').next(".exemplo").html("A area a regularizar nao pode ser maior do que a area (construida + a construir) - a demolir");
			$('input[name="aregularizar"]').val("").focus();
			return false;
		}
		if((reg+areg)>((cons+acons)-dem)){
			alert("A area regularizada + a regularizar nao pode ser maior do que a area (construida + a construir) - demolir");
			$('input[name="regularizada"]').next(".exemplo").html("A area regularizada + a regularizar nao pode ser maior do que a area (construida + a construir) - demolir");
			$('input[name="regularizada"]').val("").focus();
			$('input[name="aregularizar"]').val("");
			return false;
		}
	}
	return true;
}
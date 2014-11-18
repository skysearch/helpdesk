/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).on('change','select#proprietario_endereco_obra', function() {
    var option = $('select#proprietario_endereco_obra').attr('value');

    var proprietario = '#fieldset-DataEnderecoProprietario';
    var obra = '#fieldset-DataObra';

    if(option == 1) {
        $(proprietario+' input').val('');
        $(proprietario+' select').val('');
        
    } else {
        $(proprietario+' input.cep').val($(obra+' input.cep').val());
        $(proprietario+' input.endereco').val($(obra+' input.endereco').val());
        $(proprietario+' input.numero').val($(obra+' input.numero').val());
        $(proprietario+' input.complemento').val($(obra+' input.complemento').val());
        $(proprietario+' input.bairro').val($(obra+' input.bairro').val());
        $(proprietario+' input.estado').val($(obra+' input.estado').val());
        
        $.getScript("http://republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$(obra+' input.cep').val(), function(){
            if(resultadoCEP["resultado"]){
                $(proprietario+' input.cidade').val(unescape(resultadoCEP["cidade"]));
                $(proprietario+' select.estado').val(unescape(resultadoCEP["uf"]));
            }
        });				

    }
});

$(document).on('change','select#tecnico_autor', function() {
    var option = $('select#tecnico_autor').attr('value');

    var autor = '#fieldset-DataTecnico  #user_id';
    var tecnico = '#fieldset-DataTecnico';

    if(option == 1) {
        $(tecnico+' input').val('');
        $(tecnico+' select').val('');
        
        
    } else {
        user = $(autor).val();
        
        if($(autor).val()!=undefined && user == '') {
            var nav = $(".nav-tabs a[href=#fieldset-DataTecnico]");
            var msg = $('#messages_tecnico');
            
            //msg.html("Selecione primeiro um usuário na categoria \""+nav.find('span').html()+"\".");
            msg.html("Selecione primeiro um usuário.");
            msg.css('display','block');
            
            nav.addClass('error');
            $('select#tecnico_autor').val(1);
            
            return false;
        }
        
        $.post('public/cadernetas/user/get-user', {id:user}, function(data){
            var response = jQuery.parseJSON(data);
            
            if($(autor).val()==undefined) $(tecnico+' input.tecnico_art').val($('#art').val());

            $(tecnico+' input.nome').val(response.name);
            $(tecnico+' select.tecnico_academic_area').val(response.academic_area);
            $(tecnico+' input.crea').val(response.crea_number);
            $(tecnico+' select.telefone_tipo_1').val(response.phone_type_1);
            $(tecnico+' input.telefone_1').val(response.phone_1);
            $(tecnico+' select.telefone_tipo_2').val(response.phone_type_2);
            $(tecnico+' input.telefone_2').val(response.phone_2);
            $(tecnico+' select.endereco_tipo').val(response.address_type);
            $(tecnico+' input.endereco').val(response.address);
            $(tecnico+' input.numero').val(response.number);
            $(tecnico+' input.complemento').val(response.complement);
            $(tecnico+' input.cidade').val(response.city);
            $(tecnico+' select.estado').val(response.state);
            $(tecnico+' input.cep').val(response.postal_code);
            $(tecnico+' input.bairro').val(response.district);
            $(tecnico+' input.ie').val(response.ie);
            $(tecnico+' input.cep').val(response.postal_code);
            
        });
        
    }
});

$(document).on('change','select#user_id', function() {
    var option = $('select#user_id').attr('value');
    var tecnico_option = $('select#tecnico_autor').attr('value');
    var tecnico = '#fieldset-DataTecnico';
    
    if(tecnico_option==0) {
       $.post('public/cadernetas/user/get-user', {id:option}, function(data){
            var response = jQuery.parseJSON(data);
        
        
        $(tecnico+' input.nome').val(response.name);
        $(tecnico+' select.tecnico_academic_area').val(response.academic_area);
        $(tecnico+' input.crea').val(response.crea_number);
        $(tecnico+' select.telefone_tipo_1').val(response.phone_type_1);
        $(tecnico+' input.telefone_1').val(response.phone_1);
        $(tecnico+' select.telefone_tipo_2').val(response.phone_type_2);
        $(tecnico+' input.telefone_2').val(response.phone_2);
        $(tecnico+' select.endereco_tipo').val(response.address_type);
        $(tecnico+' input.endereco').val(response.address);
        $(tecnico+' input.numero').val(response.number);
        $(tecnico+' input.complemento').val(response.complement);
        $(tecnico+' input.cidade').val(response.city);
        $(tecnico+' select.estado').val(response.state);
        $(tecnico+' input.cep').val(response.postal_code);
        $(tecnico+' input.bairro').val(response.district);
        $(tecnico+' input.ie').val(response.ie);
        $(tecnico+' input.cep').val(response.postal_code);
       })
    }
});


$(function(){
    /*$('#proprietario_endereco_obra').change(function(){
        var option = $(this).val();
        var proprietario = '#fieldset-DataEnderecoProprietario';
        var obra = '#fieldset-DataObra';
        
        if(option == 1) {
            $(proprietario+' input').val('');
        } else {
            $(proprietario+' input.cep').val($(obra+' input.cep').val());
            $(proprietario+' input.endereco').val($(obra+' input.endereco').val());
            $(proprietario+' input.numero').val($(obra+' input.numero').val());
            $(proprietario+' input.complemento').val($(obra+' input.complemento').val());
            $(proprietario+' input.bairro').val($(obra+' input.bairro').val());
            $(proprietario+' input.cidade').val($(obra+' input.cidade').val());
            $(proprietario+' input.estado').val($(obra+' input.estado').val());
        }
    })*/
    
    
    
    /*var error = false;
    
    $('#fieldset-DataCreaCalUser input').blur(function(){
        var messages = $('#fieldset-DataCreaCalUser .messages');
        
        cons = (+$('#construida').val()); 
        acons = (+$('#aconstruir').val());
        reg = (+$('#regularizada').val());
        areg = (+$('#aregularizar').val());
        dem = (+$('#ademolir').val());
        des = (+$('#desmontavel').val());
        if($.validarSoma(cons, acons, reg, areg, dem, des)) {
            if((((cons+acons)-dem)+des)>0)
                $("#total").val(((cons+acons)-dem)+des);
            
            error = false;
            messages.css('display','none');
           
        } else {
            error = true;
            messages.css('display','block');
        }
    })*/

    
    $('#cardenetaform').submit(function() {
        //alert('Handler for .submit() called.');
        if(error){
            $('#fieldset-DataCreaCalUser fieldset').css('display','none');
            $('#fieldset-DataCreaCalUser').css('display','block');
            
            return false;
        }
    });
    
})



$.validarSoma = function(cons, acons, reg, areg, dem, des){
    var messages = $('#fieldset-DataCreaCalUser .messages');
        
    if(reg>cons){
        messages.html("A area regularizada nao pode ser maior do que a area construida");
        $('#regularizada').css('color','#C01717');
        return false;
    } else {
        $('#regularizada').css('color','#444444');
    }
    if((cons+acons)<dem){
        messages.html("A area a demolir nao pode ser maior que a (construir + construida)");
        $('#ademolir').css('color','#C01717');
        return false;
    }
    else {
        if(areg>((cons+acons)-dem)){
            messages.html("A area a regularizar nao pode ser maior do que (construida + a construir) - (a demolir)");
            $('#aregularizar').css('color','#C01717');
            return false;
        } else {
            $('#aregularizada').css('color','#444444');
        }
        if((reg+areg)>((cons+acons)-dem)){
            messages.html("A area regularizada + a regularizar nao pode ser maior do que a area (construida + a construir) - demolir");
            $('#regularizada').css('color','#C01717');
            return false;
        } else {
            $('#regularizada').css('color','#444444');
        }
    }
    return true;
}
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
    
    $('#noscript').remove();
    
    /*
     * Impede que links vazios do menu 
     * retornem uma url vazia
     */
    $('.navigation li a, .breadcrumbs a').click(function(){
        if($(this).attr('href')=='#') {
            return false;
        }
    })

    /*
     * Adicionam asterisco nos campos 
     * obrigatórios dos formulários
     */
   // $('label.required').append('<span class="required">*</span>');

    
    /*
     * Colocar barra de desenvolvido por
     */
    $('#content').append('<div style="width:240px; position:absolute; bottom:0; left:50%; margin-left:-120px; text-align: center; padding: 5px 0 5px; font-size:11px; color:#000; font-style:normal">Desenvolvido por <a href="http://www.db9.com.br" target="_blank" style="color:#000; font-weight: bold; font-family: Helvetica, Tahoma"><span style="color:#000 /*#ff6c00*/">DB9</span> Tecnologia de Dados</a></div>');
});

$(function() {
    $( ".tabs > ul" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( ".tabs > ul > li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
});
  
$(function() {
    var obj = $(".nav-tabs");
    var offset = obj.offset();
    var offsetTop = 205;
    if(offset !=undefined && offset != null) {
        var topPadding = 15;
        $(window).scroll(function() {
            if ($(window).scrollTop() > offsetTop) {
                obj.stop().animate({
                    marginTop: $(window).scrollTop() - offsetTop + topPadding
                });
            } else {
                obj.stop().animate({
                    marginTop: 0
                });
            }
        });
    }
});

$(function() {
    /*
     * Exibe mensagem avisando quando o caps lock
     * está habilitado
     */
    $('input:password').keypress(function(e) { 
        var s = String.fromCharCode( e.which );

        if((s.toUpperCase() === s && s.toLowerCase() !== s && !e.shiftKey) ||
            (s.toUpperCase() !== s && s.toLowerCase() === s && e.shiftKey)){
            $('.messages').html('Cuidado, seu CapsLock está ligado!');
            $('.messages').css('display','block');
        } else {
            $('.messages').css('display','none');
        }
    });
});


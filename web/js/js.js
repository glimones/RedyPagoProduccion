
var base_url = $("#base_url").val();
var PagmediosAmbiente = $("#PagmediosAmbiente").val();

$(document).ready(function(){
 var preguntaCreate='N';



    $("#create_new").on('click',function (){
        preguntaCreate='S';
    });


    $(document).on('click', '.btn-group', function() {
        if ((screen.width<1024)&&(preguntaCreate!='S')) {
            $("table thead").attr('style','visibility:hidden');
           // $("#ExteriorResponsive").show();
           // $("#validaExterior").hide();
           $.pjax.reload({container:'#crud-datatable'});
        }else{
            $("#ExteriorResponsive").hide();
            $("#validaExterior").show();
        }
        preguntaCreate='N';
    });
    $(document).on('click', '.pagination', function() {
         if (screen.width<1024) {
             $("table thead").attr('style','display:unset');
           //  $("#ExteriorResponsive").show();
            // $("#validaExterior").hide();
             $.pjax.reload({container:'#crud-datatable'});
         }//else{
             //$("#ExteriorResponsive").hide();
             //$("#validaExterior").show();
        // }
     });
    if (screen.width<1024) {
        $("table thead").attr('style', 'display:none');
        $("#ExteriorResponsive").show();
        $("#validaExterior").hide();
    }else{
        //$("table thead").attr('style', 'display:none');
        $("#ExteriorResponsive").hide();
        $("#validaExterior").show();
    }
//codigo resolución grande
    $(document).on('click', '.boton', function(){
        var aux = document.createElement("input");
        aux.setAttribute("value", this.name);
        document.body.appendChild(aux)
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
        copiar('S');
        $.ajax({ url: base_url+"/ajax/alertcopiaurl2"});
    }
    );



    $(document).on('click', '.boton1', function(){
        var aux = document.createElement("input");
        aux.setAttribute("value", this.name);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
        copiar('N');
        $.ajax({ url: base_url+"/ajax/alertcopiaurl"});
        });

    $("#cedulaInc").hide();

    $(document).on('click', '.pedido_estado', function(){ 
		var estado = $(this).attr("estado");
		var id = $(this).attr("id-pedido");
		// if( estado == "Pago pendiente" ){
			getpedido(id);
		// }
	});

    $('#pagos-form').on('beforeSubmit', function(e) {
        var base0 = $("#pagosform-form_base0").val();
        $("#PagoSubmitButton").html("Processing ...");
        $("#PagoSubmitButton").prop('disabled', true);
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            dataType: 'json',
            success: function (data) {
                $('#acquirerId').val(data.acquirerId);
                $('#idCommerce').val(data.idCommerce);
                $('#purchaseOperationNumber').val(data.purchaseOperationNumber);
                $('#purchaseAmount').val(data.purchaseAmount);
                $('#purchaseCurrencyCode').val(data.purchaseCurrencyCode);
                $('#shippingFirstName').val(data.shippingFirstName);
                $('#shippingLastName').val(data.shippingLastName);
                $('#shippingEmail').val(data.shippingEmail);
                $('#shippingAddress').val(data.shippingAddress);
                $('#userCommerce').val(data.userCommerce);
                $('#userCodePayme').val(data.userCodePayme);
                $('#descriptionProducts').val(data.descriptionProducts);
                $('#purchaseVerification').val(data.purchaseVerification);
                $('#taxMontoFijo').val(data.taxMontoFijo);
                $('#taxMontoIVA').val(data.taxMontoIVA);
                $('#taxMontoNoGravaIva').val(data.taxMontoNoGravaIva);
                $('#taxMontoGravaIva').val(data.taxMontoGravaIva);
                document.getElementById('f1').submit();
                // if( PagmediosAmbiente == 'Test' ){
                //     AlignetVPOS2.openModal('https://integracion.alignetsac.com/');
                // }else{
                //     AlignetVPOS2.openModal('', '2');
                // }
            },
            error: function () {
                alert("No se pudo procesar la solicitud de pago, inténtalo en unos minutos");
                $("#PagoSubmitButton").prop('disabled', false);
                $("#PagoSubmitButton").html("Pagar");
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });



    $('#pagos-form-vpos1').on('beforeSubmit', function(e) {
        $("#PagoSubmitButton").html("Processing ...");
        $("#PagoSubmitButton").prop('disabled', true);
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            dataType: 'json',
            success: function (data) {
                $('#IDACQUIRER').val(data.IDACQUIRER);
                $('#IDCOMMERCE').val(data.IDCOMMERCE);
                $('#XMLREQ').val(data.XMLREQ);
                $('#SIGNATURE').val(data.DIGITALSIGN);
                $('#SESSIONKEY').val(data.SESSIONKEY);
                
                document.getElementById('f1').submit();
                // if( PagmediosAmbiente == 'Test' ){
                //     AlignetVPOS2.openModal('https://integracion.alignetsac.com/');
                // }else{
                //     AlignetVPOS2.openModal('', '2');
                // }
            },
            error: function () {
                alert("No se pudo procesar la solicitud de pago, inténtalo en unos minutos");
                $("#PagoSubmitButton").prop('disabled', false);
                $("#PagoSubmitButton").html("Pagar");
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });

    $('#pagosform-nombres').on('input', function (e) {
        if (!/^[ a-záéíóúüñ]*$/i.test(this.value)) {
            this.value = this.value.replace(/[^ a-záéíóúüñ]+/ig,"");
        }
    });
    $('#pagosform-apellidos').on('input', function (e) {
        if (!/^[ a-záéíóúüñ]*$/i.test(this.value)) {
            this.value = this.value.replace(/[^ a-záéíóúüñ]+/ig,"");
        }
    });

    $(".casos").click(function(){
        var caso = $(this).attr("caso");
        var monto = $(this).attr("monto");
        var empresa_id = $("#empresa_id").val();
        var btn = $(this);
        if( empresa_id > 0 ){
            $.ajax({
                url: base_url+"/integraciones/generarcaso",
                type: "post",
                data: {"caso":caso, "monto":monto, "empresa_id":empresa_id},
                dataType: 'json',
                beforeSend: function () {
                    $(btn).attr("class", "btn btn-success btn-block boton-test");
                    $(btn).html("Generando...");
                },
                success: function (data) {
                    $(btn).html("Test generado");
                    window.open(data.url, data.target);
                },
                error: function () {
                    alert("No se pudo generar el caso de testing");
                }
            });
        }else{
            alert("Seleccione una empresa");
        }
    });

    $(".eliminar_testing").click(function(){
        $(this).html("Eliminando, espere ...");
        $(this).attr("class", "btn btn-danger btn-block boton-test eliminar_testing");
        var btn = $(this);
        $.ajax({
            url: base_url+"/integraciones/eliminar",
            type: "post",
            success: function (data) {
                $(btn).html("Eliminado");
            },
            error: function () {
                alert("Ocurrio un error");
            }
        });
    });

    $(".imprimir_pin").click(function(){
        var win = window.open();
        win.document.write( '<h1>'+$(this).attr("pin")+'</h1>' );
        win.print();
        win.close();
        return false;
    });
    $("#pagosform-identificacion").blur(function(){

        var base0 = $("#pagosform-form_base0").val();
 
      $.ajax({
          type: "post",
          url: base_url+"/ajax/informacioncliente",
          dataType: 'json',
          data:{'id':$(this).val(),'base0':base0},
          beforeSend: function(){
            
          },
          success: function(data){
            // console.debug(data);
            // $(".telefonos_conductor").html(data)
              //var nombresData=(data.nombres).length;
             // if((data.nombres).length>0){//gli
            $("#pagosform-nombres").val(data.nombres);
            $("#pagosform-apellidos").val(data.apellidos);
            $("#pagosform-email").val(data.email);
             // }//gli
          },
          async: false,
          cache: false
      });


    });

    $(document).on('keyup', '#pedidos-total_con_iva-disp', function(){
       var valor=$(this).val().replace('$','');
        var BaseCero=$("#pedidos-total_sin_iva-disp").val().replace('$','');
        BaseCero=BaseCero.replace('$','');
        BaseCero=BaseCero.replace(',','');
        BaseCero=BaseCero.replace(',','');
        valor=valor.replace('$','');
        valor=valor.replace(',','');
        valor=valor.replace(',','');
        valor=valor.replace(',','');
        $("#iva").val(((parseFloat(valor)*12)/100).toFixed(2));
        var valorIva=$("#iva").val();
        $("#a_pagar").val((parseFloat(valorIva)+ parseFloat(valor)+parseFloat(BaseCero)).toFixed(2));
    });
    $(document).on('keyup', '#pedidos-total_sin_iva-disp', function(){

        var sumaIva=$("#iva").val();
        var sumaTarifa=$("#pedidos-total_con_iva-disp").val().replace('$','');
        var n =sumaTarifa.length;
        if (n==0){
            sumaTarifa = parseFloat('0.00');
        }
        if (sumaTarifa.indexOf(',')){
            sumaTarifa = sumaTarifa.replace(',', '');
            sumaTarifa = sumaTarifa.replace(',', '');
            sumaTarifa=parseFloat(sumaTarifa);
        }
        var sumaNueva=$('#pedidos-total_sin_iva-disp').val().replace('$','');
        if (sumaNueva.indexOf(',')){
            sumaNueva=sumaNueva.replace(',','');
            sumaNueva=sumaNueva.replace(',','');
            sumaNueva=parseFloat(sumaNueva);
        }
        suma=parseFloat(sumaIva)+sumaNueva+sumaTarifa;
        $("#a_pagar").val(suma.toFixed(2));
    });

    $(document).on('blur', '#pedidos-total_con_iva', function(){
        calcularMontosIva();
    });


    $(document).on('blur', '#pedidos-total_sin_iva', function(){ 
        calcularMontosIva();
    });

     function copiar(valor) {
         if (valor=='S'){
             var emailLink = document.querySelector('.boton');
         }else{
             var emailLink = document.querySelector('.boton1');
         }
         var range = document.createRange();
         range.selectNode(emailLink);
         window.getSelection().addRange(range);
         try {
             var successful = document.execCommand('copy');
             var msg = successful ? 'successful' : 'unsuccessful';
             //alert('Copy email command was ' + msg);
         } catch(err) {
             //alert('Oops, unable to copy');
         }
         window.getSelection().removeAllRanges();
    }

    /*$(".boton").click(function() {
        var aux = document.createElement("input");
        aux.setAttribute("value", this.name);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);

    });
    $(".boton1").click(function() {

        var aux = document.createElement("input");
        aux.setAttribute("value", this.name);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
    });*/


});

// gli ini
$( "#pagosform-form_base12-disp" ).keyup(function() {
    var valor=$(this).val().replace('$','');
    var BaseCero=$("#pagosform-form_base0-disp").val().replace('$','');
    BaseCero=BaseCero.replace('$','');
    BaseCero=BaseCero.replace(',','');
    BaseCero=BaseCero.replace(',','');
    BaseCero=BaseCero.replace(',','');
    valor=valor.replace('$','');
    valor=valor.replace(',','');
    valor=valor.replace(',','');
    valor=valor.replace(',','');
    $("#pagosform-form_base0-disp").val('0.00');
    $("#pagosform-form_iva").val(((parseFloat(valor)*12)/100).toFixed(2));
    var valorIva=$("#pagosform-form_iva").val();
    $("#pagosform-form_total").val((parseFloat(valorIva)+ parseFloat(valor)+parseFloat(BaseCero)).toFixed(2));
    //sumaTotal();
}).on('submit', function(e){
    e.preventDefault();
});

$( "#pagosform-form_base0-disp" ).keyup(function() {

    var sumaIva=$("#pagosform-form_iva").val();
    var sumaTarifa=$("#pagosform-form_base12-disp").val().replace('$','');
        sumaTarifa=sumaTarifa.replace(',','');
        sumaTarifa=sumaTarifa.replace(',','');
        sumaTarifa=parseFloat(sumaTarifa.replace(',',''));
    var sumaNueva=$('#pagosform-form_base0-disp').val().replace('$','');
    sumaNueva=sumaNueva.replace(',','');
    sumaNueva=sumaNueva.replace(',','');
    sumaNueva=parseFloat(sumaNueva.replace(',',''));
    suma=parseFloat(sumaIva)+sumaNueva+sumaTarifa;
    $("#pagosform-form_total").val(suma.toFixed(2));
    //sumaTotal();
}).on('submit', function(e){
    e.preventDefault();
});

var cedulavalida='';
var ValidaExtranjero='';
$("#pagosform-identificacion").keyup(function () {
    //alert($("#extranjero").is(':checked'));
    if (screen.width<1024) {
        if ($("#extranjero").is(':checked')) {
            //alert('ok');
            ValidaExtranjero = 'S';
            if (!/^[ a-z0-9]*$/i.test(this.value)) {
                this.value = this.value.replace(/[^ a-z0-9]+/ig, "");
            }
        } else {
            ValidaExtranjero = 'N';
            if (!/^[ 0-9]*$/i.test(this.value)) {
                this.value = this.value.replace(/[^ 0-9]+/ig, "");
            }
        }
    }else{
        if ($("#extranjero1").is(':checked')) {
            ValidaExtranjero = 'S';
            if (!/^[ a-z0-9]*$/i.test(this.value)) {
                this.value = this.value.replace(/[^ a-z0-9]+/ig, "");
            }
        } else {
            ValidaExtranjero = 'N';
            if (!/^[ 0-9]*$/i.test(this.value)) {
                this.value = this.value.replace(/[^ 0-9]+/ig, "");
            }
        }
    }
    var cedula = $(this).val();
    if (((cedula.length >= 5) && (cedula.length <= 10))||(ValidaExtranjero==='S')){
        if ((cedula.length >= 10) && (cedula.length <= 13)) {
            var cad = $(this).val();
            var total = 0;
            var longitud = cad.length;
            var longcheck = longitud - 1;
            if (cad !== "" && longitud === 10) {
                for (i = 0; i < longcheck; i++) {
                    if (i % 2 === 0) {
                        var aux = cad.charAt(i) * 2;
                        if (aux > 9) aux -= 9;
                        total += aux;
                    } else {
                        total += parseInt(cad.charAt(i));
                    }
                }
                total = total % 10 ? 10 - total % 10 : 0;
                if ((cad.charAt(longitud - 1) == total) || ValidaExtranjero === 'S') {
                    cedulavalida = '1';
                    $("#cedulaInc").hide();
                    $.ajax({
                        type: "post",
                        url: base_url + "/ajax/informacioncliente",
                        dataType: 'json',
                        data: {'id': $(this).val()},
                        beforeSend: function () {
                        },
                        success: function (data) {
                            if (data.nombres != null) {//gli
                                cedulavalida = '2';
                                $("#pagosform-nombres").val(data.nombres);
                                $("#pagosform-apellidos").val(data.apellidos);
                                $("#pagosform-email").val(data.email);
                                $("#pagosform-email").val(data.email);
                            } else {
                                $("#pagosform-nombres").val();
                                $("#pagosform-apellidos").val();
                                $("#pagosform-email").val();
                                $("#pagosform-email").val();
                            }//gli
                        },
                        async: false,
                        cache: false
                    });
                } else {
                    cedulavalida = '0';
                    $("#pagosform-nombres").val('');
                    $("#pagosform-apellidos").val('');
                    $("#pagosform-email").val('');
                    $("#cedulaInc").show();
                    $("#PagoSubmitButtonCobroDirecto").focus();
                }
            }
        }
}else{
        cedulavalida = '0';
        $("#pagosform-nombres").val('');
        $("#pagosform-apellidos").val('');
        $("#pagosform-email").val('');
        $("#cedulaInc").show();
        $("#PagoSubmitButtonCobroDirecto").focus();
    }
});



$("#pagosform-identificacion").blur(function() {
    if(cedulavalida=='1'){
        $("#pagosform-nombres").focus();
        cedulavalida='';
    }else if(cedulavalida=='0'){
        $("#PagoSubmitButtonCobroDirecto").focus();
        $("#pagosform-identificacion").focus();
        cedulavalida='';
    }else if(cedulavalida=='2'){
        $("#pagosform-form_descripcion").focus();
        cedulavalida='';
    }

});
$('#pagosform-form_descripcion').change( function() {
    $('#pagosform-form_base12-disp').focus();
});

//gli fin

function getpedido(id){
	$.ajax({
        type: "get",
        url: base_url+"/pedidos/wsgetpedido/?id="+id,
        dataType: 'html',
        beforeSend: function(){
            $("#loader_pedido_"+id).show();
            $("#btn_pedido_"+id).hide();
        },
        success: function(data){
            $("#loader_pedido_"+id).hide();
            $("#btn_pedido_"+id).show();
            // $.pjax.reload({container:'#crud-datatable'});
            $("#crud-datatable").yiiGridView("applyFilter");
        },
        async: true,
        cache: false
    });
}

function calcularMontosIva(){
    var valor_impuesto_iva = $("#valor_iva").val();
    var valor_iva = ( $("#pedidos-total_con_iva").val() > 0) ? $("#pedidos-total_con_iva").val() : 0;
    var valor_sin_iva = ( $("#pedidos-total_sin_iva").val() ) ? $("#pedidos-total_sin_iva").val() : 0;
    
    var total_iva = parseFloat(valor_iva) * parseFloat(valor_impuesto_iva);
    var solo_iva = parseFloat(total_iva) - parseFloat(valor_iva);
    
    $("#iva").val(solo_iva.toFixed(2));
    var a_pagar = parseFloat( total_iva ) + parseFloat(valor_sin_iva);    

    $("#a_pagar").val( a_pagar.toFixed(2) );
}


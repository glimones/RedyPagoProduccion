$(document).ready(function(){

    $('#recuperar-form').on('beforeSubmit', function(e) {
        $(".response_email").html("Procesando ...");
        // $("#PagoSubmitButton").prop('disabled', true);
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            dataType: 'html',
            success: function (data) {
                $(".response_email").html(data);
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });
    
});

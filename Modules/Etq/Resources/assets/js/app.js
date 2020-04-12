$(function() {
    /**
     * Carregar filiais de determinada empresas
     */
    $('.change-firma').on('change', function() {
        let slug = $(this).attr("data-firma");
        if(this.checked) {
            let name = $(this).val();
            etqStockLoadFields(name);
        } else {
            $('#'+slug).html('');
        }
    });

    /**
     * Selecionar todos checkbox de determinada emoresa.
     * @param firma
     */
    checkFilialAll = function(firma) {
        let checkedStatus = $('#filiais-'+firma).prop("checked");
        $('.filiais_types-'+firma+' .checked-'+firma).each(function () {
            $(this).prop('checked', checkedStatus);
        });
    }

    /**
     * Selecionar todos checkbox das categorias da filial.
     * @param key
     */
    checkTypesAll = function(key) {
        let checkedStatus = $('#filial-'+key).prop("checked");
        $('.type-'+key).each(function () {
            $(this).prop('checked', checkedStatus);
        });
    }

    /**
     * Carregar filiais e categorias referente a Empresa.
     * @param name
     */
    etqStockLoadFields = function(name) {
        let form = $('#form-etq-extoque'),
            load = $('#empty_filter'),
            data_ref = $('input[name="data_ref"]').val(),
            token = $('input[name="_token"]').val();
        $.ajax({
            url: form.attr('action')+'/firmas',
            type: 'POST',
            dataType: 'json',
            data: {data_ref: data_ref, firma: name, _token:token},
            beforeSend: function() {
                load.hide();
                load.html('');
            },
            success: function(response){
                if (response.error) {
                    load.show();
                    load.html(response.error);
                }
                $('#load_filiais').prepend(response.html);
            }
        });
    }
});

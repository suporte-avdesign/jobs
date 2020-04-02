@extends('etq::layouts.master')


@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4">Filtrar Estoque</h1>
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-file-excel mr-1"></i>Exportar Excel</div>
                    <div class="card-body">
                        <form id="form-etq-extoque" action="{{route('etq-estoque-filter')}}" onsubmit="return false">
                            @csrf
                            <div class="form-group">
                                <input type="date" class="form-control" id="data_ref" name="data_ref" value="{{$data_ref}}" placeholder="{{$data_ref}}">
                            </div>
                                @include('etq::etq_estoque.partials._firmas')
                                <div id="load_filiais"></div>
                            <div class="form-group text-center" style="margin-top: 20px">
                                <button type="button" id="btn-filter-company" onclick="etqStockFilter()" class="btn btn-dark" style="display: none;">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    Download
                                </button>
                                <button id="btn-load" class="btn btn-dark" type="button" style="display: none">
                                    <span class="spinner-border spinner-border-sm" aria-hidden="true" disabled></span>
                                    Aguarde...
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2019</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
<script type='text/javascript'>
    $(document).ready(function(){
        $('.change-firma').on('change', function() {
            if(this.checked) {
                var name = $(this).val(),
                    slug = $(this).attr("data-firma");
                etqStockLoadFields(name);
            } else {
                var slug = $(this).attr("data-firma");
                $('#'+slug).html('');
                $('#btn-filter-company').hide();
            }
        })
    });

    function checkFilialAll(firma) {
        var checkedStatus = $('#filiais-'+firma).prop("checked");
        $('.filiais_types-'+firma+' .checked-'+firma).each(function () {
            $(this).prop('checked', checkedStatus);
        });
    }

    function checkTypesAll(key) {
        var checkedStatus = $('#filial-'+key).prop("checked");
        alert(checkedStatus);
        $('.type-'+key).each(function () {
            $(this).prop('checked', checkedStatus);
        });
    }

    function etqStockLoadFields(name) {
        var form = $('#form-etq-extoque'),
            data_ref = $('input[name="data_ref"]').val();
            token = $('input[name="_token"]').val();

        $.ajax({
            url: form.attr('action')+'/load',
            type: 'POST',
            dataType: 'json',
            data: {data_ref: data_ref, firma: name, _token:token},
            beforeSend: function() {
                isCheckedFilter();
                $('#empty_filter').hide();
                $('#empty_filter').html('');
            },
            success: function(response){
                if (response.error) {
                    $('#empty_filter').show();
                    $('#empty_filter').html(response.error);
                }
                $('#load_filiais').prepend(response.html);
                //isCheckedFilter();
            },
            error: function(xhr){ // Falta fazer function para tratar os erros.
                isCheckedFilter();
                $('#empty_filter').show();
                $('#empty_filter').html('Error inesperado, atualize o navegador e tente novamente');
            }
        });
    }

    function isCheckedFilter() {
        var checked=false,
            btn = $('#btn-filter-company'),
            load = $('#btn-load');
        $('.change-firma').each(function(){
            if($(this).prop("checked"))
                checked=true
        });
        if (!checked) {
            btn.hide();
            load.show();
        } else {
            btn.show();
            load.hide();
        }
    }

    function etqStockFilter(){
        var form = $('#form-etq-extoque');
        $.ajax({
            url: form.attr('action')+'/data',
            type: 'POST',
            dataType: 'json',
            data: form.serialize(),
            beforeSend: function() {
                $('#empty_filter').hide();
                $('#empty_filter').html('');
            },
            success: function(response){
                if (response.error) {
                    $('#empty_filter').show();
                    $('#empty_filter').html(response.error);
                }
                $('#load_firmas').html(response.firmas);
                $(".check-firma").click(function () {
                    //changeFiliais(response.filiais, url);
                });
            },
            error: function(xhr){ // Falta fazer function para tratar os erros.
                $('#empty_filter').show();
                $('#empty_filter').html('Error inesperado, atualize o navegador e tente novamente');
            }
        });
    }

</script>
@endpush


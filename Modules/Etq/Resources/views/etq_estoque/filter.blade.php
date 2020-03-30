@extends('etq::layouts.master')


@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4">Filtrar Estoque</h1>
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-file-excel mr-1"></i>Exportar Excel</div>
                    <div class="card-body">
                        <form id="form-etq-extoque" action="{{route('etq-estoque-companies')}}" onsubmit="return false">
                            @csrf
                            <div class="form-group">
                                <input type="date" class="form-control" id="data_ref" name="data_ref" value="{{$data_ref}}" placeholder="{{$data_ref}}">
                            </div>

                            @include('etq::etq_estoque.partials._firmas')
                            @include('etq::etq_estoque.partials._filiais')

                            <div class="form-group text-center">
                                <button type="button" onclick="etqEstoqueFilterFirma()" class="btn btn-dark">Download</button>
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
                    key = $(this).attr("data-firma");
                etqfilterFirma(name);
            } else {
                var key = $(this).attr("data-firma");
                $('#filiais_types-'+key).html('');
            }
        })
        $('.check-firma').click (function () {
            var firma = $(this).attr('data-firma'),
                checkedStatus = this.checked;
            $('#filiais_types .checked-'+firma).each(function () {
                $(this).prop('checked', checkedStatus);
            });
        });
        $('.check-filial').click (function () {
            var filial = $(this).attr('data-filial'),
                checkedStatus = this.checked;
            $('#filiais_types .check-type-'+filial).each(function () {
                $(this).prop('checked', checkedStatus);
            });
        });
    });

    function etqfilterFirma(name) {
        var form = $('#form-etq-extoque');
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: {firma: name},
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

    function etqEstoqueFilterFirma(){

        var form = $('#form-etq-extoque');
        $.ajax({
            url: form.attr('action'),
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


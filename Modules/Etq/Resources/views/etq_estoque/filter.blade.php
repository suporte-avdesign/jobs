@extends('etq::layouts.master')


@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4">Filtrar Estoque</h1>
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-file-excel mr-1"></i>Exportar Excel</div>
                    <div class="card-body">
                        @include('etq::etq_estoque.partials._message')
                        <form id="form-etq-extoque" method="POST" action="{{route('etq-estoque-export')}}">



                            <div class="form-group">
                                <label for="data_ref">Selecione a data</label><input type="date" class="form-control" id="data_ref" name="data_ref" value="{{$data_ref}}" placeholder="{{$data_ref}}">
                            </div>
                                @include('etq::etq_estoque.partials._firmas')
                                <div id="load_filiais"></div>
                            <div class="form-group text-center" style="margin-top: 20px">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    Download
                                </button>
                            </div>
                            <div class="hide">@csrf</div>
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
            return false;
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
            return false;
        }

        /**
         * Obter filiais e categorias referente a Empresa.
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
</script>
@endpush


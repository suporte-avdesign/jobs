@php $i=0; @endphp
@foreach($data as $item)
    <div class="card" id="filiais_types-{{$i}}">
        <div class="card-body">
                <div class="form-check">
                    <input class="form-check-input check-firma" type="checkbox"  data-firma="{{$loop->index}}" id="select-{{$loop->index}}">
                    <label><h5><span class="badge badge-secondary">{{$item->firma}}</span></h5></label>
                </div>

                <div id="filiais_types">
                    @foreach($item->filiais as $key => $name)
                        <div class="form-check">
                            <input class="form-check-input check-filial checked-{{$i}}" type="checkbox" name="filiais[{{$i}}][]" value="{{$name->filial}}" data-filial="{{$i}}-{{$loop->index}}" id="filial-{{$loop->index}}">
                            <label class="form-check-label" for="filial-{{$loop->index}}"><b>{{$name->filial}}</b></label>
                        </div>
                        <div class="container">
                            @foreach(array_chunk($item->types, 4) as $chunks)
                                <div class="row">
                                    @foreach($chunks as $type)
                                        <div class="col-3">
                                            <div class="form-check form-check-inline"style="left: 20px">
                                                <input class="form-check-input check-type-{{$i}}-{{$key}} checked-{{$i}}" type="checkbox" name="tipo[{{$i}}][{{$key}}][]" value="{{$type->categoria_estoque}}" data-type="{{$loop->index}}" id="type-{{$loop->index}}">
                                                <label class="form-check-label" for="type-{{$loop->index}}">{{ucwords(strtolower($type->categoria_estoque))}}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
    </div>
    @php $i++; @endphp
    <br/>
@endforeach

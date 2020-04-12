@php $i=0; @endphp
<div class="card" id="{{$slug}}">
    <div class="card-body">
        <div class="form-check">
            <input class="form-check-input check-filial" type="checkbox" id="filiais-{{$slug}}" onchange="checkFilialAll('{{$slug}}')">
            <label><h5><span class="badge badge-secondary">{{$firma}}</span></h5></label>
        </div>

        <div class="filiais_types-{{$slug}}">
            @foreach($items as $key => $item)
                <div class="form-check">
                    <input class="form-check-input check-types checked-{{$slug}}" type="checkbox" name="filiais[{{$i}}][]" value="{{$item->filiais}}"  id="filial-{{$loop->index}}" onchange="checkTypesAll('{{$i}}')">
                    <label class="form-check-label" for="filial-{{$loop->index}}"><b>{{$item->filiais}}</b></label>
                </div>
                <div class="container types-{{$i}}">
                    @foreach(array_chunk($item->types, 4) as $chunks)
                        <div class="row">
                            @foreach($chunks as $type)
                                <div class="col-3">
                                    <div class="form-check form-check-inline"style="left: 20px">
                                        <input class="form-check-input checked-{{$slug}} type-{{$i}}" type="checkbox" name="tipos[{{$i}}][{{$key}}][]" value="{{$type->categoria_estoque}}" data-type="{{$loop->index}}" id="type-{{$key}}">
                                        <label class="form-check-label" for="type-{{$loop->index}}">{{ucwords(strtolower($type->categoria_estoque))}}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @php $i++; @endphp
                <br/>
            @endforeach
        </div>
    </div>
</div>


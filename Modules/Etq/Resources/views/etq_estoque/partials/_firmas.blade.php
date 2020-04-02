@foreach($items as $item)
    <div class="form-check">
        <input class="form-check-input change-firma" type="checkbox" name="firmas[]" value="{{$item->firma}}" data-firma="{{$item->slug}}" id="firma-{{$loop->index}}">
        <label class="form-check-label" for="firma-{{$loop->index}}">{{$item->firma}}</label>
    </div>
@endforeach

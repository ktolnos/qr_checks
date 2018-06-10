<tr>
    @foreach($params['props'] as $prop)
        <td class="table-text">
            <div>{{ $item->$prop }}</div>
        </td>
    @endforeach


    <td>
        <?php $id = $params['idProp']?>
        <div class="btn-group" role="group">
            @if(in_array('edit', $params['buttons']))
                <a class="btn btn-secondary" href="{{$urlBase.'/edit/'.$item->$id}}">Edit</a>
            @endif
            @if(in_array('delete', $params['buttons']))
                <a class="btn btn-secondary" href="{{$urlBase.'/delete/'.$item->$id}}">Delete</a>
            @endif
            @if(in_array('view', $params['buttons']))
                <a class="btn btn-secondary" href="{{$urlBase.'/view/'.$item->$id}}">View</a>
            @endif

        </div>
    </td>
</tr>
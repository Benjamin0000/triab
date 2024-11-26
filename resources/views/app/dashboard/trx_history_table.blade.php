<div id="trx_table_con"> 
    <div class="table-responsive table-nowrap">
        <table class="table table-hover table-bordered text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Source</th>
                    <th>Description</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @php $no = tableNumber(10) @endphp
                @foreach($transactions as $trx)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{format_with_cur($trx->amt)}}</td>
                        <td>
                            @if($trx->type == CREDIT)
                                <span class="badge bg-success">Credit</span>
                            @else 
                                <span class="badge bg-danger">Debit</span>
                            @endif 
                        </td>
                        <td>
                            {{$trx->name}}
                        </td>
                        <td>
                            {{$trx->desc}}
                        </td>
                        <td>
                            {{$trx->created_at->isoFormat('lll')}}
                            <div>{{$trx->created_at->diffForHumans()}}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="trx_pg_links_con"> 
    {{$transactions->links()}}
    </div>
</div>
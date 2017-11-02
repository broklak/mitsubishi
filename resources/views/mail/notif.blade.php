<div>
    Hi {{$user->first_name}},
    @if($type == 'create')
    <p>Ada 1 SPK baru yang menunggu approval anda</p>
    <p>Nomor SPK : <b>{{$order->spk_code}}</b></p>
    @elseif($type == 'update')
    <p>SPK Nomor <b>{{$order->spk_code}}</b> baru saja di revisi dan menunggu approval anda.</p>
    @endif
    <p>Silahkan klik link berikut untuk melihat SPK. <a href="{{route('order.show', ['id' => $order->id])}}">Lihat SPK</a></p>
</div>
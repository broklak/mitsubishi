<div>
    Hi {{$user->first_name}},
    <p>Ada 1 SPK baru yang menunggu approval anda</p>
    <p>Nomor SPK : {{$order->spk_code}}</p>
    <p>Silahkan klik link berikut untuk melihat SPK. <a href="{{route('order.show', ['id' => $order->id])}}">Lihat SPK</a></p>
</div>
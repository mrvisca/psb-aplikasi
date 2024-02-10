<span>Hallo {{ $nama }}... Mohon simpan email ini dengan baik ya, karena ini kredensial kamu untuk menggunakan aplikasi psb.</span>

<br/><br/>
<span>Nama Guru : </span>
<span>{{ $nama }}</span>
<br/>
<span>Email : </span>
<span>{{ $email }}</span>
<br/>
<span>Password : </span>
<span>{{ $password }}</span>
<br/>
<span>Telpon : </span>
<span>{{ $telpon }}</span>
<br/>
<span>Jabatan : </span>
<span>{{ $jabatan }}</span>
<br/><br/>

<span>Regards,<span><br>
{{ config('app.name') }}

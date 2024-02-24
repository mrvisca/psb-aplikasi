<table class="table table-striped">
    <thead>
        <tr>
            <th colspan="7" style="background-color: yellow; text-align:center; font-size: 30px; height: 50px; font-weight:50;"><b>Data Master Siswa</b></th>
        </tr>
        <tr>
            <th style="width: 200px; text-align:center; height: 40px;"><b>NIS</b></th>
            <th style="width: 200px; text-align:center"><b>Nama Siswa</b></th>
            <th style="width: 200px; text-align:center"><b>Email</b></th>
            <th style="width: 200px; text-align:center"><b>Jenis Kelamin</b></th>
            <th style="width: 200px; text-align:center"><b>Kelas</b></th>
            <th style="width: 200px; text-align:center"><b>Telpon</b></th>
            <th style="width: 200px; text-align:center"><b>Periode Angkatan</b></th>
        </tr>
        <tr>
            <th style="text-align: center; background-color:#3c8dbc; color:white">1</th>
            <th style="text-align: center; background-color:#3c8dbc; color:white">2</th>
            <th style="text-align: center; background-color:#3c8dbc; color:white">3</th>
            <th style="text-align: center; background-color:#3c8dbc; color:white">4</th>
            <th style="text-align: center; background-color:#3c8dbc; color:white">5</th>
            <th style="text-align: center; background-color:#3c8dbc; color:white">6</th>
            <th style="text-align: center; background-color:#3c8dbc; color:white">7</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        <tr>
            <td style="text-align:left;"> {{ $d['nis'] }} </td>
            <td style="text-align:left;"> {{ $d['name'] }} </td>
            <td style="text-align:left;"> {{ $d['email'] }} </td>
            <td style="text-align:left;">{{ $d['jenkel'] }} </td>
            <td style="text-align:left;">{{ $d['kelas'] }} </td>
            <td style="text-align:left;">{{ $d['telpon'] }} </td>
            <td style="text-align:left;">{{ $d['periode'] }} </td>
        </tr>
        @endforeach
    </tbody>
</table>
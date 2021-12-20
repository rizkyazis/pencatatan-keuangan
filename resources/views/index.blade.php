<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
    <div class="container mt-5">
        <div id="liveAlertPlaceholder"></div>
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Saldo</h4>
                        <h2 class="card-title" id="saldo-total">Rp. 450.000,00</h2>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <form action="{{route('saldo.tambah')}}" method="POST" id="form-saldo">
                    @method("PUT")
                    @csrf
                    <input type="number" name="jumlah" id="jumlah" class="form-control bg-white mb-2">
                </form>
                <button onclick="tambahSaldo()" class="btn btn-sm btn-success">
                    Tambah
                </button>
                <button onclick="kurangSaldo()" class="btn btn-sm btn-danger">
                    Kurang
                </button>
            </div>
            <div class="col-12">
                <table class="table bg-white mt-3 border rounded-3">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Transaksi</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Saldo</th>

                        </tr>
                    </thead>
                    <tbody id="tabel-history">
                        <tr>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        saldo();

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function saldo(){
        $.ajaxSetup({
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "/api/saldo",
                    type:"GET",
                    success: function (result) {
                        let saldo = $("#saldo-total");
                        saldo.empty();
                        saldo.append("Rp."+numberWithCommas(result.data.saldo));

                        let tableHistory = $("#tabel-history");
                        tableHistory.empty();
                        let history= result.data.history;

                        let index = 1;
                        history.map(function(data){
                            tableHistory.append('<tr>'+
                            '<th scope="row">'+index+'</th>'+
                            '<td>'+data.jenis+'</td>'+
                            '<td>Rp. '+numberWithCommas(data.jumlah)+'</td>'+
                            '<td>Rp. '+numberWithCommas(data.saldo)+'</td>'+
                            '</tr>');
                            index++;
                        });


                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        console.log(errorThrown)
                    },
                    dataType: "json",
                    contentType: "application/json"
                });

        }
        function tambahSaldo(){
            var messsage = confirm("Apakah anda ingin menambah Saldo?")
            if(messsage) {
                $.ajaxSetup({
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "/api/saldo/tambah",
                    type:"PUT",
                    data: JSON.stringify({
                        "jumlah":$('#jumlah').val()
                    }),
                    success: function (result) {
                        console.log(result);
                        document.getElementById("form-saldo").reset();
                        saldo();
                        let typeAlert = "";
                        if(result.status == 'Success'){
                            typeAlert = 'success';
                        }else if(result.status == 'Error'){
                            typeAlert = 'danger';
                        }

                        alert(result.message,typeAlert);

                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        console.log(errorThrown)
                    },
                    dataType: "json",
                    contentType: "application/json"
                });
            }
        }

        function kurangSaldo(){
            var messsage = confirm("Apakah anda ingin menambah Saldo?")
            if(messsage) {
                $.ajaxSetup({
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "/api/saldo/kurang",
                    type:"PUT",
                    data: JSON.stringify({
                        "jumlah":$('#jumlah').val()
                    }),
                    success: function (result) {
                        console.log(result);
                        document.getElementById("form-saldo").reset();
                        saldo();

                        let typeAlert = "";
                        if(result.status == 'Success'){
                            typeAlert = 'success';
                        }else if(result.status == 'Error'){
                            typeAlert = 'danger';
                        }

                        alert(result.message,typeAlert);

                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        console.log(errorThrown)
                    },
                    dataType: "json",
                    contentType: "application/json"
                });
            }
        }

        function alert(message, type) {
            var wrapper = document.createElement('div')
            wrapper.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'

            document.getElementById('liveAlertPlaceholder').append(wrapper)
        }
    </script>
</body>

</html>

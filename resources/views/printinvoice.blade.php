<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
  <style>
    .clearfix:after {
    content: "";
    display: table;
    clear: both;
    }

    body {
    position: relative;
    width: 21cm;  
    height: 29.7cm; 
    margin: 0 auto; 
    color: #001028;
    background: #FFFFFF; 
    font-family: Arial, sans-serif; 
    font-size: 10px; 
    font-family: Arial;
    }

    header {
    padding: 10px 0;
    margin-bottom: 5px;
    }

    #project {
    float: left;
    }

    #company {
        float: right;
        width: 40%;
        height: 60px;
        text-align: right;
    }

    div,
    #company div {
    white-space: nowrap;        
    }

    table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 10px;
    }


    table th,
    table td {
    text-align: center;
    }

    table th {
    padding: 5px 10px;
    color: #5D6975;
    border-bottom: 1px solid #5D6975;
    white-space: nowrap;        
    font-weight: normal;
    }

    table .service,
    table .desc,
    table .no {
    text-align: left;
    }

    table td {
    padding: 10px;
    text-align: right;
    }

    table td.service {
    vertical-align: top;
    }

    table td.unit,
    table td.qty,
    table td.total {
    font-size: 1.2em;
    }

    table td.grand {
    border-top: 1px solid #5D6975;;
    }
</style>
    <header class="clearfix">
        
        <div id="company" class="clearfix">
            <div>Toko Oky</div>
            <div>455 Foggy Heights,<br /> AZ 85004, US</div>
            <div>(602) 519-0450</div>
        </div>
        <div id="project">
            <div> {{ $glb->nama_konsumen }}</div>
            <div> {{ $glb->alamat_konsumen }} </div>
            <div> {{ $glb->no_telp }} </div>
        </div>
    </header>

    <main>
        Invoice : {{ $glb->no_invoice }}
        <table>
            <thead>
            <tr>
                <th class="no">No</th>
                <th class="service">Barang</th>
                <th>Harga</th>
                <th>QTY</th>
                <th>TOTAL</th>
            </tr>
            </thead>
            <tbody>
            @php $i=1 @endphp
            @foreach($det as $dta)
            <tr>
                <td class="no">{{$i}}</td>
                <td class="service">{{ $dta->nama_barang }}</td>
                <td class="unit">{{ number_format($dta->harga, 2, ',', '.')}}</td>
                <td class="qty">{{ $dta->qty }} {{ $dta->keterangan_qty }}</td>
                <td class="total">{{ number_format($dta->total_harga, 2, ',', '.')  }}</td>
            </tr>
            @php $i++ @endphp
            @endforeach
            <tr>
                <td colspan="4">SUBTOTAL</td>
                <td class="total">{{ number_format($glb->total_belanja, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="grand total">GRAND TOTAL</td>
                <td class="grand total">Rp. {{ number_format($glb->total_bersih, 2, ',', '.') }}</td>
            </tr>
            </tbody>
        </table>
    </main>
  </body>
</html>
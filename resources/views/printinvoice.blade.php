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
    width: 24.13cm;  /* 9.5 inch = 24.13 panjang */
    height: 27.94cm; /* 11 inch = 27.94 lebar */
    margin-top: 20px; 
    padding :0 ;
    color: #001028;
    background: #FFFFFF;  
    font-size: 12px; 
    font-family:"Times New Roman";
    }

    header {
    padding: 0px 0px;
    margin-bottom: 0px;
    display:inline-block;
    }

    #project {
    float: left;
    }

    #company {
        float: left;
        
        width: 10%;
        margin-left :60%;
        text-align: right;
    }

    div,
    #company div {
    white-space: nowrap;        
    }

    table {
    width: 80%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 5px;
    }


    table th,
    table td {
    text-align: center;
    }

    table th {
    padding: 5px 5px;
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
    padding: 3px;
    text-align: right;
    }

    table td.service {
    vertical-align: top;
    }

    table td.unit,
    table td.qty,
    table td.total {
    font-size: 1.0em;
    }

    table td.grand {
    border-top: 1px solid #5D6975;;
    }
</style>
    <div id="print">
        <header class="clearfix">
        <div id="project" >
                <div> {{ $glb->nama_konsumen }}</div>
                <div> {{ $glb->alamat_konsumen }} </div>
                <div> {{ $glb->no_telp }} </div>
            </div>
            <div id="company">
                <div>Toko Oky</div>
                <div>Jl. Kusuma Bangsa no 74,<br /> Wirosari</div>
                <div>0822 2667 0094</div>
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
                <tr height="1px" cellspacing="0">
                    <td class="no">{{$i}}</td>
                    <td class="service">{{ $dta->nama_barang }}</td>
                    <td class="unit">{{ number_format($dta->harga, 2, ',', '.')}}</td>
                    <td class="qty">{{ $dta->qty }} {{ $dta->keterangan_qty }}</td>
                    <td class="total">{{ number_format($dta->total_harga, 2, ',', '.')  }}</td>
                </tr>
                @php $i++ @endphp
                @endforeach
                <tr>
                    <td colspan="4" class="grand total">GRAND TOTAL</td>
                    <td class="grand total">Rp. {{ number_format($glb->total_bersih, 2, ',', '.') }}</td>
                </tr>
                </tbody>
            </table>
        </main>
    </div>    
  </body>
</html>
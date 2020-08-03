<template>
<div>
    <button @click="download()">Download Invoice</button>
        <div id="print" ref="print">

            <header class="clearfix">
                <h1>{{ glb.no_invoice }}</h1>
                <div id="company" class="clearfix">
                    <div>Toko Oky</div>
                    <div>455 Foggy Heights,<br /> AZ 85004, US</div>
                    <div>(602) 519-0450</div>
                </div>
                <div id="project">
                    <div><span>Pelanggan</span> {{ glb.nama_konsumen }}</div>
                    <div><span>Alamat</span> {{ glb.alamat_konsumen }} </div>
                    <div><span>Telp</span> {{ glb.no_telp }} </div>
                </div>
            </header>

            <main>
                <table>
                    <thead>
                    <tr>
                        <th>no</th>
                        <th class="service">Barang</th>
                        <th>Harga</th>
                        <th>QTY</th>
                        <th>TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(dta, index) in det" :key="dta.nama_barang">
                        <td>{{index + 1}}</td>
                        <td class="service">{{ dta.nama_barang }}</td>
                        <td class="unit">{{ dta.harga | currency}}</td>
                        <td class="qty">{{ dta.qty }} {{ dta.keterangan_qty }}</td>
                        <td class="total">{{ dta.total_harga | currency}}</td>
                    </tr>
                    <tr>
                        <td colspan="4">SUBTOTAL</td>
                        <td class="total">{{ glb.total_belanja | currency}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="grand total">GRAND TOTAL</td>
                        <td class="grand total">{{ glb.total_bersih | currency}}</td>
                    </tr>
                    </tbody>
                </table>
            </main>
        </div>
        <div id="elementH"></div>
</div>
</template>
<style>
    .clearfix:after {
    content: "";
    display: table;
    clear: both;
    }

    #print {
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

    #print header {
    padding: 10px 0;
    margin-bottom: 30px;
    }


    #print h1 {
    border-top: 1px solid  #5D6975;
    border-bottom: 1px solid  #5D6975;
    color: #5D6975;
    font-size: 2.4em;
    line-height: 1.4em;
    font-weight: normal;
    text-align: center;
    margin: 0 0 20px 0;
    }

    #project {
    float: left;
    }

    #project span {
    color: #5D6975;
    text-align: right;
    width: 52px;
    margin-right: 10px;
    display: inline-block;
    font-size: 0.8em;
    }

    #company {
    float: right;
    text-align: right;
    margin-right: 10px;
    }

    #project div,
    #company div {
    white-space: nowrap;        
    }

    #print table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 10px;
    }

    #print table tr:nth-child(2n-1) td {
    background: #F5F5F5;
    }

    #print table th,
    #print table td {
    text-align: center;
    }

    #print table th {
    padding: 5px 10px;
    color: #5D6975;
    border-bottom: 1px solid #C1CED9;
    white-space: nowrap;        
    font-weight: normal;
    }

    #print table .service,
    #print table .desc {
    text-align: left;
    }

    #print table td {
    padding: 15px;
    text-align: right;
    }

    #print table td.service {
    vertical-align: top;
    }

    #print table td.unit,
    #print table td.qty,
    #print table td.total {
    font-size: 1.2em;
    }

    #print table td.grand {
    border-top: 1px solid #5D6975;;
    }
</style>


<script>
import jsPDF from 'jspdf'
import html2canvas from "html2canvas"

  export default {
      created() {
        this.getData();
        },
      data() {
        return {
            glb:{},
            det:{}
            
        }
      },
      
    methods: {
        

        getData(){
            let uri = `api/transaksiglobals/printinvoice/${this.$route.params.no_invoice}`;
            
            axios.get(uri).then(response => {
                
                this.glb = response.data.glb[0];
                this.det = response.data.det;
            }).catch(errors => {
                    console.log(errors);
                });
        },
        download(){
            var htmldt = this.$refs.print
            // var dt = $('#print').html();
            // var specialElementHandlers = {
            //     '#elementH': function (element, renderer) {
            //         return true;
            //     }
            // };
            var doc = new jsPDF('l','pt', 'a4');
            doc.setFontSize(10);
            html2canvas(htmldt, {
                scale:6,
            }).then(canvas=>{
                var image = canvas.toDataURL('image/jpeg');
                doc.addImage(image, 'JPEG', 10, 10, 800, 650);
                doc.save(this.glb.no_invoice)
            })

            // doc.fromHTML(dt, 15, 15, {
            //     'width': 170,
            //     'elementHandlers': specialElementHandlers
            // });
            // doc.save(this.glb.no_invoice)
        },
    }
  }
</script>
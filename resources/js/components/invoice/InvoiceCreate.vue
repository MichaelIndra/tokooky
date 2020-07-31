<template>
<div>
    <button @click="download()">Download Invoice</button>
        <div id="print" ref="print">
            
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="3">Invoice : <strong>{{ glb.no_invoice }}</strong></th>
                        <th>{{ glb.created_at }}</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h4>Toko OKY </h4>
                            <p> Alamat lu <br> 
                                no telp<br>
                                isi sek<br>
                            </p>
                        </td>
                        <td align="right" colspan="2">
                            <h4>Pelanggan: </h4>
                            <p>{{ glb.nama_konsumen }}<br>
                            {{ glb.alamat_konsumen }}<br>
                            {{ glb.no_telp }} <br>
                            
                            </p>
                        </td>
                    </tr>
                </thead>
                    <tbody>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                        <tr v-for="(dta) in det" :key="dta.nama_barang">
                            <td>{{ dta.nama_barang }}</td>
                            <td align="right">{{ dta.harga | currency}}</td>
                            <td>{{ dta.qty }}</td>
                            <td align="right">{{ dta.total_harga | currency}}</td>
                        </tr>
                        <tr>
                            <th colspan="3">Subtotal</th>
                            <td align="right">{{ glb.total_belanja | currency}}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <td align="right">{{ glb.total_bersih | currency}}</td>
                        </tr>
                    </tfoot>
                </table>
    </div>
</div>
</template>
<style>
    #print{
        font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        color:#333;
        text-align:left;
        font-size:10px;
        margin:0;
    }
   
    #print table{
        border-collapse:collapse;
        margin:0 auto;
        width:700px;
    }
    #print td, tr, th{
        padding:8px;
        width:120px;
    }
     h4, p{
        margin:0px;
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

            var doc = new jsPDF('l','pt', 'a4');
            doc.setFontSize(10);
            html2canvas(htmldt, {
                scale:6,
            }).then(canvas=>{
                var image = canvas.toDataURL('image/jpeg');
                doc.addImage(image, 'JPEG', 10, 10, 800, 450);
                doc.save(this.glb.no_invoice)
            })
        },
    }
  }
</script>
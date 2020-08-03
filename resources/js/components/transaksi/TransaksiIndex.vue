<template>
    <div class='container py-4'>
        <div class='row justify-content-center'>
            <div class='col-md-14'>
            <div class='card'>
                <div class='card-header'>Transaksi</div>
                <div class='card-body'>
                    <router-link :to="{ name: 'createtransaksi' }" class="btn btn-primary">Buat Transaksi</router-link>
                    <br/>
                    <br/>
                   <div class="table-style">
                        <input class="input" type="text" v-model="search" placeholder="Cari nama konsumen"
                            @input="resetPagination()" style="width: 250px;">
                        <div class="control">
                            <div class="select">
                                <select v-model="length" @change="resetPagination()">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th v-for="column in columns" :key="column.name" @click="sortBy(column.name)"
                                        :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                                        style="width: 20%; cursor:pointer;">
                                        {{column.label}}
                                    </th>
                                    <th width="250" class="text-center">Action</th>
                                    <th width="250" class="text-center">Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(transaksi, index) in paginated" :key="transaksi.no_invoice">
                                    <td width="50" class="text-center">{{ index + 1 }}</td>
                                    <td>{{ transaksi.no_invoice }}</td>
                                    <td>{{ transaksi.nama_konsumen }}</td>
                                    <td>{{ transaksi.total_belanja | currency }}</td>
                                    <td>{{ transaksi.total_bayar | currency }}</td>
                                    <td>{{ transaksi.total_bersih | currency }}</td>
                                    <td>{{ transaksi.status }}</td>
                                    <td>{{ transaksi.created_at }}</td>
                                    <td width="250" class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-success" @click="getDetailTransaksi(transaksi.no_invoice)">Detail Transaksi</button>
                                            <button v-show="transaksi.status == 'HUTANG'" class="btn btn-success" @click="transaksiBayar(transaksi.no_invoice)">Bayar</button>
                                        </div>
                                    </td>
                                    <td width="150" class="text-center">
                                        <button class="btn btn-success" @click="printInvoice(transaksi.no_invoice)">Print</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div>
                            <nav class="pagination" v-if="!tableShow.showdata">
                                <span class="page-stats">{{pagination.from}} - {{pagination.to}} of {{pagination.total}}</span>
                                <a v-if="pagination.prevPageUrl" class="btn btn-sm btn-primary pagination-previous" @click="--pagination.currentPage">
                                    Prev
                                </a>
                                <a class="btn btn-sm btn-primary pagination-previous" v-else disabled>
                                Prev
                                </a>
                                <a v-if="pagination.nextPageUrl" class="btn btn-sm pagination-next" @click="++pagination.currentPage">
                                    Next
                                </a>
                                <a class="btn btn-sm btn-primary pagination-next" v-else disabled>
                                    Next
                                </a>
                            </nav>
                            <nav class="pagination" v-else>
                                <span class="page-stats">
                                    {{pagination.from}} - {{pagination.to}} of {{filter.length}}
                                    <span v-if="`filter.length < pagination.total`"></span>
                                </span>
                                <a v-if="pagination.prevPage" class="btn btn-sm btn-primary pagination-previous" @click="--pagination.currentPage">
                                    Prev
                                </a>
                                <a class="btn btn-sm pagination-previous btn-primary" v-else disabled>
                                Prev
                                </a>
                                <a v-if="pagination.nextPage" class="btn btn-sm btn-primary pagination-next" @click="++pagination.currentPage">
                                    Next
                                </a>
                                <a class="btn btn-sm pagination-next btn-primary"  v-else disabled>
                                    Next
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        </div>
</template>
<script>
  export default {
      created() {
        this.getData();
        },
      data() {
        let sortOrders = {};
        let columns = [
            {label: 'No Invoice', name: 'no_invoice' },
            {label: 'Nama Konsumen', name: 'nama_konsumen' },
            {label: 'Total Belanja', name: 'total_belanja'},
            {label: 'Total Bayar', name: 'total_bersih'},
            {label: 'Pembayaran', name: 'total_bayar'},
            {label: 'Status Pembayaran', name: 'status'},
            {label: 'Tanggal Transaksi', name: 'created_at'},
        ];
        columns.forEach((column) => {
           sortOrders[column.name] = -1;
        });

        return {
            transaksis: [],
            columns: columns,
            sortKey: 'no_invoice',
            sortOrders: sortOrders,
            length: 10,
            search: '',
            tableShow: {
                showdata: true,
            },
            pagination: {
                currentPage: 1,
                total: '',
                nextPage: '',
                prevPage: '',
                from: '',
                to: ''
            },
            
        }
      },
      
    methods: {
        printInvoice(no_invoice){
            let uri = '/api/transaksiglobals/printinvoice/'+no_invoice;
            axios.get(uri).then(response=>{
                // let blob = new Blob([response], {type: 'arraybuffer'});
                // let link = document.createElement('a');
                // let objectURL = window.URL.createObjectURL(blob);
                // link.href = objectURL;
                // link.target = '_self';
                // link.download = "fileName.pdf";
                // (document.body || document.documentElement).appendChild(link);
                // link.click();
                // setTimeout(()=>{
                //     window.URL.revokeObjectURL(objectURL);
                //     link.remove();
                // }, 100);
            })
            window.open(uri);
        },

        getData(){
            let uri = 'api/transaksiglobals/getalltransaksi';
            axios.get(uri, {params: this.tableShow}).then(response => {
                console.log('The data: ', response.data)
                this.transaksis = response.data;
                this.pagination.total = this.transaksis.length;
            }).catch(errors => {
                    console.log(errors);
                });
        },
        paginate(array, length, pageNumber) {
            this.pagination.from = array.length ? ((pageNumber - 1) * length) + 1 : ' ';
            this.pagination.to = pageNumber * length > array.length ? array.length : pageNumber * length;
            this.pagination.prevPage = pageNumber > 1 ? pageNumber : '';
            this.pagination.nextPage = array.length > this.pagination.to ? pageNumber + 1 : '';
            return array.slice((pageNumber - 1) * length, pageNumber * length);
        },
        resetPagination() {
            this.pagination.currentPage = 1;
            this.pagination.prevPage = '';
            this.pagination.nextPage = '';
        },
        sortBy(key) {
            this.resetPagination();
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },
        getIndex(array, key, value) {
            return array.findIndex(i => i[key] == value)
        },
        getDetailTransaksi(no_invoice){
            let uri = `api/transaksiglobals/gettransaksidet/${no_invoice}`;
            let details={};
            let htmltable ='';
            let htmldata='';
            let htmlfinal ='';
            axios.get(uri).then(response => {
                details = response.data;

                htmltable = `<table id="table" border=1>
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Keterangan Qty</th>
                                        <th>Harga Satuan</th>
                                        <th>Harga Total</th>
                                    </tr>
                                </thead>
                                <tbody>`
                details.forEach((item)=>{
                    htmldata += `<tr>
                                    <td>`+item.nama_barang+`</td>
                                    <td>`+item.qty+`</td>
                                    <td>`+item.keterangan_qty+`</td>
                                    <td>`+item.harga+`</td>
                                    <td>`+item.total_harga+`</td>
                                </tr>`
                })
                htmlfinal = htmltable+htmldata+`</tbody></table>`
                this.$swal.fire({
                            title: 'Detail',
                            html : htmlfinal,
                        });




            }).catch(errors => {
                    console.log(errors);
                });

            
        },
        transaksiBayar(no_invoice){
            this.$swal.fire({
                title: 'Bayar hutang',
                input : 'text',
                showCancelButton: true,
                confirmButtonText: 'Bayar',
                showLoaderOnConfirm: true,
                preConfirm: (bayar) => {
                    let uri = `api/transaksiglobals/bayarinvoice/${no_invoice}/${bayar}`;
                    axios.get(uri).then(response => {
                        console.log('The data: ', response.data)
                        if(response.data.status){
                            this.getData();
                            this.$swal.close();
                        }
                    }).catch(errors => {
                            console.log(errors);
                        });
                },
            });

        }
        
        
    },
    computed: {
        filter() {
            let dta = this.transaksis;
            if (this.search) {
                dta = dta.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                    dta = dta.slice().sort((a, b) => {
                    let index = this.getIndex(this.columns, 'name', sortKey);
                    a = String(a[sortKey]).toLowerCase();
                    b = String(b[sortKey]).toLowerCase();
                    if (this.columns[index].type && this.columns[index].type === 'date') {
                        return (a === b ? 0 : new Date(a).getTime() > new Date(b).getTime() ? 1 : -1) * order;
                    } else if (this.columns[index].type && this.columns[index].type === 'number') {
                        return (+a === +b ? 0 : +a > +b ? 1 : -1) * order;
                    } else {
                        return (a === b ? 0 : a > b ? 1 : -1) * order;
                    }
                });
            }
            
            return dta;
        },
        paginated() {
            return this.paginate(this.filter, this.length, this.pagination.currentPage);
        }
       
    }
  }
</script>
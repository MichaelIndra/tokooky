<template>
    <div class='container py-4'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header'>Create Harga Barang</div>
                    <div class='card-body'>
                        <div class="alert alert-danger" v-if="errors.length">
                            <b>Terdapat kesalahan dalam input data:</b>
                            <ul>
                                <li v-for="error in errors" :key="error">{{ error }}</li>
                            </ul>
                        </div>
 
                        <form @submit.prevent="createHargaBarang">
                            <div class='form-group'>
                                <label htmlFor='title'>Nama Konsumen</label>
                                <input type="text" class="form-control" id="nama_konsumen" v-model="nama_konsumen" disabled>
                            </div>
                            <div class='form-group'>
                                <label htmlFor='title'>Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" v-model="nama_barang" disabled>
                            </div>
                            <div class='form-group'>
                                <label htmlFor='title'>Harga satuan</label>
                                <input type="text" class="form-control" @keypress="isNumber($event)" id="harga_satuan" v-model="harga_barang.harga_satuan" >
                            </div>
                            <div class='form-group'>
                                <label htmlFor='title'>Harga per lusin</label>
                                <input type="text" class="form-control" @keypress="isNumber($event)" id="harga_lusin" v-model="harga_barang.harga_lusin" >
                            </div>
                            <div class='form-group'>
                                <datepicker v-model="harga_barang.tgl_awal" :format="customFormatter" placeholder="Select Date" />
                                
                            </div>
                            <div class='form-group'>
                                <router-link :to="{ name: 'barangs' }" class="btn btn-secondary">Back</router-link>
                                &nbsp;
                                &nbsp;
                                <button class='btn btn-primary'>Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker'
    export default {
        created() {
            this.getHargaBarang();
            
        },
        components: {
            Datepicker
        },
        data(){
            return {
                harga_barang:{},
                errors: [],
                format: 'd MMMM yyyy',
                nama_barang : '',
                nama_konsumen : '',
            }
        },
        methods: {
            customFormatter(date) {
                return moment(date).format('DD/MM/YYYY');
            },
            createHargaBarang(e){
                console.log(this.harga_barang);
                 
                if (this.$data.harga_barang.harga_satuan != null &&
                    this.$data.harga_barang.harga_lusin != null &&
                    this.$data.harga_barang.tgl_awal != null) {
                        this.$swal.fire({
                                title: 'Success',
                                text: 'Success created harga barang',
                                icon: 'success',
                                timer: 1000
                            });
                    
                    let uri = '/api/hargabarangs/store';
                    this.axios.post(uri, this.harga_barang).then((response) => {
                        console.log(response.data);
                        this.$router.push({name: 'barangs'});
                    });
                    return true;
                }
 
                this.errors = [];
 
                if (!this.harga_barang.id_barang) {
                    this.errors.push('Nama barang wajib diisi !');
                }
                if (!this.harga_barang.harga_satuan) {
                    this.errors.push('Harga satuan wajib diisi !');
                }
                if (!this.harga_barang.harga_lusin) {
                    this.errors.push('Harga lusin wajib diisi !');
                }
                if (!this.harga_barang.tgl_awal) {
                    this.errors.push('Tanggal awal wajib diisi !');
                }
            
                e.preventDefault();
            },
            getHargaBarang(){
                let uri = `/api/hargabarangs/edit/${this.$route.params.id}`;
                this.nama_barang = this.$route.params.nama_barang;
                this.nama_konsumen = this.$route.params.nama_konsumen;
                this.axios.get(uri).then((response) => {
                    this.harga_barang = response.data;
                });
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            }

        },
        
    }
</script>
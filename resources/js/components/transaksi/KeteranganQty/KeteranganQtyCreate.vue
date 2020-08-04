<template>
    <div class='container py-4'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header'>Create Keterangan Qty</div>
                    <div class='card-body'>
                        <div class="alert alert-danger" v-if="errors.length">
                            <b>Terdapat kesalahan dalam input data:</b>
                            <ul>
                                <li v-for="error in errors" :key="error">{{ error }}</li>
                            </ul>
                        </div>
 
                        <form @submit.prevent="createKeterangan">
                            <div class='form-group'>
                                <label htmlFor='title'>Keterangan Qty</label>
                                <input type="text" class="form-control" id="keterangan_qty" maxlength="5" v-model="keterangan.keterangan_qty">
                            </div>
                            <div class='form-group'>
                                <router-link :to="{ name: 'transaksis' }" class="btn btn-secondary">Back</router-link>
                                &nbsp;
                                &nbsp;
                                <button class='btn btn-primary'>Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                keterangan:{},
                errors: [],
                keterangan_qty: null,
            }
        },
        methods: {
            createKeterangan(e){
                
                console.log(this.keterangan);
                if (this.$data.keterangan.keterangan_qty != null) {
                    this.$swal.fire({
                        title: 'Success',
                        text: "Master keterangan created successfully",
                        icon: 'success',
                        timer: 1000
                    })
                    let uri = '/api/keteranganqtys/store';
                    this.axios.post(uri, this.keterangan).then((response) => {
                        this.$router.push({name: 'transaksis'});
                    })
                    .catch(error => {
                        console.log(error)
                    });
                    return true;
                }
 
                this.errors = [];
 
                if (!this.keterangan_qty) {
                    this.errors.push('Keterangan Qty wajib diisi !');
                }
            
                e.preventDefault();
            }
        }
    }
</script>
<template>
    <div class='container py-4'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header'>Create Master Barang</div>
                    <div class='card-body'>
                        <div class="alert alert-danger" v-if="errors.length">
                            <b>Terdapat kesalahan dalam input data:</b>
                            <ul>
                                <li v-for="error in errors" :key="error">{{ error }}</li>
                            </ul>
                        </div>
 
                        <form @submit.prevent="createBarang">
                            <div class='form-group'>
                                <label htmlFor='title'>Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" v-model="barang.nama_barang">
                            </div>
                            <div class='form-group'>
                                <label htmlFor='content'>Deskripsi Barang</label>
                                <textarea type="text" class="form-control" id="deskripsi_barang" v-model="barang.deskripsi_barang" rows="3"></textarea>
                            </div>
                            <div class='form-group'>
                                <router-link :to="{ name: 'barangs' }" class="btn btn-secondary">Back</router-link>
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
                barang:{},
                errors: [],
                nama_barang: null,
                deskripsi_barang: null,
            }
        },
        methods: {
            createBarang(e){
                 
                if (this.$data.barang.nama_barang != null) {
                    this.$swal.fire({
                        title: 'Success',
                        text: "Master barang created successfully",
                        icon: 'success',
                        timer: 1000
                    })
                    let uri = '/api/barangs/store';
                    this.axios.post(uri, this.barang).then((response) => {
                        this.$router.push({name: 'barangs'});
                    });
                    return true;
                }
 
                this.errors = [];
 
                if (!this.nama_barang) {
                    this.errors.push('Nama barang wajib diisi !');
                }
            
                e.preventDefault();
            }
        }
    }
</script>
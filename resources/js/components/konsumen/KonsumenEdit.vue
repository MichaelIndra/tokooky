<template>
    <div class='container py-4'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header'>Edit Konsumen</div>
                    <div class='card-body'>
                        <div class="alert alert-danger" v-if="errors.length">
                            <b>Terdapat kesalahan dalam input data:</b>
                            <ul>
                                <li v-for="error in errors" :key="error">{{ error }}</li>
                            </ul>
                        </div>
 
                        <form @submit.prevent="updateKonsumen">
                            <div class='form-group'>
                                <label htmlFor='title'>Nama Konsumen</label>
                                <input type="text" class="form-control" id="nama_konsumen" v-model="konsumen.nama_konsumen">
                            </div>
                            <div class='form-group'>
                                <label htmlFor='content'>Alamat</label>
                                <textarea type="text" class="form-control" id="alamat_konsumen" v-model="konsumen.alamat_konsumen" rows="5"></textarea>
                            </div>
                            <div class='form-group'>
                                <label htmlFor='title'>No Telp</label>
                                <input type="text" class="form-control" id="no_telp" v-model="konsumen.no_telp">
                            </div>
                            <div class='form-group'>
                                <router-link :to="{ name: 'konsumens' }" class="btn btn-secondary">Back</router-link>
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
    export default {
        data(){
            return {
                konsumen:{},
                errors: [],
                nama_konsumen: null,
                alamat_konsumen: null,
                no_telp :null,
            }
        },
        created() {
            let uri = `/api/konsumens/edit/${this.$route.params.id}`;
            this.axios.get(uri).then((response) => {
                this.konsumen = response.data;
            });
         },
        methods: {
            updateKonsumen(e){
                 
                if (this.$data.konsumen.nama_konsumen != null) {
                    this.$swal.fire({
                        title: 'Success',
                        text: "Konsumen Update successfully",
                        icon: 'success',
                        timer: 1000
                    })
                    let uri = `/api/konsumens/update/${this.$route.params.id}`;
                    this.axios.put(uri, this.konsumen).then((response) => {
                        this.$router.push({name: 'konsumens'});
                    });
                    return true;
                }
 
                this.errors = [];
 
                if (!this.nama_konsumen) {
                    this.errors.push('Nama wajib diisi !');
                }
            
                e.preventDefault();
            }
        }
    }
</script>
<template>
    <div class='container py-4'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header'>Detail Konsumen</div>
                    <div class='card-body'>
                        <div class='form-group'>
                            <label htmlFor='title'>Nama Konsumen</label>
                            <input type="text" class="form-control" id="nama_konsumen" v-model="konsumen.nama_konsumen" disabled>
                        </div>
                        <div class='form-group'>
                            <label htmlFor='content'>Alamat</label>
                            <textarea type="text" class="form-control" id="alamat_konsumen" v-model="konsumen.alamat_konsumen" rows="5" disabled></textarea>
                        </div>
                        <div class='form-group'>
                            <label htmlFor='title'>No Telp</label>
                            <input type="text" class="form-control" id="no_telp" v-model="konsumen.no_telp" disabled>
                        </div>
                        <div class='form-group'>
                            <router-link :to="{ name: 'konsumens' }" class="btn btn-secondary">Back</router-link>
                        </div>
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
        }
    },
    created() {
        let uri = `/api/konsumens/show/${this.$route.params.id}`;
        this.axios.get(uri).then((response) => {
            this.konsumen = response.data;
        });
    },
    method: {
 
    }
  }
</script>
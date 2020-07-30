<template>
    <div class='container py-12'>
        <div class='row'>
            <div class='col-md-6'>
                <div class='card card-primary'>
                    <div class='card-header'>Last Transaction</div>
                    <div class='card-body p-0'>
                        <div class="table-responsive">
                          <table class="table table-sm">
                              <thead>
                                  <tr>
                                      <th v-for="column in columns" :key="column.name">
                                          {{column.label}}
                                      </th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr v-for="(transaksi) in transaksis" :key="transaksi.no_invoice">
                                      <td>{{ transaksi.no_invoice }}</td>
                                      <td>{{ transaksi.nama_konsumen }}</td>
                                      <td>{{ transaksi.total_belanja | currency }}</td>
                                      <td>{{ transaksi.created_at }}</td>
                                  </tr>
                              </tbody>
                          </table>
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
        let columns = [
            {label: 'No Invoice', name: 'no_invoice' },
            {label: 'Nama Konsumen', name: 'nama_konsumen' },
            {label: 'Total Bayar', name: 'total_belanja'},
            {label: 'Tanggal Transaksi', name: 'created_at'},
        ];
        
        return {
            transaksis: [],
            columns: columns,            
        }
      },
      
    methods: {
        

        getData(){
            let uri = 'api/transaksiglobals/get5transaksi';
            axios.get(uri).then(response => {
              console.log(response.data)
                this.transaksis = response.data;
            }).catch(errors => {
                    console.log(errors);
                });
        },
        
        
    },
    
  }
</script>

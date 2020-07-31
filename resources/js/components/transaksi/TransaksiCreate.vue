<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">Buat Transaksi</div>
          <div class="card-body">
            <div class="alert alert-danger" v-if="errors.length">
              <b>Terdapat kesalahan dalam input data:</b>
              <ul>
                <li v-for="error in errors" :key="error">{{ error }}</li>
              </ul>
            </div>
            <router-link :to="{ name: 'transaksis' }" class="btn btn-secondary">Back</router-link>
            <form @submit.prevent="addCart()">
              <div class="form-group">
                <label for="title">Konsumen</label>
                <multiselect
                  :options="konsumens"
                  v-model="kons"
                  label="nama_konsumen"
                  track-by="nama_konsumen"
                  placeholder="Ketik nama konsumen"
                  @input="fetchBarang"
                ></multiselect>
                <!-- <pre class="language-json"><code>{{ kons  }}</code></pre> -->
              </div>
              <div class="form-group" v-show="barangs.length > 0">
                <label for="content">Barang</label>

                <multiselect
                  :options="barangs"
                  v-model="transaksi"
                  label="nama_barang"
                  track-by="nama_barang"
                  placeholder="Ketik nama barang"
                  @input="clearQtyHarga"
                ></multiselect>
                <!-- <pre class="language-json"><code>{{ transaksi  }}</code></pre> -->
              </div>
              <div class="form-group">
                <label for="title">Harga</label>
                <input
                  type="number"
                  class="form-control"
                  v-model="transaksi.harga_satuan"
                  id="harga_satuan"
                />
              </div>
              <div class="form-group">
                <label for="title">qty</label>
                <input type="number" class="form-control" v-model="qty" id="qty" />
              </div>
              <div class="form-group">
                &nbsp;
                <button class="btn btn-primary">Create</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Daftar Transaksi</div>
          <div class="card-body">
            Total Belanja : {{totalbelanja | currency}}
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(datacart, index) in carts" :key="datacart.id">
                    <td width="50" class="text-center">{{ index + 1 }}</td>
                    <td>{{datacart.nama_barang}}</td>
                    <td>
                      <input
                        type="number"
                        min="0"
                        class="form-control"
                        v-model="datacart.qty"
                        style="width: 70px;"
                      />
                    </td>
                    <td>{{datacart.harga_satuan}}</td>
                    <td>{{datacart.harga_total}}</td>
                    <td width="200" class="text-center">
                      <div class="btn-group">
                        <button class="btn btn-success" @click="updateQty(datacart)">Update QTY</button>
                        <button class="btn btn-danger" @click="deleteCart(datacart.id)">Delete</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    </br>
    </br>
    <div class="row ">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">Buat Transaksi</div>
          <div class="card-body">
            <form @submit.prevent="makeTransaksi()">

              <div class="form-group">
                <label for="title">Bayar</label>
                <input
                    type="number"
                    min="0"
                    class="form-control"
                     v-model="make_transaksi.bayar"
                    id="bayar"
                />
              </div>
              <div class="form-group">
                &nbsp;
                <button v-show="carts.length > 0" class="btn btn-primary">Buat Transaksi</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";

export default {
  data() {
    return {
      barangs: [],
      transaksi: {},
      konsumens: [],
      errors: [],
      kons: [],
      qty: 0,
      data: {},
      carts: {},
      dataqty: 0,
      totalbelanja: 0,
      grandtotal : 0,
      make_transaksi : {},
      
    };
  },
  created() {
    this.fetchKonsumen();
    this.fetchCart();
  },
  methods: {
    fetchKonsumen() {
      let uri = "/api/konsumens/";
      axios
        .get(uri)
        .then((response) => {
          this.konsumens = response.data;
          this.konsumens["onchange"];
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    fetchBarang() {
      let uri = `/api/barangs/getbarangandharga/`;
      console.log("url : " + uri);
      this.barangs = [];
      axios
        .get(uri)
        .then((response) => {
          this.barangs = response.data;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    fetchCart() {
      let uri = `/api/carts/deleteall/`;
      axios
        .get(uri)
        .then((response) => {
          // this.carts = response.data.data;
          // this.totalbelanja = response.data.total;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    clearQtyHarga(){
        this.transaksi.harga_satuan = 0;
        this.qty = 0;
    },
    addCart() {
      this.data.id_konsumen = this.kons.id_konsumen;
      this.data.id_barang = this.transaksi.id_barang;
      this.data.harga_satuan = this.transaksi.harga_satuan;
      this.data.qty = this.qty;
      if (
        this.data.id_konsumen != null &&
        this.data.id_barang != null &&
        this.data.qty != null
      ) {
        let uri = `/api/carts/store/`;
        axios
          .post(uri, this.data)
          .then((response) => {
            console.log("The data: ", response.data);
            if (response.data.success == true) {
              this.carts = response.data.data;
              this.totalbelanja = response.data.total;
            }else{
                this.$swal
                .fire({
                  title: "Error",
                  text: response.data.message,
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Update",
                  cancelButtonText: "Batal",
                })
                .then((result) => {
                  if (result.value) {
                    this.fetchCart
                    
                  }
                });
            }
          })
          .catch((errors) => {
            console.log(errors);
          });
        return true;
      }
         this.errors = [];
        if (!this.data.id_konsumen) {
            this.errors.push('Nama konsumen wajib diisi !');
        }
        if (!this.data.id_barang) {
            this.errors.push('Nama barang wajib diisi !');
        }
        if (!this.data.harga_satuan) {
            this.errors.push('Harga barang wajib diisi !');
        }
        if (!this.data.qty) {
            this.errors.push('QTY wajib diisi !');
        }
        

        e.preventDefault();
    },makeTransaksi(){
        this.make_transaksi.diskon = 0;
        if(this.make_transaksi.bayar == null){
            this.make_transaksi.bayar =0;
        }
        let uri = `/api/transaksiglobals/store/`;
        axios
          .post(uri, this.make_transaksi)
          .then((response) => {
            if (response.data.status == true) {
                this.$router.push({name: 'transaksis'});
            }
          })
          .catch((errors) => {
            console.log(errors);
          });
        return true;
    },
    createGrandTotal(){
      this.grandtotal = this.totalbelanja - this.make_transaksi.diskon
    },
    isNumber: function (evt) {
      evt = evt ? evt : window.event;
      var charCode = evt.which ? evt.which : evt.keyCode;
      if (
        charCode > 31 &&
        (charCode < 48 || charCode > 57) &&
        charCode !== 46
      ) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
    updateQty(data) {
      console.log(data);
      this.$swal
        .fire({
          title: "Apakah kamu yakin?",
          text: "Update qty",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Update",
          cancelButtonText: "Batal",
        })
        .then((result) => {
          if (result.value) {
            this.$swal.fire({
              title: "Success!",
              text: "Cart Update successfully",
              icon: "success",
              timer: 1000,
            });
            let uri = `api/carts/update/${data.id}/${data.qty}`;
            this.axios.put(uri).then((response) => {
              if (response.data.success == true) {
                this.carts = response.data.data;
                this.totalbelanja = response.data.total;
              }
            });
          }
        });
    },
    deleteCart(id) {
      this.$swal
        .fire({
          title: "Apakah kamu yakin?",
          text: "Jika kamu hapus, maka data tidak akan kembali lagi.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Hapus Deh",
          cancelButtonText: "Nggak Jadi",
        })
        .then((result) => {
          if (result.value) {
            this.$swal.fire({
              title: "Success!",
              text: "Cart deleted successfully",
              icon: "success",
              timer: 1000,
            });
            let uri = `api/carts/delete/${id}`;
            this.axios.delete(uri).then((response) => {
              if (response.data.success == true) {
                this.carts = response.data.data;
                this.totalbelanja = response.data.total;
              }
            });
          }
        });
    },
  },
  components: {
    Multiselect,
  },
  computed: {},
};
</script>
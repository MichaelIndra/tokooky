<template>
    <div class='container py-4'>
        <div class='row justify-content-center'>
            <div class='col-md-8'>
            <div class='card'>
                <div class='card-header'>Data Konsumen</div>
                <div class='card-body'>
                    <router-link :to="{ name: 'createkonsumen' }" class="btn btn-primary">Create new konsumen</router-link>
                    <br/>
                    <br/>
                    <div class="table-style">
                        <input class="input" type="text" v-model="search" placeholder="Search..."
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
                                        style="width: 40%; cursor:pointer;">
                                        {{column.label}}
                                    </th>
                                    <th width="200" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(konsumen, index) in paginatedKonsumen" :key="konsumen.id_konsumen">
                                    <td width="50" class="text-center">{{ index + 1 }}</td>
                                    <td>{{ konsumen.nama_konsumen }}</td>
                                    <td>{{ konsumen.alamat_konsumen }}</td>
                                    <td>{{ konsumen.created_at }}</td>
                                    <td width="200" class="text-center">
                                        <div class="btn-group">
                                            <router-link :to="{name: 'editkonsumen', params: { id: konsumen.id_konsumen }}" class="btn btn-success">Edit</router-link>
                                            <button class="btn btn-danger" @click = "deletePost(konsumen.id_konsumen)">Delete</button>
                                        </div>
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
                                    {{pagination.from}} - {{pagination.to}} of {{filteredUsers.length}}
                                    <span v-if="`filteredUsers.length < pagination.total`"></span>
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
        this.getKonsumen();
        },
      data() {
        let sortOrders = {};
        let columns = [
            {label: 'Nama Konsumen', name: 'nama_konsumen' },
            {label: 'Alamat Konsumen', name: 'alamat_konsumen'},
            {label: 'Date Added', name: 'created_at'},
        ];
        columns.forEach((column) => {
           sortOrders[column.name] = -1;
        });

        return {
            konsumens: [],
            columns: columns,
            sortKey: 'created_at',
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
        deletePost(id)
        {
            this.$swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Jika kamu hapus, maka data tidak akan kembali lagi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus Deh',
                cancelButtonText: 'Nggak Jadi'
                }).then((result) => {
                if (result.value) {
                    this.$swal.fire({
                        title: 'Success!',
                        text: 'Konsumen deleted successfully',
                        icon: 'success',
                        timer: 1000
                    });
                    let uri = `api/konsumens/delete/${id}`;
                    this.axios.delete(uri).then(response => {
                        this.konsumens.splice(this.konsumens.indexOf(id), 1);
                    });
                    console.log("Deleted konsumen with id ..." +id);
                }
            })
        },

        getKonsumen(){
            let uri = 'api/konsumens/search';
            axios.get(uri, {params: this.tableShow}).then(response => {
                console.log('The data: ', response.data)
                this.konsumens = response.data;
                this.pagination.total = this.konsumens.length;
            }).catch(errors => {
                    console.log(errors);
                });;
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
    },
    computed: {
        filteredUsers() {
            let konsumens = this.konsumens;
            if (this.search) {
                konsumens = konsumens.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                konsumens = konsumens.slice().sort((a, b) => {
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
            return konsumens;
        },
        paginatedKonsumen() {
            return this.paginate(this.filteredUsers, this.length, this.pagination.currentPage);
        }
    }
  }
</script>
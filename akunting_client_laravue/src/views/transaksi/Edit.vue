<template>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-8">
                <router-link :to="{ name: 'transaksi.index'}" class="btn btn-primary btn-sm rounded shadow mb-3">
                    Back
                </router-link>
                <div class="card rounded shadow">
                    <div class="card-header">
                        Edit Transaksi
                    </div>
                    <div class="card-body">
                        <form @submit.prevent = "update()">
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Transaksi</label>
                                <input type="text" class="form-control" v-model="transaksi.nama_transaksi">
                                <div v-if="validation.nama_transaksi" class="text-danger">
                                    {{ validation.nama_transaksi[0] }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nominal</label>
                                <input type="text" class="form-control" v-model="transaksi.nominal">
                                <div v-if="validation.nominal" class="text-danger">
                                    {{ validation.nominal[0] }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Waktu</label>
                                <input type="text" class="form-control" placeholder="yyyy-mm-dd hh:mm:ss" v-model="transaksi.waktu_transaksi">
                                <div v-if="validation.waktu_transaksi" class="text-danger">
                                    {{ validation.waktu_transaksi[0] }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Type</label>
                                <select id="" class="form-select" v-model="transaksi.type">
                                    <option value="pengeluaran">Pengeluaran</option>
                                    <option value="pemasukan">Pemasukan</option>
                                </select>
                                <div v-if="validation.type" class="text-danger">
                                    {{ validation.type[0] }}
                                </div>
                            </div>
                            <button class="btn btn-outline-primary">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

export default {
    setup(){
        //data binding
        let transaksi = reactive({
            nama_transaksi: '',
            nominal: '',
            waktu_transaksi: '',
            type: '',
        });
        
        const validation = ref([]);

        const router = useRouter();
        const route = useRoute();

        onMounted(() => {
            axios.get(`http://127.0.0.1:8000/api/transaksi/${route.params.id}`)
            .then((result) => {
                transaksi.nama_transaksi = result.data.data.nama_transaksi
                transaksi.nominal = result.data.data.nominal
                transaksi.waktu = result.data.data.waktu_transaksi
                transaksi.type = result.data.data.type
            }).catch((err) => {
                console.log(err.response.data)
            }); 
        });

        function update(){
            axios.put(
                `http://127.0.0.1:8000/api/transaksi/${route.params.id}`,
                transaksi
            )
            .then(() => {
                router.push({
                    name: 'transaksi.index'
                });
            }).catch((err) => {
                validation.value = err.response.data
            });
        }

        return {
            transaksi,
            validation,
            router,
            route,
            update
        }
    }
}
</script>
<template>
    <div>
        <div class="container">
            <div class="card card-default mt-10">
                <div class="card-header">
                    <h3>Users
                        <router-link class="btn btn-primary" :to="{ name: 'create-user'}" style="float: right">
                        NEW USER
                         </router-link>
                    </h3>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr class="text-center">
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody v-if="users.length">
                            <tr v-for="(user) in users">
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td class="text-center">
                                    <router-link :to="{ name: 'update-user', params: {id: user.id}}">Edit</router-link>
                                    <a><a href="javascript:void(0)" @click="deleteUser(user)">Delete</a></a>
                                </td>
                            </tr>
                            </tbody>
                            <tbody v-else>
                            <tr>
                                <td colspan="4" class="text-center"><h4>No user available</h4></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="pagination-sec">
                            <div class="text-center" v-show="total > query.limit">
                                <paginate
                                    :click-handler="setPage"
                                    :container-class="'pagination'"
                                    :next-text="'>>'"
                                    :page-count="countPages"
                                    :prev-text="'<<'"
                                    ref="paginateBottom">
                                </paginate>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import Paginate from 'vuejs-paginate'
    import userAPI from "../../api/user";
    import Notify from 'vue2-notify';
    import Vue from "vue";
    Vue.use(Notify, {position: 'bottom-right'})
    export default {
        name: "list",
        components: {
            Paginate
        },
        computed: {
            countPages () {
                return Math.ceil(this.total / this.query.limit)
            }
        },
        data () {
            return {
                users: [],
                query: {
                    limit: 5,
                    page: 1
                },
                total: 0
            }
        },
        created() {
            this.loadUsers()
        },
        methods: {
            loadUsers () {
                return userAPI.getUsers(this.query).then((response) => {
                    this.users = response.data
                    this.total = response.total
                })
            },
            setPage (page) {
                this.query.page = page
                if (this.$refs.paginateBottom) {
                    this.$refs.paginateBottom.selected = page
                }
                this.loadUsers()
            },
            deleteUser(user) {
                userAPI.deleteUser(user.id).then((response) => {
                    if (response.status) {
                        this.loadUsers()
                        Vue.$notify(response.message, 'success')
                    } else {
                        this.display.processing = false
                        Vue.$notify(response.message, 'error')
                    }
                })
            }
        }
    }
</script>

<style>
    .mt-10 {
        margin-top: 10px;
    }
    .pagination-sec {
        float: left;
        width: 100% !important;
        text-align: center;
        margin-bottom:50px;
    }
    .pagination-sec li {
        display: inline-block;
        margin: 0px 3px;
    }
    .pagination-sec .prv a, .pagination-sec .next a {
        width: 40px;
        height: 35px;
        display: inline-block;
        border: 1px solid #ddd;
        text-align: center;
        line-height: 35px;
    }
    .pagination-sec li a {
        width: 40px;
        height: 35px;
        display: inline-block;
        text-align: center;
        line-height: 35px;
        font-weight:600;
        font-size:16px;
        color:#363537;
    }
    .pagination-sec li a:hover{
        background:#0096BE;
        color:#fff;
    }

    .pagination .active a{
        background: #00b4ef;
        color: #FFF;
    }

    .pagination-sec {
        margin: 0 auto;
        margin-bottom: 10px;
    }
</style>

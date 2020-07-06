<template>
    <div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Create New User</div>

                        <div class="card-body">
                            <form>
                                <div class="alert alert-danger" v-if="errors">
                                    <ul>
                                        <li v-for="(error, index) in errors">{{ error[0] }}</li>
                                    </ul>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                    <div class="col-md-6">
                                        <input v-model="form.name" id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                    <div class="col-md-6">
                                        <input v-model="form.email" id="email" type="email" class="form-control" name="email" autocomplete="email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                    <div class="col-md-6">
                                        <input v-model="form.password" id="password" type="password" class="form-control" name="password" autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                    <div class="col-md-6">
                                        <input v-model="form.password_confirmation" id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="button" class="btn btn-primary" @click="update">
                                            Update
                                        </button>
                                        <router-link class="btn btn-primary" :to="{ name: 'users'}">
                                            Cancel
                                        </router-link>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import userAPI from "../../api/user";
    import config from "../../config";
    export default {
        name: "update",
        data() {
            return {
                errors: null,
                userId: '',
                form: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                }
            }
        },
        created() {
            return userAPI.getUsers({id: this.$route.params.id}).then((response) => {
                if (response.status) {
                    if (response[0])  {
                        let user = response[0]
                        this.userId = user.id
                        this.form.name = user.name
                        this.form.email = user.email
                    } else {
                        this.$router.push({path: config.host + '/users'});
                    }
                }
            })
        },
        methods: {
            update() {
                this.errors = null
                userAPI.updateUser(this.userId, this.form).then((response) => {
                    if (response.status) {
                        this.$router.push({path: config.host + '/users'});
                    } else {
                        if (response.errors) {
                            this.errors = response.errors
                        } else {
                            this.errors = {'error': [response.message]}
                        }
                    }
                }).catch((error) => {
                    this.errors = {'error': ['Something went wrong. Please try again']}
                })
            }
        }
    }
</script>

<style scoped>

</style>

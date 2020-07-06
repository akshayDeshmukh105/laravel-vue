<template>
    <div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Login</div>

                        <div class="card-body">
                            <form method="POST">
                                <div class="alert alert-danger" v-if="errors">
                                    <ul>
                                        <li v-for="(error, index) in errors">{{ error[0] }}</li>
                                    </ul>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" v-model="form.email" autocomplete="email" autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" v-model="form.password" autocomplete="current-password">
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="button" class="btn btn-primary" @click="authenticate">
                                            Login
                                        </button>
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
import Vue from 'vue'
import authAPI from "../../api/auth";
import Notify from 'vue2-notify';
import config from "../../config";
Vue.use(Notify, {position: 'bottom-right'})
export default {
    name: "login",
    data() {
        return {
            form: {
                email: '',
                password: ''
            }
        }
    },
    computed: {
      errors () {
          return this.$store.getters.authError
        }
    },
    methods: {
        authenticate() {
            this.$store.dispatch('login');
            authAPI.login(this.form).then((response) => {
                if (response.status) {
                    this.$store.commit('loginSuccess', response)
                    // this.$router.push({path: config.host})
                    location.href = config.host + '/'
                } else {
                    if (response.errors) {
                        this.$store.commit('loginFailed', response)
                    } else {
                        this.$store.commit('loginFailed', {errors: {'error': [response.message]}})
                    }
                }
            }).catch((error) => {
                console.log(error)
                this.$store.commit('loginFailed', {errors: {'error': ['Something went wrong. Please try again']}})
            })
        }
    },
    created() {
    }
}
</script>

<style scoped>

</style>

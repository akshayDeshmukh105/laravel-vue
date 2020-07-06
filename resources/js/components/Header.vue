<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <router-link class="navbar-brand" :to="host">Laravel-Vue</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <template v-if="!user">
                            <li>
                                <router-link :to="host + '/login'" class="nav-link">Login</router-link>
                            </li>
<!--                            <li>-->
<!--                                <router-link to="/register" class="nav-link">Register</router-link>-->
<!--                            </li>-->
                        </template>
                        <template v-else>
                            <li>
                                <router-link  :to="host + '/users'" class="nav-link">Users</router-link>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                    {{user.name}}<span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a @click="logout" class="dropdown-item">Logout</a>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
import config from "../config";
export default {
    name: "Header",
    data: () => {
        return {
            host: config.host
        }
    },
    computed: {
        user () {
            return this.$store.getters.currentUser
        }
    },
    created() {
    },
    methods: {
        logout () {
            this.$store.commit('logout');
            this.$router.push({path: config.host+'/login'})
        }
    }
}
</script>

<style scoped>

</style>

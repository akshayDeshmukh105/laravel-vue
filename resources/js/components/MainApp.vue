<template>
    <div>
        <div id="main">
            <Header/>
            <div class="content">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import Header from "./Header";
    import axios from 'axios'
    export default {
        name: "MainApp",
        components: {Header},
        created() {
            axios.interceptors.request.use(function (config) {
                let token = localStorage.getItem('authToken')
                if (token) {
                    config.headers.authorization = token
                    config.headers.accept = 'application/json'
                }
                return config
            })
        }
    }
</script>

<style scoped>

</style>

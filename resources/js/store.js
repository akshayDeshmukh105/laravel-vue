import authApi from './api/auth'

const user = authApi.getAuthUser()
const authToken = authApi.getAuthUserToken()

export default {
    state: {
        isLoggedIn: !!user,
        currentUser: user,
        authToken: authToken,
        loading: false,
        authError: null,
        users: []
    },
    getters: {
        isLoading (state) {
            return state.loading
        },
        isLoggedIn (state) {
            return state.isLoggedIn
        },
        currentUser (state) {
            return state.currentUser
        },
        authError (state) {
            return state.authError
        },
        authToken (state) {
            return state.authToken
        },
        users (state) {
            return state.users
        }
    },
    mutations: {
        login(state) {
            state.loadiing = true
            state.authError = null
        },
        loginSuccess(state, data) {
            state.authError = null
            state.isLoggedIn = true
            state.loadiing = false
            state.currentUser = data.user
            state.authToken = data.token
            localStorage.setItem('user', JSON.stringify(state.currentUser))
            localStorage.setItem('authToken', ('Bearer ' + state.authToken))
        },
        loginFailed (state, data) {
            state.authError = data.errors
            state.isLoggedIn = false
            state.loadiing = false
        },
        logout(state) {
            localStorage.removeItem('user')
            localStorage.removeItem('authToken')
            axios.interceptors.request.use(function (config) {
                config.headers.authorization = ''
                return config
            })
            state.isLoggedIn = false
            state.loadiing = false
            state.currentUser = null
        }
    },
    actions: {
        login (state) {
            state.commit('login')
        }
    }
}

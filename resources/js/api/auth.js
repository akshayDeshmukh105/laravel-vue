import config from '../config'

const authAPI = {
    login(credentials) {
        return new Promise((resolve, reject) => {
            axios.post(config.host + '/api/login', credentials).then((response) => {
                resolve(response.data)
            }, (error) => {
                reject('Something went wrong. Please try again')
            })
        })
    },
    getAuthUser () {
        let user = localStorage.getItem('user');
        if (user && user !== 'undefined') {
            return JSON.parse(user);
        } else {
            return null;
        }
    },
    getAuthUserToken () {
        let authToken = localStorage.getItem('authToken');
        if (authToken && authToken !== 'undefined') {
            return authToken;
        } else {
            return null;
        }
    }
}
export default authAPI

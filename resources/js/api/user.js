import config from "../config";

const userAPI = {
    getUsers(params) {
        let stringParams = Object.keys(params).filter((i) => {
            return params[i] !== '' && params[i] !== null
        }).map((k) => {
            return params[k] !== null && params[k] !== undefined && typeof params[k] === 'object' ? Object.keys(params[k]).map((m) => {
                return encodeURIComponent(k) + '[' + encodeURIComponent(m) + ']' + '=' + encodeURIComponent(params[k][m])
            }).join('&') : encodeURIComponent(k) + '=' + encodeURIComponent(params[k])
        }).join('&')
        let url = config.host + '/api/users?' + stringParams

        return new Promise((resolve, reject) => {
            axios.get(url).then((response) => {
                console.log(response.data)
                resolve(response.data)
            }, (error) => {
                reject(error)
            })
        })
    },
    createUser(data) {
        return new Promise((resolve, reject) => {
            let url = config.host + '/api/users/create'
            axios.post(url, data).then((response) => {
                resolve(response.data)
            }, (error) => {
                reject(error)
            })
        })
    },
    updateUser(userId, data) {
        return new Promise((resolve, reject) => {
            let url = config.host + '/api/users/update/' + userId
            axios.post(url, data).then((response) => {
                resolve(response.data)
            }, (error) => {
                reject(error)
            })
        })
    },
    deleteUser(userId) {
        return new Promise((resolve, reject) => {
            axios.delete(config.host + '/api/users/' + userId).then((response) => {
                resolve(response.data)
            }, (error) => {
                reject(error)
            })
        })
    }
}

export default userAPI

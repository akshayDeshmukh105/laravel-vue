import config from "./config";
import Home from "./components/Home";
import Login from "./components/authentication/login";
import Users from "./components/users/list";
import UserCreate from "./components/users/create";
import UserUpdate from "./components/users/update";

export const routes = [
    {
        path: config.host,
        name: 'home',
        component: Home,
        meta : {
            auth: true
        }
    },
    {
        path: config.host + '/login',
        name: 'login',
        component: Login
    },
    {
        path: config.host + '/users',
        name: 'users',
        component: Users,
        meta : {
            auth: true
        }
    },
    {
        path: config.host + '/create-user',
        name: 'create-user',
        component: UserCreate,
        meta : {
            auth: true
        }
    },
    {
        path: config.host + '/update-user/:id',
        name: 'update-user',
        component: UserUpdate,
        meta : {
            auth: true
        }
    }
]

import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../views/auth/Login.vue'
import Main from '../components/layouts/Main'
import Dashboard from '../views/dashboard/Index.vue'
import StudentList from "../views/students/Index"
import UserList from '../views/users/Index'
import MenuList from '../views/menu/List'
import MenuPermission from '../views/users/MenuPermission'
import SliderList from '../views/slider/Index'
import EventList from '../views/event/Index'
import ProgramList from '../views/program/Index'
import CustomerList from '../views/customer/Index'
import WebMenu from '../views/web_menu/Index'
import SubMenu from '../views/sub_menu/Index'
import PageList from '../views/pages/Index'
import PageDetails from '../views/pages/Details.vue'


import NotFound from '../views/404/Index';
import {baseurl} from '../base_url'

Vue.use(VueRouter);

const config = () => {
    let token = localStorage.getItem('token');
    return {
        headers: {Authorization: `Bearer ${token}`}
    };
}
const checkToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === 'undefined' || token === null || token === '') {
        next(baseurl + 'login');
    } else {
        next();
    }
};

const activeToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === 'undefined' || token === null || token === '') {
        next();
    } else {
        next(baseurl);
    }
};

const routes = [
    {
        path: baseurl,
        component: Main,
        redirect: {name: 'Dashboard'},
        children: [
            //DASHBAORD
            {
                path: baseurl + 'dashboard',
                name: 'Dashboard',
                component: Dashboard
            },
            //SESSION SETTINGS

            {path: baseurl + 'student-list', name: 'StudentList', component: StudentList},
            {path: baseurl + 'user-list', name: 'UserList', component: UserList},

            //menu vue route
            {path: baseurl + 'menu-list', name: 'MenuList', component: MenuList},
            {path: baseurl + 'user-menu-permission', name: 'UserMenuPermission', component: MenuPermission},
            //slider
            {path: baseurl + 'slider-list', name: 'SliderList', component: SliderList},
            //event
            {path: baseurl + 'event-list', name: 'EventList', component: EventList},
            //program
            {path: baseurl + 'program-list', name: 'ProgramList', component: ProgramList},
            //customer
            {path: baseurl + 'customer-list', name: 'CustomerList', component: CustomerList},
            //Submenu
            {path: baseurl + 'web-sub-menu', name: 'SubMenu', component: SubMenu},
            //Webmenu
            {path: baseurl + 'web-menu', name: 'WebMenu', component: WebMenu},




            //pages
            {path: baseurl + 'page-list', name: 'PageList', component: PageList},
            //page details
            {path: baseurl + 'page-details/:id', name: 'PageDetails', component: PageDetails},

],
        beforeEnter(to, from, next) {
            checkToken(to, from, next);
        }
    },
    {
        path: baseurl + 'login',
        name: 'Login',
        component: Login,
        beforeEnter(to, from, next) {
            activeToken(to, from, next);
        }
    },
    {
        path: baseurl + '*',
        name: 'NotFound',
        component: NotFound,
    },
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.baseurl,
    routes
});

router.afterEach(() => {
    $('#preloader').hide();
});

export default router

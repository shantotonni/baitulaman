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
import SliderDetails from '../views/slider/Details.vue'
import EventList from '../views/event/Index'
import EventDetails from '../views/event/Details.vue'
import ProgramList from '../views/program/Index'
import ProgramDetails from '../views/program/Details.vue'
import CustomerList from '../views/customer/Index'
import WebMenu from '../views/web_menu/Index'
import SubMenu from '../views/sub_menu/Index'
import PageList from '../views/pages/Index'
import PageDetails from '../views/pages/Details.vue'
import OurImam from '../views/our_imam/Index.vue'
import OurImamDetails from '../views/our_imam/Details.vue'
import Blog from '../views/blog/Index.vue'
import BlogDetails from '../views/blog/Details.vue'
import VolunteerList from '../views/volunteer/Index.vue'

import AdvisorsBoard from '../views/advisors_board/Index.vue'
import ShuraCommitee from '../views/shura_committee/Index.vue'
import ProgramSchedule from '../views/program_schedule/Index.vue'
import RamadanCalendar from '../views/ramadan/Index.vue'
import Testimonial from '../views/testimonial/Index.vue'
import TestimonialDetails from '../views/testimonial/Details.vue'

import Contact from '../views/contact/Index.vue'
import Mailing from '../views/mailing/Index.vue'
import Question from '../views/question/Index.vue'

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
            {path: baseurl + 'dashboard', name: 'Dashboard', component: Dashboard},

            {path: baseurl + 'student-list', name: 'StudentList', component: StudentList},
            {path: baseurl + 'user-list', name: 'UserList', component: UserList},
            {path: baseurl + 'menu-list', name: 'MenuList', component: MenuList},
            {path: baseurl + 'user-menu-permission', name: 'UserMenuPermission', component: MenuPermission},
            {path: baseurl + 'slider-list', name: 'SliderList', component: SliderList},
            {path: baseurl + 'slider-details/:id', name: 'SliderDetails', component: SliderDetails},
            {path: baseurl + 'event-list', name: 'EventList', component: EventList},
            {path: baseurl + 'event-details/:id', name: 'EventDetails', component: EventDetails},
            {path: baseurl + 'program-list', name: 'ProgramList', component: ProgramList},
            {path: baseurl + 'program-details/:id', name: 'ProgramDetails', component: ProgramDetails},
            {path: baseurl + 'customer-list', name: 'CustomerList', component: CustomerList},
            {path: baseurl + 'web-sub-menu', name: 'SubMenu', component: SubMenu},
            {path: baseurl + 'web-menu', name: 'WebMenu', component: WebMenu},
            {path: baseurl + 'our-imam', name: 'OurImam', component: OurImam},
            {path: baseurl + 'imam-details/:id', name: 'OurImamDetails', component: OurImamDetails},
            {path: baseurl + 'volunteer-list', name: 'VolunteerList', component: VolunteerList},
            {path: baseurl + 'blog', name: 'Blog', component: Blog},
            {path: baseurl + 'blog-details/:id', name: 'BlogDetails', component: BlogDetails},
            {path: baseurl + 'page-list', name: 'PageList', component: PageList},
            {path: baseurl + 'page-details/:id', name: 'PageDetails', component: PageDetails},
            {path: baseurl + 'advisors-board', name: 'AdvisorsBoard', component: AdvisorsBoard},
            {path: baseurl + 'shura-commitee', name: 'ShuraCommitee', component: ShuraCommitee},
            {path: baseurl + 'program-schedule', name: 'ProgramSchedule', component: ProgramSchedule},
            {path: baseurl + 'ramadan-calendar', name: 'RamadanCalendar', component: RamadanCalendar},
            {path: baseurl + 'testimonial', name: 'Testimonial', component: Testimonial},
            {path: baseurl + 'testimonial-details/:id', name: 'TestimonialDetails', component: TestimonialDetails},

            //nee route
            {path: baseurl + 'contact-list', name: 'Contact', component: Contact},
            {path: baseurl + 'mailing-list', name: 'Mailing', component: Mailing},
            {path: baseurl + 'question-list', name: 'Question', component: Question},
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

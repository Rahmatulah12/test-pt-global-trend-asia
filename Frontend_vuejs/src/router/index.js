import Vue from 'vue';
import VueRouter from 'vue-router';
import Nprogress from 'nprogress';
import 'nprogress/nprogress.css';

Vue.use(VueRouter);
Nprogress.configure({ easing: 'ease', speed: 800 });

const routes = [

  { // Auth Login & Registration
    path: '/',
    redirect: '/login',
    name: 'Authentication',
    component: () => import('../components/Auth.vue'),
    children: [
      {
        path: 'login',
        name: 'Login',
        component: () => import('../views/auths/Login.vue'),
        beforeEnter: (to, from, next) => {
          if(localStorage.getItem('token') != null)
          {
            next({name: "Student"});
          }
          next();
        }
      },
      {
        path: 'registration',
        name: 'Registration',
        component: () => import('../views/auths/Registration.vue'),
        beforeEnter: (to, from, next) => {
          if(localStorage.getItem('token') != null)
          {
            next({name: "Student"});
          }
          next();
        }
      },
    ],
  },

  { // Student
    path: '/',
    redirect: '/student',
    name: 'Main Student',
    component: () => import('../components/Main.vue'),
    children: [

      {
        path: 'student',
        name: 'Student',
        component: () => import('../views/students/Index.vue'),
        beforeEnter: (to, from, next) => {
          if(localStorage.getItem('token') == null)
          {
            next({name: "Login"});
          }
          next();
        }
      },

      {
        path: 'add-student',
        name: 'Add Student',
        component: () => import('../views/students/Form.vue'),
        beforeEnter: (to, from, next) => {
          if(localStorage.getItem('token') == null)
          {
            next({name: "Login"});
          }
          next();
        }
      },

      {
        path: 'edit-student/:id',
        name: 'Edit Student',
        component: () => import('../views/students/Form.vue'),
        beforeEnter: (to, from, next) => {
          if(localStorage.getItem('token') == null)
          {
            next({name: "Login"});
          }
          next();
        }
      },

    ],
  },

  { // Hobby
    path: '/',
    redirect: '/hobby',
    name: 'Main Hobby',
    component: () => import('../components/Main.vue'),
    children: [

      {
        path: 'hobby',
        name: 'Hobby',
        component: () => import('../views/hobbies/Index.vue'),
        beforeEnter: (to, from, next) => {
          if(localStorage.getItem('token') == null)
          {
            next({name: "Login"});
          }
          next();
        }
      },

      {
        path: 'add-student-hobby',
        name: 'Add Student Hobby',
        component: () => import('../views/studentHobby/Form.vue'),
        beforeEnter: (to, from, next) => {
          if(localStorage.getItem('token') == null)
          {
            next({name: "Login"});
          }
          next();
        }
      },

      {
        path: 'edit-student-hobby/:id',
        name: 'Edit Student Hobby',
        component: () => import('../views/studentHobby/Form.vue'),
        beforeEnter: (to, from, next) => {
          if(localStorage.getItem('token') == null)
          {
            next({name: "Login"});
          }
          next();
        }
      },

    ],
  },

  { // Student Hobby
    path: '/',
    redirect: '/student-hobby',
    name: 'Main Student Hobby',
    component: () => import('../components/Main.vue'),
    children: [

      {
        path: 'student-hobby',
        name: 'Student Hobby',
        component: () => import('../views/studentHobby/Index.vue'),
        beforeEnter: (to, from, next) => {
          if(localStorage.getItem('token') == null)
          {
            next({name: "Login"});
          }
          next();
        }
      },

    ],
  },

  {
    path: '*',
    name: '404 Error',
    component: () => import('../views/404.vue')
  },

]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

router.beforeEach((to, from, next) => {
  Nprogress.start();
  next();
});

router.afterEach((to, from, next) => {
  Nprogress.done();
});

export default router

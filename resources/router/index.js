import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
        path: '/posts',
        name: 'PostIndex',
        component: () => import("../views/post/PostIndex.vue")
    },
    {
        path: '/posts/create',
        name: 'PostCreate',
        component: () => import("../views/post/PostCreate.vue")
    },
    {
        path: '/posts/:id/edit',
        name: 'PostEdit',
        component: () => import("../views/post/PostUpdate.vue")
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import("../views/authorization/Login.vue")
    },
    {
        path: '/',
        name: 'Home',
        component: () => import("../views/home/Home.vue"),
    },

  ]
})

router.beforeEach((to, from, next)=>{

    const token = localStorage.getItem('JWT');

    axios.defaults.headers['Authorization'] = 'Bearer ' + token;

    if (to.name !== 'Login' &&  !token ){
        next({
            path: '/login',
            replace: true
        })
    } else {
        next();
    }
})

export default router

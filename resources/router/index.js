import { createRouter, createWebHistory } from 'vue-router'
import axios from "axios";
import roles from "../helper/roles";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
        path: '/posts',
        name: 'PostIndex',
        component: () => import("../views/post/PostIndex.vue"),
        meta:{
            roles:[
                roles.ADMIN,
                roles.HR
            ]
        }
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
        component: () => import("../views/authorization/Login.vue"),
        meta:{
            roles: [

            ]
        }
    },
    {
        path: '/',
        name: 'Home',
        component: () => import("../views/home/Home.vue"),
        meta:{
            roles: [

            ]
        }
    },
      {
          path: '/forbidden',
          name: 'Forbidden',
          component: () => import("../views/error/Forbidden.vue"),
          meta:{
              roles: [

              ]
          }
      },

  ]
})

function canAccessPage(role, to)
{
    return to.meta.roles.includes(role) || to.meta.roles.length === 0;
}

router.beforeEach((to, from, next) => {

    const token = localStorage.getItem('JWT');

    const userRole = parseInt(localStorage.getItem('USER_ROLE_ID'))

    if(!canAccessPage(userRole, to))
    {
        next('/forbidden');
    }

    if(to.name !== 'Login' && !token)
    {
        next('/login')
    }
    else
    {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        next()
    }
})

export default router

import { createRouter, createWebHistory } from 'vue-router'
import axios from "axios";
import roles from "../helper/roles";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
      {
          path: '/users/:id/edit',
          name: 'UserEdit',
          component: () => import("../views/user/UserEdit.vue"),
          meta:{
              roles: [
                  roles.ADMIN
              ]
          }
      },
      {
          path: '/users',
          name: 'UserIndex',
          component: () => import("../views/user/UserIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN
              ]
          }
      },
      {
          path: '/users/create',
          name: 'UserCreate',
          component: () => import("../views/user/UserCreate.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
              ]
          }
      },
      {
          path: '/income-statistics',
          name: 'IncomeIndex',
          component: () => import("../views/statistics/IncomeIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.ACCOUNTANT
              ]
          }
      },
      {
          path: '/expenses-statistics',
          name: 'ExpensesIndex',
          component: () => import("../views/statistics/ExpensesIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.ACCOUNTANT
              ]
          }
      },
      {
          path: '/purchases-statistics',
          name: 'PurchasesIndex',
          component: () => import("../views/statistics/PurchasesIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.ACCOUNTANT,
                  roles.PURCHASING_MANAGER
              ]
          }
      },
      {
          path: '/sales-statistics',
          name: 'SalesIndex',
          component: () => import("../views/statistics/SalesIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.ACCOUNTANT,
                  roles.SALES_MANAGER
              ]
          }
      },
      {
          path: '/item-invoices',
          name: 'ItemInvoiceIndex',
          component: () => import("../views/item-invoice/ItemInvoiceIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.PURCHASING_MANAGER,
                  roles.ACCOUNTANT
              ]
          }
      },
      {
          path: '/invoices',
          name: 'InvoiceIndex',
          component: () => import("../views/invoice/InvoiceIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.SALES_MANAGER,
                  roles.ACCOUNTANT
              ]
          }
      },
      {
          path: '/applications',
          name: 'ApplicationIndex',
          component: () => import("../views/application/ApplicationIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.PURCHASING_MANAGER
              ]
          }
      },
      {
          path: '/providers',
          name: 'ProviderIndex',
          component: () => import("../views/provider/ProviderIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.PURCHASING_MANAGER
              ]
          }
      },
      {
          path: '/clients',
          name: 'ClientIndex',
          component: () => import("../views/clients/ClientIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.SALES_MANAGER
              ]
          }
      },
      {
          path: '/orders',
          name: 'OrderIndex',
          component: () => import("../views/orders/OrderIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.SALES_MANAGER
              ]
          }
      },
      {
          path: '/promotions',
          name: 'PromotionIndex',
          component: () => import("../views/promotion/PromotionIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.MARKETING_MANAGER
              ]
          }
      },
      {
          path: '/item-categories',
          name: 'CategoryIndex',
          component: () => import("../views/category/CategoryIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.MARKETING_MANAGER
              ]
          }
      },
      {
          path: '/items',
          name: 'ItemIndex',
          component: () => import("../views/item/ItemIndex.vue"),
          meta:{
              roles: [
                  roles.ADMIN,
                  roles.PURCHASING_MANAGER
              ]
          }
      },
      {
        path: '/employees',
        name: 'EmployeeIndex',
        component: () => import("../views/employee/EmployeeIndex.vue"),
        meta:{
            roles: [
                roles.ADMIN,
                roles.HR
            ]
        }
      },
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
        component: () => import("../views/post/PostCreate.vue"),
        meta:{
            roles:[
                roles.ADMIN,
                roles.HR
            ]
        }
    },
    {
        path: '/posts/:id/edit',
        name: 'PostEdit',
        component: () => import("../views/post/PostUpdate.vue"),
        meta:{
            roles:[
                roles.ADMIN,
                roles.HR
            ]
        },
        props: true
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

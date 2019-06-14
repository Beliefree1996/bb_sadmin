import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/views/layout/Layout'

/* Router Modules */
import componentsRouter from './modules/components'
import chartsRouter from './modules/charts'
import table1Router from './modules/table1'
import table2Router from './modules/table2'
import table3Router from './modules/table3'
import table4Router from './modules/table4'
import table5Router from './modules/table5'
import table6Router from './modules/table6'
import table7Router from './modules/table7'
import tableRouter from './modules/table'
import patterTestRouter from './modules/patterTest'
import matchTestRouter from './modules/matchTest'

import nestedRouter from './modules/nested'

/** note: Submenu only appear when children.length>=1
 *  detail see  https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 **/

/**
 * hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
 *                                if not set alwaysShow, only more than one route under the children
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noredirect           if `redirect:noredirect` will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    will control the page roles (you can set multiple roles)
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    noCache: true                if true, the page will no be cached(default is false)
    breadcrumb: false            if false, the item will hidden in breadcrumb(default is true)
  }
 **/
export const constantRouterMap = [
    {
        path: '/redirect',
        component: Layout,
        hidden: true,
        children: [
            {
                path: '/redirect/:path*',
                component: () => import('@/views/redirect/index')
            }
        ]
    },
    {
        path: '/login',
        component: () => import('@/views/login/index'),
        hidden: true
    },
    {
        path: '/auth-redirect',
        component: () => import('@/views/login/authredirect'),
        hidden: true
    },
    {
        path: '/404',
        component: () => import('@/views/errorPage/404'),
        hidden: true
    },
    {
        path: '/401',
        component: () => import('@/views/errorPage/401'),
        hidden: true
    },
    {
        path: '',
        component: Layout,
        redirect: 'dashboard',
        children: [
            {
                path: 'dashboard',
                component: () => import('@/views/dashboard/index'),
                name: 'Dashboard',
                meta: {title: 'dashboard', icon: 'dashboard', noCache: true}
            }
        ]
    },

    // {
    //   path: 'wxcrm',
    //   component: Layout,
    //   children: [
    //     {
    //       path: 'https://www.bbxxjs.com/wxcrm',
    //       name: 'Wxcrm',
    //       meta: { title: 'wxcrm', icon: 'dashboard', noCache: true }
    //     }
    //   ]
    // },

    // {
    //   path: 'TwNumberTable',
    //   component: () => import('@/views/table/TwNumberTable'),
    //   name: 'TwNumberTable',
    //   meta: { title: 'TwNumberTable' }
    // },
    // {
    //   path: '/documentation',
    //   component: Layout,
    //   redirect: '/documentation/index',
    //   children: [
    //     {
    //       path: 'index',
    //       component: () => import('@/views/documentation/index'),
    //       name: 'Documentation',
    //       meta: { title: 'documentation', icon: 'documentation', noCache: true }
    //     }
    //   ]
    // },
    // {
    //   path: '/guide',
    //   component: Layout,
    //   redirect: '/guide/index',
    //   children: [
    //     {
    //       path: 'index',
    //       component: () => import('@/views/guide/index'),
    //       name: 'Guide',
    //       meta: { title: 'guide', icon: 'guide', noCache: true }
    //     }
    //   ]
    // }
]

export default new Router({
    // mode: 'history', // require service support
    scrollBehavior: () => ({y: 0}),
    routes: constantRouterMap
})

export const asyncRouterMap = [
    /** When your routing table is too long, you can split it into small modules**/
    table1Router,
    // 信息汇总
    {
        path: '/information_aggregation',
        component: Layout,
        redirect: '/information_aggregation/index',
        name: 'information_aggregation',
        meta: {
            roles: ['sale']
        },
        children: [
            {
                path: 'index',
                component: () => import('@/views/information_aggregation/index'),
                name: 'index',
                meta: { icon: 'component', title: '信息汇总' }
            },
        ]
    },
    // 菜单管理
    {
        path: '/menu',
        component: Layout,
        redirect: '/menu/index',
        name: 'menu',
        meta: {roles: ['admin']},
        children: [
            {
                path: 'index',
                component: () => import('@/views/menu/index'),
                name: 'menuList',
                meta: { icon: 'component', title: '菜单管理' }
            },
        ]
    },

    //使用客户统计信息
    {
        path: '/company_analyse',
        component: Layout,
        redirect: '/company_analyse/index',
        name: 'company_analyse',
        meta: {icon: 'component',title: '客户统计信息' ,roles: ['admin'] , ids:[194]},
        children: [
            {
                path: 'index',
                component: () => import('@/views/company_analyse/index'),
                name: 'company_list',
                meta: { icon: 'component', title: '客户统计信息' }
            },
            {
                path: 'info',
                component: () => import('@/views/company_analyse/info'),
                name: 'company_info',
                meta: { icon: 'component', title: '统计信息'},
                hidden:true
            },
        ]
    },


    patterTestRouter,
    matchTestRouter,
    table7Router,
    table6Router,
    table5Router,
    table2Router,
    table3Router,
    table4Router,
    tableRouter,

    {
        path: 'balance-click',
        component: Layout,
        children: [
            {
                path: 'http://106.15.179.51:8080/chs/index.html',
                meta: {title: 'balanceClick', icon: 'money'},
                roles: ['company']
            }
        ]
    },

    {
        path: 'external-link',
        component: Layout,
        children: [
            {
                path: 'https://www.bbxxjs.com/',
                meta: {title: 'externalLink', icon: 'link'},
                roles: ['sale', 'company', 'seat']
            }
        ]
    },

    {path: '*', redirect: '/404', hidden: true}
]

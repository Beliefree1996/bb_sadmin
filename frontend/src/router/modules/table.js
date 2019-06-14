/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/views/layout/Layout'

const tableRouter = {
    path: '/table',
    component: Layout,
    redirect: '/table/GetTaskList',
    name: 'Table',
    meta: {
        title: '账户管理',
        icon: 'table',
        roles: ['admin']
    },
    children: [
        {
            path: 'GetTaskList/:name',
            component: () => import('@/views/table/GetTaskList'),
            name: 'GetTaskList',
            meta: {title: '业务员列表', noCache: true},
            hidden: true
        },
        {
            path: 'GetTaskRobotList/:name',
            component: () => import('@/views/table/GetTaskRobotList'),
            name: 'GetTaskRobotList',
            meta: {title: '用户任务列表', noCache: true},
            hidden: true
        },
        {
            path: 'RechArgeUserTable/:name/:gid',
            component: () => import('@/views/table/RechArgeUserTable'),
            name: 'RechArgeUserTable',
            meta: {title: '充值页面', noCache: true},
            hidden: true
        },
        {
            path: 'UserlistTable',
            component: () => import('@/views/table/UserlistTable'),
            name: 'UserlistTable',
            meta: {title: '用户列表'}
        },
        {
            path: 'UserMTListTable',
            component: () => import('@/views/table/UserMTListTable'),
            name: 'UserMTListTable',
            meta: {title: '用户任务管理'}
        },
    ]
}
export default tableRouter

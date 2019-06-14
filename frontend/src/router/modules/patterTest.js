import Layout from '@/views/layout/Layout'

// 话术二表，测试用
const patterTestRouter = {
    path: '/patterTest',
    component: Layout,
    redirect: '/patterTest/index',
    name: 'patterTest',
    meta: {
        title: '新版话术管理',
        icon: 'liucheng',
        roles: ['admin',"company"],
    },
    children: [

        {
            path: 'patterList',
            component: () => import('@/views/patterTest/index'),
            name: 'patterList',
            meta: {title: '话术列表'}
        },
        {
            path: 'patterDetail',
            component: () => import('@/views/patterTest/patterDetail'),
            name: 'patterDetail',
            meta: {title: '话术详情'},
            hidden: true
        },
        {
            path: 'roleManage',
            component: () => import('@/views/patterTest/roleManage'),
            name: 'roleManage',
            meta: {title: '角色管理'},
            hidden: true
        },
        {
            path: 'patterWordEdit',
            component: () => import('@/views/patterTest/patterWordEdit'),
            name: 'patterWordEdit',
            meta: {title: '话术语句编辑', noCache: true},
            hidden: true
        },

        {
            path: 'voiceList',
            component: () => import('@/views/patterTest/voiceList'),
            name: 'voiceList',
            meta: {title: '语音资源库'}
        },

        {
            path: 'wordBaseList',
            component: () => import('@/views/patterTest/wordBaseList'),
            name: 'wordBaseList',
            meta: {title: '关键词库'}
        },
        {
            path: 'wordBaseDetail',
            component: () => import('@/views/patterTest/wordBaseDetail'),
            name: 'wordBaseDetail',
            meta: {title: '关键词列表'},
            hidden: true
        },

        {
            path: 'sceneList',
            component: () => import('@/views/patterTest/sceneList'),
            name: 'sceneList',
            meta: {title: '情景词库'}
        },
        {
            path: 'sceneDetail',
            component: () => import('@/views/patterTest/sceneDetail'),
            name: 'sceneDetail',
            meta: {title: '情景词列表'},
            hidden: true
        },
    ]
}
export default patterTestRouter
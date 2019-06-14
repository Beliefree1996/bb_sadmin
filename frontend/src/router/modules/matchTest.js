import Layout from '@/views/layout/Layout'

// 话术二表，测试用
const matchTestRouter = {
    path: '/matchTest',
    component: Layout,
    redirect: '/matchTest/index',
    name: 'matchTest',
    meta: {
        title: '关键词匹配测试',
        icon: 'liucheng',
        roles: ['company'],
        ids:[]
    },
    children: [
        {
            path: 'index',
            component: () => import('@/views/matchTest/index'),
            name: 'test',
            meta: {title: '匹配测试'}
        },
    ]
}
export default matchTestRouter
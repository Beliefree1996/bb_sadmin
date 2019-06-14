/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/views/layout/Layout'

const table4Router = {
    path: '/table4',
    component: Layout,
    redirect: '/table/complex-table',
    name: 'Table4',
    meta: {
        title: 'VoiceManage',
        icon: 'liucheng',
        roles: ['sale', 'company']
    },
    children: [
        {
            path: 'VoiceResource',
            component: () => import('@/views/table/VoiceResource'),
            name: 'VoiceResource',
            meta: { title: 'VoiceResource' }
        },
        {
            path: 'VoiceList',
            component: () => import('@/views/table/VoiceList'),
            name: 'VoiceList',
            meta: { title: 'VoiceList' }
        },
        {
            path: 'PatterWord/:id(\\d+)',
            component: () => import('@/views/table/PatterWord'),
            name: 'PatterWord',
            meta: { title: 'PatterWord' },
            hidden: true
        },
        {
            path: 'PatterWordEdit/:id(\\d+)',
            component: () => import('@/views/table/PatterWordEdit'),
            name: 'PatterWordEdit',
            meta: { title: 'PatterWordEdit', noCache: true },
            hidden: true
        },
    ]
}
export default table4Router
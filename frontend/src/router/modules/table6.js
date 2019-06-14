/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/views/layout/Layout'

const table6Router = {
    path: '/table6',
    component: Layout,
    redirect: '/table/complex-table',
    name: 'Table6',
    meta: {
        title: '',
        icon: 'list',
        roles: ['company']
    },
    children: [
        {
            path: 'CrmList',
            component: () => import('@/views/table/CrmList'),
            name: 'CrmList',
            meta: { title: 'CrmList' }
        },
        {
            path: 'CrmListDetails/:id(\\d+)',
            component: () => import('@/views/table/CrmListDetails'),
            name: 'CrmListDetails',
            meta: { title: 'CrmListDetails', noCache: true },
            hidden: true
        },
    ]
}
export default table6Router


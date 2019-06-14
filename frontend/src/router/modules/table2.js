/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/views/layout/Layout'

const table2Router = {
  path: '/table2',
  component: Layout,
  redirect: '/table/complex-table',
  name: 'Table2',
  meta: {
    title: 'Manage',
    icon: 'edit',
    roles: [ 'company']
  },
  children: [
    {
      path: 'IsOpenTask',
      component: () => import('@/views/table/IsOpenTask'),
      name: 'IsOpenTask',
      meta: { title: 'IsOpenTask' }
    },
    {
      path: 'IsDownTable',
      component: () => import('@/views/table/IsDownTable'),
      name: 'IsDownTable',
      meta: { title: 'IsDownTable' }
    },
    {
      path: 'CallCharge',
      component: () => import('@/views/table/CallCharge'),
      name: 'CallCharge',
      meta: { title: 'CallCharge' }
    },
    {
      path: 'DownDoc',
      component: () => import('@/views/table/DownDoc'),
      name: 'DownDoc',
      meta: { title: 'DownDoc' }
    },
    {
      path: 'DocDetails/:id(\\d+)',
      component: () => import('@/views/table/DocDetails'),
      name: 'DocDetails',
      meta: { title: 'DocDetails', noCache: true },
      hidden: true
    },
  ]
}
export default table2Router


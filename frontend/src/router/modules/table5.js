/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/views/layout/Layout'

const table1Router = {
  path: '/table5',
  component: Layout,
  redirect: '/table/complex-table',
  name: 'Table5',
  meta: {
    title: '',
    icon: 'demo',
    roles: ['sale']
  },
  children: [
    {
      path: 'Demonstration',
      component: () => import('@/views/table/Demonstration'),
      name: 'Demonstration',
      meta: { title: 'Demonstration' }
    },
  ]
}
export default table1Router


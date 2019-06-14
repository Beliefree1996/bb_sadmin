/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/views/layout/Layout'

const tableRouter7 = {
    path: '/sms',
    component: Layout,
    redirect: '/sms/index',
    name: 'Table7',
    meta: {
        title: 'Sms',
        icon: 'liucheng',
        roles: ['admin']
    },
    children: [
        {
            path: 'Smslist',
            component: () => import('@/views/sms/Smslist'),
            name: 'Smslist',
            meta: {title: 'SmsSendlist'}
        },
        {
            path: 'Smssend',
            component: () => import('@/views/sms/Smssend'),
            name: 'Smssend',
            meta: {title: 'SmsSend'}
        },
        {
            path: 'Smsmoban',
            component: () => import('@/views/sms/Smsmoban'),
            name: 'Smsmoban',
            meta: {title: 'SmsMoban'}
        },
        {
            path: 'Smsqunsend',
            component: () => import('@/views/sms/Smsqunsend'),
            name: 'Smsqunsend',
            meta: {title: 'SmsQunSend'}
        },
        {
            path: 'Smssendtype',
            component: () => import('@/views/sms/Smssendtype'),
            name: 'Smssendtype',
            meta: {title: 'SmsSendType'}
        },
    ]
}
export default tableRouter7

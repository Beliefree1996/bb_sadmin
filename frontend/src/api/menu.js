import request from '@/utils/request'


export function getMenus() {
    return request({
        url: '/getMenus',
        method: 'post',
    })
}


export function editMenu(data) {
    return request({
        url: '/editMenu',
        method: 'post',
        data
    })
}


export function getMenuer(data) {
    return request({
        url: '/getMenuer',
        method: 'post',
        data
    })
}
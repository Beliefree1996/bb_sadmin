import request from '@/utils/request'


export function getAllPatter() {
    return request({
        url: '/getAllPatter',
        method: 'post',
    })
}


export function startCall(data) {
    return request({
        url: '/startCall',
        method: 'post',
        data
    })
}


export function getCurQueue(data) {
    return request({
        url: '/getCurQueue',
        method: 'post',
        data
    })
}
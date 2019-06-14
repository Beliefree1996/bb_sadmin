import request from '@/utils/request'


export function getInformations() {
    return request({
        url: '/getInformations',
        method: 'post',
    })
}

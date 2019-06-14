import request from '@/utils/request'

export function getgatewayrouting() {
    return request({
        url: 'https://api.bbxxjs.com/api/getgatewayrouting',
        method: 'get'
    })
}

export function getgeerategroup(data) {
    return request({
        url: 'https://api.bbxxjs.com/api/getgeerategroup',
        method: 'get',
        withCredentials: true,
        data
    })
}

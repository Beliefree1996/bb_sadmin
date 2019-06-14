import request from '@/utils/request'



export function fetchUserlist() {
    return request({
        url: '/company_analyse',
        method: 'post'
    })
}


export function getCompanyAnalyse(data) {
    return request({
        url: '/company_analyse_info',
        method: 'post',
        data
    })
}
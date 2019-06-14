import request from '@/utils/request'
import qs from 'qs';


export function fetchPatter() {
    return request({
        url: 'https://api.bbxxjs.com/api/ai/patter',
        method: 'post',
        // xhrFields: {
        //     withCredentials: true
        // },
        // withCredentials: true,
        // crossDomain: true,
        // headers:{
        //     'Accept':'application/x-www-form-urlencoded'
        // }
    })
}


export function getDispatchList(data) {
    return request({
        url: 'https://api.bbxxjs.com/api/ai/bsword',
        method: 'get',
        params:data
    })
}

export function updateKeyIndex(data) {
    return request({
        url: 'https://api.bbxxjs.com/api/ai/reindex',
        method: 'get',
        params:data
    })
}

export function putKeyword(data) {
    return request({
        url: 'https://api.bbxxjs.com/api/ai/putkey',
        method: 'POST',
        data:qs.stringify(data),
        headers:{
            "Content-Type":'application/x-www-form-urlencoded'
        }
    })
}

export function diffKeyword(data) {
    return request({
        url: 'https://api.bbxxjs.com/api/ai/diffword',
        method: 'get',
        params:data
    })
}
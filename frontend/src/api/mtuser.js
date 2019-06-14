import request from '@/utils/request'

export function fetchUserlist() {
  return request({
    url: 'https://api.bbxxjs.com/api/mtuserlist',
    method: 'get'
  })
}

export function fetchSUserlist(id) {
  return request({
    url: 'https://api.bbxxjs.com/api/suserlist',
    method: 'get',
    params: { sid: id }
  })
}

export function fetchSpeech(id) {
  return request({
    url: 'https://api.bbxxjs.com/api/speechlist',
    method: 'get',
    params: { uid: id }
  })
}

export function delUser(id) {
  return request({
    url: 'https://api.bbxxjs.com/api/del/user',
    method: 'post',
    data: { sid: id }
  })
}

export function fetchSRTUserlist(id) {
  return request({
    url: 'https://api.bbxxjs.com/api/srtuserlist',
    method: 'get',
    params: { uid: id }
  })
}

export function delSRTUser(id) {
  return request({
    url: 'https://api.bbxxjs.com/api/del/srtuser',
    method: 'post',
    data: { sid: id }
  })
}

export function addAutoDialertask(data) {
  return request({
    url: 'https://api.bbxxjs.com/api/add/autodialertask',
    method: 'post',
    data
  })
}

export function RechargeVos(data) {
  return request({
    url: 'https://api.bbxxjs.com/api/rechargevos',
    method: 'post',
    data
  })
}

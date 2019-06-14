import request from '@/utils/request'

export function fetchList(data) {
  return request({
    url: 'https://api.bbxxjs.com/api/list',
    method: 'post',
    data
  })
}

export function fetchUserlist() {
  return request({
    url: 'https://api.bbxxjs.com/api/userlist',
    method: 'get'
  })
}

export function fetchGUserlist() {
  return request({
    url: 'https://api.bbxxjs.com/api/guserlist',
    method: 'get'
  })
}

export function setisdown(data) {
  return request({
    url: 'https://api.bbxxjs.com/api/setisdown',
    method: 'post',
    data
  })
}

export function adduser(data) {
  return request({
    url: 'https://api.bbxxjs.com/api/adduser/',
    method: 'post',
    data
  })
}

export function adduerlist(data) {
  return request({
    url: 'https://api.bbxxjs.com/api/adduserlist',
    method: 'post',
    data
  })
}

export function createlistaicti(data) {
  return request({
    url: 'https://api.bbxxjs.com/api/createlistaicti',
    method: 'post',
    data
  })
}

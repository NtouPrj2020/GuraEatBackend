import axios from 'axios'

//初始化
let guestRequest = axios.create({
    baseURL: process.env.MIX_APP_URL_LOCALHOST,
    headers: { 'Content-Type': 'application/json' },
})
let userRequest = axios.create({
    baseURL: process.env.MIX_APP_URL_LOCALHOST,
    headers: { 'Content-Type': 'application/json' },
    withCredentials: true
})

//正式機
if(process.env.APP_ENV==="production"){
    //不需要authorization
    guestRequest = axios.create({
        baseURL: process.env.MIX_APP_URL_PRODUCTION,
        headers: { 'Content-Type': 'application/json' },
    })
    //需要authorization
    userRequest = axios.create({
        baseURL: process.env.MIX_APP_URL_PRODUCTION,
        headers: { 'Content-Type': 'application/json' },
        withCredentials: true
    })
}

//本地測試機
if(process.env.APP_ENV==="local"){
    //不需要authorization
    guestRequest = axios.create({
        baseURL: process.env.MIX_APP_URL_LOCALHOST,
        headers: { 'Content-Type': 'application/json' },
    })
    //需要authorization
    userRequest = axios.create({
        baseURL: process.env.MIX_APP_URL_LOCALHOST,
        headers: { 'Content-Type': 'application/json'},
        withCredentials: true
    })
}


//統一管理 API Call
//data: 放資料
//config: 放header資料, 像是authorization
export const customerSignUpAPI = (data) => guestRequest.post('/api/v1/guest/customer_signup', data);
export const deliveryManSignUpAPI = (data) => guestRequest.post('/api/v1/guest/customer_signup', data);
export const customerLoginAPI = (data) => guestRequest.post('/api/v1/guest/customer_login', data);
export const deliveryManLoginAPI = (data) => guestRequest.post('/api/v1/guest/delivery_man_login', data);
export const customerLogoutAPI = (data,config) => userRequest.post('/api/v1/users/customer/logout', data,config);
export const deliveryManLogoutAPI = (data,config) => userRequest.post('/api/v1/users/delivery_man/logout', data,config);
export const getRestaurantall = (data) => userRequest.post('/api/v1/users/customer/restaurant/all', data);

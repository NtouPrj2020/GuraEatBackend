import axios from "axios";

//初始化
let guestRequest = axios.create({
    baseURL: process.env.MIX_APP_URL_LOCALHOST,
    headers: { "Content-Type": "application/json" }
});
let userRequest = axios.create({
    baseURL: process.env.MIX_APP_URL_LOCALHOST,
    headers: { "Content-Type": "application/json" },
    withCredentials: true
});

//正式機
if (process.env.APP_ENV === "production") {
    //不需要authorization
    guestRequest = axios.create({
        baseURL: process.env.MIX_APP_URL_PRODUCTION,
        headers: { "Content-Type": "application/json" }
    });
    //需要authorization
    userRequest = axios.create({
        baseURL: process.env.MIX_APP_URL_PRODUCTION,
        headers: { "Content-Type": "application/json" },
        withCredentials: true
    });
}

//本地測試機
if (process.env.APP_ENV === "local") {
    //不需要authorization
    guestRequest = axios.create({
        baseURL: process.env.MIX_APP_URL_LOCALHOST,
        headers: { "Content-Type": "application/json" }
    });
    //需要authorization
    userRequest = axios.create({
        baseURL: process.env.MIX_APP_URL_LOCALHOST,
        headers: { "Content-Type": "application/json" },
        withCredentials: true
    });
}

//統一管理 API Call
//data: 放資料
//config: 放header資料, 像是authorization

export const customerSignUpAPI = (
    config //id 0
) => guestRequest.post("/api/v1/guest/customer/signup", config);
export const deliveryManSignUpAPI = (
    config //id 0
) => guestRequest.post("/api/v1/guest/delivery_man/signup", config);
export const customerLoginAPI = (
    config //id 1
) => guestRequest.post("/api/v1/guest/customer/login", config);
export const deliveryManLoginAPI = (
    config //id 1
) => guestRequest.post("/api/v1/guest/delivery_man/login", config);
export const restaurantLoginAPI = (
    config //id 1
) => guestRequest.post("/api/v1/guest/restaurant/login", config);
export const switchUserModeCustomerAPI = (
    config,
    data //id 2
) => guestRequest.post("unknown", config, data);
export const switchUserModeDeliveryManAPI = (
    config,
    data //id 2
) => guestRequest.post("unknown", config, data);
export const switchUserModeRestaurantAPI = (
    config,
    data //id 2
) => guestRequest.post("unknown", config, data);
export const forgotPasswordAPI = (
    config,
    data //id 3
) => guestRequest.post("unknown", config, data);
export const getCustomerInfo = (
    config // id 4
) => guestRequest.post("unknown", config);
export const editCustomerInfo = (
    config,
    data //id 5
) => guestRequest.post("unknown", config, data);
export const getRestaurantall = (
    config //id 6
) => userRequest.get("/api/v1/users/customer/restaurant/all", config);
export const getRestaurantByKeyword = (
    config,
    data //id 7
) =>
    userRequest.post(
        "/api/v1/users/customer/restaurant/searchByKeyword",
        config,
        data
    );
export const getDishByRestaurantID = (
    config // id 8
) => userRequest.get("/api/v1/users/customer/restaurant/getAllDish", config);
export const getDeliveryTimeID = (
    config // id 9
) => userRequest.get("unkown", config);
export const sendOrderAPI = (
    config,
    data // id 10
) => userRequest.post("unkown", config, data);
export const getOrderstatusAPI = (
    config // id 11
) => userRequest.get("unkown", config);
export const giveRateAPI = (
    config,
    data // id 12
) => userRequest.post("unkown", config, data);
export const getOrderHistoryCustomerAPI = (
    config // id 13
) => userRequest.get("unkown", config);
export const getDeliveryManInfoAPI = (
    config // id 14
) => userRequest.get("unkown", config);
export const editDeliveryManInfo = (
    config,
    data //id 15
) => guestRequest.post("unknown", config, data);

export const customerLogoutAPI = (config, data) =>
    userRequest.post("/api/v1/users/customer/logout", config, data);
export const deliveryManLogoutAPI = (config, data) =>
    userRequest.post("/api/v1/users/delivery_man/logout", config, data);
export const getRestaurantByID = config =>
    userRequest.get("/api/v1/users/customer/restaurant/searchByID", config);
export const getDishByDishID = config =>
    userRequest.get("/api/v1/users/restaurant/menu/getDishbyID", config);
export const restaurantEditDish = (config, data) =>
    userRequest.put("/api/v1/users/restaurant/menu/editDish", config, data);
export const restaurantDeleteDish = config =>
    userRequest.delete("/api/v1/users/restaurant/menu/deleteDish", config);
export const restaurantAddDish = (config, data) =>
    userRequest.post("/api/v1/users/restaurant/menu/addDish", config, data);

//後面加上API

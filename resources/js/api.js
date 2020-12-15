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

//id 0
export const customerSignUpAPI = config =>
    guestRequest.post("/api/v1/guest/customer/signup", config);
//id 0
export const deliveryManSignUpAPI = config =>
    guestRequest.post("/api/v1/guest/delivery_man/signup", config);
//id 1
export const customerLoginAPI = config =>
    guestRequest.post("/api/v1/guest/customer/login", config);
//id 1
export const deliveryManLoginAPI = config =>
    guestRequest.post("/api/v1/guest/delivery_man/login", config);
//id 1
export const restaurantLoginAPI = config =>
    guestRequest.post("/api/v1/guest/restaurant/login", config);
//id 2
export const customerSwitchUserModeAPI = (config, data) =>
    userRequest.post("/api/v1/users/customer/changeRoleCustomer", config, data);
//id 2
export const deliveryManSwitchUserModeAPI = (config, data) =>
    userRequest.post(
        "/api/v1/users/delivery_man/changeRoleDeliveryMan",
        config,
        data
    );
//id 2
export const restaurantSwitchUserModeAPI = (config, data) =>
    userRequest.post(
        "/api/v1/users/restaurant/changeRoleRestaurant",
        config,
        data
    );
//id 3
export const forgotPasswordAPI = (config, data) =>
    guestRequest.post("unknown", config, data);
// id 4
export const customerGetInfoAPI = config =>
    userRequest.get("/api/v1/users/customer/info", config);
//id 5
export const customerEditInfoAPI = (config, data) =>
    userRequest.put("/api/v1/users/customer/info", config, data);
//id 6
export const customerGetAllRestaurantAPI = config =>
    userRequest.get("/api/v1/users/customer/restaurant/all", config);
//id 7
export const customerGetRestaurantByKeywordAPI = config =>
    userRequest.get(
        "/api/v1/users/customer/restaurant/searchByKeyword",
        config
    );
// id 8
export const customerGetAllDishByRestaurantIDAPI = config =>
    userRequest.get("/api/v1/users/customer/restaurant/getAllDish", config);
// id 9
export const customerGetDeliveryTimeIDAPI = config =>
    userRequest.get(
        "/api/v1/users/customer/getDistanceAndTimeByAddress",
        config
    );
// id 10
export const customerSendOrderAPI = (config, data) =>
    userRequest.post("/api/v1/users/customer/order/send", config, data);
// id 11
export const customerGetOrderstatusAPI = config =>
    userRequest.get("/api/v1/users/customer/order/now", config);
// id 12
export const customerGiveRateAPI = (config, data) =>
    userRequest.post("unkown", config, data);
// id 13
export const customerGetOrderHistoryAPI = config =>
    userRequest.get("unkown", config);
// id 14
export const deliveryManGetInfoAPI = config =>
    userRequest.get("/api/v1/users/delivery_man/info", config);
//id 15
export const deliveryManEditInfoAPI = (config, data) =>
    userRequest.put("/api/v1/users/delivery_man/info", config, data);
// id 16
export const deliveryManChangeStateAPI = (config, data) =>
    userRequest.put("/api/v1/users/delivery_man/status", config, data);
// id 17
export const deliveryManCheckOrderStateAPI = config =>
    userRequest.get("unknown", config);
// id 18
export const deliveryManConfirmOrderAPI = (config, data) =>
    userRequest.post("unknown", config, data);
// id 19
export const deliveryManGetHistoryOrderAPI = config =>
    userRequest.get("unknown", config);
// id 20
export const restaurantAddDishAPI = (config, data) =>
    userRequest.post("/api/v1/users/restaurant/menu/addDish", config, data);
// id 21
export const restaurantEditDishAPI = (config, data) =>
    userRequest.put("/api/v1/users/restaurant/menu/editDish", config, data);
// id 22
export const restaurantDeleteDishAPI = config =>
    userRequest.delete("/api/v1/users/restaurant/menu/deleteDish", config);
// id 23
export const restaurantGetAllDishAPI = config =>
    userRequest.get("/api/v1/users/restaurant/menu/getAllDish", config);
// id unknown
export const restaurantGetInfoAPI = config =>
    userRequest.get("/api/v1/users/restaurant/info", config);
// id 24
export const restaurantEditInfoAPI = (config, data) =>
    userRequest.put("/api/v1/users/restaurant/info", config, data);
// id 25
export const deliveryManSwitchOrderStateAPI = (config, data) =>
    userRequest.put("unknown", config, data);
// id 26
export const customerSendLocationAPI = (config, data) =>
    userRequest.post("/api/v1/users/customer/location", config, data);
// id 27
export const customerGetLocationAPI = config =>
    userRequest.get("/api/v1/users/customer/location", config);
// id 28
export const deliveryManSendLocationAPI = (config, data) =>
    userRequest.post("/api/v1/users/delivery_man/location", config, data);
// id 29
export const deliveryManGetLocationAPI = config =>
    userRequest.get("/api/v1/users/delivery_man/location", config);

//以下為未定義在SDD中的
export const customerLogoutAPI = (config, data) =>
    userRequest.post("/api/v1/users/customer/logout", config, data);
export const customerGetRestaurantByTagAPI = config =>
    userRequest.get("/api/v1/users/customer/restaurant/searchByTag", config);
export const customerGetRestaurantByIDAPI = config =>
    userRequest.get("/api/v1/users/customer/restaurant/searchByID", config);
export const customerlocationToAddressAPI = config =>
    userRequest.get("/api/v1/users/customer/locationToAddress", config);
export const customerGetAllOrdersAPI = config =>
    userRequest.get("/api/v1/users/customer/order/getAllOrders", config);
export const deliveryManLogoutAPI = (config, data) =>
    userRequest.post("/api/v1/users/delivery_man/logout", config, data);
export const restaurantGetDishByDishIDAPI = config =>
    userRequest.get("/api/v1/users/restaurant/menu/getDishbyID", config);
export const restaurantGetAllTagAPI = config =>
    userRequest.get("/api/v1/users/restaurant/tags", config);

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPasswordReset;
use App\Models\DeliveryMan;
use App\Models\DeliveryManPasswordReset;
use App\Notifications\CustomerPasswordResetRequest;
use App\Notifications\DeliveryManPasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    /** @OA\Post(
     *      path="/api/v1/guest/customer/signup",
     *      operationId="customerSignUp",
     *      tags={"AuthController"},
     *      summary="顧客註冊",
     *      description="顧客註冊",
     *      @OA\Parameter(
     *          name="name",
     *          description="使用者名稱",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          description="Email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="phone",
     *          description="phone",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="address",
     *          description="address",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password",
     *          description="password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="顧客註冊成功",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer",
     *                         description="status",
     *                         example=200
     *                     ),
     *                     @OA\Property(
     *                         property="method",
     *                         type="string",
     *                         description="method",
     *                         example = "deliveryManSignUp"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="message",
     *                         example = "ok"
     *                     )
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="請求格式錯誤"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="顧客已註冊"
     *      )
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customerSignUp(Request $request)
    {
        //驗證資料
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required',
        ]);

        //查詢是否存在
        $user = Customer::where('email', $request->email)->first();
        if ($user != null) {
            $data = [
                "status" => 401,
                "method" => "customerSignUp",
                "message" => "already sign",
            ];
            return response()->json($data, 401);
        }

        //建立帳戶
        $user = new Customer;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = bcrypt($request['password']);
        $user->save();

        if ($user != null) {
            $data = [
                "status" => 201,
                "method" => "customerSignUp",
                "message" => "success",
            ];
            return response()->json($data, 201);
        } else {
            $data = [
                "method" => "customerSignUp",
                "message" => "unexpected error",
            ];
            return response()->json($data, 500);
        }

    }

    /** @OA\Post(
     *      path="/api/v1/guest/delivery_man/signup",
     *      operationId="deliveryManSignUp",
     *      tags={"AuthController"},
     *      summary="外送員註冊",
     *      description="外送員註冊",
     *      @OA\Parameter(
     *          name="name",
     *          description="使用者名稱",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          description="Email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="phone",
     *          description="phone",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="license_id",
     *          description="license_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password",
     *          description="password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="外送員註冊成功",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer",
     *                         description="status",
     *                         example=200
     *                     ),
     *                     @OA\Property(
     *                         property="method",
     *                         type="string",
     *                         description="method",
     *                         example = "deliveryManSignUp"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="message",
     *                         example = "ok"
     *                     )
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="請求格式錯誤"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="外送員已註冊"
     *      )
     * )
     */
    public function deliveryManSignUp(Request $request)
    {
        //驗證資料
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'license_id' => 'required',
            'password' => 'required',
        ]);

        //查詢是否存在
        $user = DeliveryMan::where('email', $request->email)->first();
        if ($user != null) {
            $data = [
                "status" => 401,
                "method" => "deliveryManSignUp",
                "message" => "already sign",
            ];
            return response()->json($data, 401);
        }

        //建立帳戶
        $user = new DeliveryMan;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->license_id = $request->license_id;
        $user->password = bcrypt($request['password']);
        $user->save();

        if ($user != null) {
            $data = [
                "status" => 201,
                "method" => "deliveryManSignUp",
                "message" => "success",
            ];
            return response()->json($data, 201);
        } else {
            $data = [
                "method" => "deliveryManSignUp",
                "message" => "unexpected error",
            ];
            return response()->json($data, 500);
        }

    }

    /** @OA\Post(
     *      path="/api/v1/guest/customer/login",
     *      operationId="customerLogin",
     *      tags={"AuthController"},
     *      summary="顧客登入",
     *      description="顧客登入",
     *      @OA\Parameter(
     *          name="email",
     *          description="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          description="password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="device_name",
     *          description="device_name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="登入成功",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer",
     *                         description="status",
     *                         example=200
     *                     ),
     *                     @OA\Property(
     *                         property="method",
     *                         type="string",
     *                         description="method",
     *                         example = "customerLogin"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="message",
     *                         example = "ok"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="object",
     *                         @OA\Property(
     *                                  property="access_token",
     *                                  type="string",
     *                                  description="token",
     *                                  example = "wefgwehwehwgt8798t97g79erg"
     *                          ),
     *                         @OA\Property(
     *                                  property="name",
     *                                  type="string",
     *                                  description="name",
     *                                  example = "ㄐㄐ"
     *                          ),
     *                         @OA\Property(
     *                                  property="email",
     *                                  type="string",
     *                                  description="email",
     *                                  example = "00857211@mail.ntou.edu.tw"
     *                          ),
     *                         @OA\Property(
     *                                  property="phone",
     *                                  type="string",
     *                                  description="phone",
     *                                  example = "0987654321"
     *                          ),
     *                         @OA\Property(
     *                                  property="address",
     *                                  type="string",
     *                                  description="address",
     *                                  example = "xxxxxxxxxx"
     *                          )
     *                     )
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="請求格式錯誤"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="帳號不存在或密碼錯誤"
     *      )
     * )
     */
    public function customerLogin(Request $request)
    {
        //驗證資料
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        //找尋使用者
        $user = Customer::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $data = [
                "status" => 401,
                "method" => "customerLogin",
                "message" => "failed",
                "data" => [
                    "access_token" => "",
                    "name" => "",
                    "email" => "",
                    "phone" => "",
                    "address" => ""
                ]
            ];
            return response()->json($data, 401);
        } else {
            $data = [
                "status" => 200,
                "method" => "customerLogin",
                "message" => "success",
                "data" => [
                    "access_token" => $user->createToken($request->device_name)->plainTextToken,
                    "name" => $user->name,
                    "email" => $user->email,
                    "phone" => $user->phone,
                    "address" => $user->address
                ]
            ];
            return response()->json($data, 200);
        }
    }

    /** @OA\Post(
     *      path="/api/v1/guest/delivery_man/login",
     *      operationId="deliveryManLogin",
     *      tags={"AuthController"},
     *      summary="外送員登入",
     *      description="外送員登入",
     *      @OA\Parameter(
     *          name="email",
     *          description="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          description="password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="device_name",
     *          description="device_name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="登入成功",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer",
     *                         description="status",
     *                         example=200
     *                     ),
     *                     @OA\Property(
     *                         property="method",
     *                         type="string",
     *                         description="method",
     *                         example = "deliveryManLogin"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="message",
     *                         example = "ok"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="object",
     *                         @OA\Property(
     *                                  property="access_token",
     *                                  type="string",
     *                                  description="token",
     *                                  example = "wefgwehwehwgt8798t97g79erg"
     *                          ),
     *                         @OA\Property(
     *                                  property="name",
     *                                  type="string",
     *                                  description="name",
     *                                  example = "ㄐㄐ"
     *                          ),
     *                         @OA\Property(
     *                                  property="email",
     *                                  type="string",
     *                                  description="email",
     *                                  example = "00857211@mail.ntou.edu.tw"
     *                          ),
     *                         @OA\Property(
     *                                  property="phone",
     *                                  type="string",
     *                                  description="phone",
     *                                  example = "0987654321"
     *                          ),
     *                         @OA\Property(
     *                                  property="license_id",
     *                                  type="string",
     *                                  description="license_id",
     *                                  example = "C87-630"
     *                          )
     *                     )
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="請求格式錯誤"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="帳號不存在或密碼錯誤"
     *      )
     * )
     */
    public function deliveryManLogin(Request $request)
    {
        //驗證資料
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        //找尋使用者
        $user = DeliveryMan::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $data = [
                "status" => 401,
                "method" => "customerLogin",
                "message" => "failed",
                "data" => [
                    "access_token" => "",
                    "name" => "",
                    "email" => "",
                    "phone" => "",
                    "address" => "",
                ]
            ];
            return response()->json($data, 401);
        } else {
            $data = [
                "status" => 200,
                "method" => "customerLogin",
                "message" => "success",
                "data" => [
                    "access_token" => $user->createToken($request->device_name)->plainTextToken,
                    "name" => $user->name,
                    "email" => $user->email,
                    "phone" => $user->phone,
                    "address" => $user->license_id,
                ]
            ];
            return response()->json($data, 200);
        }
    }

    /** @OA\Post(
     *      path="/api/v1/users/customer/logout",
     *      operationId="customerLogout",
     *      tags={"AuthController"},
     *      summary="顧客登出",
     *      description="顧客登出",
     *      security={
     *         {
     *              "Authorization": {}
     *         }
     *      },
     *     @OA\Parameter(
     *          name="device_name",
     *          description="device_name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="登出成功",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer",
     *                         description="status",
     *                         example=200
     *                     ),
     *                     @OA\Property(
     *                         property="method",
     *                         type="string",
     *                         description="method",
     *                         example = "customerLogout"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="message",
     *                         example = "ok"
     *                     )
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="請求格式錯誤"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="認證錯誤"
     *      )
     * )
     */
    public function customerLogout(Request $request)
    {
        $user = $request->user();

        if ($user != null) {
            $request->user()->tokens()->where('name', $request->device_name)->delete();
            $data = [
                "status" => 200,
                "method" => "customerLogout",
                "message" => "success"
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "status" => 403,
                "method" => "customerLogout",
                "message" => "user not found"
            ];
            return response()->json($data, 403);
        }
    }

    /** @OA\Post(
     *      path="/api/v1/users/delivery_man/logout",
     *      operationId="deliveryManLogout",
     *      tags={"AuthController"},
     *      summary="外送員登出",
     *      description="外送員登出",
     *      security={
     *         {
     *              "Authorization": {}
     *         }
     *      },
     *     @OA\Parameter(
     *          name="device_name",
     *          description="device_name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="登出成功",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer",
     *                         description="status",
     *                         example=200
     *                     ),
     *                     @OA\Property(
     *                         property="method",
     *                         type="string",
     *                         description="method",
     *                         example = "deliveryManLogin"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="message",
     *                         example = "ok"
     *                     )
     *                 )
     *             )
     *         }
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="請求格式錯誤"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="認證錯誤"
     *      )
     * )
     */
    public function deliveryManLogout(Request $request)
    {
        $user = $request->user();

        if ($user != null) {
            $request->user()->tokens()->where('name', $request->device_name)->delete();
            $data = [
                "status" => 200,
                "method" => "deliveryManLogout",
                "message" => "success"
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "status" => 403,
                "method" => "deliveryManLogout",
                "message" => "user not found"
            ];
            return response()->json($data, 403);
        }
    }

    public function customerCreatePasswordResetRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = Customer::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'message' => 'We have e-mailed your password reset link!'
            ], 200);
        $passwordReset = CustomerPasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
            ]
        );
        if ($user && $passwordReset) {
            $user->sendPasswordResetNotification($passwordReset->token);
            return response()->json([
                'status' => 200,
                'method' => "customerCreatePasswordResetRequest",
                'message' => 'password reset link e-mailed'
            ], 200);
        }


    }

    public function deliveryManCreatePasswordResetRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = DeliveryMan::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'message' => 'We have e-mailed your password reset link!'
            ], 200);
        $passwordReset = DeliveryManPasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
            ]
        );
        if ($user && $passwordReset) {
            $user->sendPasswordResetNotification($passwordReset->token);
            return response()->json([
                'status' => 200,
                'method' => "deliveryManCreatePasswordResetRequest",
                'message' => 'password reset link e-mailed'
            ], 200);
        }


    }

    public function customerCheckPasswordResetRequestToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);
        $passwordReset = CustomerPasswordReset::where('token', $request->token)->first();
        if (!$passwordReset){
            return response()->json([
                'status' => 200,
                'method' => 'customerCheckPasswordResetRequestToken',
                'message' => 'This password reset token is invalid'
            ], 404);
        }else if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'status' => 403,
                'method' => 'customerCheckPasswordResetRequestToken',
                'message' => 'This password reset token is expired'
            ], 403);
        }else{
            return response()->json([
                'status' => 200,
                'method' => 'customerCheckPasswordResetRequestToken',
                'message' => 'token is valid'
            ], 200);
        }
    }

    public function deliveryManCheckPasswordResetRequestToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);
        $passwordReset = DeliveryManPasswordReset::where('token', $request->token)->first();
        if (!$passwordReset){
            return response()->json([
                'status' => 200,
                'method' => 'deliveryManCheckPasswordResetRequestToken',
                'message' => 'This password reset token is invalid'
            ], 404);
        }else if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'status' => 403,
                'method' => 'deliveryManCheckPasswordResetRequestToken',
                'message' => 'This password reset token is expired'
            ], 403);
        }else{
            return response()->json([
                'status' => 200,
                'method' => 'deliveryManCheckPasswordResetRequestToken',
                'message' => 'token is valid'
            ], 200);
        }
    }

    public function customerPasswordReset(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'token' => 'required'
        ]);

        $passwordReset = CustomerPasswordReset::where('token', $request->token)->first();

        if (!$passwordReset){
            return response()->json([
                'status' => 200,
                'method' => 'customerPasswordReset',
                'message' => 'This password reset token is invalid'
            ], 404);
        }else if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'status' => 403,
                'method' => 'customerPasswordReset',
                'message' => 'This password reset token is expired'
            ], 403);
        }else{
            $user = Customer::where('email', $passwordReset->email)->first();
            $user->password = bcrypt($request->password);
            $user->save();
            $passwordReset->delete();
            $user->notify(new PasswordResetSuccess($passwordReset));
            return response()->json([
                'status' => 200,
                'method' => 'customerPasswordReset',
                'message' => 'password reset success'
            ], 200);
        }
    }

    public function deliveryManPasswordReset(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'token' => 'required'
        ]);

        $passwordReset = DeliveryManPasswordReset::where('token', $request->token)->first();

        if (!$passwordReset){
            return response()->json([
                'status' => 200,
                'method' => 'customerPasswordReset',
                'message' => 'This password reset token is invalid'
            ], 404);
        }else if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'status' => 403,
                'method' => 'customerPasswordReset',
                'message' => 'This password reset token is expired'
            ], 403);
        }else{
            $user = DeliveryMan::where('email', $passwordReset->email)->first();
            $user->password = bcrypt($request->password);
            $user->save();
            $passwordReset->delete();
            $user->notify(new PasswordResetSuccess($passwordReset));
            return response()->json([
                'status' => 200,
                'method' => 'deliveryManPasswordReset',
                'message' => 'password reset success'
            ], 200);
        }
    }

}

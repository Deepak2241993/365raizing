<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\LoginDetails;
use App\Models\AdminTask;
use App\Models\StaffTask;
use App\Models\Brand;
use App\Models\Company;
use App\Mail\MailMytask;
use App\Models\Employee;
use App\Models\EmployeeTask;
use App\Models\VendorTaskAssign;
use App\Models\vendor;
use App\Models\Holiday;
use App\Models\EmpLeave;
use Redirect;
use Auth;
use Session;
use Validator;
use App\Mail\AdminMail;
use Mail;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Hash;
class ApiController extends Controller
{
/**
 * @OA\Post(
 *     path="/login_alluser",
 *     summary="Login and get an API token",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123"),
 *             @OA\Property(property="type", type="string", format="text", example="master_admin")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful login",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="error", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Login successfully"),
 *             @OA\Property(property="user", type="object", example={"id":1,"name":"User","email":"user@example.com"})
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="error", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Invalid login credentials")
 *         )
 *     )
 * )
 */
public function AuthLogin(Request $request) {
    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required|min:8',
        'type' => 'required'
    ]);

    $credentials = $request->only('email', 'password');
    $credentials['type'] = $request->input('type');
    $credentials['status'] = 1;

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $data = [
            'uemail' => $request->email,
            'ip' => $request->ip(),
            'login_time' => now()->format('H:i:s'),
            'login_date' => now()->format('m-d-Y'),
            'current_status' => 1
        ];

        $location = Location::get($data['ip']);
        if ($location) {
            $data['latitude'] = $location->latitude;
            $data['longitude'] = $location->longitude;
        }

        // Log login details, but avoid using sessions
        LoginDetails::create($data);

        // Example of how to generate a response without sessions
        $response = [
            'success' => true,
            'error' => false,
            'message' => 'Login successfully',
            'user' => $user
        ];

        return response()->json($response);
    } else {
        return response()->json([
            'success' => false,
            'error' => true,
            'message' => 'Invalid email or password.'
        ], 401);
    }
}




}



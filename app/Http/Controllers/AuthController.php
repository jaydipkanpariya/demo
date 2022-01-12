<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'auanaothorized']]);
    }
    public function anaothorized()
    {
        return response()->json(['message' => 'Auaothorized']);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'customer_email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $myTTL = 30; //minutes

        auth()->factory()->setTTL($myTTL);
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized , Inavlid Credentials '], 401);
        }
        $user=auth()->user();
        $user->customer_session_token=$token;
        $user->customer_session_token_expiry= Carbon::now()->addMinutes($myTTL);
        $user->save();
        return $this->createNewToken($token);
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

   
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
       
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
          //  'user' => auth()->user()
        ]);
    }

}
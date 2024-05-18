<?php
namespace App\Http\Controllers\api;

use App\Http\Requests\ForgotPassword\ResetPasswordRequest;
use App\Models\Event\Event;
use App\Models\User\MobileUser;
use App\Services\api\UserService;
use App\Traits\FileStorageTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use FileStorageTrait ;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile()
    {
        $user = $this->userService->getUserById(auth()->id());

        if (!$user) {
            return response()->json([
                'status' => true
                , 'message' => 'User not found'
            ]);
        }

        return response()->json(['status' => true
            ,'user' => $user]);
    }

    public function GetUser($id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['status' => true
                ,'message' => 'User not found']);
        }

        return response()->json(['status' => true
            ,'user' => $user]);
    }

    public function update(Request $request)
    {
        $updatedUser = $this->userService->updateUser($request->all());

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'user' => $updatedUser
        ]);
    }

    public function destroy()
    {
        $this->userService->deleteUser();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->userService->resetUserPassword($request->old_password, $request->new_password);

        if (isset($result['error'])) {
            return response()->json(['status' => false,
                'message' => 'password is incorrect'
            ]);
        }

        return response()->json(['status' => true
                ,$result]);
    }

    public function change_type()
    {
        $user = Auth::user();

        if ($user->type == 'normal') {
            $user->update(
                ['type' => 'private']
            ) ;
            return response()->json([
                'status' => true ,
                'message' =>'Changed Successfully (Private)'
            ],200) ;
        }else{
            $user->update(
                ['type' => 'normal']
            ) ;

            return response()->json([
                'status' => true ,
                'message' =>'Changed Successfully (Public)'
            ],200) ;
        }
    }

    public function searchFriend(Request $request)
    {
        $result = MobileUser::where(function ($query) use ($request) {
            $query->where('first_name', 'like', '%' . $request['Search'] . '%')
                ->orWhere('last_name', 'like', '%' . $request['Search'] . '%');
        })->where('is_verified', true)->paginate(4);

        return response()->json([
            'status' => true ,
            'result' => $result
        ]);
    }
}

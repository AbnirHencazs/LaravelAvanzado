<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserTokenController extends Controller
{
    public function __invoke( Request $request )
    {
        //Como este controlador solo tendra un metodo por eso lo llamamos __invoke
        //tambien llamado sigma action controllers
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);
        
        //Obtenemos el usuario que coincida con el email del request
        $user = User::where( 'email', $request->get('email') )->first();
        
        //Validamos la contraseña
        if( isset($user) ){
            if( !Hash::check( $request->password, $user->password ) ){
                throw ValidationException::withMessages([
                    'password' => 'Acceso incorrecto'
                ]);
            }
        }else{
            throw ValidationException::withMessages([
                'email' => 'El email no existe o es incorrecto'
            ]);
        }
        
        return response()->json([
            'token' => $user->createToken( $request->device_name )->plainTextToken
        ]);

    }
}

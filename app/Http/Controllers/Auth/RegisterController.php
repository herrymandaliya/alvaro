<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Password;
use DB;
 
// phpinfo(); die;/
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    public function showRegistrationForm()
    {
        $country = DB::table('countries')->get();
        return view('auth.register',['country' => $country]);
    }

    public function create(Request $request )
    { 

        
        $users  = DB::table('users')->where('email',$request['email'])->first();
        if ($users === null) {
            $user = User::create([
                'name' => $request['name'],
                'surname'=> $request['surname'],
                'id_number'=> $request['id_number'],
                'local'=> $request['local'],
                'cell_phone'=> $request['cell_phone'],
                'whatsapp_number'=> $request['whatsapp_number'],
                'email' => $request['email'],
                'birth_date'=> $request['birth_date'],
                'birth_place'=> $request['birth_place'],
                'street'=> $request['street'],
                'no'=> $request['no'],
                'sector'=> $request['sector'],
                'municipality'=> $request['municipality'],
                'circumscription'=> $request['circumscription'],
                'neighborhood'=> $request['neighborhood'],
                'urbanization'=> $request['urbanization'],
                'electoral_college'=> $request['electoral_college'],
                'college_location'=> $request['college_location'],
                'unit_of_change'=> $request['unit_of_change'],
                'postcode'=>$request['postcode'],
                'city'=> $request['city'],
                'province'=> $request['province'],
                'country'=> $request['country'],
                'housing_type'=> $request['housing_type'],
                'housing_condition'=> $request['housing_condition'],
                'monthly_fee'=> $request['monthly_fee'],
                'national'=>$request['national'],
                'regional'=> $request['regional'],
                'provincial'=> $request['provincial'],
                'municipal'=> $request['municipal'],
                'distrital'=> $request['distrital'],
                'change_unit'=> $request['change_unit'],
                'study_level'=> $request['study_level'],
                'profession'=> $request['profession'],
                'institution_name'=> $request['institution_name'],
                'language'=> $request['language'],
                'employment_status'=> $request['employment_status'],
                'working_company_name'=> $request['working_company_name'],
                'position_held_by'=> $request['position_held_by'],
                'tlf_company'=>$request['tlf_company'],
                'belongs_company_name'=> $request['belongs_company_name'],
                'city_and_address'=> $request['city_and_address'],
                'family_relation'=> $request['family_relation'],
                'member_name'=> $request['member_name'],
            
            ]);
            if( $request['photo'] ) {
                $store_csv =$request['photo'];
                $name = $store_csv->getClientOriginalName();
                $file_name = pathinfo($name, PATHINFO_FILENAME);
                $store_csv->move(public_path().'/uploads/avatar/'.$user->id.'/', $name ); 
                $user->photo =('/uploads/avatar').'/'.$user->id.'/'.$name ;
                $user->save();
            }
            Password::broker()->createToken($user);
            if($user){
                $token = Password::broker()->createToken($user);
                \Mail::to( $request['email'])->send(new \App\Mail\SendTestMail($user, $token));
            } 
            // return Redirect::to('/register'); 
            return redirect()->route('register')->with('success', 'Email successfully Send. You can Create Password !');
        }else{
            return redirect()->route('register')->with('error', 'Email already Exist!');
        }
    }
    public function createPassword(Request $request){
             
        $data = $request->all();
            //dd($data['email'],$request->get('email'));
            $result = DB::table('password_resets')->where('email',$data['email'])->first();

            if($result!=""){

            } else {
                abort(403);
            }
        return view('auth.passwords.create-password');
    }

    public function generatePassword(Request $request){

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
      
        ]);
            
        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where('email', $request->email)->delete();
            
        return Redirect::to('/register'); 
    }
}

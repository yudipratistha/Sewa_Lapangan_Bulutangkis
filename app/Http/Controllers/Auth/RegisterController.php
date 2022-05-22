<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function createPemilikLapangan(Request $request){
        $this->validate($request,[
            'nama_lapangan_pemilik_lapangan' => 'required',
            'nama_pemilik_lapangan' => 'required',
            'email_pemilik_lapangan' => 'required, unique:users',
            'password_pemilik_lapangan' => 'required',
            'nomor_telepon_pemilik_lapangan' => 'required',
            'lapangan_buka_dari_hari' => 'required',
            'lapangan_buka_sampai_hari' => 'required',
            'lapangan_buka_dari_jam' => 'required',
            'lapangan_buka_sampai_jam' => 'required',
            'lat_alamat_pemilik_lapangan' => 'required',
            'lng_alamat_pemilik_lapangan' => 'required',
            'alamat_tertulis_pemilik_lapangan' => 'required',
        ]);

        $user = new User;

        $user->name = $request->nama_pemilik_lapangan;
        $user->email = $request->email_pemilik_lapangan;
        $user->password = Hash::make($request->password_pemilik_lapangan);
        $user->nomor_telepon = $request->nomor_telepon_pemilik_lapangan;
        $user->user_status = 2;
        $user->save();

        $lapangan = new Lapangan;
        $lapangan->nama_lapangan = $request->nama_lapangan_pemilik_lapangan;
        $lapangan->alamat_lapangan = $request->alamat_tertulis_pemilik_lapangan;
        $lapangan->id_pemilik = $user->id;
        $lapangan->buka_dari_hari = $request->lapangan_buka_dari_hari;
        $lapangan->buka_sampai_hari = $request->lapangan_buka_sampai_hari;
        $lapangan->buka_dari_jam = $request->lapangan_buka_dari_jam;
        $lapangan->buka_sampai_jam = $request->lapangan_buka_sampai_jam;
        $lapangan->titik_koordinat_lat = $request->lat_alamat_pemilik_lapangan;
        $lapangan->titik_koordinat_lng = $request->lng_alamat_pemilik_lapangan;
        $lapangan->court = $request->jumlah_court_pemilik_lapangan;
        $lapangan->save();
        
        return response()->json($data);
    }
}

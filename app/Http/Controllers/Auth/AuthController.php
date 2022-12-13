<?php

namespace App\Http\Controllers\auth;

use App\Models\User;

use App\Models\Courts;
use App\Models\Lapangan;
use App\Models\StatusCourt;
use Illuminate\Http\Request;

use App\Models\StatusLapangan;
use App\Models\TipeStatusCourt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\StatusVerifikasiLapangan;
use Illuminate\Foundation\Auth\RegistersUsers;

class AuthController extends Controller
{
    public function loginForm(){
        return view('auth.login');
    }

    public function registrationForm(){
        return view('auth.register');
    }

    public function loginPemilikLapangan(Request $request){
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))){
            if (auth()->user()->RolePengguna->first()->role_tag == 'administrator') {
                return redirect()->route('administrator.dashboard');
            }if (auth()->user()->RolePengguna->first()->role_tag == 'field_owner') {
                return redirect()->route('pemilikLapangan.dashboard');
            }else if(auth()->user()->RolePengguna->first()->role_tag == 'tenant_user') {
                return redirect()->route('penyewaLapangan.dashboard');
            }
        }else{
            return redirect()->route('login')
                ->with('error','Email-Address And Password Are Wrong.');
        }
    }

    public function createPemilikLapangan(Request $request){
        $request->validate([
            'nama_lapangan_pemilik_lapangan' => 'required',
            'nama_pemilik_lapangan' => 'required',
            'email_pemilik_lapangan' => 'required|unique:tb_pengguna,email',
            'password_pemilik_lapangan' => 'required',
            'nomor_telepon_pemilik_lapangan' => 'required',
            'harga_lapangan_per_jam' => 'required',
            'jumlah_court_pemilik_lapangan' => 'required',
            'lapangan_buka_dari_hari' => 'required',
            'lapangan_buka_sampai_hari' => 'required',
            'lapangan_buka_dari_jam' => 'required',
            'lapangan_buka_sampai_jam' => 'required',
            'lat_alamat_pemilik_lapangan' => 'required',
            'lng_alamat_pemilik_lapangan' => 'required',
            'alamat_tertulis_pemilik_lapangan' => 'required',
        ]);
        // $foto_lapangan_1 = $request->foto_lapangan_1;
        // $request->foto_lapangan_1->move(public_path('images'), "test.jpg");

        $user = new User;

        $user->name = $request->nama_pemilik_lapangan;
        $user->email = $request->email_pemilik_lapangan;
        $user->password = Hash::make($request->password_pemilik_lapangan);
        $user->nomor_telepon = $request->nomor_telepon_pemilik_lapangan;
        $user->id_role_pengguna = 3;
        $user->save();

        $lapangan = new Lapangan;
        $lapangan->nama_lapangan = $request->nama_lapangan_pemilik_lapangan;
        $lapangan->alamat_lapangan = $request->alamat_tertulis_pemilik_lapangan;
        $lapangan->id_pengguna = $user->id;
        $lapangan->buka_dari_hari = $request->lapangan_buka_dari_hari;
        $lapangan->buka_sampai_hari = $request->lapangan_buka_sampai_hari;
        $lapangan->buka_dari_jam = $request->lapangan_buka_dari_jam;
        $lapangan->buka_sampai_jam = $request->lapangan_buka_sampai_jam;
        $lapangan->titik_koordinat_lat = $request->lat_alamat_pemilik_lapangan;
        $lapangan->titik_koordinat_lng = $request->lng_alamat_pemilik_lapangan;
        $lapangan->harga_per_jam = $request->harga_lapangan_per_jam;
        $lapangan->jumlah_court = $request->jumlah_court_pemilik_lapangan;

        if ($request->hasFile('foto_lapangan_1')) {
            $userPath = 'file/'.$user->id.'/';
            Storage::disk('public')->makeDirectory($userPath);
            $fotoLapanganPath = $userPath.$request->nama_lapangan_pemilik_lapangan;
            Storage::disk('public')->makeDirectory($fotoLapanganPath);
            $pathFotoLapangan_1 = $request->file('foto_lapangan_1')->storeAs(
                $fotoLapanganPath, "foto_lapangan_1.jpg", 'public'
            );
            $lapangan->foto_lapangan_1 = $pathFotoLapangan_1;
        }
        if ($request->hasFile('foto_lapangan_2')) {
            $userPath = 'file/'.$user->id.'/';
            Storage::disk('public')->makeDirectory($userPath);
            $fotoLapanganPath = $userPath.$request->nama_lapangan_pemilik_lapangan;
            Storage::disk('public')->makeDirectory($fotoLapanganPath);
            $pathFotoLapangan_2 = $request->file('foto_lapangan_2')->storeAs(
                $fotoLapanganPath, "foto_lapangan_2.jpg", 'public'
            );
            $lapangan->foto_lapangan_2 = $pathFotoLapangan_2;
        }
        if ($request->hasFile('foto_lapangan_3')) {
            $userPath = 'file/'.$user->id.'/';
            Storage::disk('public')->makeDirectory($userPath);
            $fotoLapanganPath = $userPath.$request->nama_lapangan_pemilik_lapangan;
            Storage::disk('public')->makeDirectory($fotoLapanganPath);
            $pathFotoLapangan_3 = $request->file('foto_lapangan_3')->storeAs(
                $fotoLapanganPath, "foto_lapangan_3.jpg", 'public'
            );
            $lapangan->foto_lapangan_3 = $pathFotoLapangan_3;
        }

        $lapangan->save();

        $dataCourtArr = array();

        for($courtCount= 0; $courtCount<$request->jumlah_court_pemilik_lapangan; $courtCount++){
            array_push($dataCourtArr, array(
                'id_lapangan' => $lapangan->id,
                'nomor_court' => $courtCount+1,
                'status_court' => 1
            ));
        }
        Courts::insert($dataCourtArr);

        $dataCourts = Courts::where('tb_courts.id_lapangan', $lapangan->id)->get();

        $dataTipeStatusCourt = TipeStatusCourt::where('tb_tipe_status_court.tipe_status', 'Tersedia')->first();


        $statusCourtArr = array();
        $lapanganBuka = strtotime($request->lapangan_buka_dari_jam);
        $lapanganTutup = strtotime($request->lapangan_buka_sampai_jam);

        foreach($dataCourts as $dataCourt){
            for($jam=$lapanganBuka; $jam<$lapanganTutup; $jam+=3600){
                array_push($statusCourtArr, array(
                    'id_court' => $dataCourt->id,
                    'id_tipe_status_court' => $dataTipeStatusCourt->id,
                    'jam_status_berlaku_dari' => date('H:i', $jam),
                    'jam_status_berlaku_sampai' => date('H:i', $jam + 3600)
                ));
            }
        }

        StatusCourt::insert($statusCourtArr);

        $statusLapanganVerif = new StatusVerifikasiLapangan();
        $statusLapanganVerif->id_lapangan = $lapangan->id;
        $statusLapanganVerif->status_verifikasi = 'belum diverifikasi';
        $statusLapanganVerif->save();

        return redirect()->route('login');
    }

    public function createPenyewaLapangan(Request $request){
        $request->validate([
            'nama_penyewa_lapangan' => 'required',
            'email_penyewa_lapangan' => 'required|unique:tb_pengguna,email',
            'password_penyewa_lapangan' => 'required',
            'nomor_telepon_penyewa_lapangan' => 'required'
        ]);

        $user = new User;

        $user->name = $request->nama_penyewa_lapangan;
        $user->email = $request->email_penyewa_lapangan;
        $user->id_role_pengguna = 4;
        $user->password = Hash::make($request->password_penyewa_lapangan);
        $user->nomor_telepon = $request->nomor_telepon_penyewa_lapangan;
        $user->save();

        return redirect()->route('login');
    }

    public function logout() {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }

    public function updatePassword(Request $request){
        $request->validate([
            'new_password' => 'required|confirmed',
        ],
        [
            'new_password.required' => 'Inputan tidak boleh kosong!',
            'new_password.confirmed' => 'Ulangi password tidak cocok!',
        ]);

        // if(!Hash::check($request->old_password, auth()->user()->password)){
        //     return back()->with("error", "Old Password Doesn't match!");
        // }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with("status", "Password changed successfully!");
    }

}

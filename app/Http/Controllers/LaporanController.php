<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\booking_gym;
use App\Models\taktivasi;
use App\Models\tdeposit_reguler;
use App\Models\tdeposit_kelas;
use App\Models\kelas;
use App\Models\instruktur;
use App\Models\jadwal_harian;
use App\Models\booking_kelas;
use App\Models\presensi_instruktur;
use Illuminate\Support\Collection;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
class LaporanController extends Controller
{
    public function LaporanGym()
    {
        $laporan = collect([]);
        App::setLocale('id');
        $now = Carbon::now();
        $nowIndonesia= $now->setTimezone('Asia/Jakarta');
        $nowFormatted= $nowIndonesia->translatedFormat('Y-F-d');
       
        $tanggal_cetak = $now->translatedFormat('d');
        $bulan = $now->translatedFormat('F');
        $tahun = $now->translatedFormat('Y');
        $tanggal = $tanggal_cetak." ".$bulan." ".$tahun;
        // $tanggalangka = 

        // $bulan = Carbon::create(null, substr($now, 5,2), null)->format('F');
        
        $tahun = substr($now,0,4);

        $akhir = Carbon::now();
        $akhir->endOfMonth();
        
        $akhir = substr($akhir, 8,2);

        $temp = (int)$akhir;
        
        for($i = 0;$i<$temp;$i++){
            $tgl = Carbon::now();
            $tgl->startOfMonth(); 
            $tgl->addDays($i);
            $presensi = booking_gym::where('tanggal','=', $tgl)->where('status','=', 1)->get();
            $count = count($presensi);
            $storeData['id'] = $i;
            $storeData['jumlah'] = $count;
            $storeData['tanggal'] = Carbon::create($tgl)->format("d M Y") ;
            
            $laporan->add($storeData);
        }
        $tgl = Carbon::now()->format('d M Y');

        if(count($laporan) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $laporan,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal_cetak' => $tanggal
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => $temp
        ], 400);   

    }

    public function LaporanKelas(){
        $now = Carbon::now();
        $bulan = substr($now, 5,2);

        $sekarang = Carbon::now();
        $sekarangIndo = $sekarang->setTimezone('Asia/Jakarta');
        
        $sekarangFormated = $sekarangIndo->translatedFormat('Y-F-d');

        $tgl = $sekarang->translatedFormat('d');
        $bulanindo = $sekarang->translatedFormat('F');
        $tahun = $sekarang->translatedFormat('Y');

        $tanggal_cetak = $tgl.' '.$bulanindo.' '.$tahun;

        $laporan = collect([]);

        $kelas = kelas::all();
        $instruktur = instruktur::all();

        foreach($kelas as $item1){
            foreach($instruktur as $item2){
                $id1 = $item1->id;
                $id2 = $item2->id;
                // return response([
                //     'message' => 'ada',
                //     'data' => $id1,
                //     'data2' => $id2
                // ], 200);

                $jadwalHarian = jadwal_harian::whereMonth('tanggal', '=', $bulan)
                                            ->where('id_kelas','=',$id1)
                                            ->where('id_instruktur','=',$id2)
                                            ->get();
                // return response([
                //     'message' => 'ada',
                //     'data' => $jadwalHarian
                // ], 200);                            
                if(count($jadwalHarian)>0){
                    $storeData['kelas'] = $item1->nama_kelas;
                    $storeData['instruktur'] = $item2->nama_instruktur;

                //     return response([
                //     'message' => 'ada',
                //     'data' => $storeData['kelas'],
                //     'ins' => $storeData['instruktur']
                // ], 200);  

                    $jumlahLibur = jadwal_harian::whereMonth('tanggal', $bulan)
                                                ->where('id_kelas','=', $item1->id)
                                                ->where('id_instruktur','=',$item2->id)
                                                ->where('status','=',0)
                                                ->get(); 
                    $storeData['jumlah_libur'] = count($jumlahLibur); 

                    $storeData['jumlah_peserta'] = 0;
                    foreach($jadwalHarian as $item3){
                        $jumlahPeserta = booking_kelas::where('id_jadwal_harian','=',$item3->id)
                                                    ->where('status','=',1)
                                                    ->get();
                        $storeData['jumlah_peserta'] = $storeData['jumlah_peserta'] + count($jumlahPeserta);
                    }
                    $laporan->add($storeData);                   
                }
                
            }
        }

        if(!is_null($laporan)){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $laporan,
                'bulan' => $bulanindo,
                'tahun' => $tahun,
                'tgl_cetak' => $tanggal_cetak
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);   
    }

        
    public function LaporanInstruktur(){
        $sekarang = Carbon::now();
        $sekarangIndo = $sekarang->setTimezone('Asia/Jakarta');
        
        $sekarangFormated = $sekarangIndo->translatedFormat('Y-F-d');

        $tgl = $sekarang->translatedFormat('d');
        $bulanindo = $sekarang->translatedFormat('F');
        $tahun = $sekarang->translatedFormat('Y');

        $tanggal_cetak = $tgl.' '.$bulanindo.' '.$tahun;
        $bulan = Carbon::now()->format('m');

        $laporan = collect([]);
        $instruktur = instruktur::all();
        $data['Jumlah_Hadir'] = 0;
        $jadwalHarian[] = null;

        foreach($instruktur as $dataInstruktur){
            $data['Nama_Instruktur'] = $dataInstruktur->nama_instruktur;
            $jadwalHarian = DB::select(
                'SELECT * from jadwal_harian
                where id_instruktur = "'.$dataInstruktur['id'].'"
                AND MONTH(tanggal) = "'.$bulan.'";'
            );

            // return response([
            //     'message' => 'Retrieve All Success',
            //     'data' => $jadwalHarian
            // ], 200);

            if(count($jadwalHarian) > 1){
                foreach($jadwalHarian as $dataJadwalHarian){
                    $jumlahHadir = DB::select(
                        'SELECT * from presensi_instruktur
                        where id_jadwal_harian = "'.$dataJadwalHarian->id.'"
                        AND MONTH(tgl_presensi) = "'.$bulan.'";'
                    );
                    $data['Jumlah_Hadir'] = $data['Jumlah_Hadir'] + count($jumlahHadir);
                }
            }else{
                $jumlahHadir = DB::select(
                    'SELECT * from presensi_instruktur
                    where id_jadwal_harian = "'.$jadwalHarian[0]->id.'"
                    AND MONTH(tgl_presensi) = "'.$bulan.'";'
                );
                $data['Jumlah_Hadir'] = count($jumlahHadir);
            }

            $jumlahLibur = DB::select(
                'SELECT * from jadwal_harian
                where id_instruktur = "'.$dataInstruktur['id'].'"
                AND status = 0
                AND MONTH(tanggal) = "'.$bulan.'";'
            );

            $data['Jumlah_Libur'] = count($jumlahLibur);

            $keterlambatan = $dataInstruktur['jumlah_terlambat'];
            $detik = strtotime($keterlambatan) - strtotime('00:00:00');
            $data['Waktu_Terlambat'] = $detik;
            
            $laporan->add($data);
        }

        if (!is_null($laporan)) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $laporan,
                'bulan' => $bulanindo,
                'tahun' => $tahun,
                'tgl_cetak' => $tanggal_cetak
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }

    public function LaporanPendapatan(){

        $laporan = collect([]);
        $sekarang = Carbon::now();
        $sekarangIndo = $sekarang->setTimezone('Asia/Jakarta');
        
        $sekarangFormated = $sekarangIndo->translatedFormat('Y-F-d');

        $tgl = $sekarang->translatedFormat('d');
        $bulanindo = $sekarang->translatedFormat('F');
        $tahun = $sekarang->translatedFormat('Y');

        $tanggal_cetak = $tgl.' '.$bulanindo.' '.$tahun;
        
        for($i = 1;$i<13;$i++){
            $bulan = Carbon::create(null, $i, 1)->format('F');
            $storeData['bulan'] = $bulan;
            $taktivasi = taktivasi::whereMonth('tanggal_transaksi',$i)->get();
            $tdeposit_reguler = tdeposit_reguler::whereMonth('tanggal_transaksi',$i)->get();
            $storeData['tdeposit_reguler'] = 0;
            foreach($tdeposit_reguler as $item){
                $storeData['tdeposit_reguler'] = $storeData['tdeposit_reguler'] + $item->jumlah_deposit;
            }
            $storeData['tdeposit_kelas'] = 0;
            $tdeposit_kelas = tdeposit_kelas::whereMonth('tanggal_transaksi',$i)->get();
            foreach($tdeposit_kelas as $item){
                $storeData['tdeposit_kelas'] = $storeData['tdeposit_kelas'] + $item->total_harga;
            }
            $storeData['taktivasi'] = count($taktivasi) * 3000000;
            $storeData['total_deposit'] = $storeData['tdeposit_reguler'] + $storeData['tdeposit_kelas'];
            $storeData['total'] = $storeData['tdeposit_reguler'] + $storeData['tdeposit_kelas'] + $storeData['taktivasi'];
            $laporan->add($storeData);
        }

        $storeData['totalSemua'] = 0;

        foreach($laporan as $item){
            $storeData['totalSemua'] = $item['total'] + $storeData['totalSemua'];
        }
        $now = Carbon::now()->format('Y-m-d');
        $tgl = Carbon::now()->format('Y-F-d');
        $storeData['tahun'] = substr($now,0,4);
        $storeData['tanggal'] = $tgl;

        $laporan['total_semua'] = $storeData['totalSemua'];
        $laporan['tahun'] = $storeData['tahun'];
        $laporan['tanggal'] = $storeData['tanggal'];
        $sekarang = Carbon::now();
        $bulan = $sekarang->format('F');
        
        if(!is_null($laporan)){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $laporan,
                'bulan' => $bulan,
                'tgl_cetak' => $tanggal_cetak
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }

    public function cetakGym()
    {
 
    	$pdf = PDF::loadview('laporan_gym');
    	return $pdf->download('laporan-gym-pdf');
    }
}
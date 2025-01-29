<?php

use App\Http\Controllers\{
    AktivaController,
    AuthController,
    BankOrKasController,
    GroupController,
    LaporanController,
    MasterPerusahaanController,
    ModalController,
    PerkiraanController,
    PostingController,
    SetPemakaiController,
    ShareController,
    TestController,
};
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'loginView'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //Menu Berkas
    Route::prefix('berkas')->name('berkas.')->group(function () {
        //perusahaan
        Route::get('perusahaan', [MasterPerusahaanController::class, 'index'])->name('perusahaan.index')->middleware('policy:HASACCESS,0003');
        Route::post('perusahaan/{type}', [MasterPerusahaanController::class, 'update'])->name('perusahaan.update')->where('type', 'perusahaan|nomor')->middleware('policy:ISKOREKSI,0003');

        //set pemakai
        Route::get('set-pemakai', [SetPemakaiController::class, 'index'])->name('set-pemakai.index')->middleware('policy:HASACCESS,0004');
        Route::put('set-pemakai/{USERID}', [SetPemakaiController::class, 'update'])->name('set-pemakai.update')->middleware('policy:ISKOREKSI,0004');
        Route::put('set-pemakai-coa/{USERID}', [PerkiraanController::class, 'updateCOA'])->name('set-pemakai-coa.update')->middleware('policy:ISKOREKSI,0004');
        Route::post('set-pemakai-karyawan', [SetPemakaiController::class, 'createKaryawan'])->name('set-pemakai-karyawan.create')->middleware('policy:ISTAMBAH,0004');
        Route::put('set-pemakai-karyawan/{USERID}', [SetPemakaiController::class, 'updateKaryawan'])->name('set-pemakai-karyawan.update')->middleware('policy:ISKOREKSI,0004');
        Route::delete('set-pemakai-karyawan/{USERID}', [SetPemakaiController::class, 'deleteKaryawan'])->name('set-pemakai-karyawan.delete')->middleware('policy:ISHAPUS,0004');

        Route::get('get-periode', [ShareController::class, 'getPeriode'])->name('get-periode')->middleware('policy:HASACCESS,0001');
        Route::put('set-periode', [ShareController::class, 'setPeriode'])->name('set-periode')->middleware('policy:ISKOREKSI,0001');
    });

    //Menu Master Data
    Route::prefix('master-data')->name('master-data.')->group(function () {
        //master Accounting
        Route::prefix('master-accounting')->name('master-accounting.')->group(function () {
            //master perkiraan
            Route::resource('perkiraan', PerkiraanController::class)->names('perkiraan')->middleware('policy:HASACCESS,01001001');
            Route::post('get-saldo-awal/{perkiraan}', [PerkiraanController::class, 'getSaldoAwal'])->name('get-saldo-awal')->middleware('policy:HASACCESS,01001001');
            Route::post('set-saldo-awal/{perkiraan}', [PerkiraanController::class, 'setSaldoAwal'])->name('set-saldo-awal.update')->middleware('policy:ISKOREKSI,01001001');
            Route::post('get-budget/{perkiraan}', [PerkiraanController::class, 'getBudget'])->name('get-budget')->middleware('policy:HASACCESS,01001001');
            Route::post('set-budget/{perkiraan}', [PerkiraanController::class, 'setBudget'])->name('set-budget.update')->middleware('policy:ISKOREKSI,01001001');

            //master aktiva
            Route::resource('aktiva', AktivaController::class)->names('aktiva')->middleware('policy:HASACCESS,01001002')->only(['index', 'store']);
            Route::put('aktiva/{aktiva}/{devisi}', [AktivaController::class, 'update'])->name('aktiva.update');
            Route::delete('aktiva/{aktiva}/{devisi}', [AktivaController::class, 'destroy'])->name('aktiva.destroy');
            Route::get('aktiva/{aktiva}/{devisi}', [AktivaController::class, 'getSaldoAwal'])->name('aktiva.saldo-awal');
            Route::post('aktiva/{aktiva}/{devisi}', [AktivaController::class, 'setSaldoAwal']);

            //master posting
            Route::get('posting', [PostingController::class, 'posting'])->name('posting.index')->middleware('policy:HASACCESS,01001008');
            Route::get('posting/{posting}', [PostingController::class, 'getTable'])->name('posting.getTable');
            Route::post('posting/{posting}', [PostingController::class, 'storePosting']);
            Route::delete('posting/{posting}/{id}', [PostingController::class, 'deletePosting'])->name('posting.deletePosting');
        });
        //master Bahan dan Barang
        Route::prefix('master-bahan-barang')->name('master-bahan-dan-barang.')->group(function () {
            //master Goup
            Route::resource('group', GroupController::class)->names('group')->middleware('policy:HASACCESS,01002015')->only(['index', 'destroy', 'store', 'update']);
            // sub group
            Route::get('{group}/sub', [GroupController::class, 'getSubGroup'])->name('sub-group');
            Route::post('{group}/sub', [GroupController::class, 'storeSubGroup']);
            Route::post('{group}/sub/{subgroup}', [GroupController::class, 'updateSubGroup']);
            Route::delete('{group}/sub/{subgroup}/destroy', [GroupController::class, 'deleteSubGroup'])->name('sub-group.destroy');
            // departrmen sub group
            Route::get('{group}/sub/{subgroup}/departemen', [GroupController::class, 'getDepartemen'])->name('sub-group.departemen');
            Route::post('{group}/sub/{subgroup}/departemen', [GroupController::class, 'storeDepartemen']);
            Route::post('{group}/sub/{subgroup}/departemen/{KodeDepartemen}', [GroupController::class, 'updateDepartemen']);
            Route::delete('{group}/sub/{subgroup}/departemen/{KodeDepartemen}/destroy', [GroupController::class, 'deleteDepartemen'])->name('sub-group.departemen.destroy');
        });
    });

    Route::prefix('accounting')->name('accounting')->group(function () {
        // transaksi bank or kas
        Route::prefix('transaksi-bank-or-kas')->name('.bank-or-kas')->middleware('policy:HASACCESS,02001')->group(function () {
            Route::get('/', [BankOrKasController::class, 'index'])->name('.index');
            Route::post('/', [BankOrKasController::class, 'store']);
            Route::put('/', [BankOrKasController::class, 'update']);
            Route::delete('/', [BankOrKasController::class, 'delete'])->name('.delete');
            Route::post('/detail', [BankOrKasController::class, 'getKasBankDetailByNoBukti'])->name('.detail-kasbank');
            Route::get('/download-kasbank', [BankOrKasController::class, 'downloadKasBank']);
            Route::post('/get-nomor-bukti', [BankOrKasController::class, 'getNomorBukti']);
            Route::post('/get-data-hutang', [BankOrKasController::class, 'getDataHutang'])->name('.get-data-hutang');
            Route::post('/kas-bank-detail', [BankOrKasController::class, 'storeKasbank']);
            Route::put('/kas-bank-detail', [BankOrKasController::class, 'updateKasBank']);
            Route::delete('/kas-bank-detail', [BankOrKasController::class, 'deleteKasBank'])->name('.delete-kasbank');
            Route::post('/set-otorisasi', [BankOrKasController::class, 'setOtorisasi']);
            Route::post('/pelunasan-hutang', [BankOrKasController::class, 'pelunasanHutang'])->name('.pelunasan-hutang');
            Route::post('/hapus-pelunasan', [BankOrKasController::class, 'hapusPelunasan'])->name('.hapus-pelunasan');
        });
    });

    Route::prefix('POS')->name('pos')->group(function () {
        // Route::get('/')
    });

    //global
    Route::prefix('/')->group(function () {
        Route::put('ganti-password', [AuthController::class, 'gantiPassword'])->name('ganti-password');
        Route::get('get-karyawan-select', [ShareController::class, 'getKaryawanSelect']);
        Route::get('get-departemen-select', [ShareController::class, 'getDepartemenSelect']);
        Route::get('get-jabatan-select', [ShareController::class, 'getJabatanSelect']);
        Route::get('get-valas-select', [ShareController::class, 'getValasSelect']);
        Route::get('get-arus-kas-select', [ShareController::class, 'getArusKasSelect']);
        Route::get('get-arus-kas-det-select', [ShareController::class, 'getArusKasDetSelect']);
        Route::get('get-group-aktiva-select', [ShareController::class, 'getGroupAktivaSelect']);
        Route::get('get-devisi-select', [ShareController::class, 'getDevisiSelect']);
        Route::get('get-akumulasi-penyusutan-select', [ShareController::class, 'getAkumulasiPenyusutanSelect']);
        Route::get('get-biaya-select', [ShareController::class, 'getBiayaSelect']);
        Route::get('get-kelompok-kas-select', [ShareController::class, 'getKelompokKasSelect']);
        Route::get('get-kelompok-kas-bank-select', [ShareController::class, 'getKelompokKasOrBankSelect']);

        Route::get('get-user/{USERID}', [ShareController::class, 'getUser'])->name('get-user');
        Route::get('get-user-access/{USERID}', [ShareController::class, 'getUserAccess'])->name('get-user-access');

        Route::get('get-customer-hutang', [ShareController::class, 'getCustomerHutang'])->name('get-customer-hutang');
        // routing modal
        Route::post('get-modal', [ModalController::class, 'getModal'])->name('get-modal');
    });

    Route::prefix('/laporan-laporan')->name('laporan-laporan.')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'viewLaporan'])->name('view-laporan');
        Route::post('/laporan', [LaporanController::class, 'generateLaporan']);
        Route::get('/laporan-pdf', [LaporanController::class, 'generateLaporan'])->name('generate-laporan-pdf');
    });
});

Route::get('/tester-query', TestController::class);

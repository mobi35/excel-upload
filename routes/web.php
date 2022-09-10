<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Rap2hpoutre\FastExcel\FastExcel as FastExcelFastExcel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $collection = (new FastExcelFastExcel)->import('file.xlsx');

    return view('welcome', [
        'header' => array_keys($collection[0]),
        'main_header' => array_keys(User::first()?->toArray() ?? [])
    ] );
});
// });

Route::post('upload', function () { 
    $realName = request()->all()['file']->getClientOriginalName();
    request()->all()['file']->move('uploads', $realName );

    $currentHeader = json_decode(request()->all()['headers'], true);
    // dd( );
    (new FastExcelFastExcel())->import('uploads/'. $realName , function ($line) use($currentHeader) {
    
    return User::create([
        'name' => $line[array_values(collect($currentHeader)->filter(fn ($a) => array_key_first($a) === 'name' )->toArray())[0]['name']],
        'email' => $line[array_values(collect($currentHeader)->filter(fn ($a) => array_key_first($a) === 'email' )->toArray())[0]['email']],
        'password' => 'noval'
    ]);
    });

});

Route::post('excel', function () {
    $realName = request()->all()['file']->getClientOriginalName();
    request()->all()['file']->move('uploads', $realName );

    $users = User::select(['name', 'email'])->get();

    $collection = (new FastExcelFastExcel)->import('file.xlsx');
    return [
                'excel_header' => array_keys($collection[0]),
                'main_header' => Schema::getColumnListing('users')
                ];

    });


@php 
/** 
 * https://laravel.com/docs/8.x/envoy
 * 
 * $ envoy run <nome_task>
 */
@endphp

@setup    
echo "setup ..."
@endsetup

@servers(['localhost' => '127.0.0.1'])

@task('test', ['on' => ['localhost']])
./vendor/bin/phpunit 	
@endtask

@task('encrypt_db', ['on' => ['localhost']])
php artisan encryptable:encryptModel 'App\Models\User'
@endtask

@task('decrypt_db', ['on' => ['localhost']])
php artisan encryptable:decryptModel 'App\Models\User'
@endtask

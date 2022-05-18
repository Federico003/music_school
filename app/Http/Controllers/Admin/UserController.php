<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\Services\GoogleSheet;
use App\Services\GoogleCalendarEvent;
use App\Models\User;
use App\Exports\UsersExport;

use Carbon\Carbon;
use Auth;
use Validator;
use DB;
use PDF;
use Log;

class UserController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * Ritorna il contenuto della DataTable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JSON
     */
    public function list(Request $request)
    {    
        $users = User::all();
        
        return DataTables::of($users)
            ->addColumn('actions', function($user) {
                $buttons = '<span class="mr-1"><a href="user/'. $user->id .'/edit" id="'. $user->id .'" class="btn waves-effect btn-primary">Modifica</a></span>';
                $buttons .= '<span class="mr-1"><a href="user/'. $user->id .'/print" id="'. $user->id .'" class="btn waves-effect btn-default">Stampa</a></span>';
                
                if (Auth::user()->id != $user->id) {
                    $buttons .= '<span class="mr-1"><button id="'. $user->id .'" class="btn waves-effect btn-danger btn-elimina">Elimina</button></span>';
                }

                return $buttons;
            })
            ->editColumn('rolename', function($user) {
                return $user->roleName();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = User::$roles;

        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|string|unique:users|max:255',
                'password'  => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return redirect()->route('admin.user.create')->withErrors($validator)->withInput();
            }

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);

            $user = User::create($input);
            $user->assignRole($input['role']);
            
            return view('admin.user.index');   
        
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = User::$roles;

        if ($user) {
            return view('admin.user.edit', compact('user', 'roles'));
        } 

        return redirect()->route('admin.user.index')->withErrors("L' utente non esiste");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (! $user) {
                return redirect()->route('admin.user.index')->withErrors("L' utente non esiste");
            } 

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|string|max:255',
                'password' => 'nullable|sometimes|string|min:6',
            ]);

            if ($validator->fails()) {
                return redirect()->route('admin.user.edit', $id)->withErrors($validator)->withInput();
            }

            $input = $request->all();

            // Verifico se la password è da aggiornare
            if ($input['password'] && strlen($input['password']) > 0) {
                $input['password'] = Hash::make($input['password']);
        
            } else {
                unset($input['password']);
            }

            $user->update($input);
            $user->syncRoles($input['role']);

            return redirect()->route('admin.user.index');    

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (! $user) {
                return self::makeJsonResponseBadRequest('Utente non trovato');
            } 

            $user->delete();

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return self::makeJsonResponse([], 200, "Utente eliminato");
    }

    /**
     * Esporta la risorsa specificata su file Excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function export_excel() 
    {
        return Excel::download(new UsersExport, 'Utenti.xlsx');
    }

    /**
     * Esporta la risorsa specificata su Google Sheet.
     *
     * @return \Illuminate\Http\Response
     */
    public function export_gsheet() 
    {
        $users = User::all();
        $data = [array('Nome', 'Ruolo', 'Data di creazione')];

        foreach ($users as $user) {
            $data[] = array(
                $user->name,
                $user->roleName(),
                $user->created_at->format('Y-m-d'),
            );
        }
        
        try {
            $googleSheet = new GoogleSheet();
            $googleSheet->saveDataToSheet($data, true); // true -> overwrite

            $googleCalendarEvent = new GoogleCalendarEvent();
            $googleCalendarEvent->saveEvent("Esportazione completata", "Esportazione completata con successo", Carbon::now(), 30);

        } catch (Exception $e) {
            Log::info($e->getMessage());

            return redirect()->route('admin.user.index')->withErrors('L\'export non è andato a buon fine');   
        }    

        return redirect()->route('admin.user.index')->with('success', 'Export riuscito.');   
    }

    /**
     * Stampa una scheda informativa sull'utente.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id) 
    {
        /**
         * !!! POTREBBE ESSERE UTILE: https://codepen.io/rafaelcastrocouto/pen/LFAes 
         */
    
        try {
            $user = User::find($id);

            if (! $user) {
                return redirect()->route('admin.user.index')->withErrors("L'utente non esiste");
            } 
                
            $pdf = PDF::loadView('admin.user.pdf.show', compact('user'));

            return $pdf->download('User.pdf');

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }    
    }
}

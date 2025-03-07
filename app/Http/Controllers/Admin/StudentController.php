<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;


class StudentController extends Controller
{
    
    public function list(Request $request): JsonResponse
    {
        $query = User::query()->select('users.*')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
    
        // Se viene passato un ruolo, filtriamo gli utenti
        if ($request->has('role') && !empty($request->role)) {
            $query->where('roles.name', $request->role);
        }
    
        return DataTables::of($query)
            ->addColumn('actions', function ($user) {
                $buttons = '<span class="mr-1"><a href="'.$user->id.'" data-id="'.$user->id.'" class="btn btn-default waves-effect" title="Visualizza"><i class="material-icons">remove_red_eye</i></a></span>';
                $buttons .= '<span class="mr-1"><a href="'.$user->id.'/edit" data-id="'.$user->id.'" class="btn btn-primary waves-effect" title="Modifica"><i class="material-icons">edit</i></a></span>';
                $buttons .= '<span class="mr-1"><a href="'.$user->id.'/print" data-id="'.$user->id.'" class="btn btn-default waves-effect" title="Stampa"><i class="material-icons">print</i></a></span>';
                if (Auth::user()->id !== $user->id) {
                    $buttons .= '<span class="mr-1"><button id="'.$user->id.'" class="btn btn-danger btn-delete waves-effect" title="Elimina"><i class="material-icons">delete</i></button></span>';
                }
                return $buttons;
            })
            ->editColumn('rolename', function ($user) {
                return $user->roles->pluck('name')->implode(', '); // Mostra tutti i ruoli separati da virgola
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    
    
}
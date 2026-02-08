<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\JsonResponse;
use App\Models\State;
use App\Services\Serv_Copomex;

class StateController extends Controller
{
    public function index()
    {
        return view('states.index');
    }

    public function data(Request $request)
    {
        $query = State::query();

        return datatables()->of($query)
            ->addColumn('actions', function ($state) {
                $name_state = trim($state->name_state);
                return '<a href="#"class="btn btn-info link-municipalities" data-state="' . $name_state . '">Ver Municipios</a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function synchronize(): JsonResponse
    {
        try {
            Artisan::call('app:load-states-copomex');

            return response()->json([
                'success' => true,
                'message' => 'States correctly synchronized with COPOMEX.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function municipalities(string $state, Serv_Copomex $service)
    {
        try {
            return response()->json([
                'estado' => $state,
                'municipios' => $service->getMunicipalitiesByState($state)
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

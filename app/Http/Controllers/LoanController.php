<?php

namespace App\Http\Controllers;

use App\Loan;
use Illuminate\Http\Request;
use JWTAuth;

class LoanController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return $this->user->loans()->get(['description', 'amount'])->toArray();
    }

    public function show($id)
    {
        $loan = $this->user->loans()->find($id);
    
        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, loan with id ' . $id . ' cannot be found'
            ], 400);
        }
    
        return $loan;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'amount' => 'required|integer',
        ]);
    
        $loan = new Loan();
        $loan->description = $request->description;
        $loan->amount = $request->amount;
    
        if ($this->user->loans()->save($loan))
            return response()->json([
                'success' => true,
                'loan' => $loan
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, loan could not be added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $loan = $this->user->loans()->find($id);
    
        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, loan with id ' . $id . ' cannot be found'
            ], 400);
        }
    
        $updated = $loan->fill($request->all())
            ->save();
    
        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, loan could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $loan = $this->user->loans()->find($id);
    
        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, loan with id ' . $id . ' cannot be found'
            ], 400);
        }
    
        if ($loan->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Loan could not be deleted'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TableContoller extends Controller
{

    public function table_details($id)   //id table
    {
        $table = Table::where('id', $id)->first();
        return view('Admin.Restaurants.table_details', compact('table'));
    }
    public function table_create($id)   //id table
    {
        $Restaurant_id = $id;
        return view('Admin.Tables.create', compact('Restaurant_id'));
    }
    public function create(Request $request)
    {
        $Restaurant_id = $request->res_id;
        return view('Admin.Tables.create', compact('Restaurant_id'));
    }
    public function add_table($res_id)
    {
        $Restaurant_id = $res_id;
        return view('Admin.Tables.create', compact('Restaurant_id'));
    }
    public function index(Request $request)
    {
    $today = $request->date ?? Carbon::now();
        if(auth()->user()->role == 'staff'){
        $res = Restaurant::where('user_id', auth()->id())->first();
        
        $res_id = $res->id;
        $tables = Table::where('Restaurant_id', $res_id)->get();
        }else{
           $res_id = $request->query('res_id', null);
        }
        $tables = Table::where('Restaurant_id', $res_id)
        ->with(['reservations' => function ($query) use ($today) {
            $query->whereDate('reservation_date', $today);
            $query->where('status','next');
        },'nextReservations'])->withcount('nextReservations')->get();

         
        return view('Admin.Tables.index', compact('tables', 'res_id','today'));
    }
    public function rest_tables($id)
    {
        $res = Restaurant::where('id', $id)->first();
        $res_id = $res->id;
        $tables = Table::where('Restaurant_id', $res->id)->get();
        return view('Admin.Tables.index', compact('tables', 'res_id'));
    }
    public function show(Request $request, $id)
    {
        $today = $request->date ?? Carbon::now();
        $table = Table::where('id', $id)
            ->with(['reservations' => function ($query) use ($today) {
                $query->whereDate('reservation_date', $today);
            }])
            ->firstOrFail();
        return view('Admin.Tables.show', compact('table', 'today'));
    }
    public function store(Request $request)
    {
    if(auth()->user()->roleName == 'staff'){
    $restaurant = Restaurant::where('user_id',auth()->id())->first();
    }else{
    
    $restaurant = Restaurant::where('id',$request->Restaurant_id)->first();
    }
    
        $validator = Validator::make($request->all(), []);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if($restaurant){
        
            DB::beginTransaction();
            $tableData = $request->all();
            $tableData['Restaurant_id'] = $restaurant->id; // Add the restaurant_id to the data array
        
            // Create the table entry using the modified data array
            $table = Table::create($tableData);
            DB::commit();
            if($table){
            switch ($request->input('action')) {
                case 'more_add':
                    return redirect()->route('table_create', $request->Restaurant_id);
                    break;

                case 'add_and_cancel':
                if(auth()->user()->roleName == 'staff'){
                return redirect()->route('restaurant_tables');
                }
                    return redirect()->back();
                    break;
            }
            }else{
            return redirect()->back()->with('error', 'table number must be unique');
            }
        }else
        {
         $table = Table::create($request->all());
         switch ($request->input('action')) {
                case 'more_add':
                    return redirect()->route('table_create', $request->Restaurant_id);
                    break;

                case 'add_and_cancel':
                    return redirect()->route('rest_tables', $request->Restaurant_id);
                    break;
            }
        }
    }
    public function edit($id)
    {
        $table = Table::where('id', $id)->first();
        return view('Admin.Tables.edit', compact('table'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), []);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            DB::beginTransaction();
            $table = Table::where('id', $id)->first();
            $table->update($request->all());
            DB::commit();
            return redirect()->route('rest_tables', $table->Restaurant_id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error');
        }
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $table = Table::where('id', $id)->first();
            $table->delete();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error');
        }
    }
}

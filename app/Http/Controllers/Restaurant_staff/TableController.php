<?php

namespace App\Http\Controllers\Restaurant_staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\TableRepositoryInterface;
use Carbon\Carbon;
use App\Http\Requests\TableRequest;
use App\Models\Restaurant;
use App\Models\Table;

class TableController extends Controller
{
    protected $tableRepository;

    public function __construct(TableRepositoryInterface $tableRepository)
    {
        $this->tableRepository = $tableRepository;
    }
   public function restaurant_tables(Request $request)
    {
        $today = $request->date ?? Carbon::now();
        $res = Restaurant::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        $res_id = $res->id;
        $tables = Table::where('Restaurant_id', $res->id)
        ->with(['reservations' => function ($query) use ($today) {
            $query->whereDate('reservation_date', $today);
            $query->where('status','next');
        },'nextReservations'])->withcount('nextReservations')->get();

        return view('staff.Tables.index', compact('tables', 'res_id','today'));
    }
 


    public function today_tables($id)
    {
        $today = Carbon::now();
        Table::where('id', $id)
            ->whereHas('reservations', function ($query) use ($date) {
                $query->whereDate('reservation_date', $date);
            })
            ->with(['reservations' => function ($query) use ($date) {
                $query->whereDate('reservation_date', $date);
            }])
            ->first();
        return view('staff.Tables.show', compact('table', 'today'));
    }

    public function date_tables(Request $request, $id)
    {
        $date = $request->date;
        $table = $this->tableRepository->getTablesWithReservations($id, $date);
        return view('staff.Tables.show', compact('table', 'date'));
    }

    // ... الأساليب الأخرى بنفس الطريقة

    public function store(TableRequest $request)
    {
        $data = $request->validated();
        $this->tableRepository->createTable($data);

        // منطق التحويل بناءً على الإجراء
        return redirect()->route('tables.create', ['res_id' => $request->res_id]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $table = $this->tableRepository->updateTable($id, $data);
        if(auth()->user()->roleName == 'staff'){
        // منطق التحويل بناءً على الإجراء
        return redirect()->route('restaurant_tables');
        }else{
        return redirect()->route('rest_tables', $table->Restaurant_id);
        }
    }

    public function destroy($id)
    {
        $this->tableRepository->deleteTable($id);
        return redirect()->route('tables.index');
    }
}

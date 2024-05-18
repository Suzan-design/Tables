<?php

namespace App\Repositories;

use App\Models\Table;
use App\Repositories\Interfaces\TableRepositoryInterface;
use Carbon\Carbon;

class TableRepository implements TableRepositoryInterface
{
    public function getTablesWithReservations($id, $date)
    {
        return Table::where('id', $id)
            ->whereHas('reservations', function ($query) use ($date) {
                $query->whereDate('reservation_date', $date);
            })
            ->with(['reservations' => function ($query) use ($date) {
                $query->whereDate('reservation_date', $date);
            }])
            ->first();
    }

    public function getAllTables($restaurantId)
    {
        return Table::where('Restaurant_id', $restaurantId)->get();
    }

    public function findTableById($id)
    {
        return Table::findOrFail($id);
    }

    public function createTable($data)
    {
        return Table::create($data);
    }

    public function updateTable($id, $data)
    {
         $table = $this->findTableById($id);
        $table->update($data);
        return $table;
    }

    public function deleteTable($id)
    {
        Table::where('id', $id)->delete();
    }
}

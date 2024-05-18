<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

use App\Models\times; // Assume your model is named 'Time' for storing work times


class GenerateMonthlyWorkTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'worktimes:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate work times for the next month if they do not exist.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            $lastTime = times::where('Restaurant_id', $restaurant->id)
                            ->orderBy('date_end', 'desc')
                            ->first();

            if ($lastTime) {
                $lastEndDate = Carbon::parse($lastTime->date_end);
                $nextMonthStart = $lastEndDate->copy()->addDay();
                $nextMonthEnd = $nextMonthStart->copy()->addMonth()->subDay();

                $existingTimes = times::where('Restaurant_id', $restaurant->id)
                                      ->whereBetween('date_start', [$nextMonthStart, $nextMonthEnd])
                                      ->exists();

                if (!$existingTimes) {
                    $newTimes = new times;
                    $newTimes->Restaurant_id = $restaurant->id;
                    $newTimes->date_start = $nextMonthStart->toDateString();
                    $newTimes->date_end = $nextMonthEnd->toDateString();

                    $newTimes->sat_from = $lastTime->sat_from;
                    $newTimes->sat_to = $lastTime->sat_to;
                    $newTimes->sun_from = $lastTime->sun_from;
                    $newTimes->sun_to = $lastTime->sun_to;
                    $newTimes->mon_from = $lastTime->mon_from;
                    $newTimes->mon_to = $lastTime->mon_to;
                    $newTimes->tue_from = $lastTime->tue_from;
                    $newTimes->tue_to = $lastTime->tue_to;
                    $newTimes->wed_from = $lastTime->wed_from;
                    $newTimes->wed_to = $lastTime->wed_to;
                    $newTimes->thu_from = $lastTime->thu_from;
                    $newTimes->thu_to = $lastTime->thu_to;
                    $newTimes->fri_from = $lastTime->fri_from;
                    $newTimes->fri_to = $lastTime->fri_to;

                    $newTimes->save();
                    
                    $daysTimes = [
                    'sat' => ['from' => $newTimes->sat_from, 'to' => $newTimes->sat_to],
                    'sun' => ['from' => $newTimes->sun_from, 'to' => $newTimes->sun_to],
                    'mon' => ['from' => $newTimes->mon_from, 'to' => $newTimes->mon_to],
                    'tue' => ['from' => $newTimes->tue_from, 'to' => $newTimes->tue_to],
                    'wed' => ['from' => $newTimes->wed_from, 'to' => $newTimes->wed_to],
                    'thu' => ['from' => $newTimes->thu_from, 'to' => $newTimes->thu_to],
                    'fri' => ['from' => $newTimes->fri_from, 'to' => $newTimes->fri_to],
                ];
                
                foreach ($restaurant->tables as $table) {
                    $startDate = Carbon::parse($newTimes->date_start);
                    $endDate = Carbon::parse($newTimes->date_end);
                    // حساب الفرق بين تاريخ البداية وتاريخ النهاية بالأيام
                    $daysDiff = $startDate->diffInDays($endDate);


                    for ($i = 0; $i <= $daysDiff; $i++) {

                        $reservationDate = $startDate->addDays($i);
                        // الحصول على اسم اليوم لتحديد أوقات البداية والنهاية المناسبة
                        $dayName = strtolower($reservationDate->format('D'));
                        $startTime = Carbon::parse($daysTimes[$dayName]['from']);
                        $endTime = Carbon::parse($daysTimes[$dayName]['to']);
                        $reservation = Reservation::where('Restaurant_id',$restaurant->id)->where('table_id',$table->id)->first();
                        // حساب عدد السجلات المراد إنشاؤها لهذا اليوم
                        $totalRecords = $startTime->diffInMinutes($endTime) / 30;
                        for ($j = 0; $j < $totalRecords; $j++) {
                            $reservation = new Reservation();
                            $reservation->table_id = $table->id;
                            $reservation->Restaurant_id = $restaurant->id;
                            $reservation->duration = $reservation->duration;
                            // إضافة نصف ساعة إلى وقت الحجز
                            $reservationTime = $startTime->addMinutes(30 * $j);
                            $reservation->reservation_time =  $reservationTime->format('H:i');
                            $reservation->reservation_date = $reservationDate->copy()->setTimeFromTimeString($reservationTime->toTimeString());
                            $reservation->save();
                            //    return redirect()->route('today_reservations');
                        }
                    }
                }

                $times = times::where('Restaurant_id', $restaurant->id)->first();
                DB::commit();
                
                }
            }
        }

        $this->info('Work times generation check complete.');
    }
}

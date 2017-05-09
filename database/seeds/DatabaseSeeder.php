<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lyric;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LyricsTableSeeder::class);
        DB::table('lyric_user')->truncate();
        $users = User::all();
        $lyrics = Lyric::all();

        foreach ($users as $user) {
            for ($i = 0; $i < 20; $i++) {
                $dt = Carbon::now();
                $playedTime = $dt->subDays(rand(1, 100))->subHours(rand(1, 24))->subMinutes(rand(1, 60))
                    ->subSeconds(rand(1, 60))->format('Y-m-d H:i:s');
                $lyricId = $lyrics->random()->id;
                $user->lyrics()->attach($lyricId, [
                    'score' => rand(0, 200),
                    'level' => rand(1, 4),
                    'created_at' => $playedTime,
                ]);
            }
        }
    }
}

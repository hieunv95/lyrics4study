<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Lyric;

class LyricsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lyric::truncate();
        $lyricsFile = storage_path('app\public\lyricsSample.txt');
        $lrc = explode("/", File::get($lyricsFile));

        $lyrics = [
        [
            'title' => "When You Believe",
            'artist' => 'Mariah Carey, Whitney Houston',
            'link_id' => 'LKaXY4IdZ40',
            'lyric' => $lrc[0],
          ],
          [
            'title' => "I Will Always Love You",
            'artist' => 'Whitney Houston',
            'link_id' => '3JWTaaS7LdU',
            'lyric' => $lrc[1],
          ],
          [
            'title' => "My Heart Will Go On",
            'artist' => 'Celine Dion',
            'link_id' => 'WNIPqafd4As',
            'lyric' => $lrc[2],
          ],
          [
            'title' => "Someone Like You",
            'artist' => 'Adele',
            'link_id' => 'hLQl3WQQoQ0',
            'lyric' => $lrc[3],
          ],
          [
            'title' => "Chandelier",
            'artist' => 'Sia',
            'link_id' => '2vjPBrBU-TM',
            'lyric' => $lrc[4],
          ],
          [
            'title' => 'My Love',
            'artist' => 'Westlife',
            'link_id' => 'ulOb9gIGGd0',
            'lyric' => $lrc[5],
          ],
          [
            'title' => "If I ain't got you",
            'artist' => 'Alicia Keys',
            'link_id' => 'Ju8Hr50Ckwk',
            'lyric' => $lrc[6],
          ],
          [
            'title' => "All of Me",
            'artist' => 'John Legend',
            'link_id' => '450p7goxZqg',
            'lyric' => $lrc[7],
          ],
        ];

        foreach ($lyrics as $lyric) {
            Lyric::create($lyric);
        }
        //factory(App\Models\Lyric::class, 5)->create();
    }
}

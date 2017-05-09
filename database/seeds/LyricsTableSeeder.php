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
        $userId = 12;

        $lyrics = [
        [
            'title' => "Hello",
            'artist' => 'Adele',
            'link_id' => 'YQHsXMglC9A',
            'lyric' => getLyricsFromFile('Hello-Adele.srt'),
            'user_id' => $userId,
            'viewed' => rand(10, 100),
          ],
          [
            'title' => "The Lazy Song",
            'artist' => 'Bruno Mars',
            'link_id' => 'fLexgOxsZu0',
            'lyric' => getLyricsFromFile('Bruno Mars-The Lazy Song.srt'),
            'user_id' => $userId,
            'viewed' => rand(10, 100),
          ],
          [
            'title' => "I'm Yours",
            'artist' => 'Jason Mraz',
            'link_id' => 'EkHTsc9PU2A',
            'lyric' => getLyricsFromFile("Jason Mraz - I'm yours.srt"),
            'user_id' => $userId,
            'viewed' => rand(10, 100),
          ],
          [
            'title' => "Bad Day",
            'artist' => 'Daniel Powter',
            'link_id' => 'gH476CxJxfg',
            'lyric' => getLyricsFromFile("Daniel Powter - Bad Day.srt"),
            'user_id' => $userId,
            'viewed' => rand(10, 100),
          ],
          [
            'title' => "Hero",
            'artist' => 'Mariah Carey',
            'link_id' => '0IA3ZvCkRkQ',
            'lyric' => getLyricsFromFile("Mariah Carey - Hero.srt"),
            'user_id' => $userId,
            'viewed' => rand(10, 100),
          ],
          [
            'title' => 'Hurt',
            'artist' => 'Christina Aguilera',
            'link_id' => 'wwCykGDEp7M',
            'lyric' => getLyricsFromFile("Christina Aguilera - Hurt.srt"),
            'user_id' => $userId,
            'viewed' => rand(10, 100),
          ],
          [
            'title' => "I Have A Dream",
            'artist' => 'Westlife',
            'link_id' => '_HkL8GuU9_0',
            'lyric' => getLyricsFromFile("Westlife - I Have A Dream.srt"),
            'user_id' => $userId,
            'viewed' => rand(10, 100),
          ],
          [
            'title' => "Earth Song",
            'artist' => 'Michael Jackson',
            'link_id' => 'XAi3VTSdTxU',
            'lyric' => getLyricsFromFile("Earth song - Michael Jackson.srt"),
            'user_id' => $userId,
            'viewed' => rand(10, 100),
          ],
          [
            'title' => "Heal The World",
            'artist' => 'Michael Jackson',
            'link_id' => 'BWf-eARnf6U',
            'lyric' => getLyricsFromFile("Michael Jackson - Heal The World.srt"),
            'user_id' => $userId,
            'viewed' => rand(10, 100),
          ],
        ];

        foreach ($lyrics as $lyric) {
            Lyric::create($lyric);
        }
        //factory(App\Models\Lyric::class, 5)->create();
    }
}

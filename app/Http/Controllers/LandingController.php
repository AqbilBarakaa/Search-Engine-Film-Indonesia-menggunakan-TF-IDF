<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class LandingController extends Controller
{
    public function index()
    {
        $process = new Process(["python", "query.py", "film", "10000", ""]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $lines = array_filter(explode("\n", $process->getOutput()));
        $results = [];
        foreach ($lines as $line) {
            $item = json_decode($line, true);
            if ($item) $results[] = $item;
        }
        
        $genres = array_filter(array_unique(array_column($results, 'genre')));
        sort($genres);

        $ratingRanges = [
            '8' => 'Rating > 8.0',
            '7' => 'Rating 7.0 - 7.9',
            '6' => 'Rating 6.0 - 6.9',
            '5' => 'Rating < 6.0'
        ];
        
        return view('landing', compact('results', 'genres', 'ratingRanges'));
    }

    public function search(Request $r)
    {
        $query = $r->input('q', '');
        $rank = $r->input('rank', 10);

        logger()->info("SEARCH q={$query}, rank={$rank}");

        $process = new Process(["python", "query.py", "film", $rank, $query]);
        $process->run();

        logger()->info("Process stdout:", ['out'=>$process->getOutput()]);
        logger()->info("Process stderr:", ['err'=>$process->getErrorOutput()]);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $lines = array_filter(explode("\n", $process->getOutput()));
        $data = [];
        foreach ($lines as $line) {
            $item = json_decode($line, true);
            if ($item) $data[] = $item;
        }

        return response()->json($data);
    }
}

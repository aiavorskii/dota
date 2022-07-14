<?php

namespace App\Console\Commands;

use App\Models\League;
use App\Models\ParseJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Response;

class LeagueParse extends Command
{

    const ENDPOINT_URL = 'https://www.dota2.com/webapi/IDOTA2League/GetLeagueInfoList/v001';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:league';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse data from Dota API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GuzzleClient $guzzle )
    {
        parent::__construct();
        $this->http = $guzzle;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //
        try {
            $lastJob = ParseJob::firstOrCreate([
                'endpoint' => self::ENDPOINT_URL,
            ]);

            $headers = $lastJob->last_updated ? [
                'If-Modified-Since' => $lastJob->last_updated
            ] : [];

            $this->http->request('GET', self::ENDPOINT_URL);
            $response = $this->http->request('GET', self::ENDPOINT_URL, [
                'headers' => $headers
            ]);

            if ($response->getStatusCode() == Response::HTTP_NOT_MODIFIED) {
                return 0;
            }

        } catch (\Exception $e) {
            // handling exception
            return 0;
        }

        $this->save($response);

        $lastModified = $response->getHeader('last-modified');
        $lastModifiedString = array_pop($lastModified);
        $timestamp = Carbon::createFromTimeString($lastModifiedString);

        $lastJob->update(['last_updated' => $timestamp]);

        return 0;
    }

    protected function formatData($data) {
        return collect($data->infos)->map(function($item) {
            return [
                'league_external_id' => $item->league_id,
                'name' => $item->name,
                'tier' => $item->tier,
                'region' => $item->region,
                'status' => $item->status,
                'most_recent_activity' => $item->most_recent_activity,
                'start_timestamp' => $item->start_timestamp,
                'end_timestamp' => $item->end_timestamp,
            ];
        })->toArray();
    }

    protected function save($response) {
        try {
            // @todo replace with stream reader and filter?
            $json = $response->getBody()->getContents();
            $data = json_decode($json);

            $formattedData =$this->formatData($data);

            foreach($formattedData as $league) {
                League::updateOrCreate([
                    'league_external_id' => $league['league_external_id'],
                ], $league);
            }
        } catch(\Exception $e) {
            // handling exception
        }
    }
}

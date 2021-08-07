<?php

namespace App\Console\Commands\Record;

use App\Services\Record\RecordService;
use Illuminate\Console\Command;

class DisableExpiredRecords extends Command
{
    protected $signature = 'app:record:disable-expired';
    protected $description = 'Disable expired records';

    private $recordService;

    public function __construct(RecordService $recordService)
    {
        $this->recordService = $recordService;
        parent::__construct();
    }

    public function handle()
    {
        $this->recordService->disableExpiredRecords();
    }
}

<?php

namespace App\Console\Commands;


use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;

class DeleteOldScreenshot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete-old-screenshot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $files = Storage::files(null, true);
        foreach ($files as $item)
        {
            if(preg_match("/^.*\.(jpg|jpeg|png|gif)$/i", $item))
            {
                Storage::delete($item);
            }
        }
    }
}

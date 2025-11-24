<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all uploaded files from storage/public';

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
        $directory = 'pdfs'; // specify your uploaded files folder

        // Safety check to prevent accidental deletion of root disk
        if (empty($directory)) {
            $this->error("Directory is not specified! Aborting deletion.");
            return 1; // exit with error
        }

        if (Storage::disk('public')->exists($directory)) {

            // Delete all files and subdirectories inside the specified folder
            Storage::disk('public')->deleteDirectory($directory);

            $this->info("All uploaded files in '$directory' have been deleted.");

        } else {
            $this->warn("Directory '$directory' does not exist. Nothing to delete.");
        }

        return 0;

    }
}

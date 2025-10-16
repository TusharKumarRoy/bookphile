<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Author;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateAuthors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'authors:cleanup-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicate author entries from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of duplicate authors...');
        
        // Find duplicate authors based on first_name and last_name
        $duplicates = DB::table('authors')
            ->select('first_name', 'last_name', DB::raw('COUNT(*) as count'), DB::raw('GROUP_CONCAT(id) as ids'))
            ->groupBy('first_name', 'last_name')
            ->having('count', '>', 1)
            ->get();
        
        if ($duplicates->isEmpty()) {
            $this->info('No duplicate authors found.');
            return 0;
        }
        
        $this->info("Found {$duplicates->count()} sets of duplicate authors:");
        
        foreach ($duplicates as $duplicate) {
            $this->line("- {$duplicate->first_name} {$duplicate->last_name} ({$duplicate->count} duplicates)");
            
            $ids = explode(',', $duplicate->ids);
            $keepId = array_shift($ids); // Keep the first one
            
            // Get all authors with these IDs
            $authors = Author::whereIn('id', $ids)->get();
            
            // Move all books from duplicates to the kept author
            foreach ($authors as $author) {
                // Move books to the kept author
                DB::table('author_book')
                    ->where('author_id', $author->id)
                    ->update(['author_id' => $keepId]);
                
                // Delete the duplicate author
                $author->delete();
                $this->line("  Merged author ID {$author->id} into ID {$keepId}");
            }
        }
        
        $this->info('Duplicate cleanup completed successfully!');
        return 0;
    }
}

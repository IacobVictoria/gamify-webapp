<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity;
use Illuminate\Support\Str;

class GenerateActivitySlugs extends Command
{
    protected $signature = 'activities:generate-slugs';
    protected $description = 'Generate missing slugs for existing activities based on their title';

    public function handle()
    {
        $this->info('Generating slugs for activities...');

        $activities = Activity::all();
        $updated = 0;

        foreach ($activities as $activity) {
            if (empty($activity->slug)) {
                $originalSlug = Str::slug($activity->title);
                $slug = $originalSlug;
                $counter = 1;

                // Ensure uniqueness
                while (Activity::where('slug', $slug)->where('id', '!=', $activity->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }

                $activity->slug = $slug;
                $activity->save();
                $updated++;
                $this->line("✔️  Slug added for activity [ID: {$activity->id}] → {$slug}");
            }
        }

        $this->info("✅ Done. {$updated} activities updated.");
    }
}

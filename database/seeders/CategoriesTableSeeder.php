<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// php artisan db:seed --class=CategoriesTableSeeder
class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories1 = [
            'AI Detection',
            'Art',
            'Audio',
            'Avatars',
            'Business',
            'Chat',
            'Coaching',
            'Data Analysis',
            'Design',
            'Development',
            'Education',
            'Email',
            'Finance',
            'Gaming',
            'Images',
            'Legal',
            'Marketing',
            'Music',
            'Podcasting',
            'Productivity',
            'Prompt Guides',
            'Research',
            'SEO',
            'Social Media',
            'Speech',
            'Translation',
            'Video',
            'Writing',
            'Free AI tools',
            'OpenSource AI tools',
        ];

        $categories2 = [
            "Copywriting", "Email Assistant", "General Writing", "Paraphrase", "Prompts", "SEO", "Social Media Assistant", "Story Teller", "Summarizer",
            "Art", "Avatars", "Design Assistant", "Image Editing", "Logo Generator", "Image Generator",
            "Code Assistant", "Developer Tools", "Low Code/No Code", "Spreadsheets", "Sql",
            "Audio Editing", "Music", "Text To Speech", "Transcriber",
            "Personalized Videos", "Video Editing", "Video Generator",
            "3D",
            "Customer Support", "E-commerce", "Education Assistant", "Fashion", "Finance", "Human Resources", "Legal Assistant", "Personalization", "Productivity", "Real Estate", "Sales", "Startup", "Memory", "Presentations",
            "Experiments", "Fun", "Gaming", "Gift Ideas", "Health care", "Life Assistant", "Research", "Resources", "Search Engine", "Fitness", "Travel"
        ];

        $allCategories = collect()
            ->merge($categories1)
            ->merge($categories2)
            ->unique()
            ->toArray();

        // Seed the categories into the database
        foreach ($allCategories as $category) {
            DB::table('categories')->insertOrIgnore([
                'name' => $category,
                'slug' => str($category)->slug()
            ]);
        }
    }
}

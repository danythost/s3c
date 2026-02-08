<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Home Page Content (Hero Section)
        \App\Models\Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home Hero',
                'content' => 'Instant mobile data top-up and a premium marketplace for quality fashion, clothes, bags, and shoes. Everything you need, in one secure place.',
                'image_url' => 'images/hero_bg.png',
                'meta' => [
                    'hero_title_prefix' => 'Fast & Reliable',
                    'hero_title_suffix' => 'Solutions',
                    'hero_title_end' => 'for Your Lifestyle',
                ],
                'is_active' => true,
            ]
        );

        // About Us Content (How It Works)
        \App\Models\Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About Us - How It Works',
                'content' => 'We simplify your digital lifestyle by providing instant VTU services alongside a curated marketplace for premium fashion items.',
                'image_url' => null,
                'meta' => [
                    'section_subtitle' => 'SEAMLESS EXPERIENCE',
                    'section_title' => 'How S3C Simplifies Life',
                    'steps' => [
                        [
                            'title' => 'Create Profile',
                            'desc' => 'Secure registration in seconds. Your automated digital wallet is instantly provisioned upon signup.',
                            'color' => 'blue'
                        ],
                        [
                            'title' => 'Smart Funding',
                            'desc' => 'Use your dedicated virtual bank account for real-time funding. Transact with confidence and total transparency.',
                            'color' => 'emerald'
                        ],
                        [
                            'title' => 'Shop & Deliver',
                            'desc' => 'Instantly top-up data or explore our premium marketplace. Experience lightning-fast delivery on all services.',
                            'color' => 'purple'
                        ]
                    ]
                ],
                'is_active' => true,
            ]
        );

        // Developers Content
        \App\Models\Page::updateOrCreate(
            ['slug' => 'developers'],
            [
                'title' => 'Developers API',
                'content' => 'Integrate our robust VTU services into your own applications. Our API is built for speed, reliability, and ease of use.',
                'image_url' => null,
                'meta' => [
                    'section_subtitle' => 'FOR DEVELOPERS',
                    'section_title' => 'Build with S3C API',
                    'features' => [
                        '99.9% Uptime',
                        'Instant Webhook Notifications',
                        'Detailed Documentation',
                        'Sandbox Environment'
                    ],
                    'docs_link' => '#'
                ],
                'is_active' => true,
            ]
        );

        // CEO/Founder Content
        $ceoPage = \App\Models\Page::where('slug', 'ceo')->first();
        \App\Models\Page::updateOrCreate(
            ['slug' => 'ceo'],
            [
                'title' => 'Meet the CEO',
                'content' => $ceoPage->content ?? 'Leading S3C with a vision to revolutionize digital transactions and premium e-commerce in Africa.',
                'image_url' => $ceoPage->image_url ?? null,
                'meta' => array_merge([
                    'name' => 'CEO Name',
                    'position' => 'Founder & CEO',
                    'signature_text' => 'Building the future, one transaction at a time.',
                    'socials' => [
                        'whatsapp' => '#',
                        'facebook' => '#',
                        'gmail' => 'ceo@s3c.com.ng',
                        'telegram' => '#',
                    ]
                ], $ceoPage->meta ?? []),
                'is_active' => true,
            ]
        );

        // Team/Developers Content
        $teamPage = \App\Models\Page::where('slug', 'team')->first();
        \App\Models\Page::updateOrCreate(
            ['slug' => 'team'],
            [
                'title' => 'Meet Our Developers',
                'content' => $teamPage->content ?? 'The talented engineers and designers who build and maintain the S3C ecosystem.',
                'image_url' => $teamPage->image_url ?? null,
                'meta' => array_merge([
                    'members' => []
                ], $teamPage->meta ?? []),
                'is_active' => true,
            ]
        );
    }
}

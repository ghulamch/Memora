<?php

namespace Database\Seeders;

use App\Models\ApiToken;
use App\Models\Photo;
use App\Models\Template;
use App\Models\TemplateSlot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@photobooth.com',
            'password' => Hash::make('password'),
        ]);

        // Create API tokens
        $token1 = ApiToken::generate('Main Upload Token');
        $token2 = ApiToken::generate('Limited Token', now()->addDays(30), 1000);

        echo "===========================================\n";
        echo "API Tokens Generated:\n";
        echo "===========================================\n";
        echo "Token 1 (Unlimited): " . $token1->token . "\n";
        echo "Token 2 (30 days, 1000 max): " . $token2->token . "\n";
        echo "===========================================\n\n";

        // Create sample templates
        $this->createSampleTemplates();

        echo "Seeding completed successfully!\n";
        echo "Admin Email: admin@photobooth.com\n";
        echo "Admin Password: password\n";
    }

    private function createSampleTemplates()
    {
        // Template 1: Simple 2x2 Grid
        $template1 = Template::create([
            'name' => '2x2 Grid Classic',
            'description' => 'Template klasik dengan 4 foto dalam grid 2x2',
            'canvas_width' => 1200,
            'canvas_height' => 1800,
            'is_active' => true,
        ]);

        $slotConfigs = [
            ['x' => 50, 'y' => 50, 'width' => 500, 'height' => 800],
            ['x' => 650, 'y' => 50, 'width' => 500, 'height' => 800],
            ['x' => 50, 'y' => 950, 'width' => 500, 'height' => 800],
            ['x' => 650, 'y' => 950, 'width' => 500, 'height' => 800],
        ];

        foreach ($slotConfigs as $index => $config) {
            TemplateSlot::create([
                'template_id' => $template1->id,
                'slot_order' => $index,
                'x' => $config['x'],
                'y' => $config['y'],
                'width' => $config['width'],
                'height' => $config['height'],
                'border_style' => 'solid',
                'border_width' => 3,
                'border_color' => '#ffffff',
                'border_radius' => 10,
            ]);
        }

        // Template 2: Single Large Photo
        $template2 = Template::create([
            'name' => 'Single Portrait',
            'description' => 'Template untuk satu foto besar portrait',
            'canvas_width' => 1200,
            'canvas_height' => 1800,
            'is_active' => true,
        ]);

        TemplateSlot::create([
            'template_id' => $template2->id,
            'slot_order' => 0,
            'x' => 100,
            'y' => 100,
            'width' => 1000,
            'height' => 1600,
            'border_style' => 'solid',
            'border_width' => 5,
            'border_color' => '#000000',
            'border_radius' => 20,
        ]);

        // Template 3: 3 Photos Collage
        $template3 = Template::create([
            'name' => '3 Photos Collage',
            'description' => 'Tiga foto dengan layout artistic',
            'canvas_width' => 1200,
            'canvas_height' => 1800,
            'is_active' => true,
        ]);

        $collageSlots = [
            ['x' => 50, 'y' => 50, 'width' => 550, 'height' => 850, 'rotation' => -3],
            ['x' => 600, 'y' => 100, 'width' => 550, 'height' => 700, 'rotation' => 2],
            ['x' => 300, 'y' => 950, 'width' => 600, 'height' => 800, 'rotation' => -1],
        ];

        foreach ($collageSlots as $index => $config) {
            TemplateSlot::create([
                'template_id' => $template3->id,
                'slot_order' => $index,
                'x' => $config['x'],
                'y' => $config['y'],
                'width' => $config['width'],
                'height' => $config['height'],
                'rotation' => $config['rotation'],
                'border_style' => 'solid',
                'border_width' => 8,
                'border_color' => '#ffffff',
                'border_radius' => 0,
            ]);
        }

        echo "Created 3 sample templates\n";
    }
}

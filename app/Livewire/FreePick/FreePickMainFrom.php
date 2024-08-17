<?php

namespace App\Livewire\FreePick;

use Livewire\Component;

class FreePickMainFrom extends Component
{
    public $styles = [
        '' => 'بدون استایل',
        'photo' => 'photo',
        'digital-art' => 'digital-art',
        '3d' => '3d',
        'painting' => 'painting',
        'low-poly' => 'low-poly',
        'pixel-art' => 'pixel-art',
        'anime' => 'anime',
        'cyberpunk' => 'cyberpunk',
        'comic' => 'comic',
        'vintage' => 'vintage',
        'cartoon' => 'cartoon',
        'vector' => 'vector',
        'studio-shot' => 'studio-shot',
        'dark' => 'dark',
        'sketch' => 'sketch',
        'mockup' => 'mockup',
        '2000s-pone' => '2000s-pone',
        '70s-vibe' => '70s-vibe',
        'watercolor' => 'watercolor',
        'art-nouveau' => 'art-nouveau',
        'origami' => 'origami',
        'surreal' => 'surreal',
        'fantasy' => 'fantasy',
        'traditional-japan' => 'traditional-japan',
    ];

    public $colors = [
        '' => 'بدون رنگ',
        'b&w' => 'b&w',
        'pastel' => 'pastel',
        'sepia' => 'sepia',
        'dramatic' => 'dramatic',
        'vibrant' => 'vibrant',
        'orange&teal' => 'orange&teal',
        'film-filter' => 'film-filter',
        'split' => 'split',
        'electric' => 'electric',
        'pastel-pink' => 'pastel-pink',
        'gold-glow' => 'gold-glow',
        'autumn' => 'autumn',
        'muted-green' => 'muted-green',
        'deep-teal' => 'deep-teal',
        'terracotta&teal' => 'terracotta&teal',
        'red&blue' => 'red&blue',
        'cold-neon' => 'cold-neon',
        'burgundy&blue' => 'burgundy&blue',
    ];

    public $lightnings = [
        '' => 'بدون نورپردازی',
        'studio' => 'studio',
        'warm' => 'warm',
        'cinematic' => 'cinematic',
        'volumetric' => 'volumetric',
        'golden-hour' => 'golden-hour',
        'long-exposure' => 'long-exposure',
        'cold' => 'cold',
        'iridescent' => 'iridescent',
        'dramatic' => 'dramatic',
        'hardlight' => 'hardlight',
        'redscale' => 'redscale',
        'indoor-light' => 'indoor-light',
    ];

    public $framings = [
        '' => 'بدون کادر',
        'portrait' => 'portrait',
        'macro' => 'macro',
        'panoramic' => 'panoramic',
        'aerial-view' => 'aerial-view',
        'close-up' => 'close-up',
        'cinematic' => 'cinematic',
        'high-angle' => 'high-angle',
        'low-angle' => 'low-angle',
        'symmetry' => 'symmetry',
        'fish-eye' => 'fish-eye',
        'first-person' => 'first-person',
    ];
    public $selectedStyle = 'digital-art'; // مقدار پیش‌فرض برای style
    public $selectedColor = 'vibrant'; // مقدار پیش‌فرض برای color
    public $selectedLightning = 'cinematic'; // مقدار پیش‌فرض برای lightning
    public $selectedFraming = 'portrait'; // مقدار پیش‌فرض برای framing
    public function render()
    {
        return view('livewire.free-pick.free-pick-main-from');
    }
}

<?php

namespace LaraZeus\Bolt\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

/**
 * @property string $key
 * @property string $label
 * @property string $desc
 * @property string $color
 * @property string $chartColor
 * @property string $icon
 * @property string $class
 */
class FormsStatus extends Model
{
    use HasUlids;
    use Sushi;

    public function getRows(): array
    {
        return [
            [
                'key' => 'NEW',
                'label' => __('New'),
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'success',
                'chartColor' => '#21C55D',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-success-700 bg-success-500/10',
            ],
            [
                'key' => 'OPEN',
                'label' => __('Open'),
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'success',
                'chartColor' => '#21C55D',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-success-700 bg-success-500/10',
            ],
            [
                'key' => 'CLOSE',
                'label' => __('closed'),
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'danger',
                'chartColor' => '#EF4444',
                'icon' => 'heroicon-o-x-circle',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-danger-700 bg-danger-500/10',
            ],
        ];
    }

    protected function sushiShouldCache(): bool
    {
        return ! app()->isLocal();
    }
}

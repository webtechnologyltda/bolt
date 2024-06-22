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
                'key' => 'WAIT_PAYMENT',
                'label' => 'Aguardando Pagamento',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'info',
                'chartColor' => '#07B5FA',
                'icon' => 'untitledui-text-align-left',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-info-700 bg-info-500/10',
            ],
            [
                'key' => 'WAIT_QUEUE',
                'label' => 'Fila de Espera',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'danger',
                'chartColor' => '#C59F21',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-success-700 bg-success-500/10',
            ],
            [
                'key' => 'APPROVED',
                'label' => 'Aprovada',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'success',
                'chartColor' => '#21C55D',
                'icon' => 'heroicon-o-document',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-success-700 bg-success-500/10',
            ],
            [
                'key' => 'CANCELED',
                'label' => 'Cancelada',
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

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
                'label' => 'Novo',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'sky',
                'chartColor' => '#3b82f6',
                'icon' => 'gmdi-new-releases-r',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-info-700 bg-info-500/10',
            ],
            [
                'key' => 'WAIT_PAYMENT',
                'label' => 'Aguardando Pagamento',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'amber',
                'chartColor' => '#f59e0b',
                'icon' => 'gmdi-payment',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-warning-700 bg-warning-500/10',
            ],
            [
                'key' => 'WAIT_QUEUE',
                'label' => 'Fila de Espera',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'violet',
                'chartColor' => '#a855f7',
                'icon' => 'fluentui-people-queue-20',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-purple-700 bg-purple-500/10',
            ],
            [
                'key' => 'APPROVED',
                'label' => 'Aprovada',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'green',
                'chartColor' => '#22c55e',
                'icon' => 'iconpark-success',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-success-700 bg-success-500/10',
            ],
            [
                'key' => 'CANCELED',
                'label' => 'Cancelada',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'rose',
                'chartColor' => '#f43f5e',
                'icon' => 'gmdi-cancel',
                'class' => 'px-2 py-0.5 text-xs rounded-xl text-danger-700 bg-danger-500/10',
            ],
        ];
    }

    protected function sushiShouldCache(): bool
    {
        return ! app()->isLocal();
    }
}

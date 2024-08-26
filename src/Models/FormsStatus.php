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
                'color' => 'info',
                'chartColor' => '#3b82f6',
                'icon' => 'gmdi-new-releases-r',
                'class' => 'px-2 py-1 ml-2 text-sm font-bold rounded-xl text-sky-700 dark:text-sky-950 bg-sky-500/10 dark:bg-sky-400',
            ],
            [
                'key' => 'WAIT_PAYMENT',
                'label' => 'Aguardando Pagamento',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'warning',
                'chartColor' => '#f59e0b',
                'icon' => 'gmdi-payment',
                'class' => 'px-2 py-1 ml-2 text-sm font-bold rounded-xl text-amber-700 dark:text-amber-950 bg-amber-500/10 dark:bg-amber-400',
            ],
            [
                'key' => 'WAIT_QUEUE',
                'label' => 'Fila de Espera',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'violet',
                'chartColor' => '#a855f7',
                'icon' => 'fluentui-people-queue-20',
                'class' => 'px-2 py-1 ml-2 text-sm font-bold rounded-xl text-violet-700 dark:text-violet-950 bg-violet-500/10 dark:bg-violet-400',
            ],
            [
                'key' => 'APPROVED',
                'label' => 'Aprovada',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'success',
                'chartColor' => '#22c55e',
                'icon' => 'iconpark-success',
                'class' => 'px-2 py-1 ml-2 text-sm font-bold rounded-xl text-green-700 dark:text-green-950 bg-green-500/10 dark:bg-green-400',
            ],
            [
                'key' => 'CANCELED',
                'label' => 'Cancelada',
                'description' => 'used when a new form created by the user or an employee',
                'color' => 'danger',
                'chartColor' => '#f43f5e',
                'icon' => 'gmdi-cancel',
                'class' => 'px-2 py-1 ml-2 text-sm font-bold rounded-xl text-red-700 dark:text-red-950 bg-rose-500/10 dark:bg-red-400',
            ],
        ];
    }

    protected function sushiShouldCache(): bool
    {
        return ! app()->isLocal();
    }
}

<?php

namespace LaraZeus\Bolt\Filament\Resources\FormResource\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use LaraZeus\Bolt\Models\Form;

class ResponsesPerMonth extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    public Form $record;

    public ?string $filter = 'per_day';

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): string
    {
        return __('Responses Count');
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'per_day' => __('Per Day'),
            'per_month' => __('Per month'),
            'per_year' => __('Per year'),
        ];
    }

    protected function getData(): array
    {
        $label = null;

        $data = Trend::model(config('zeus-bolt.models.Response'))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            );

        if ($this->filter == 'per_day') {
            $label = __('Per day');
            $data = $data->perDay();
        } elseif ($this->filter == 'per_month') {
            $label = __('Per month');
            $data = $data->perMonth();
        } elseif ($this->filter == 'per_year') {
            $label = __('Per year');
            $data = $data->perYear();
        }

        $data = $data->count();

        return [
            'datasets' => [
                [
                    'label' => $label,
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}

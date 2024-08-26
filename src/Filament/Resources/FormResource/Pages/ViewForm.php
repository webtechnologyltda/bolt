<?php

namespace LaraZeus\Bolt\Filament\Resources\FormResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use LaraZeus\Bolt\Filament\Resources\FormResource;
use LaraZeus\Bolt\Models\Form;

/**
 * @property Form $record.
 */
class ViewForm extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = FormResource::class;

    public function getTitle(): string | Htmlable
    {
        return __('View Form');
    }

    public static function getNavigationLabel(): string
    {
        return __('View Form');
    }

    protected function getHeaderActions(): array
    {
        return [
            // LocaleSwitcher::make(),
            Action::make('open')
                ->label(__('View Form'))
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->tooltip(__('View Form'))
                ->color('warning')
                ->url(fn () => route('bolt.form.show', $this->record))
                ->visible(fn (Form $record) => $record->extensions === null)
                ->openUrlInNewTab(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        $widgets = [
            FormResource\Widgets\FormOverview::class,
            FormResource\Widgets\ResponsesPerMonth::class,
            FormResource\Widgets\ResponsesPerStatus::class,
            FormResource\Widgets\ResponsesPerFields::class,
        ];

        return $widgets;
    }
}

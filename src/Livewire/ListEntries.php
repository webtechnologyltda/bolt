<?php

namespace LaraZeus\Bolt\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\View\View;
use LaraZeus\Bolt\Models\Response;
use Livewire\Component;

class ListEntries extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                config('zeus-bolt.models.Response')::query()->with('form')->where('user_id', auth()->user()->id)
            )
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->filters([
                SelectFilter::make('category')
                    ->attribute('form.category_id')
                    ->searchable()
                    ->relationship('form.category', 'name')
                    ->preload()
                    ->label(__('Category')),
            ])
            ->columns([
                Stack::make([
                    TextColumn::make('status')
                        ->badge()
                        ->label(__('status'))
                        ->formatStateUsing(fn ($state) => config('zeus-bolt.models.FormsStatus')::where('key', $state)->first()->label)
                        ->color(fn (string $state) => config('zeus-bolt.models.FormsStatus')::where('key', $state)->first()->color)
                        ->icon(fn ($state) => config('zeus-bolt.models.FormsStatus')::where('key', $state)->first()->icon)
                        ->grow(true),
                ]),
                Stack::make([
                    TextColumn::make('form.category.name')
                        ->searchable('acamps_categories.name')
                        ->formatStateUsing(fn ($state) => __('Category').' '.$state),
                ]),
                Stack::make([
                    TextColumn::make('form.name')
                        ->searchable('name')
                        ->label(__('Form Name'))
                        ->url(fn (Response $record): string => route('bolt.entry.show', $record)),
                ]),
                Stack::make([
                    TextColumn::make('updated_at')->label(__('Updated At'))->dateTime('d/M/Y H:i'),
                ]),
            ]);
    }

    public function render(): View
    {
        seo()
            ->title(__('My Responses').' '.config('zeus.site_title', 'Laravel'))
            ->description(__('My Responses').' '.config('zeus.site_description', 'Laravel'))
            ->site(config('zeus.site_title', 'Laravel'))
            ->rawTag('favicon', '<link rel="icon" type="image/x-icon" href="'.asset('favicon/favicon.ico').'">')
            ->rawTag('<meta name="theme-color" content="'.config('zeus.site_color').'" />')
            ->withUrl()
            ->twitter();

        return view(app('boltTheme').'.list-entries')
            ->layout(config('zeus.layout'));
    }
}

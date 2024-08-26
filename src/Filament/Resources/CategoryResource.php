<?php

namespace LaraZeus\Bolt\Filament\Resources;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use LaraZeus\Bolt\BoltPlugin;
use LaraZeus\Bolt\Enums\Resources;
use LaraZeus\Bolt\Filament\Resources\CategoryResource\Pages\CreateCategory;
use LaraZeus\Bolt\Filament\Resources\CategoryResource\Pages\EditCategory;
use LaraZeus\Bolt\Filament\Resources\CategoryResource\Pages\ListCategories;
use LaraZeus\Bolt\Models\Category;

class CategoryResource extends BoltResource
{
    protected static ?string $navigationIcon = 'clarity-tags-line';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModel(): string
    {
        return BoltPlugin::getModel('Category');
    }

    public static function getNavigationBadge(): ?string
    {
        if (! BoltPlugin::getNavigationBadgesVisibility(Resources::CategoryResource)) {
            return null;
        }

        return (string) BoltPlugin::getModel('Category')::query()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->label(__('Name'))
                            ->afterStateUpdated(function (Set $set, $state, $context) {
                                if ($context === 'edit') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')->required()->maxLength(255)->label(__('slug')),
                        TextInput::make('ordering')->required()->numeric()->label(__('ordering')),
                        Toggle::make('is_active')->label(__('Is Active'))->default(1),
                        Textarea::make('description')->maxLength(65535)->columnSpan(['sm' => 2])->label(__('Description')),
                        FileUpload::make('logo')
                            ->disk(config('zeus-bolt.uploadDisk'))
                            ->directory(config('zeus-bolt.uploadDirectory'))
                            ->visibility(config('zeus-bolt.uploadVisibility'))
                            ->columnSpan(['sm' => 2])
                            ->label(__('logo')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->disk(config('zeus-bolt.uploadDisk'))
                    ->visibility(config('zeus-bolt.uploadVisibility'))
                    ->toggleable()
                    ->label(__('Logo')),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('forms_count')
                    ->counts('forms')
                    ->label(__('Forms'))
                    ->toggleable()
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->toggleable()
                    ->label(__('Is Active')),
            ])
            ->reorderable('ordering')
            ->defaultSort('id', 'description')
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ]),
            ])
            ->filters([
                TrashedFilter::make(),
                Filter::make('is_active')
                    ->label(__('is active'))
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
                Filter::make('not_active')
                    ->label(__('not active'))
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->where('is_active', false)),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ]);
    }

    /** @phpstan-return Builder<Category> */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Categories');
    }

    public static function getNavigationLabel(): string
    {
        return __('Categories');
    }
}

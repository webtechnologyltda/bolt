<?php

namespace LaraZeus\Bolt\Filament\Resources;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
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
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use LaraZeus\Bolt\BoltPlugin;
use LaraZeus\Bolt\Enums\Resources;
use LaraZeus\Bolt\Filament\Resources\CategoryResource\Pages\CreateCategory;
use LaraZeus\Bolt\Filament\Resources\CategoryResource\Pages\EditCategory;
use LaraZeus\Bolt\Filament\Resources\CategoryResource\Pages\ListCategories;
use LaraZeus\Bolt\Filament\Resources\CategoryResource\RelationManagers\UsersRelationManager;
use LaraZeus\Bolt\Models\Category;

class CategoryResource extends BoltResource
{
    protected static ?string $navigationIcon = 'iconpark-camp';

    protected static ?int $navigationSort = 0;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        if (! BoltPlugin::getNavigationBadgesVisibility(Resources::CategoryResource)) {
            return null;
        }

        return (string) config('zeus-bolt.models.Category')::query()->count();
    }

    public static function getModel(): string
    {
        return config('zeus-bolt.models.Category');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'default' => 1,
                        'lg' => 4
                    ])
                    ->schema([

                        FileUpload::make('logo')
                            ->disk(config('zeus-bolt.uploadDisk'))
                            ->directory(config('zeus-bolt.uploadDirectory'))
                            ->visibility(config('zeus-bolt.uploadVisibility'))
                            ->columnSpan(['sm' => 5])
                            ->avatar()
                            ->hiddenLabel()
                            ->label('Foto de identificação')
                            ->optimize('webp')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                            ->placeholder(fn () => new HtmlString('<span><a class="text-primary-600 font-bold">Clique aqui</a></br>Para adicionar uma foto</span>'))
                            ->resize(15)
                            ->alignCenter()
                            ->imageEditor()
                            ->imagePreviewHeight('250px')
                            ->previewable(true)
                            ->columnSpan(1)
                            ->imageCropAspectRatio('1:1')
                            ->loadingIndicatorPosition('center')
                            ->panelAspectRatio('1:1')
                            ->removeUploadedFileButtonPosition('top-center')
                            ->uploadButtonPosition('center')
                            ->uploadProgressIndicatorPosition('center')
                            ->imageEditorMode(2)
                            ->panelLayout('integrated')
                            ->extraInputAttributes(['height' => '250'])
                            ->imageEditorEmptyFillColor('#000000'),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['sm' => 1, 'lg' => 3])
                            ->live(onBlur: true)
                            ->label(__('Name'))
                            ->afterStateUpdated(function (Set $set, $state, $context) {
                                if ($context === 'edit') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->label(__('slug'))
                            ->columnSpan(['sm' => 1, 'lg' => 1]),

                        Hidden::make('ordering')
                            ->default(Category::query()->count()),

                        RichEditor::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label(__('Description')),

                        Toggle::make('is_active')
                            ->columnSpanFull()
                            ->label(__('Is Active'))
                            ->default(1),
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
                    ->square()
                    ->wrap()
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

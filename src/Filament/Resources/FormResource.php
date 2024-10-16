<?php

namespace LaraZeus\Bolt\Filament\Resources;

use Filament\Facades\Filament;
use Closure;
use Exception;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\Bolt\BoltPlugin;
use LaraZeus\Bolt\Concerns\HasOptions;
use LaraZeus\Bolt\Concerns\Schemata;
use LaraZeus\Bolt\Enums\Resources;
use LaraZeus\Bolt\Filament\Actions\ReplicateFormAction;
use LaraZeus\Bolt\Filament\Resources\FormResource\Pages;
use LaraZeus\Bolt\Models\Form as ZeusForm;
use LaraZeus\BoltPro\BoltProServiceProvider;
use LaraZeus\BoltPro\Livewire\PrefilledForm;
use LaraZeus\BoltPro\Livewire\ShareForm;
use LaraZeus\ListGroup\Infolists\ListEntry;

class FormResource extends BoltResource
{
    use HasOptions;
    use Schemata;

    protected static ?string $navigationIcon = 'clarity-form-line';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static Closure|array|null $boltFormSchema = null;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationBadge(): ?string
    {
        if (! BoltPlugin::getNavigationBadgesVisibility(Resources::FormResource) || Filament::getTenant() !== null) {
            return null;
        }

        return (string)config('zeus-bolt.models.Form')::query()->count();
    }

    public static function getModel(): string
    {
        return config('zeus-bolt.models.Form');
    }

    public static function getModelLabel(): string
    {
        return __('Form');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Forms');
    }

    public static function getNavigationLabel(): string
    {
        return __('Forms');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()->schema([
                    TextEntry::make('name')
                        ->label(__('name')),

                    ListEntry::make('items')
                        ->visible(fn (ZeusForm $record) => $record->extensions !== null)
                        ->heading(__('Form Links'))
                        ->list()
                        ->state(fn ($record) => $record->slug_url),

                    TextEntry::make('slug')
                        ->label(__('slug'))
                        ->url(fn (ZeusForm $record) => route('bolt.form.show', ['slug' => $record->slug]))
                        ->visible(fn (ZeusForm $record) => $record->extensions === null)
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->openUrlInNewTab(),

                    TextEntry::make('description')
                        ->label(__('description')),
                    IconEntry::make('is_active')
                        ->label(__('is active'))
                        ->icon(fn (string $state): string => match ($state) {
                            '0' => 'clarity-times-circle-solid',
                            default => 'clarity-check-circle-line',
                        })
                        ->color(fn (string $state): string => match ($state) {
                            '0' => 'warning',
                            '1' => 'success',
                            default => 'gray',
                        }),

                    TextEntry::make('start_date')
                        ->label(__('start date'))
                        ->dateTime(),
                    TextEntry::make('end_date')
                        ->label(__('end date'))
                        ->dateTime(),
                ])
                    ->columns(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema(static::$boltFormSchema ?? static::getMainFormSchema());
    }

    public static function getBoltFormSchemaUsing(array|Closure|null $form): void
    {
        static::$boltFormSchema = $form;
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('ordering')
            ->columns([
                TextColumn::make('id')->sortable()->label(__('Form ID'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')->searchable()->sortable()->label(__('Form Name'))->toggleable(),
                TextColumn::make('category.name')->searchable()->label(__('Category'))->sortable()->toggleable(),
                IconColumn::make('is_active')->boolean()->label(__('Is Active'))->sortable()->toggleable(),
                TextColumn::make('start_date')->dateTime()->searchable()->sortable()->label(__('Start Date'))->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('end_date')->dateTime()->searchable()->sortable()->label(__('End Date'))->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('responses_exists')->boolean()->exists('responses')->label(__('Responses Exists'))->sortable()->toggleable(),
                TextColumn::make('responses_count')->counts('responses')->label(__('Responses Count'))->sortable()->toggleable(),
            ])
            ->actions(static::getActions())
            ->filters([
                TrashedFilter::make(),
                TernaryFilter::make('is_active')
                    ->label(__('Is Active')),

                SelectFilter::make('category_id')
                    ->options(config('zeus-bolt.models.Category')::pluck('name', 'id'))
                    ->label(__('Category')),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ]);
    }

    public static function getActions(): array
    {
        $action = [
            ViewAction::make(),
            EditAction::make('edit'),
            ReplicateFormAction::make(),
            RestoreAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),

            ActionGroup::make([
                Action::make('entries')
                    ->color('warning')
                    ->label(__('Entries'))
                    ->icon('clarity-data-cluster-line')
                    ->tooltip(__('view all entries'))
                    ->url(fn (ZeusForm $record): string => url('admin/responses?form_id='.$record->id)),
            ])
                ->dropdown(false),
        ];

        $advancedActions = $moreActions = [];

        $moreActions[] = ActionGroup::make($advancedActions)->dropdown(false);

        return [ActionGroup::make(array_merge($action, $moreActions))];
    }

    /** @phpstan-return Builder<ZeusForm> */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        $pages = [
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
            'view' => Pages\ViewForm::route('/{record}'),
            'report' => Pages\ManageResponses::route('/{record}/report'),
            'browse' => Pages\BrowseResponses::route('/{record}/browse'),
            'viewResponse' => Pages\ViewResponse::route('/{record}/response/{responseID}'),
        ];

        if (class_exists(BoltProServiceProvider::class)) {
            //@phpstan-ignore-next-line
            $pages['prefilled'] = PrefilledForm::route('/{record}/prefilled');
            //@phpstan-ignore-next-line
            $pages['share'] = ShareForm::route('/{record}/share');
        }

        return $pages;
    }

    public static function getWidgets(): array
    {
        $widgets = [
            FormResource\Widgets\FormOverview::class,
            FormResource\Widgets\ResponsesPerMonth::class,
            FormResource\Widgets\ResponsesPerStatus::class,
            FormResource\Widgets\ResponsesPerFields::class,
        ];

        return $widgets;
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $formNavs = [
            Pages\ViewForm::class,
            Pages\EditForm::class,
        ];

        $respNavs = [
            Pages\ManageResponses::class,
            Pages\BrowseResponses::class,
        ];

        return $page->generateNavigationItems([
            ...$formNavs,
            ...$respNavs,
        ]);
    }

    public function getBoltFormSchema(): array|Closure|null
    {
        return static::$boltFormSchema;
    }
}

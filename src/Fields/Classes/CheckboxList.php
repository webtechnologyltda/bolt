<?php

namespace LaraZeus\Bolt\Fields\Classes;

use LaraZeus\Accordion\Forms\Accordion;
use LaraZeus\Accordion\Forms\Accordions;
use LaraZeus\Bolt\Facades\Bolt;
use LaraZeus\Bolt\Fields\FieldsContract;
use LaraZeus\Bolt\Models\Field;
use LaraZeus\Bolt\Models\FieldResponse;

class CheckboxList extends FieldsContract
{
    public string $renderClass = \Filament\Forms\Components\CheckboxList::class;

    public int $sort = 3;

    public function title(): string
    {
        return __('Checkbox List');
    }

    public function icon(): string
    {
        return 'tabler-list-check';
    }

    public function description(): string
    {
        return __('checkbox items from data source');
    }

    public static function getOptions(?array $sections = null, ?array $field = null): array
    {
        return [
            self::dataSource(),

            Accordions::make('check-list-options')
                ->accordions([
                    Accordion::make('general-options')
                        ->label(__('General Options'))
                        ->icon('iconpark-checklist-o')
                        ->schema([
                            self::required(),
                            self::columnSpanFull(),
                            self::htmlID(),
                        ]),
                    self::hintOptions(),
                    self::visibility($sections),
                    // @phpstan-ignore-next-line
                    ...Bolt::hasPro() ? \LaraZeus\BoltPro\Facades\GradeOptions::schema($field) : [],
                    Bolt::getCustomSchema('field', resolve(static::class)) ?? [],
                ]),
        ];
    }

    public static function getOptionsHidden(): array
    {
        return [
            // @phpstan-ignore-next-line
            Bolt::hasPro() ? \LaraZeus\BoltPro\Facades\GradeOptions::hidden() : [],
            ...Bolt::getHiddenCustomSchema('field', resolve(static::class)) ?? [],
            self::hiddenDataSource(),
            self::hiddenVisibility(),
            self::hiddenHtmlID(),
            self::hiddenHintOptions(),
            self::hiddenRequired(),
            self::hiddenColumnSpanFull(),
        ];
    }

    public function getResponse(Field $field, FieldResponse $resp): string
    {
        return $this->getCollectionsValuesForResponse($field, $resp);
    }

    // @phpstan-ignore-next-line
    public function appendFilamentComponentsOptions($component, $zeusField, bool $hasVisibility = false)
    {
        parent::appendFilamentComponentsOptions($component, $zeusField, $hasVisibility);

        $options = FieldsContract::getFieldCollectionItemsList($zeusField);

        $component = $component->options($options);

        if (request()->filled($zeusField->options['htmlId'])) {
            $component = $component->default(request($zeusField->options['htmlId']));

            //todo set default items for datasources
        } elseif ($selected = $options->where('itemIsDefault', true)->pluck('itemKey')->isNotEmpty()) {
            $component = $component->default($selected);
        }

        return $component->live();
    }
}

@php use Filament\Infolists\Infolist; @endphp
<div x-data class="space-y-4 my-6 mx-4 w-full">

    <x-slot name="header">
        <h2>{{ __('Show Entry Details') }}</h2>
    </x-slot>

    <x-slot name="breadcrumbs">
        <li class="flex items-center">
            <a href="{{ route('bolt.entries.list') }}">{{ __('My Entries') }}</a>
            @svg('iconpark-rightsmall-o', 'fill-current w-4 h-4 mx-3')
        </li>

        <li class="flex items-center">
            {{ __('Show entry') }} # {{ $response->id }}
        </li>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2 space-y-4">
            <x-filament::section>
                <div class="grid grid-cols-1">
                    @foreach ($response->fieldsResponses as $resp)
                        <div class="py-2 text-ellipsis overflow-auto">
                            <p>{{ $resp->field->name }}</p>
                            <p class="font-semibold mb-2">{!! (new $resp->field->type())->getResponse($resp->field, $resp) !!}</p>
                            <hr/>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        </div>
        <div class="md:col-span-1 space-y-4">
            <div>
                <div class="space-y-2">
                    <x-filament::section>
                        <x-slot name="heading" class="text-primary-600">
                            <p class="my-3 mx-1 text-primary-600 font-semibold">
                                @svg('gmdi-checklist-o', 'text-primary-600 w-6 h-6 inline mr-2')
                                {{ __('Entry Details') }}</p>
                        </x-slot>

                        <div class="flex flex-col text-primary-600 dark:text-primary-400 font-bold mb-4">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Form') }}:</span>
                            {{ $response->form->name ?? '' }}
                        </div>

                        <div>
                            <span>{{ __('status') }}</span>
                            @php $getStatues = $response->statusDetails() @endphp
                            <span class="{{ $getStatues['class'] }}"
                                  x-tooltip="{
                                    content: @js(__('status')),
                                    theme: $store.theme,
                                  }">
                                @svg($getStatues['icon'], 'w-6 h-6 inline')
                                {{ $getStatues['label'] }}
                            </span>
                        </div>

                        {{--                        <div class="flex flex-col">--}}
                        {{--                            <span>{{ __('Notes') }}:</span>--}}
                        {{--                            {!! nl2br($response->notes) !!}--}}
                        {{--                        </div>--}}

                    </x-filament::section>
                </div>
            </div>
            <x-filament::section class="w-full">
                <x-slot name="heading" class="text-primary-600">
                    @svg('heroicon-s-user', 'text-gray-700 dark:text-white w-6 h-6 inline mr-2')
                    {{ __('User Details') }}
                </x-slot>
                <p class="mb-4">
                    <span class="text-base font-bold">{{ __('Name') }}</span>:
                    @if ($response->user_id === null)
                        {{ __('Visitor') }}
                    @else
                        {{ $response->user->name ?? '' }}
                    @endif
                </p>
                <p class="flex flex-col">
                    <span class="text-base font-light">{{ __('Answered in') }}:</span>
                    <span
                        class="font-semibold">{{ $response->created_at->format('d/M/Y H:i') }}</span>
                </p>
            </x-filament::section>
        </div>
    </div>
</div>

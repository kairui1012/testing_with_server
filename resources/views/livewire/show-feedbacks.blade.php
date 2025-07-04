<div class="overflow-x-auto ">

    <div class="block md:hidden">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            {{-- 顶部筛选栏 --}}
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider">反馈记录</h3>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="inline-flex items-center justify-between px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span>{{ $selectedWeek ? $this->getWeekRange($selectedWeek) : 'Week' }}</span>
                            <svg class="w-3 h-3 ml-1 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-10 w-32 mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                            <div class="py-1">
                                @php
                                $availableWeeks = $feedbacks->pluck('week')->unique()->sort()->values();
                                @endphp
                                @foreach($availableWeeks as $week)
                                <button wire:click="$set('selectedWeek', '{{ $week }}')" @click="open = false"
                                    class="w-full px-3 py-2 text-left text-xs text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 {{ $selectedWeek == $week ? 'bg-blue-50 text-blue-700' : '' }}">
                                    {{ $this->getWeekRange($week) }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 卡片列表 --}}
            <div class="divide-y divide-gray-200">
                @forelse($feedbacks as $feedback)
                <div wire:key="{{ $feedback->id }}" class="p-4">
                    {{-- 卡片头部 --}}
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                ID: {{ $feedback->id }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $this->getWeekRange($feedback->week) }}</span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $feedback->created_at->format('Y-m-d') }}</span>
                    </div>

                    {{-- Good 字段 --}}
                    <div class="mb-3">
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs font-medium text-gray-600 uppercase">Good</span>
                        </div>
                        @if($editingField === 'good-' . $feedback->id)
                        <textarea wire:model.live="editingValue" wire:blur="saveField" wire:keydown.enter="saveField"
                            wire:keydown.escape="cancelEdit"
                            class="w-full p-2 border border-gray-300 rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            rows="2" autofocus>
                            </textarea>
                        @else
                        <div class="text-sm text-gray-700 bg-gray-50 p-2 rounded-md cursor-pointer hover:bg-gray-100 transition-colors duration-150 min-h-[2.5rem] flex items-center"
                            wire:click="editField('{{ $feedback->id }}', 'good')">
                            {{ $feedback->good ?: '目前为空，点击编辑' }}
                        </div>
                        @endif
                    </div>

                    {{-- Bad 字段 --}}
                    <div class="mb-3">
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs font-medium text-gray-600 uppercase">Bad</span>
                        </div>
                        @if($editingField === 'bad-' . $feedback->id)
                        <textarea wire:model.live="editingValue" wire:blur="saveField" wire:keydown.enter="saveField"
                            wire:keydown.escape="cancelEdit"
                            class="w-full p-2 border border-gray-300 rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            rows="2" autofocus>
                            </textarea>
                        @else
                        <div class="text-sm text-gray-700 bg-gray-50 p-2 rounded-md cursor-pointer hover:bg-gray-100 transition-colors duration-150 min-h-[2.5rem] flex items-center"
                            wire:click="editField('{{ $feedback->id }}', 'bad')">
                            {{ $feedback->bad ?: '目前为空，点击编辑' }}
                        </div>
                        @endif
                    </div>

                    {{-- Remark 字段 --}}
                    <div class="mb-3">
                        <div class="flex items-center mb-1">
                            <svg class="w-4 h-4 text-yellow-500 mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            <span class="text-xs font-medium text-gray-600 uppercase">Remark</span>
                        </div>
                        @if($editingField === 'remark-' . $feedback->id)
                        <textarea wire:model.live="editingValue" wire:blur="saveField" wire:keydown.enter="saveField"
                            wire:keydown.escape="cancelEdit"
                            class="w-full p-2 border border-gray-300 rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            rows="2" autofocus>
                            </textarea>
                        @else
                        <div class="text-sm text-gray-700 bg-gray-50 p-2 rounded-md cursor-pointer hover:bg-gray-100 transition-colors duration-150 min-h-[2.5rem] flex items-center"
                            wire:click="editField('{{ $feedback->id }}', 'remark')">
                            {{ $feedback->remark ?: '目前为空，点击编辑' }}
                        </div>
                        @endif
                    </div>

                    {{-- Reference --}}
                    @if($feedback->referrer)
                    <div class="text-xs text-gray-500">
                        <span class="font-medium">参考: </span>{{ $feedback->referrer }}
                    </div>
                    @endif
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <p class="text-sm">暂无反馈记录</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 桌面端表格视图 --}}
    <div class="hidden md:block">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                        Good
                        <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                        Bad
                        <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                        Remark
                        <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                        Week
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                        Reference
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                        Created At
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($feedbacks as $feedback)
                <tr wire:key="{{ $feedback->id }}" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $feedback->id }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        @if($editingField === 'good-' . $feedback->id)
                        <textarea wire:model.live="editingValue" wire:blur="saveField" wire:keydown.enter="saveField"
                            wire:keydown.escape="cancelEdit"
                            class="w-full p-2 border border-gray-300 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="3" autofocus>
                                    </textarea>
                        @else
                        <div class="break-words cursor-pointer hover:bg-gray-100 p-2 rounded"
                            wire:click="editField('{{ $feedback->id }}', 'good')">
                            {{ $feedback->good ?: '目前为空，点击编辑' }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        @if($editingField === 'bad-' . $feedback->id)
                        <textarea wire:model.live="editingValue" wire:blur="saveField" wire:keydown.enter="saveField"
                            wire:keydown.escape="cancelEdit"
                            class="w-full p-2 border border-gray-300 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="3" autofocus>
                                    </textarea>
                        @else
                        <div class="break-words cursor-pointer hover:bg-gray-100 p-2 rounded"
                            wire:click="editField('{{ $feedback->id }}', 'bad')">
                            {{ $feedback->bad ?: '目前为空，点击编辑' }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        @if($editingField === 'remark-' . $feedback->id)
                        <textarea wire:model.live="editingValue" wire:blur="saveField" wire:keydown.enter="saveField"
                            wire:keydown.escape="cancelEdit"
                            class="w-full p-2 border border-gray-300 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="3" autofocus>
                                    </textarea>
                        @else
                        <div class="break-words cursor-pointer hover:bg-gray-100 p-2 rounded"
                            wire:click="editField('{{ $feedback->id }}', 'remark')">
                            {{ $feedback->remark ?: '目前为空，点击编辑' }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $this->getWeekRange($feedback->week) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $feedback->referrer }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $feedback->created_at->format('Y-m-d') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        暂无反馈记录
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

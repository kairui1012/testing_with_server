
<div class="overflow-x-auto">
    <table class="w-full bg-white border border-gray-200 rounded-lg shadow">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    ID
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    Good
                    <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    Bad
                     <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    Remark
                     <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
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
                            <textarea
                                wire:model.live="editingValue"
                                wire:blur="saveField"
                                wire:keydown.enter="saveField"
                                wire:keydown.escape="cancelEdit"
                                class="w-full p-2 border border-gray-300 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                                rows="3"
                                autofocus>
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
                            <textarea
                                wire:model.live="editingValue"
                                wire:blur="saveField"
                                wire:keydown.enter="saveField"
                                wire:keydown.escape="cancelEdit"
                                class="w-full p-2 border border-gray-300 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                                rows="3"
                                autofocus>
                            </textarea>
                        @else
                            <div class="break-words cursor-pointer hover:bg-gray-100 p-2 rounded"
                                wire:click="editField('{{ $feedback->id }}', 'bad')">
                                {{ $feedback->bad ?: '目前为空，点击编辑'  }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        @if($editingField === 'remark-' . $feedback->id)
                            <textarea
                                wire:model.live="editingValue"
                                wire:blur="saveField"
                                wire:keydown.enter="saveField"
                                wire:keydown.escape="cancelEdit"
                                class="w-full p-2 border border-gray-300 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                                rows="3"
                                autofocus>
                            </textarea>
                        @else
                            <div class="break-words cursor-pointer hover:bg-gray-100 p-2 rounded"
                                wire:click="editField('{{ $feedback->id }}', 'remark')">
                                {{ $feedback->remark ?: '目前为空，点击编辑'  }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $feedback->week }}
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
                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                        暂无反馈记录
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


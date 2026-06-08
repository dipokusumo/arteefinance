@php($cellState = $getState())

<div x-data="{
        name: @js($getName()),
        recordKey: @js($getRecordKey()),
        isEditing: false,
        state: @js($cellState),
        originalState: @js($cellState)
    }" class="w-full">
    {{-- Display --}}
    <div x-show="!isEditing" @click="isEditing = true"
        class="group flex min-h-8 cursor-pointer items-center rounded-lg px-2 py-1 transition hover:bg-gray-50 dark:hover:bg-gray-800">
        <span class="truncate text-sm text-gray-950 dark:text-white">
            {{ filled($cellState) ? $cellState : '—' }}
        </span>

        <svg class="ml-2 h-4 w-4 opacity-0 transition group-hover:opacity-100 text-gray-400"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15.232 5.232l3.536 3.536M9 11l6.768-6.768a2.5 2.5 0 113.536 3.536L12.536 14.536A4 4 0 019.707 15.707L7 16l.293-2.707A4 4 0 018.464 10.536z" />
        </svg>
    </div>

    {{-- Edit --}}
    <div x-show="isEditing" x-transition
        class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white p-2 shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <input type="text" x-model="state" @keydown.enter.prevent="
                isEditing = false;
                $wire.updateTableColumnState(name, recordKey, state)
            " @keydown.escape.prevent="
                state = originalState;
                isEditing = false
            "
            class="fi-input block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white" />

        {{-- Save --}}
        <button @click="
                originalState = state;
                isEditing = false;
                $wire.updateTableColumnState(name, recordKey, state)
            " type="button"
            class="flex h-8 w-8 items-center justify-center rounded-lg bg-success-500 text-white transition hover:bg-success-600">
            ✓
        </button>

        {{-- Cancel --}}
        <button @click="
                state = originalState;
                isEditing = false
            " type="button"
            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-200 text-gray-700 transition hover:bg-red-500 dark:bg-gray-700 dark:text-gray-200">
            ✕
        </button>
    </div>
</div>
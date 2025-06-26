{{-- resources/views/daily-log.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen font-sans">
  @include('layouts.navigation')

  <main class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

      {{-- Header --}}
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
          📝 {{ __('Daily Testing Log') }} / {{ __('每日测试日志') }}
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
          {{ __('Track your daily app testing activities') }} / {{ __('跟踪您的每日应用测试活动') }}
        </p>
      </div>

      {{-- Alpine Inline Data --}}
      <div x-data="{ notes: '', logs: [], addLog() { if (!this.notes.trim()) return; this.logs.unshift({ date: new Date().toISOString().split('T')[0], notes: this.notes }); this.notes = ''; } }" class="space-y-10">

        {{-- Notes Entry --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            {{ __('Testing Notes') }} / {{ __('测试笔记') }}
          </h2>

          <textarea data-gramm="false" x-model="notes" rows="4"
            placeholder="{{ __('What did you test today? Any issues found?') }} / {{ __('您今天测试了什么？发现了什么问题吗？') }}"
            class="mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white"
          ></textarea>

         <button @click="addLog()" :disabled="notes.trim() === ''"
            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded disabled:opacity-50"
          >
            + {{ __('Add to History') }} / {{ __('添加到历史') }}
          </button>
        </div>

        {{-- History --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            🗂️ {{ __('Testing History') }} / {{ __('测试历史') }}
          </h2>

          <template x-if="logs.length === 0">
            <p class="text-gray-500 dark:text-gray-400">{{ __('No entries yet.') }} / {{ __('暂无记录。') }}</p>
          </template>

          <template x-for="log in logs" :key="log.date + log.notes">
            <div class="border p-4 rounded-lg mb-3 bg-gray-50 dark:bg-gray-700">
              <div class="flex items-center justify-between mb-1">
                <div class="text-base font-medium text-gray-900 dark:text-white" x-text="log.date"></div>
              </div>
              <p class="text-sm text-gray-600 dark:text-gray-300" x-text="log.notes"></p>
            </div>
          </template>
        </div>

      </div>
    </div>
  </main>
</body>
</html>


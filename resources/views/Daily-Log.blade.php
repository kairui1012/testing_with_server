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

      {{-- Header Section --}}
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
          📝 {{ __('Daily Testing Log') }} / {{ __('每日测试日志') }}
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
          {{ __('Track your daily app testing activities') }} / {{ __('跟踪您的每日应用测试活动') }}
        </p>
      </div>

      <div x-data="dailyLog()" class="space-y-10">

        {{-- Session Card --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">

          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                {{ __('Testing Notes') }} / {{ __('测试笔记') }}
              </h2>
            </div>
          </div>

          <div>
            <textarea x-model="notes" rows="3"
              placeholder="{{ __('What did you test today? Any issues found?') }} / {{ __('您今天测试了什么？发现了什么问题吗？') }}"
              class="mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white"
            ></textarea>
          </div>

          {{-- Add to History Button --}}
          <button @click="addLog()" :disabled="notes.trim() === ''"
            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded disabled:opacity-50"
          >
            + {{ __('Add to History') }} / {{ __('添加到历史') }}
          </button>
        </div>

        {{-- Testing History Section --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            🗂️ {{ __('Testing History') }} / {{ __('测试历史') }}
          </h2>

          <template x-for="log in logs" :key="log.date + log.notes">
            <div class="border p-4 rounded-lg mb-3 bg-gray-50 dark:bg-gray-700">
              <div class="flex items-center justify-between mb-1">
                <div class="text-base font-medium text-gray-900 dark:text-white" x-text="log.date"></div>
                <span
                  :class="log.done 
                    ? 'inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800'
                    : 'inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800'"
                  x-text="log.done ? '{{ __('Completed') }}' : '{{ __('Pending') }}'"
                ></span>
              </div>
              <p class="text-sm text-gray-600 dark:text-gray-300">
                <span x-text="log.notes"></span>
              </p>
            </div>
          </template>
        </div>

      </div>
    </div>
  </main>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('dailyLog', () => ({
        notes: '',
        logs: [],
        addLog() {
          const date = new Date().toISOString().split('T')[0];
          this.logs.unshift({
            date: date,
            notes: this.notes,
            done: true
          });
          this.notes = '';
        }
      }));
    });
  </script>
</body>
</html>

<x-app-layout>
    <div class="relative min-h-screen bg-gray-100 dark:bg-gray-900 sm:pt-24 pb-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ ...messages({ userId }), nickname: userNickname }" x-init="init()">
                <h3 class="text-2xl" x-text="nickname"></h3>
                <x-message-cards />

                <div class="bg-white h-64 flex text-pink-600 items-center justify-center mx-3 my-5 rounded-lg shadow-md" id="infinite-scroll-trigger">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Loading...</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const user = @json(Auth::user()); // ログインしているユーザー
    const userId = @json($user->id); // 現在表示されている画面のユーザーID
    const userNickname = @json($user->nickname); // 現在表示されている画面のユーザーのニックネーム
</script>

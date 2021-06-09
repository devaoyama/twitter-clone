<x-app-layout>
    <div class="relative min-h-screen bg-gray-100 dark:bg-gray-900 sm:pt-24 pb-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @auth
                <x-message-create-form :content="old('content')" />
            @endauth
            @foreach($messages as $message)
                <x-message-card :message="$message" />
            @endforeach
            {{ $messages->links() }}
        </div>
    </div>
</x-app-layout>

@props(['content'])

<form action="{{ route('messages.store') }}" method="post" class="w-full bg-white shadow p-8 my-8 text-gray-700">
    @csrf

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <!-- Message field -->
    <div class="flex flex-wrap mb-6">
        <div class="relative w-full appearance-none label-floating">
            <textarea name="content" class="autoexpand tracking-wide py-2 px-4 mb-3 leading-relaxed appearance-none block w-full bg-gray-200 border border-gray-200 rounded focus:outline-none focus:bg-white focus:border-gray-500" id="message" placeholder="Message..." rows="5">{{ $content ?? '' }}</textarea>
            <label for="message" class="absolute tracking-wide py-2 px-4 mb-4 opacity-0 leading-tight block top-0 left-0 cursor-text">Message...
            </label>
        </div>
    </div>

    <div class="flex justify-end">
        <x-button class="ml-3">投稿</x-button>
    </div>
</form>
<hr>

@props(['message'])

<div class="mx-3 my-5 relative bg-white rounded-lg shadow-md">
    <div class="flex justify-between py-4 px-6">
        <div class="text-gray-dark">{{ $message->created_at->format('Y年m月d日 h時m分') }}</div>
        @if(Auth::id() === $message->user_id)
            <div class="text-gray-dark flex items-center">
                <p>{{ $message->user->nickname }}＠{{ $message->user->id }}</p>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="ml-2 text-lg font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">︙</button>
                    </x-slot>
                    <x-slot name="content">
                        <form method="POST" action="{{ route('messages.destroy', ['message' => $message->id]) }}">
                            @csrf

                            <x-dropdown-link class="text-red-600" onclick="event.preventDefault();this.closest('form').submit();">
                                削除
                            </x-dropdown-link>
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        @else
            <div class="text-gray-dark">{{ $message->user->nickname }}＠{{ $message->user->id }}</div>
        @endif
    </div>
    <p class="px-10 pt-8 py-6 text-lg text-gray-darkest leading-relaxed">{!! nl2br(e($message->content)) !!}</p>
    <p class="pb-4 flex justify-end">
        <button class="focus:outline-none" type="button">
            <span class="pr-2 text-xl">
                いいね
            </span>
        </button>
        <span class="pr-10 text-lg text-gray">3</span>
    </p>
</div>

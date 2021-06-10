@props(['message'])

<div class="mx-3 my-5 relative bg-white rounded-lg shadow-md">
    <div class="flex justify-between py-4 px-6">
        <div class="text-gray-dark flex items-center">
            {{ $message->updated_at->format('Y年m月d日 h時i分') }}
            @if($message->created_at != $message->updated_at)
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            @endif
        </div>
        @if(Auth::id() === $message->user_id)
            <div class="text-gray-dark flex items-center">
                <p>{{ $message->user->nickname }}＠{{ $message->user->id }}</p>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="ml-2 text-lg font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">︙</button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link class="text-green-600" href="{{ route('messages.edit', ['message' => $message->id]) }}">
                            編集
                        </x-dropdown-link>
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

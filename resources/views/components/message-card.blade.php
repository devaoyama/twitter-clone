@props(['message'])

<div class="mx-3 my-5 relative bg-white rounded-lg shadow-md">
    <p class="flex justify-between py-4 px-6">
        <span class="text-gray-dark">{{ $message->created_at->format('Y年m月d日 h時m分') }}</span>
        <span class="text-gray-dark">{{ $message->user->nickname }}＠{{ $message->user->id }}</span>
    </p>
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

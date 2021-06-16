<template x-for="item in items">
    <div class="mx-3 my-5 relative bg-white rounded-lg shadow-md">
        <div class="flex justify-between py-4 px-6">
            <div class="text-gray-dark flex items-center" x-text="item.updated_at">
                <template x-if="item.isEdited">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </template>
            </div>
            <div class="text-gray-dark flex items-center">
                <p  x-text="`${item.user.nickname}@${item.user.id}`"></p>
                <template x-if="user?.id === item.user.id">
                    <div class="relative" @click.away="item.open = false" @close.stop="item.open = false">
                        <div @click="item.open = !item.open">
                            <button class="ml-2 text-lg font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">︙</button>
                        </div>
                        <div x-show="item.open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
                             @click="item.open = false"
                        >
                            <div class="rounded-md ring-1 ring-black ring-opacity-5">
                                <a
                                    x-bind:href="`/messages/${item.id}`"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out text-green-600"
                                >
                                    編集
                                </a>
                                <a
                                    class="block px-4 py-2 text-sm text-red-600 leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="event.preventDefault();console.log('削除')"
                                >
                                    削除
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <p class="px-10 pt-8 py-6 text-lg text-gray-darkest leading-relaxed" x-html="item.content"></p>
        <template x-if="user !== null">
            <p class="pb-4 flex justify-end items-center">
                <template x-if="item.isLiked">
                    <button
                        class="focus:outline-none"
                        type="button"
                        @click="requestLike({ isLiked: item.isLiked, messageId: item.id }).then(() => {
                            item.isLiked = false;
                            item.likedCount--;
                        });"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="red" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </template>
                <template x-if="!item.isLiked">
                    <button
                        class="focus:outline-none"
                        type="button"
                        @click="requestLike({ isLiked: item.isLiked, messageId: item.id }).then(() => {
                            item.isLiked = true;
                            item.likedCount++;
                        });"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </template>
                <span class="pr-10 text-lg text-gray" x-text="item.likedCount"></span>
            </p>
        </template>

        <template x-if="user === null">
            <p class="pb-4 flex justify-end items-center">
                <a href="/login" class="block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>
                <span class="pr-10 text-lg text-gray" x-text="item.likedCount"></span>
            </p>
        </template>
    </div>
</template>

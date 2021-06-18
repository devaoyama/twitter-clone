<template x-for="item in items">
    <div class="mx-3 my-5 relative bg-white rounded-lg shadow-md">
        <div class="flex justify-between py-4 px-6">
            <div class="text-gray-dark flex items-center">
                <span x-text="item.updated_at"></span>
                <template x-if="item.isEdited">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </template>
            </div>
            <div class="text-gray-dark flex items-center">
                <a class="block" x-bind:href="`/users/${item.user.id}`" x-text="`${item.user.nickname}@${item.user.id}`"></a>
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
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out text-green-600"
                                    @click="event.preventDefault();item.isEditing = true;"
                                >
                                    編集
                                </a>
                                <a
                                    class="block px-4 py-2 text-sm text-red-600 leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="event.preventDefault();deleteItem(item.id);"
                                >
                                    削除
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>


        <template x-if="item.isEditing">
            <div class="w-full bg-white p-4 my-4 text-gray-700">
                <template x-if="item.errors.length > 0">
                    <ul class="my-3 list-disc list-inside text-sm text-red-600">
                        <template x-for="error in item.errors">
                            <li x-text="error"></li>
                        </template>
                    </ul>
                </template>

                <div class="flex flex-wrap mb-6">
                    <div class="relative w-full appearance-none label-floating">
                        <textarea name="content" class="autoexpand tracking-wide py-2 px-4 mb-3 leading-relaxed appearance-none block w-full bg-gray-200 border border-gray-200 rounded focus:outline-none focus:bg-white focus:border-gray-500" id="message" placeholder="Message..." rows="5" x-model="item.content"></textarea>
                        <label for="message" class="absolute tracking-wide py-2 px-4 mb-4 opacity-0 leading-tight block top-0 left-0 cursor-text">Message...
                        </label>
                    </div>
                </div>

                <div class="flex justify-between">
                    <button
                        class="inline-flex items-center px-4 py-2 bg-white-800 border border-transparent rounded-md font-semibold text-xs text-gray uppercase tracking-widest hover:bg-white-700 active:bg-white-900 focus:outline-none focus:border-gray-900 focus:ring ring-white-300 disabled:opacity-25 transition ease-in-out duration-150"
                        @click="cancelEdit(item)"
                    >
                        戻る
                    </button>
                    <x-button type="button" class="ml-3" @click="editItem(item)">編集</x-button>
                </div>
            </div>
        </template>
        <template x-if="!item.isEditing">
            <p class="px-10 pt-8 py-6 text-lg text-gray-darkest leading-relaxed" x-html="item.content.replace(/[\n|\r|\n\r]/g, '<br>')"></p>
        </template>


        <template x-if="user !== null">
            <p class="pb-4 flex justify-end items-center">
                <template x-if="item.isLiked">
                    <button
                        class="focus:outline-none"
                        type="button"
                        @click="unlikeItem(item)"
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
                        @click="likeItem(item)"
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

<div class="w-full bg-white shadow p-8 my-8 text-gray-700">
    <template x-if="errors.length > 0">
        <ul class="my-3 list-disc list-inside text-sm text-red-600">
            <template x-for="error in errors">
                <li x-text="error"></li>
            </template>
        </ul>
    </template>

    <div class="flex flex-wrap mb-6">
        <div class="relative w-full appearance-none label-floating">
            <textarea name="content" class="autoexpand tracking-wide py-2 px-4 mb-3 leading-relaxed appearance-none block w-full bg-gray-200 border border-gray-200 rounded focus:outline-none focus:bg-white focus:border-gray-500" id="message" placeholder="Message..." rows="5" x-model="newContent"></textarea>
            <label for="message" class="absolute tracking-wide py-2 px-4 mb-4 opacity-0 leading-tight block top-0 left-0 cursor-text">Message...
            </label>
        </div>
    </div>

    <div class="flex justify-end">
        <x-button
            type="button"
            class="ml-3"
            @click="createItem({ content: newContent })"
        >
            投稿
        </x-button>
    </div>
</div>

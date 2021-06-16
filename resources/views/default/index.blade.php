<x-app-layout>
    <div class="relative min-h-screen bg-gray-100 dark:bg-gray-900 sm:pt-24 pb-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div x-data="infiniteScroll()" x-init="init()">
                <template x-if="user !== null">
                    <div>
                        <x-message-create-form />
                        <hr>
                    </div>
                </template>

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
    const user = @json(Auth::user());

    function infiniteScroll() {
        return {
            triggerElement: null,
            itemsPerPage: 10,
            nextLink: '/api/messages',
            observer: null,
            isObserverPolyfilled: false,
            items: [],
            errors: [],
            newContent: '',
            init(elementId) {
                const ctx = this
                this.triggerElement = document.querySelector(elementId ? elementId : '#infinite-scroll-trigger')

                if (!('IntersectionObserver' in window) ||
                    !('IntersectionObserverEntry' in window) ||
                    !('isIntersecting' in window.IntersectionObserverEntry.prototype) ||
                    !('intersectionRatio' in window.IntersectionObserverEntry.prototype))
                {
                    this.isObserverPolyfilled = true

                    window.alpineInfiniteScroll = {
                        scrollFunc() {
                            const position = ctx.triggerElement.getBoundingClientRect()

                            if(position.top < window.innerHeight && position.bottom >= 0) {
                                ctx.getItems()
                            }
                        }
                    }

                    window.addEventListener('scroll', window.alpineInfiniteScroll.scrollFunc)
                } else {
                    this.observer = new IntersectionObserver(function(entries) {
                        if(entries[0].isIntersecting === true) {
                            ctx.getItems()
                        }
                    }, { threshold: [0] })
                    this.observer.observe(this.triggerElement)
                }
            },
            async getItems() {
                await fetchMessage({
                    link: this.nextLink,
                    onFetchMessage: ({ data, links }) => {
                        this.nextLink = links.next;
                        this.items = this.items.concat(data.map(v => ({
                            ...v,
                            tmpContent: v.content,
                            open: false,
                            isEditing: false,
                            errors: [],
                        })));
                        if (this.nextLink === null) {
                            if (this.isObserverPolyfilled) {
                                window.removeEventListener('scroll', window.alpineInfiniteScroll.scrollFunc)
                            } else {
                                this.observer.unobserve(this.triggerElement)
                            }
                            this.triggerElement.parentNode.removeChild(this.triggerElement)
                        }
                    },
                });
            },
            async createItem({ content }) {
                await storeMessage({
                    content,
                    onStoreMessage: (data) => {
                        this.items.unshift({
                            ...data,
                            tmpContent: content,
                            open: false,
                            isEditing: false,
                            errors: [],
                        });
                        this.newContent = '';
                        this.errors = [];
                    },
                    onStoreMessageError: (errors) => {
                        for (let k in errors) {
                            this.errors.push(...errors[k]);
                        }
                    },
                });
            },
            cancelEdit(item) {
                item.content = item.tmpContent;
                item.isEditing = false;
                item.errors = [];
            },
            async editItem(item) {
                await updateMessage({
                    messageId: item.id,
                    content: item.content,
                    onUpdateMessage: (data) => {
                        item.updated_at = data.updated_at;
                        item.isEdited = true;
                        item.isEditing = false;
                        item.tmpContent = item.content;
                        item.errors = [];
                    },
                    onUpdateMessageError: (errors) => {
                        for (let k in errors) {
                            item.errors.push(...errors[k]);
                        }
                    },
                })
            },
            async deleteItem(messageId) {
                await destroyMessage({
                    messageId,
                    onDestroyMessage: () => {
                        this.items = this.items.filter(item => item.id !== messageId);
                    },
                });
            },
            async likeItem(item) {
                await storeLike({
                    messageId: item.id,
                    onStoreLike: () => {
                        item.isLiked = true;
                        item.likedCount++;
                    },
                    onStoreLikeError: () => {},
                });
            },
            async unlikeItem(item) {
                await destroyLike({
                    messageId: item.id,
                    onDestroyLike: () => {
                        item.isLiked = false;
                        item.likedCount--;
                    },
                    onDestroyLikeError: () => {},
                });
            },
        };
    }
</script>

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

    const requestLike = ({ isLiked, messageId }) => {
        if (!isLiked) {
            return fetch('/api/likes', {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8',
                },
                body: JSON.stringify({ message_id: messageId }),
            });
        }
        return fetch('/api/likes', {
            method: 'delete',
            headers: {
                'Content-Type': 'application/json; charset=utf-8',
            },
            body: JSON.stringify({ message_id: messageId }),
        });
    }

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
                const response = await fetch(this.nextLink);
                const { data, links } = await response.json();

                this.nextLink = links.next;
                this.items = this.items.concat(data.map(v => ({
                    ...v,
                    tmpContent: v.content,
                    open: false,
                    isEditing: false,
                    errors: [],
                })));
                if(this.nextLink === null) {
                    if(this.isObserverPolyfilled) {
                        window.removeEventListener('scroll', window.alpineInfiniteScroll.scrollFunc)
                    } else {
                        this.observer.unobserve(this.triggerElement)
                    }

                    this.triggerElement.parentNode.removeChild(this.triggerElement)
                }
            },
            async createItem({ content }) {
                const response = await fetch('/api/messages', {
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8',
                    },
                    body: JSON.stringify({ content }),
                });
                if (response.ok) {
                    const { data } = await response.json();
                    this.items.unshift({
                        ...data,
                        tmpContent: content,
                        open: false,
                        isEditing: false,
                        errors: [],
                    });
                    this.newContent = '';
                    this.errors = [];
                } else {
                    if (response.status === 400) {
                        const { errors } = await response.json();
                        for (let k in errors) {
                            this.errors.push(...errors[k]);
                        }
                    } else {
                        alert('予期せぬエラーが発生しましtあ。');
                    }
                }

            },
            cancelEdit(item) {
                item.content = item.tmpContent;
                item.isEditing = false;
                item.errors = [];
            },
            async editItem(item) {
                const response = await fetch(`/api/messages/${item.id}`, {
                    method: 'put',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8',
                    },
                    body: JSON.stringify({ content: item.content }),
                });
                if (response.ok) {
                    const { data } = await response.json();
                    item.updated_at = data.updated_at;
                    item.isEdited = true;
                    item.isEditing = false;
                    item.tmpContent = item.content;
                    item.errors = [];
                } else {
                    if (response.status === 400) {
                        const { errors } = await response.json();
                        for (let k in errors) {
                            item.errors.push(...errors[k]);
                        }
                    } else {
                        alert('予期せぬエラーが発生しましtあ。');
                    }
                }
            },
            async deleteItem(messageId) {
                const response = await fetch(`/api/messages/${messageId}`, {
                    method: 'delete',
                });
                if (response.ok) {
                    this.items = this.items.filter(item => item.id !== messageId);
                } else {
                    alert('削除に失敗しました。もう一度やり直してください。');
                }
            }
        }
    }
</script>

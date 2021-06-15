<x-app-layout>
    <div class="relative min-h-screen bg-gray-100 dark:bg-gray-900 sm:pt-24 pb-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @auth
                <x-message-create-form :content="old('content')" />
            @endauth
            <div class="p-8" x-data="infiniteScroll()" x-init="init()">
                <x-message-cards />

                <div class="bg-white h-64 w-full flex text-pink-600 items-center justify-center" id="infinite-scroll-trigger">
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
                            var position = ctx.triggerElement.getBoundingClientRect()

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
                this.items = this.items.concat(data.map(v => ({ ...v, open: false })));
                if(this.nextLink === null) {
                    if(this.isObserverPolyfilled) {
                        window.removeEventListener('scroll', window.alpineInfiniteScroll.scrollFunc)
                    } else {
                        this.observer.unobserve(this.triggerElement)
                    }

                    this.triggerElement.parentNode.removeChild(this.triggerElement)
                }
            }
        }
    }
</script>

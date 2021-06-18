function messages({ userId }) {
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

                        if (position.top < window.innerHeight && position.bottom >= 0) {
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
            const link = userId === undefined
                ? this.nextLink
                : this.nextLink + '?user_id=' + userId;
            await fetchMessage({
                link: link,
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
window.messages = messages;

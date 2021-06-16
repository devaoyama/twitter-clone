const destroyLike = async ({
    messageId,
    onDestroyLike,
    onDestroyLikeError,
}) => {
    const response = await fetch('/api/likes', {
        method: 'delete',
        headers: {
            'Content-Type': 'application/json; charset=utf-8',
        },
        body: JSON.stringify({ message_id: messageId }),
    });
    if (response.ok) {
        onDestroyLike();
    } else {
        if (response.status === 400) {
            onDestroyLikeError();
        } else {
            alert('予期せぬエラーが発生しました');
        }
    }
};
window.destroyLike = destroyLike;

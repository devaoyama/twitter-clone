const storeLike = async ({
    messageId,
    onStoreLike,
    onStoreLikeError,
}) => {
    const response = await fetch('/api/likes', {
        method: 'post',
        headers: {
            'Content-Type': 'application/json; charset=utf-8',
        },
        body: JSON.stringify({ message_id: messageId }),
    });
    if (response.ok) {
        onStoreLike();
    } else {
        if (response.status === 400) {
            onStoreLikeError()
        } else {
            alert('予期せぬエラーが発生しました');
        }
    }
};
window.storeLike = storeLike;
